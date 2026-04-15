<?php

namespace App\Mail;

use App\Models\MlmUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MlmActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public MlmUser $user, 
        public string $activationUrl
    ) {}

    public function build()
    {
        return $this->subject('Activate Your MLM Account - VSRMLM')
            ->from(
                config('mail.from.address', 'mailerbotv3@vibrantick.in'), 
                config('mail.from.name', 'VSRMLM')
            )
            // ✅ Use plain view (not markdown)
            ->view('emails.mlm-activation')
            ->with([
                'userName' => $this->user->user_name,
                'firstName' => $this->user->first_name,
                'activationUrl' => $this->activationUrl,
                'expiryHours' => 24,
                'user' => $this->user, // Pass full user object if needed
            ]);
    }
}