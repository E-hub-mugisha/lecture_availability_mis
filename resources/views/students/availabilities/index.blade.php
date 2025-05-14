@extends('layouts.app')

@section('title', 'Student Availability')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-clock"></i> My Availability</h4>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addAvailabilityModal">
                <i class="fas fa-plus-circle"></i> Add Availability
            </button>
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
                            <th>Day</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($availabilities as $availability)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $availability->day }}</td>
                            <td>{{ \Carbon\Carbon::parse($availability->date)->format('l, F j, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}</td>
                            <td>
                                @if($availability->status == 'available')
                                <span class="badge bg-success">Available</span>
                                @elseif($availability->status == 'break')
                                <span class="badge bg-info">Break</span>
                                @elseif($availability->status == 'lunch')
                                <span class="badge bg-warning">Lunch</span>
                                @elseif($availability->status == 'meeting')
                                <span class="badge bg-primary">Meeting</span>
                                @elseif($availability->status == 'training')
                                <span class="badge bg-secondary">Training</span>
                                @else
                                <span class="badge bg-danger">Unavailable</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $availability->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal{{ $availability->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('student.availabilityDelete', $availability->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger">Delete Availability</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this availability slot?
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

<!-- Add Availability Modal -->
<div class="modal fade" id="addAvailabilityModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('student.availability.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Availability</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">

                        <label for="day" class="form-label">Day</label>
                        <select class="form-control" id="day" name="day" required>
                            <option value="">Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Select Date:</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time:</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time:</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                    <!-- Status Field -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                            <option value="break">Break</option>
                            <option value="lunch">Lunch</option>
                            <option value="meeting">Meeting</option>
                            <option value="training">Training</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection