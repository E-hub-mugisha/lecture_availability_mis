@extends('layouts.app')

@section('title', 'Student Availability')

@section('content')
<div class="container">
    <h2 class="mb-4">Student Availability</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table id="dataTables" class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($availabilities as $availability)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $availability->student->names }}</td>
                    <td>{{ \Carbon\Carbon::parse($availability->date)->format('l, F j, Y') }}</td>
                    <td>
                        <span class="badge bg-info text-dark">
                            {{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }} - 
                            {{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $availability->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($availability->status) }}
                        </span>
                    </td>
                    <td>
                        @if ($availability->status == 'available')
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bookModal{{ $availability->id }}">
                                <i class="bi bi-calendar-check"></i> Book
                            </button>
                        @elseif ($availability->status == 'requested')
                            <button class="btn btn-sm btn-secondary">Cancel</button>
                        @else
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#requestModal{{ $availability->id }}">
                                <i class="bi bi-envelope"></i> Request
                            </button>
                        @endif
                    </td>
                </tr>

                <!-- Book Appointment Modal -->
                <div class="modal fade" id="bookModal{{ $availability->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('lecturer.book', $availability->id) }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Book Appointment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        Confirm booking with <strong>{{ $availability->student->name }}</strong> on
                                        <strong>{{ \Carbon\Carbon::parse($availability->date)->format('F j, Y') }}</strong>
                                        at <strong>{{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }}</strong>?
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
                <div class="modal fade" id="requestModal{{ $availability->id }}" tabindex="-1" aria-hidden="true">
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
                                        <strong>{{ $availability->student->name }}</strong> is currently unavailable. 
                                        Would you like to request an appointment?
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
            </tbody>
        </table>
    </div>
</div>
@endsection
