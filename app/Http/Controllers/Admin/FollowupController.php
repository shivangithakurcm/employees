<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FollowUpController extends Controller
{
    // Aaj ke + overdue followups page
   public function today()
{
    $today = Carbon::today();

    // Auto-mark overdue
    FollowUp::whereDate('date', '<', $today)
        ->where('status', 'pending')
        ->update(['status' => 'overdue']);

    $todayList = FollowUp::with('lead')
        ->whereDate('date', $today)
        ->where('status', 'pending')
        ->orderBy('time')
        ->get();

    $overdueList = FollowUp::with('lead')
        ->where('status', 'overdue')
        ->orderBy('date')
        ->orderBy('time')
        ->get();

    $doneToday = FollowUp::whereDate('date', $today)
        ->where('status', 'done')
        ->count();

    return view('admin.followups.today', compact('todayList', 'overdueList', 'doneToday'));
}
    // Naya followup schedule karo (lead detail page se)
    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'date'    => 'required|date|after_or_equal:today',
            'time'    => 'required',
            'comment' => 'nullable|string|max:500',
        ]);

        FollowUp::create([
            'lead_id' => $request->lead_id,
            'date'    => $request->date,
            'time'    => $request->time,
            'comment' => $request->comment,
            'status'  => 'pending',
        ]);

        return back()->with('success', '✅ Follow-up scheduled successfully!');
    }

    // Mark as done
    public function markDone($id)
    {
        FollowUp::findOrFail($id)->update(['status' => 'done']);

        return back()->with('success', '✅ Marked as done!');
    }
}