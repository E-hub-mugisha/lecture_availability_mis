@extends('layouts.app')

@section('title', 'Departments | Admin')

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-4"><i class="fas fa-building"></i> Departments</h4>

                    <!-- Add Department Button -->
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                        <i class="fas fa-plus-circle"></i> Add Department
                    </button>
                </div>
                <div class="card-body">
                    <!-- Department Table -->
                    <table id="dataTables" class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col"><i class="fas fa-list-ol"></i> </th>
                                <th scope="col"><i class="fas fa-building"></i> Department Name</th>
                                <th scope="col"><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departments as $department)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $department->name }}</td>
                                <td>
                                    <!-- Grouped buttons with icons -->
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#assignLecturerModal{{ $department->id }}">
                                            <i class="fas fa-chalkboard-teacher"></i> Assign Lecturer
                                        </button>
                                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Assign Lecturer Modal -->
                            <div class="modal fade" id="assignLecturerModal{{ $department->id }}" tabindex="-1" aria-labelledby="assignLecturerModalLabel{{ $department->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('departments.assign', $department->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="assignLecturerModalLabel{{ $department->id }}">Assign Lecturer to {{ $department->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label for="lecturer">Select Lecturer:</label>
                                                <input type="hidden" name="department_id" value="{{ $department->id }}">
                                                <select name="lecturer_id" class="form-control" required>
                                                    @foreach($lecturers as $lecturer)
                                                    <option value="{{ $lecturer->id }}">{{ $lecturer->names }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Assign</button>
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

        <!-- Add Department Modal -->
        <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addDepartmentModalLabel">Add New Department</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Department Name:</label>
                                <input type="text" name="name" class="form-control" required placeholder="Enter Department Name">
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
    </div>
</div>
@endsection