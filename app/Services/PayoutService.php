<?php
namespace App\Services;

use App\Models\MlmUser;
use App\Models\PayoutBalance;
use App\Models\PayoutTransaction;

class PayoutService
{
    public function processPairMatching($downlineUser, $orderCC)
    {
        $ccRate = \App\Models\CCSetting::getActiveRate();
        $currentNode = $downlineUser;
        $maxLevels = 10;

        for ($level = 1; $level <= $maxLevels; $level++) {
            $sponsor = $currentNode->sponsor;
            if (!$sponsor || !$sponsor->is_active || $sponsor->is_deleted) break;

            $tree = \App\Models\MLMTree::where('mlm_user_id', $currentNode->id)->first();
            if (!$tree || in_array($tree->position, ['none', null])) break;

            $position = $tree->position;
            $balance = PayoutBalance::firstOrNew(['mlm_user_id' => $sponsor->id]);
            
            if ($position === 'left') {
                $balance->left_cc += $orderCC;
            } else {
                $balance->right_cc += $orderCC;
            }
            $balance->save();

            $matched = min($balance->left_cc, $balance->right_cc);
            if ($matched > 0) {
                $pairCC = $matched * 2;
                $pairValue = $pairCC * $ccRate;
                $grossCommission = $pairValue * ($sponsor->commission_percentage / 100);
                $netIncome = $grossCommission / 2;

                $balance->available_balance += $netIncome;
                $balance->total_earned += $netIncome;
                $balance->total_matched_cc += $matched;
                $balance->save();

                PayoutTransaction::create([
                    'mlm_user_id' => $sponsor->id,
                    'type' => 'matching_income',
                    'cc_amount' => $pairCC,
                    'currency_amount' => $netIncome,
                    'status' => 'credited',
                    'description' => "Pair match: {$matched} CC × 2 at Level {$level}",
                    'meta' => [
                        'matched_cc' => $matched,
                        'cc_rate' => $ccRate,
                        'commission_pct' => $sponsor->commission_percentage,
                        'level' => $level,
                    ],
                ]);

                // Flush matched CC
                $balance->left_cc -= $matched;
                $balance->right_cc -= $matched;
                $balance->save();
            }

            $currentNode = $sponsor;
        }
    }

    public function getUserPayoutSummary($userId)
    {
        $config = \App\Models\PayoutConfig::first();
        $thresholdCC = $config ? $config->getThresholdCC() : 800;
        
        $personalCC = \App\Models\OrderItem::whereHas('order', fn($q) => 
            $q->where('user_id', $userId)->where('status', 'COMPLETED')
        )->sum('cc_points');
        
        $totalProducts = \App\Models\OrderItem::whereHas('order', fn($q) => 
            $q->where('user_id', $userId)->where('status', 'COMPLETED')
        )->sum('quantity');
        
        $productsForPayout = $config ? $config->products_for_payout : 40;
        $balance = \App\Models\PayoutBalance::where('mlm_user_id', $userId)->first();

        return [
            'personal_cc' => $personalCC,
            'left_team_cc' => $balance ? $balance->left_cc : 0,
            'right_team_cc' => $balance ? $balance->right_cc : 0,
            'available_balance' => $balance ? $balance->available_balance : 0,
            'locked_balance' => $balance ? $balance->locked_balance : 0,
            'total_earned' => $balance ? $balance->total_earned : 0,
            'is_eligible' => $totalProducts >= $productsForPayout,
            'threshold_cc' => $thresholdCC,
            'progress_percent' => min(100, ($totalProducts / $productsForPayout) * 100),
            'products_needed' => max(0, $productsForPayout - $totalProducts),
        ];
    }
}