<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Lead;
use App\Models\FollowUp;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_employees' => Employee::count(),
            'total_leads'     => Lead::count(),
            'total_qualified' => Lead::where('status', 'qualified')->count(),
            'total_prospect'  => Lead::where('status', 'proposal_sent')->count(),
            'total_lost'      => Lead::whereIn('status', ['lost', 'not_interested', 'not_in_scope'])->count(),
            'total_won'       => Lead::where('status', 'won')->count(),
        ];

        // Auto-mark overdue
        FollowUp::whereDate('date', '<', Carbon::today())
            ->where('status', 'pending')
            ->update(['status' => 'overdue']);

        $followupStats = [
            'today'    => FollowUp::whereDate('date', Carbon::today())->where('status', 'pending')->count(),
            'overdue'  => FollowUp::where('status', 'overdue')->count(),
            'upcoming' => FollowUp::whereDate('date', '>', Carbon::today())
                            ->whereDate('date', '<=', Carbon::today()->addDays(7))
                            ->where('status', 'pending')
                            ->count(),
        ];

        // ✅ Monthly Trend — last 6 months
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::today()->subMonths($i);
            $monthlyTrend[] = [
                'month' => $month->format('M Y'),
                'leads' => Lead::whereYear('created_at', $month->year)
                               ->whereMonth('created_at', $month->month)
                               ->count(),
                'won'   => Lead::where('status', 'won')
                               ->whereYear('created_at', $month->year)
                               ->whereMonth('created_at', $month->month)
                               ->count(),
                'lost'  => Lead::whereIn('status', ['lost', 'not_interested', 'not_in_scope'])
                               ->whereYear('created_at', $month->year)
                               ->whereMonth('created_at', $month->month)
                               ->count(),
            ];
        }

        // ✅ This Month Stats
        $thisMonth = [
            'leads'    => Lead::whereMonth('created_at', Carbon::today()->month)
                              ->whereYear('created_at', Carbon::today()->year)
                              ->count(),
            'won'      => Lead::where('status', 'won')
                              ->whereMonth('created_at', Carbon::today()->month)
                              ->whereYear('created_at', Carbon::today()->year)
                              ->count(),
            'lost'     => Lead::whereIn('status', ['lost', 'not_interested', 'not_in_scope'])
                              ->whereMonth('created_at', Carbon::today()->month)
                              ->whereYear('created_at', Carbon::today()->year)
                              ->count(),
            'followups' => FollowUp::whereMonth('date', Carbon::today()->month)
                              ->whereYear('date', Carbon::today()->year)
                              ->count(),
        ];

        // ✅ Conversion Rate
        $totalLeads      = Lead::count();
        $conversionRate  = $totalLeads > 0
            ? round((Lead::where('status', 'won')->count() / $totalLeads) * 100, 1)
            : 0;

        // ✅ Hot Leads — 3+ din se pending follow-up
        $hotLeads = Lead::whereHas('followUps', function($q) {
            $q->where('status', 'overdue');
        })->with(['followUps' => function($q) {
            $q->where('status', 'overdue')->orderBy('date');
        }])->limit(5)->get();

        // ✅ Recent Activity — last 5 leads
        $recentLeads = Lead::latest()->limit(5)->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'followupStats',
            'monthlyTrend',
            'thisMonth',
            'conversionRate',
            'hotLeads',
            'recentLeads'
        ));
    }
}