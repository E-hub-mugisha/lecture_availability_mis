@extends('layouts.app')
@section('title', 'Departments')
@section('content')
<div class="container">
    <h2 class="mb-4">My Department</h2>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Button to Open Modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#availabilityModal">
        Add Department
    </button>

    <!-- Availability Table -->
    <table id="dataTables" class="table table-striped">
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
                <td>{{ $availability->date }}</td>
                <td>{{ $availability->start_time }}</td>
                <td>{{ $availability->end_time }}</td>
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
                    <!-- Delete Button -->
                    <form action="{{ route('lecturer.availabilityDelete', $availability->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this availability?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal for Adding Availability -->
<div class="modal fade" id="availabilityModal" tabindex="-1" aria-labelledby="availabilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="availabilityModalLabel">Add Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('lecturer.availability.store') }}">
                    @csrf

                    <!-- Day Selection Field -->
                    <input type="hidden" name="lecturer_id" value="{{ Auth::user()->lecturer->id }}">
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

                    <!-- Date Field -->
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>

                    <!-- Start Time Field -->
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="start_time" name="start_time" required>
                    </div>

                    <!-- End Time Field -->
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" class="form-control" id="end_time" name="end_time" required>
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

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection