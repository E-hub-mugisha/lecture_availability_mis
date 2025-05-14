<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    //
    public function index()
    {
        // Fetch all appointments from the database
        $appointments = Appointment::all();

        // Pass the appointments to the view
        return view('admin.appointments.index', compact('appointments'));
    }
}
