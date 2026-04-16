<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeApiController extends Controller
{
    // ✅ STORE
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:employees,email'
        ]);

        $employee = Employee::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'contact'     => $request->contact,
            'designation' => $request->designation,
            'password'    => Hash::make($request->password ?? '123456'),
            'status'      => 1
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Employee created',
            'data' => $employee
        ]);
    }

    // ✅ GET ALL
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Employee::all()
        ]);
    }

    
    // ✅ MULTIPLE UPDATE (SAFE 🔥)
   public function updateMultiple(Request $request)
{
    $ids = $request->input('ids');

    // ✅ allow single OR multiple
    if (empty($ids)) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid ids'
        ]);
    }

    // if single id comes like 7 → convert to array [7]
    if (!is_array($ids)) {
        $ids = [$ids];
    }

    // optional: convert "7,8,9" string into array
    if (count($ids) == 1 && str_contains($ids[0], ',')) {
        $ids = explode(',', $ids[0]);
    }

    $data = [];

    if ($request->has('name')) {
        $data['name'] = $request->name;
    }

    if ($request->has('designation')) {
        $data['designation'] = $request->designation;
    }

    if (empty($data)) {
        return response()->json([
            'status' => false,
            'message' => 'No data to update'
        ]);
    }

    $updatedCount = Employee::whereIn('id', $ids)->update($data);

    return response()->json([
        'status' => true,
        'updated_rows' => $updatedCount,
        'message' => 'Updated successfully'
    ]);
}
public function destroyMultiple(Request $request)
{
    $ids = $request->ids;

    // Validate ids
    if (!$ids || !is_array($ids)) {
        return response()->json([
            'status' => false,
            'message' => 'Please provide valid ids'
        ]);
    }

    // Delete records
    Employee::whereIn('id', $ids)->delete();

    return response()->json([
        'status' => true,
        'message' => 'Selected employees deleted successfully'
    ]);
}



}