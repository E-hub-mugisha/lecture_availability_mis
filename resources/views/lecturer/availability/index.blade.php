@extends('layouts.app')
@section('title', 'My Availability | Lecturer Dashoard')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>My Availability</h2>
        <!-- Button to Open Modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#availabilityModal">
            <i class="fas fa-plus"></i> Add Availability
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Availability Table -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTables" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availabilities as $availability)
                        <tr>
                            <td>{{ $availability->day }}</td>
                            <td>{{ \Carbon\Carbon::parse($availability->date)->format('l, F j, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}</td>
                            <td>
                                <span class="badge 
                                    @switch($availability->status)
                                        @case('available') bg-success @break
                                        @case('break') bg-info @break
                                        @case('lunch') bg-warning @break
                                        @case('meeting') bg-primary @break
                                        @case('training') bg-secondary @break
                                        @default bg-danger
                                    @endswitch">
                                    {{ ucfirst($availability->status) }}
                                </span>
                            </td>
                            <td>
                                <!-- Delete Button -->
                                <form action="{{ route('lecturer.availabilityDelete', $availability->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this availability?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Delete">
                                        <i class="fas fa-trash"></i>
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

<!-- Modal for Adding Availability -->
<div class="modal fade" id="availabilityModal" tabindex="-1" aria-labelledby="availabilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="availabilityModalLabel">Add Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('lecturer.availability.store') }}">
                    @csrf

                    <div class="row">
                        <!-- Day Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="day" class="form-label">Day</label>
                            <select class="form-control" id="day" name="day" required>
                                <option value="">Select Day</option>
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required min="{{ now()->format('Y-m-d') }}">
                        </div>

                        <!-- Start Time -->
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>

                        <!-- End Time -->
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>

                        <!-- Status Selection -->
                        <div class="col-md-12 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Select Status</option>
                                @foreach(['available', 'unavailable', 'break', 'lunch', 'meeting', 'training'] as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
