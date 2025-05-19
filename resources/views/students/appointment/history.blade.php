@extends('layouts.app')

@section('title', 'My Appointments | Student')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-calendar-alt"></i> My Appointments</h4>
        </div>
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table id="dataTables" class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Lecturer</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $appointment->lecturer->names }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('l, F j, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->time)->format('g:i A') }}</td>
                            <td>
                                <span class="badge 
                                    @if($appointment->status == 'pending') bg-warning 
                                    @elseif($appointment->status == 'approved') bg-success 
                                    @elseif($appointment->status == 'canceled') bg-danger 
                                    @elseif($appointment->status == 'rescheduled') bg-info
                                    @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <!-- Cancel Button -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $appointment->id }}">
                                    <i class="fas fa-ban"></i> Cancel
                                </button>

                                <!-- Delete Button -->
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $appointment->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>

                        <!-- Cancel Modal -->
                        <div class="modal fade" id="cancelModal{{ $appointment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('student.appointments.cancel', $appointment->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-warning">Cancel Appointment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to cancel this appointment?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning">Yes, Cancel</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal{{ $appointment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('student.appointments.destroy', $appointment->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger">Delete Appointment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to permanently delete this appointment?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection