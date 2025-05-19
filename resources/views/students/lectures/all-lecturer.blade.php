@extends('layouts.app')

@section('title', 'Lecturers List')

@section('content')
<div class="container">
    <h2 class="mb-4">Lecturers List</h2>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach ($lecturers as $lecturer)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $lecturer->names }}</h5>
                    <p class="card-text">View availability to book an appointment</p>

                    <!-- Button to open modal -->
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#availabilityModal{{ $lecturer->id }}">
                        View Availability
                    </button>
                </div>
            </div>
        </div>

        <!-- Availability Modal -->
        <div class="modal fade" id="availabilityModal{{ $lecturer->id }}" tabindex="-1" aria-labelledby="availabilityModalLabel{{ $lecturer->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="availabilityModalLabel{{ $lecturer->id }}">
                            Availability of {{ $lecturer->names }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($lecturer->lecturerAvailabilities->isEmpty())
                        <p>No availability slots available at the moment.</p>
                        @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lecturer->lecturerAvailabilities as $availability)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($availability->date)->format('l, F j, Y') }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $availability->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($availability->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($availability->status == 'available')
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bookModal{{ $lecturer->id }}-{{ $availability->id }}">
                                            <i class="bi bi-calendar-check"></i> Book Appointment
                                        </button>
                                        @else
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#requestModal{{ $lecturer->id }}-{{ $availability->id }}">
                                            <i class="bi bi-envelope"></i> Request Appointment
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Book Appointment & Request Appointment Modals -->
        @foreach ($lecturer->lecturerAvailabilities as $availability)
        <!-- Book Appointment Modal -->
        <div class="modal fade" id="bookModal{{ $lecturer->id }}-{{ $availability->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('student.lecture.appointments.store', $availability->id ) }}" method="POST">
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

        <!-- Request Appointment Modal -->
        <div class="modal fade" id="requestModal{{ $lecturer->id }}-{{ $availability->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('appointments.request', $availability->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title">Request Appointment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                <strong>{{ $lecturer->names }}</strong> is currently unavailable.
                                Would you like to request an appointment for a later time?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning">Request</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
        @endforeach
    </div>
</div>
@endsection