@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Admin Dashboard</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Total Lecturers Card -->
                        <div class="col-md-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Lecturers</h5>
                                    <p class="card-text">{{ $lecturersCount }} Lecturers</p>
                                    <a href="{{ route('admin.lecturers.index') }}" class="btn btn-primary btn-sm">Manage Lecturers</a>
                                </div>
                            </div>
                        </div>

                        <!-- Total Students Card -->
                        <div class="col-md-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Students</h5>
                                    <p class="card-text">{{ $studentsCount }} Students</p>
                                    <a href="{{ route('admin.students.index') }}" class="btn btn-primary btn-sm">Manage Students</a>
                                </div>
                            </div>
                        </div>

                        <!-- Total Appointments Card -->
                        <div class="col-md-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Appointments</h5>
                                    <p class="card-text">{{ $appointmentsCount }} Appointments</p>
                                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-primary btn-sm">Manage Appointments</a>
                                </div>
                            </div>
                        </div>

                        <!-- Total Departments Card -->
                        <div class="col-md-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Departments</h5>
                                    <p class="card-text">{{ $departmentsCount }} Departments</p>
                                    <a href="{{ route('admin.departments.index') }}" class="btn btn-primary btn-sm">Manage Departments</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <!-- Recent Appointments Section -->
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5>Recent Appointments</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($recentAppointments as $appointment)
                                        <li class="list-group-item">
                                            {{ $appointment->user->name }} - {{ $appointment->appointment_date }}
                                        </li>
                                        @endforeach
                                    </ul>
                                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-primary btn-sm mt-2">View All Appointments</a>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Lecturers Section -->
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5>Recent Lecturers</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($recentLecturers as $lecturer)
                                        <li class="list-group-item">
                                            {{ $lecturer->user->name }} - {{ $lecturer->department->name ?? 'No Department' }}
                                        </li>
                                        @endforeach
                                    </ul>
                                    <a href="{{ route('admin.lecturers.index') }}" class="btn btn-primary btn-sm mt-2">View All Lecturers</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
