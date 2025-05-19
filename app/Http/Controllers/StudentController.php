<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentBookedStudent;
use App\Models\Appointment;
use App\Models\Lecture;
use App\Models\Lecturer;
use App\Models\LecturerAvailability;
use App\Models\Student;
use App\Models\StudentAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Student::findOrFail(Auth::user()->id); // assuming User hasOne Student

        // Total appointments count
        $totalAppointments = Appointment::where('student_id', $student->id)->count();

        // Upcoming appointments count
        $upcomingAppointments = Appointment::where('student_id', $student->id)
            ->where('appointment_date', '>=', Carbon::today())
            ->count();

        // Prepare data for a simple weekly appointments chart (next 7 days)
        $dates = [];
        $counts = [];

        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->addDays($i)->format('Y-m-d');
            $dates[] = Carbon::today()->addDays($i)->format('M d');

            $count = Appointment::where('student_id', $student->id)
                ->where('appointment_date', $date)
                ->count();

            $counts[] = $count;
        }

        return view('studen.dashboard', compact('totalAppointments', 'upcomingAppointments', 'dates', 'counts'));
    }
    public function lectureAvailable()
    {
        // Fetch available lecturers and their available times
        $availabilities = LecturerAvailability::with('lecturer') // Assuming LectureAvailability has a relationship to Lecturer
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

        $student = Student::where('user_id', $user->id)->first();


        // Check if the availability exists
        $availability = LecturerAvailability::findOrFail($id);


        // Create the appointment
        $appointment = new Appointment();
        $appointment->student_id = $student->id;
        $appointment->lecturer_id = $availability->lecturer_id;
        $appointment->availability_id = $availability->id;
        $appointment->appointment_date = $availability->date;
        $appointment->time = $request->input('time');
        $appointment->status = 'pending';
        $appointment->save();

        // Send email notification to the lecturer
        $lecturerEmail = $availability->lecturer->user->email ?? null;
        $studentEmail = $student->user->email ?? null;

        if ($lecturerEmail) {
            Mail::to($lecturerEmail)->send(new \App\Mail\AppointmentBooked($appointment));
        }

        if ($studentEmail) {
            Mail::to($studentEmail)->send(new \App\Mail\AppointmentBookedStudent($appointment));
        }

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
    public function lecturers()
    {
        $lecturers = Lecturer::all();
        return view('students.lectures.all-lecturer', compact('lecturers'));
    }
    public function history()
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $appointments = Appointment::where('student_id', $student->id)->get();

        return view('students.appointment.history', compact('appointments'));
    }
}
