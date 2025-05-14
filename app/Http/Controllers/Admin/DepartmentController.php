<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lecture;
use App\Models\Lecturer;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $lecturers = Lecturer::all();
        return view('admin.departments.index', compact('departments', 'lecturers'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $department->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        // Set department_id to null for all lecturers assigned to this department
        Lecture::where('department_id', $department->id)->update(['department_id' => null]);

        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully.');
    }

    public function assignLecturer(Request $request)
    {
        $request->validate([
            'lecturer_id' => 'required|exists:lectures,id', // Use 'lecturers' instead of 'lectures'
            'department_id' => 'required|exists:departments,id'
        ]);

        $lecturer = Lecturer::where('id', $request->lecturer_id)->first(); // Use Lecturer model

        if (!$lecturer) {
            return redirect()->route('admin.departments.index')->with('error', 'Lecturer not found');
        }

        $lecturer->department_id = $request->department_id;
        $lecturer->save();

        return redirect()->route('admin.departments.index')->with('success', 'Lecturer assigned successfully');
    }
}
