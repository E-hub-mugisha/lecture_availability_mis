@extends('layouts.app')

@section('title', 'Lecture Availability | Student')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Available Lectures</h2>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTables" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Lecturer</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($availabilities as $availability)
                            @php
                                $formattedDate = \Carbon\Carbon::parse($availability->date)->format('l, F j, Y');
                                $startTime = \Carbon\Carbon::parse($availability->start_time)->format('g:i A');
                                $endTime = \Carbon\Carbon::parse($availability->end_time)->format('g:i A');
                            @endphp
                            <tr>
                                <td>{{ $availability->lecturer->names }}</td>
                                <td>{{ $formattedDate }}</td>
                                <td>{{ $startTime }} - {{ $endTime }}</td>
                                <td>
                                    <span class="badge 
                                    {{ $availability->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($availability->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookAppointmentModal{{ $availability->id }}">
                                        <i class="fas fa-calendar-alt"></i> Book Appointment
                                    </button>
                                </td>
                            </tr>

                            <!-- Book Appointment Modal -->
                            <div class="modal fade" id="bookAppointmentModal{{ $availability->id }}" tabindex="-1" aria-labelledby="bookAppointmentLabel{{ $availability->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('student.lecture.appointments.store', $availability->id ) }}">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="bookAppointmentLabel{{ $availability->id }}">
                                                    Book Appointment with {{ $availability->lecturer->names }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="time" class="form-label">Appointment Time</label>
                                                    <input type="time" class="form-control" id="time" name="time" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Confirm Booking</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Book Appointment Modal -->

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
