<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();

        return view('department.index', compact('departments'));
    }

    public function create()
    {
        return view('department.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required | string | max:255',
            'description' => 'nullable | string',
            'status' => 'required | string | max:50'
        ]);

        Department::create($request->all());

        return redirect()->route('departments.index')->with('Success', 'Department Created Successfully');
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);

        return view('department.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required | string | max:255',
            'description' => 'nullable | string',
            'status' => 'required | string | max:50'
        ]);

        $department = Department::findOrFail($id);
        $department->update($request->all());

        return redirect()->route('departments.index')->with('Success', 'Department Updated Successfully');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('departments.index')->with('Success', 'Department Deleted Successfully');
    }
}
