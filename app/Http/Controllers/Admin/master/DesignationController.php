<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Designation;

class DesignationController extends Controller
{
    public function index()
    {
        $items = Designation::latest()->paginate(20);
        return view('admin.master.designation', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:designations,name'
        ]);
        Designation::create(['name' => $request->name]);
        return back()->with('success', 'Designation added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:designations,name,' . $id
        ]);
        Designation::findOrFail($id)->update(['name' => $request->name]);
        return back()->with('success', 'Designation updated successfully.');
    }

    public function destroy($id)
    {
        Designation::findOrFail($id)->delete();
        return back()->with('success', 'Designation deleted successfully.');
    }
}
