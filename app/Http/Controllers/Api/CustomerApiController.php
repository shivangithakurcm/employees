<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Master\ProjectType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerApiController extends Controller
{
    /**
     * GET /api/customers
     * Query params: search, project_type, city, date_from, date_to, per_page
     */
    public function index(Request $request): JsonResponse
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
            $query->where(function ($q) use ($request) {
                $q->where('won_city', 'like', '%' . $request->city . '%')
                  ->orWhere('city',   'like', '%' . $request->city . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('updated_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('updated_at', '<=', $request->date_to);
        }

        $perPage   = $request->integer('per_page', 15);
        $customers = $query->latest('updated_at')->paginate($perPage);

        return response()->json([
            'data'  => $customers->items(),
            'meta'  => [
                'current_page' => $customers->currentPage(),
                'last_page'    => $customers->lastPage(),
                'per_page'     => $customers->perPage(),
                'total'        => $customers->total(),
            ],
            'counts' => [
                'total'          => Lead::where('status', 'won')->count(),
                'token_received' => Lead::where('status', 'won')
                                        ->where('won_token_received', 'yes')
                                        ->count(),
            ],
        ]);
    }

    /**
     * GET /api/customers/{id}
     */
    public function show($id): JsonResponse
    {
        $customer = Lead::where('status', 'won')->findOrFail($id);

        return response()->json([
            'data' => $customer,
        ]);
    }

    /**
     * PUT /api/customers/{id}
     */
    public function update(Request $request, $id): JsonResponse
    {
        $customer = Lead::where('status', 'won')->findOrFail($id);

        $validated = $request->validate([
            'won_name'           => 'sometimes|string|max:255',
            'won_contact'        => 'sometimes|string|max:50',
            'won_email'          => 'sometimes|email|max:255',
            'won_designation'    => 'sometimes|string|max:255',
            'won_business_name'  => 'sometimes|string|max:255',
            'won_gst_no'         => 'sometimes|string|max:20',
            'won_location'       => 'sometimes|string|max:255',
            'won_city'           => 'sometimes|string|max:100',
            'won_state'          => 'sometimes|string|max:100',
            'won_country'        => 'sometimes|string|max:100',
            'won_project_type'   => 'sometimes|string|max:100',
            'won_project_detail' => 'sometimes|string',
            'won_final_cost'     => 'sometimes|numeric|min:0',
            'won_milestone'      => 'sometimes|string',
            'won_timeline'       => 'sometimes|string|max:255',
            'project_status'     => 'sometimes|string|max:100',
        ]);

        $customer->update($validated);

        return response()->json([
            'message' => 'Customer updated successfully.',
            'data'    => $customer->fresh(),
        ]);
    }

    /**
     * DELETE /api/customers/{id}
     */
    public function destroy($id): JsonResponse
    {
        $customer = Lead::where('status', 'won')->findOrFail($id);
        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully.',
        ]);
    }

    /**
 * POST /api/customers
 */
public function store(Request $request): JsonResponse
{
    $validated = $request->validate([
        'first_name'         => 'required|string|max:100',
        'last_name'          => 'required|string|max:100',
        'contact_number'     => 'required|numeric|digits:10',
        'email'              => 'nullable|email|unique:leads,email',
        'won_name'           => 'nullable|string|max:255',
        'won_contact'        => 'nullable|string|max:50',
        'won_email'          => 'nullable|email|max:255',
        'won_designation'    => 'nullable|string|max:255',
        'won_business_name'  => 'nullable|string|max:255',
        'won_gst_no'         => 'nullable|string|max:20',
        'won_location'       => 'nullable|string|max:255',
        'won_city'           => 'nullable|string|max:100',
        'won_state'          => 'nullable|string|max:100',
        'won_country'        => 'nullable|string|max:100',
        'won_project_type'   => 'nullable|string|max:100',
        'won_project_detail' => 'nullable|string',
        'won_final_cost'     => 'nullable|numeric|min:0',
        'won_milestone'      => 'nullable|string',
        'won_timeline'       => 'nullable|string|max:255',
        'won_token_received' => 'nullable|in:yes,no',
        'won_token_amount'   => 'nullable|numeric',
        'won_amount_type'    => 'nullable|string|max:100',
        'won_received_date'  => 'nullable|date',
        'won_gst_type'       => 'nullable|string|max:100',
    ]);

    $validated['status']     = 'won';
    $validated['discussion'] = 'add';

    $customer = Lead::create($validated);

    return response()->json([
        'status'  => true,
        'message' => 'Customer created successfully.',
        'data'    => $customer
    ], 201);
}
} //iknHFxwiHD3ejdPhWVMvx7pCi0hfVwzyRZEAlyzaa277b85f