@extends('layouts.app')

@section('title', 'Students List')

@section('content')
<div class="container">
    <h2 class="mb-4">Students List</h2>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach ($students as $student)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $student->names }}</h5>
                    <p class="card-text">View availability to book an appointment</p>

                    <!-- Button to open modal -->
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#availabilityModal{{ $student->id }}">
                        View Availability
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal for Availability -->
        <div class="modal fade" id="availabilityModal{{ $student->id }}" tabindex="-1" aria-labelledby="availabilityModalLabel{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="availabilityModalLabel{{ $student->id }}">Availability of {{ $student->names }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($student->availabilities->isEmpty())
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
                                @foreach ($student->availability as $availability) <!-- Correct reference for availabilities -->
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($availability->date)->format('l, F j, Y') }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }} - 
                                        {{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}
                                    </td>
                                    <td>
                                        <span class="badge 
                                                    {{ $availability->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($availability->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($availability->status == 'available')
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bookModal{{ $student->id }}-{{ $availability->id }}">
                                            <i class="bi bi-calendar-check"></i> Book Appointment
                                        </button>
                                        @else
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#requestModal{{ $student->id }}-{{ $availability->id }}">
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

        <!-- Book Appointment Modal -->
        @foreach ($student->availability as $availability) <!-- Loop for availability -->
        <div class="modal fade" id="bookModal{{ $student->id }}-{{ $availability->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('lecturer.book.student', $availability->id) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Book Appointment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                            <input type="hidden" name="student_availability_id" value="{{ $availability->id }}"> <!-- Correct reference for availability -->
                            <input type="hidden" name="availability_id" value="{{ $availability->id }}"> <!-- Correct reference for availability -->
                            <p>
                                Confirm booking with <strong>{{ $student->names }}</strong> on
                                <strong>{{ \Carbon\Carbon::parse($availability->date)->format('F j, Y') }}</strong> at
                                <strong>{{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }}</strong>?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Confirm</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Request Appointment Modal -->
        <div class="modal fade" id="requestModal{{ $student->id }}-{{ $availability->id }}" tabindex="-1" aria-hidden="true">
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
                                <strong>{{ $student->names }}</strong> is currently unavailable.
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
