@extends('layouts.app')

@section('title', 'Lectures | Admin Dashboard')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-chalkboard-teacher"></i> Lectures</h4>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addLectureModal">
                        <i class="fas fa-plus"></i> Add New Lecture
                    </button>
                </div>
                <div class="card-body">
                    <table id="dataTables" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th scope="col"><i class="fas fa-list-ol"></i> Lecture Number</th>
                                <th scope="col"><i class="fas fa-user"></i> Names</th>
                                <th scope="col"><i class="fas fa-envelope"></i> Email</th>
                                <th scope="col"><i class="fas fa-phone"></i> Phone</th>
                                <th scope="col"><i class="fas fa-building"></i> Department</th>
                                <th scope="col"><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lectures as $lecture)
                            <tr>
                                <td>{{ $lecture->staff_number }}</td>
                                <td>{{ $lecture->names }}</td>
                                <td>{{ $lecture->user->email }}</td>
                                <td>{{ $lecture->phone }}</td>
                                <td>{{ $lecture->department ? $lecture->department->name : 'No Department Assigned' }}</td>
                                <td>
                                    <a href="{{ route('admin.lecturers.profile', $lecture->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>

                                    <form action="{{ route('admin.lecturers.destroy', $lecture->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this lecturer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.lectures.availability', $lecture->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-clock"></i> Availability
                                    </a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Lecture Modal -->
        <div class="modal fade" id="addLectureModal" tabindex="-1" aria-labelledby="addLectureModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLectureModalLabel"><i class="fas fa-plus-circle"></i> Add New Lecture</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.lecturers.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="names" class="form-label"><i class="fas fa-user"></i> Names</label>
                                <input type="text" class="form-control" id="names" name="names" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"><i class="fas fa-key"></i> Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label"><i class="fas fa-phone"></i> Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label"><i class="fas fa-map-marker-alt"></i> Address</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="mb-3">
                                <label for="department_id" class="form-label"><i class="fas fa-building"></i> Department</label>
                                <select class="form-control" id="department_id" name="department_id">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Lecture</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection