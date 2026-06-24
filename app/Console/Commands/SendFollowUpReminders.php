<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Mail\FollowUpReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendFollowUpReminders extends Command
{
    protected $signature = 'followups:send-reminders';
    protected $description = 'Send email reminders for today\'s follow-ups';

   public function handle()
{
    $dueLeads = Lead::whereDate('date', Carbon::today())
        ->whereNotIn('status', ['won', 'lost'])
        ->where('is_reminded', false)  // ← Yeh add karo
        ->get();

    $count = 0;

    foreach ($dueLeads as $lead) {
        if ($lead->email) {
            Mail::to($lead->email)->send(new FollowUpReminderMail($lead));
            $lead->update(['is_reminded' => true]);  // ← Yeh add karo
            $this->info("Email sent to: {$lead->email}");
            $count++;
        }
    }

    $this->info("Total processed: {$count}");
}
}