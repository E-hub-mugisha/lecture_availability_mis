@extends('layouts.app')

@section('title', 'Appointments | Lecturer')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Appointments</h2>
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
            <table id="dataTables" class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($appointment->student)->names ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('l, F j, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->time)->format('g:i A') }}</td>
                            <td>
                                <span class="badge 
                                    @if($appointment->status == 'pending') bg-warning 
                                    @elseif($appointment->status == 'approved') bg-success 
                                    @elseif($appointment->status == 'canceled') bg-danger 
                                    @elseif($appointment->status == 'requested') bg-secondary
                                    @elseif($appointment->status == 'rescheduled') bg-info
                                    @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $appointment->id }}">
                                    <i class="fas fa-calendar-alt"></i> Reschedule
                                </button>

                                <form action="{{ route('lecturer.appointments.approve', $appointment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>

                                <form action="{{ route('lecturer.appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals (Moved Outside the Loop) -->
@foreach ($appointments as $appointment)
    <div class="modal fade" id="rescheduleModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('lecturer.appointments.reschedule', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reschedule Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="new_date" class="form-label">New Date</label>
                            <input type="date" name="new_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_time" class="form-label">New Time</label>
                            <input type="time" name="new_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

@endsection
