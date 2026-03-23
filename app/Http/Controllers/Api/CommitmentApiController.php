<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommitmentOne;
use App\Models\CommitmentTwo;
use App\Models\CommitmentThree;
use App\Models\CommitmentFour;


class CommitmentApiController extends Controller
{
    

    public function index()
    {

        return response()->json([
            'commitment_one' => [
                'sub_title' => CommitmentOne::first()?->sub_title,
                'main_title' => CommitmentOne::first()?->main_title,
                'description_1' => CommitmentOne::first()?->description_1,
            ],
            'commitment_two' => [
                'sub_title' => CommitmentTwo::first()?->sub_title,
                'main_title' => CommitmentTwo::first()?->main_title,
                'description_1' => CommitmentTwo::first()?->description_1,
            ],
            'commitment_three' => [
                'sub_title' => CommitmentThree::first()?->sub_title,
                'main_title' => CommitmentThree::first()?->main_title,
                'description_1' => CommitmentThree::first()?->description_1,
            ],
            'commitment_four' => [
                'sub_title' => CommitmentFour::first()?->sub_title,
                'main_title' => CommitmentFour::first()?->main_title,
                'description_1' => CommitmentFour::first()?->description_1,
            ],
           
        ]);
    }
}