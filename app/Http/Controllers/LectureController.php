<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Lecture;
use App\Models\LectureAvailability;
use App\Models\Student;
use App\Models\StudentAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LectureController extends Controller
{
    public function index()
    {
        $lecturer = Lecture::where('user_id', Auth::user()->id)->first();
        $availabilities = LectureAvailability::all();
        return view('lecturer.availability.index', compact('availabilities', 'lecturer'));
    }

    public function Availability()
    {
        $lecturers = Lecture::where('user_id', Auth::id())->first();
        $availabilities = LectureAvailability::where('lecturer_id', $lecturers->id)->get();
        return view('lecturer.availability.index', compact('availabilities'));
    }

    public function appointment()
    {
        $lecturers = Lecture::where('user_id', Auth::id())->first();
        $appointments = Appointment::where('lecturer_id', $lecturers->id)->get();
        return view('lecturer.appointments.index', compact('appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today', // Ensures the date is today or in the future
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', // Valid days of the week
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'status' => 'required | in:available,unavailable,break,lunch,meeting,training',
            
        ]);

        $lecturer = Lecture::where('user_id', Auth::id())->first();
        $existingAvailability = LectureAvailability::where('lecturer_id', $lecturer->id)
            ->where('date', $request->date)
            ->where('day', $request->day)
            ->where('start_time', $request->start_time)
            ->where('end_time', $request->end_time)
            ->first();
        if ($existingAvailability) {
            return redirect()->back()->withErrors('This availability slot already exists.');
        }

        LectureAvailability::create([
            'lecturer_id' => $lecturer->id,
            'day' => $request->day,
            'date' => $request->date,
            'status' => $request->status,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('lecturer.availability.index')->with('success', 'Availability added successfully!');
    }
    public function destroy($id)
    {
        $availability = LectureAvailability::findOrFail($id);


        // Delete the availability
        $availability->delete();

        return redirect()->route('lecturer.availability.index')->with('success', 'Availability deleted successfully.');
    }

    public function students()
    {
        // Fetch all students with their availabilities
        $availabilities = StudentAvailability::all();
        return view('lecturer.students.index', compact('availabilities'));
    }
    // Lecturer books an appointment
    public function bookAppointment(Request $request, $student)
    {
        $request->validate([
            'availability_id' => 'required|exists:student_availabilities,id',
            'student_id' => 'required|exists:students,id',
            'student_availability_id' => 'required|exists:student_availabilities,id',
        ]);

        // Fetch the availability slot
        $availability = StudentAvailability::findOrFail($request->student_availability_id);

        // Check if the slot is still available
        if ($availability->status == 'booked') {
            return back()->withErrors('This slot is already booked.');
        }

        $lecture = Lecture::where('user_id', Auth::id())->first();

        // Create an appointment
        $appointment = new Appointment();
        $appointment->student_id = $student;
        $appointment->lecturer_id = $lecture->id;
        $appointment->availability_id = $availability->id;
        $appointment->appointment_date = $availability->date;
        $appointment->time = $availability->start_time;
        $appointment->status = 'pending';
        $appointment->save();

        // update lecture availability status
        $lectureAvailability = LectureAvailability::findOrFail($lecture->id);
        $lectureAvailability->status = 'requested';
        $lectureAvailability->availability_id = $appointment->id;
        $lectureAvailability->update();

        // Update the availability status to 'booked'
        $availability->availability_id = $appointment->id;
        $availability->status = 'request';
        $availability->update();

        return redirect()->back()->with('success', 'Appointment requested successfully!');
    }
    public function show()
    {
        $lecture = Lecture::where('user_id', Auth::id())->first(); // Assuming user has one lecture profile
        $departments = Department::all();

        return view('lecturer.profile.index', compact('lecture', 'departments'));
    }
    public function updateLecture(Request $request)
    {
        $request->validate([
            'staff_number' => 'required|string|max:255',
            'names' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $lecture = Auth::user()->lecture;

        $lecture->update([
            'staff_number' => $request->staff_number,
            'names' => $request->names,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('lecturer.profile')->with('success', 'Profile updated successfully!');
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
    public function requestAppointment($availabilityId)
    {
        // Find the availability record
        $availability = StudentAvailability::findOrFail($availabilityId);

        // Change the status of the availability to "requested"
        $availability->status = 'request';
        $availability->save(); // Use save() instead of update() to ensure it's saved properly

        // Get the lecturer ID
        $lecturerId = Lecture::where('user_id', Auth::user()->id)->first();

        // Get the lecturer availability
        $availability = LectureAvailability::findOrFail($availabilityId);

        // Create a new appointment record in the appointments table with the "requested" status
        $appointment = new Appointment(); // Assuming you have an Appointment model
        $appointment->student_id = $availability->student_id; // Link to the student
        $appointment->availability_id = $availability->id; // Link to the availability
        $appointment->lecturer_id = $lecturerId->id; // Link to the lecturer
        $appointment->status = 'requested'; // Appointment status
        $appointment->save();

        // Change the status of the availability to "requested"
        $availability->status = 'request';
        $availability->availability_id = $appointment->id; // Link the availability to the appointment
        $availability->save(); // Use save() instead of update() to ensure it's saved properly

        // Send a success message and redirect back
        return redirect()->back()->with('success', 'Appointment request sent.');
    }
    public function allStudent()
    {
        $students = Student::all();
        return view('lecturer.students.students', compact('students'));
    }
}
