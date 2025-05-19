<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentsController extends Controller
{
    //
    public function index()
    {
        $students = Student::all();
        return view('admin.students.index', compact('students'));
    }
    public function create()
    {
        return view('admin.students.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'names' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        // Create the user first
        $user = User::create([
            'name' => $request->names,
            'email' => $request->email,
            'phone' => $request->phone,
            'email_verified_at' => now(),
            'address' => $request->address,
            'password' => Hash::make($request['password']),
            'type' => 0, // 0 for student
        ]);

        // Create the student record
        Student::create([
            'names' => $request->names,
            'user_id' => $user->id,
            'student_number' => 'STD' . str_pad($user->id, 6, '0', STR_PAD_LEFT), // Generate registration number
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully.');
    }
    public function studentProfile($id)
    {
        $student = Student::findOrFail($id);
        return view('admin.students.profile', compact('student'));
    }
    public function studentAppointments()
    {
        $student = Student::all();
        $appointments = $student->appointments()->with('lecturer')->get();
        return view('admin.students.appointments', compact('student', 'appointments'));
    }
    public function updateStudent(Request $request, $id)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'names' => 'required|string|max:255',
        ]);

        // Find the student and ensure it exists
        $student = Student::findOrFail($id);

        // Update user details
        $student->update([
            'names' => $request->names,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'Students info updated successfully');
    }
}
