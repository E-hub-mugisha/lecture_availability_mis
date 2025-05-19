<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Lecture;
use App\Models\LectureAvailability;
use App\Models\Lecturer;
use App\Models\LecturerAvailability;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LectureController extends Controller
{
    //
    public function index()
    {
        $lectures = Lecturer::all();
        $departments = Department::all();
        return view('admin.lectures.index', compact('lectures', 'departments'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'names' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        // Create the user first
        $user = User::create([
            'name' => $request->names,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => Hash::make($request['password']),
            'type' => 2, // 2 for lecturer
        ]);

        // Create the lecturer record
        Lecturer::create([
            'names' => $request->names,
            'user_id' => $user->id,
            'staff_number' => 'LEC' . str_pad($user->id, 6, '0', STR_PAD_LEFT), // Generate staff number
            'department_id' => $request->department_id,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.lecturers.index')->with('success', 'Lecture added successfully.');
    }
    public function availableLecture()
    {
        $lectures = LecturerAvailability::where('status', 'available')->get();
        return view('admin.lectures.available', compact('lectures'));
    }
    public function appointments()
    {
        $appointments = Appointment::all();
        return view('admin.lectures.appointments', compact('appointments'));
    }
    public function showLecture($id)
    {
        $departments = Department::all();
        $lecture = Lecturer::findOrFail($id);
        return view('admin.lectures.profile', compact('lecture', 'departments'));
    }
    public function updateDepartment(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id'
        ]);

        $lecture = Lecturer::where('id', $id)->first();

        if (!$lecture) {
            return redirect()->route('admin.lecturers.profile')->with('error', 'Lecture not found');
        }

        $lecture->department_id = $request->department_id;
        $lecture->save();

        return redirect()->back()->with('success', 'Department assigned successfully');
    }
    public function updateLecture(Request $request, $id)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'names' => 'required|string|max:255',
        ]);

        // Find the lecture and ensure it exists
        $lecture = Lecturer::find($id);

        if (!$lecture) {
            return redirect()->back()->with('error', 'Lecture not found.');
        }

        // Find the associated user
        $user = Lecturer::find($lecture->user_id);

        if (!$user) {
            return redirect()->back()->with('error', 'Associated user not found.');
        }

        // Update user details
        $user->update([
            'names' => $request->names,
            'address' => $request->address,
            'phone' => $request->phone,
            'department_id' => $request->department_id,
        ]);

        return redirect()->back()->with('success', 'Lecture info updated successfully');
    }
    public function availability($id)
    {
        $lecture = Lecturer::findOrFail($id);
        $availabilities = LecturerAvailability::where('lecturer_id', $id)->get();
        return view('admin.lectures.availability', compact('lecture', 'availabilities'));
    }
    public function storeAvailability(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today', // Ensures the date is today or in the future
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', // Valid days of the week
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'status' => 'required | in:available,unavailable,break,lunch,meeting,training',
            
        ]);

        $lecturer = Lecturer::findOrFail($id);
        if (!$lecturer) {
            return redirect()->back()->with('error', 'Lecturer not found.');
        }
        $existingAvailability = LecturerAvailability::where('lecturer_id', $lecturer->id)
            ->where('date', $request->date)
            ->where('day', $request->day)
            ->where('start_time', $request->start_time)
            ->where('end_time', $request->end_time)
            ->first();
        if ($existingAvailability) {
            return redirect()->back()->withErrors('This availability slot already exists.');
        }

        LecturerAvailability::create([
            'lecturer_id' => $lecturer->id,
            'day' => $request->day,
            'date' => $request->date,
            'status' => $request->status,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->back()->with('success', 'Availability added successfully!');
    }

    public function bookLecturerAppointment(Request $request, $id)
    {
        $request->validate([
            'time' => 'required'
        ]);

        $lectures = LecturerAvailability::findOrFail($id);
        
        $appointments = new Appointment();
        $appointments->student_id = auth()->user()->id;
        $appointments->availability_id = $lectures->id;
        $appointments->appointment_date = $lectures->date;
        $appointments->time = $request->time;
        $appointments->status = 'pending';
        $appointments->save();
        // Update the availability status to 'unavailable'
        $lectures->status = 'unavailable';
        $lectures->save();
        return redirect()->back()->with('success', 'Appointment booked successfully!');
        
    }
    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'new_date' => 'required|date',
            'new_time' => 'required',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update([
            'appointment_date' => $request->new_date,
            'time' => $request->new_time,
            'status' => 'rescheduled',
        ]);

        return redirect()->back()->with('success', 'Appointment rescheduled successfully.');
    }
    public function approveAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Appointment approved successfully.');
    }
    public function cancelAppointment($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return redirect()->back()->with('error', 'The appointment does not exist.');
        }

        $appointment->update(['status' => 'canceled']);

        return redirect()->back()
            ->with('success', 'Appointment cancelled successfully.');
    }
}
