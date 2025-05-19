@extends('layouts.app')

@section('title', 'Lecturer Appointments')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Lecturer Appointments
                </div>
                <div class="card-body">
                    <table id="dataTables" class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Lecturer</th>
                                <th scope="col">Student</th>
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr>
                                <th scope="row">{{ $appointment->id }}</th>
                                <td>{{ $appointment->availability->lecturer->names }}</td>
                                <td>{{ $appointment->student->names }}</td>
                                <td>{{ $appointment->appointment_date }}</td>
                                <td>{{ $appointment->time }}</td>
                                <td>
                                    @if($appointment->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($appointment->status == 'confirmed')
                                    <span class="badge bg-success">Confirmed</span>
                                    @else
                                    <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $appointment->id }}">
                                        <i class="fas fa-calendar-alt"></i> Reschedule
                                    </button>

                                    <form action="{{ route('admin.lecturer.appointments.approve', $appointment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.lecturer.appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
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
    </div>
</div>

<!-- Modals (Moved Outside the Loop) -->
@foreach ($appointments as $appointment)
    <div class="modal fade" id="rescheduleModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.lecturer.appointments.reschedule', $appointment->id) }}" method="POST">
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