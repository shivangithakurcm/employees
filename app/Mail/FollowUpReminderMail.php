<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FollowUpReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;

    public function __construct($lead)
    {
        $this->lead = $lead;
    }

    public function build()
    {
        return $this->subject('Follow-up Reminder')
                    ->view('emails.followup-reminder')
                    ->with(['lead' => $this->lead]);
    }
}