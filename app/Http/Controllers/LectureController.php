<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\LecturerAvailability;
use App\Models\Student;
use App\Models\StudentAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LectureController extends Controller
{
    public function dashboard()
    {
        $lecturer = Auth::user()->lecturer;

        // All appointments for this lecturer
        $appointments = Appointment::where('availability_id', $lecturer->id)->get();

        // Upcoming appointments
        $upcomingAppointments = $appointments->where('appointment_date', '>=', Carbon::today());

        // Weekly appointment count (last 7 days)
        $weeklyData = Appointment::selectRaw('DATE(appointment_date) as date, COUNT(*) as count')
            ->where('availability_id', $lecturer->id)
            ->where('appointment_date', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $weeklyData->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray();
        $data = $weeklyData->pluck('count')->toArray();

        return view('lecturer.dashboard', compact('appointments', 'upcomingAppointments', 'labels', 'data'));
    }
    public function index()
    {
        $lecturer = Lecturer::where('user_id', Auth::user()->id)->first();
        $availabilities = LecturerAvailability::all();
        return view('lecturer.availability.index', compact('availabilities', 'lecturer'));
    }

    public function Availability()
    {
        $lecturers = Lecturer::where('user_id', Auth::id())->first();
        $availabilities = LecturerAvailability::where('lecturer_id', $lecturers->id)->get();
        return view('lecturer.availability.index', compact('availabilities'));
    }

    public function appointment()
    {
        $lecturer = Lecturer::where('user_id', Auth::id())->first();

        if (!$lecturer) {
            return redirect()->back()->with('error', 'Lecturer profile not found.');
        }

        // Get all availability IDs for the lecturer
        $availabilityIds = LecturerAvailability::where('lecturer_id', $lecturer->id)->pluck('id');

        // Fetch appointments for all the lecturer's availabilities
        $appointments = Appointment::whereIn('availability_id', $availabilityIds)->get();

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

        $user = Auth::user();
        // Find the lecturer associated with the authenticated user
        // Assuming the lecturer is linked to the user through a 'user_id' foreign key
        $lecturer = Lecturer::where('user_id', $user->id)->first();

        LecturerAvailability::create([
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
        $availability = LecturerAvailability::findOrFail($id);


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
    
    public function show()
    {
        $lecture = Lecturer::where('user_id', Auth::id())->first(); // Assuming user has one lecture profile
        $departments = Department::all();

        return view('lecturer.profile.index', compact('lecture', 'departments'));
    }
    public function updateLecture(Request $request, $id)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'department_id' => 'nullable',
            'names' => 'required|string|max:255',
        ]);

        // Find the lecture and ensure it exists
        $lecture = Lecturer::find($id);

        // Update user details
        $lecture->update([
            'names' => $request->names,
            'address' => $request->address,
            'phone' => $request->phone,
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

        $availability = LecturerAvailability::findOrFail($appointment->availability_id);

        $lecturerEmail = $availability->lecturer->user->email ?? null;
        $studentEmail = $appointment->student->user->email ?? null;

        if ($lecturerEmail) {
            Mail::to($lecturerEmail)->send(new \App\Mail\AppointmentBooked($appointment));
        }

        if ($studentEmail) {
            Mail::to($studentEmail)->send(new \App\Mail\AppointmentBookedStudent($appointment));
        }

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
        $lecturerId = Lecturer::where('user_id', Auth::user()->id)->first();

        // Get the lecturer availability
        $availability = LecturerAvailability::findOrFail($availabilityId);

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
    public function viewSchedule()
    {
        $lecturer = Lecturer::where('user_id', Auth::id())->first();

        if (!$lecturer) {
            return redirect()->back()->with('error', 'Lecturer profile not found.');
        }

        $availabilities = LecturerAvailability::where('lecturer_id', $lecturer->id)
            ->whereDate('date', '>=', now())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('lecturer.availability.schedule', compact('availabilities'));
    }
}
