<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\LectureAvailability;
use App\Models\Student;
use App\Models\StudentAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    //
    public function lectureAvailable()
    {
        // Fetch available lecturers and their available times
        $availabilities = LectureAvailability::with('lecturer') // Assuming LectureAvailability has a relationship to Lecturer
            ->where('status', 'available') // Assuming availability has a status field
            ->get();

        $student = Student::where('user_id', Auth::user()->id)->first();

        return view('students.lectures.index', compact('availabilities', 'student'));
    }

    public function bookAppointment(Request $request, $id)
    {
        $request->validate([
            'time' => 'required',
        ]);

        $user = Auth::user();


        // Check if the availability exists
        $availability = LectureAvailability::findOrFail($id);


        // Create the appointment
        $appointment = new Appointment();
        $appointment->student_id = Auth::user()->id;
        $appointment->lecturer_id = $availability->lecturer_id;
        $appointment->availability_id = $availability->id;
        $appointment->time = $request->input('time');
        $appointment->status = 'pending';
        $appointment->save();

        return redirect()->route('student.appointments.index')->with('success', 'Appointment booked successfully.');
    }
    public function appointment()
    {
        $student = Student::where('user_id', Auth::user()->id)->first();

        $appointments = Appointment::where('student_id', $student->id)->get();

        return view('students.appointment.index', compact('appointments'));
    }
    public function cancelAppointment($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return redirect()->back()->with('error', 'The appointment does not exist.');
        }

        $appointment->update(['status' => 'canceled']);

        return redirect()->route('student.appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }
    public function destroyAppointment($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return redirect()->back()->with('error', 'The appointment does not exist.');
        }

        $appointment->delete();

        return redirect()->route('student.appointments.index')->with('success', 'Appointment deleted successfully.');
    }
    public function studentAvailablity()
    {
        $students = Student::where('user_id', Auth::id())->first();
        $availabilities = StudentAvailability::where('student_id', $students->id)->get();
        return view('students.availabilities.index', compact('availabilities'));
    }
    public function storeAvailability(Request $request)
    {
        $request->validate([
            'day' => 'required',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $student = Student::where('user_id', Auth::user()->id)->first();

        StudentAvailability::create([
            'student_id' => $student->id,
            'day' => $request->input('day'),
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('student.availability')->with('success', 'Availability added successfully!');
    }
    public function destroy($id)
    {
        $availability = StudentAvailability::findOrFail($id);

        $availability->delete();

        return redirect()->route('student.availability')->with('success', 'Availability deleted successfully.');
    }
}
