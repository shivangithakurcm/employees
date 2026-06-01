<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Master\ProjectType;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::where('status', 'won');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('won_name',          'like', "%$s%")
                  ->orWhere('first_name',       'like', "%$s%")
                  ->orWhere('last_name',        'like', "%$s%")
                  ->orWhere('won_business_name','like', "%$s%")
                  ->orWhere('contact_number',   'like', "%$s%")
                  ->orWhere('won_contact',      'like', "%$s%")
                  ->orWhere('won_email',        'like', "%$s%")
                  ->orWhere('email',            'like', "%$s%");
            });
        }

        if ($request->filled('project_type')) {
            $query->where('won_project_type', $request->project_type);
        }
        if ($request->filled('city')) {
            $query->where(function($q) use ($request) {
                $q->where('won_city', 'like', '%'.$request->city.'%')
                  ->orWhere('city',   'like', '%'.$request->city.'%');
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('updated_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('updated_at', '<=', $request->date_to);
        }

        $customers    = $query->latest('updated_at')->paginate(15);
        $projectTypes = ProjectType::all();

        $counts = [
            'total'          => Lead::where('status', 'won')->count(),
            'token_received' => Lead::where('status', 'won')->where('won_token_received', 'yes')->count(),
        ];

        return view('admin.customers.index', compact('customers', 'counts', 'projectTypes'));
    }

    public function show($id)
    {
        $customer     = Lead::where('status', 'won')->findOrFail($id);
        $projectTypes = ProjectType::all();
        return view('admin.customers.show', compact('customer', 'projectTypes'));
    }

    public function edit($id)
    {
        $customer     = Lead::where('status', 'won')->findOrFail($id);
        $projectTypes = ProjectType::all();
        return view('admin.customers.edit', compact('customer', 'projectTypes'));
    }

    public function update(Request $request, $id)
    {
        $customer = Lead::where('status', 'won')->findOrFail($id);

        $customer->update([
            'won_name'           => $request->won_name,
            'won_contact'        => $request->won_contact,
            'won_email'          => $request->won_email,
            'won_designation'    => $request->won_designation,
            'won_business_name'  => $request->won_business_name,
            'won_gst_no'         => $request->won_gst_no,
            'won_location'       => $request->won_location,
            'won_city'           => $request->won_city,
            'won_state'          => $request->won_state,
            'won_country'        => $request->won_country,
            'won_project_type'   => $request->won_project_type,
            'won_project_detail' => $request->won_project_detail,
            'won_final_cost'     => $request->won_final_cost,
            'won_milestone'      => $request->won_milestone,
            'won_timeline'       => $request->won_timeline,
            'project_status'     => $request->project_status,
        ]);

        return redirect()->route('admin.customers.show', $customer->id)
                         ->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Lead::where('status', 'won')->findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.customers.index')
                         ->with('success', 'Customer deleted.');
    }
}