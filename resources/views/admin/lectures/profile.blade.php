@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container">
    <!-- Profile Content -->
    <div class="main-body">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb bg-light p-2 rounded">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-chalkboard-teacher"></i> Lecture</a></li>
                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-user"></i> Lecture Profile</li>
            </ol>
        </nav>

        <div class="row gutters-sm">
            <!-- Profile Image Section -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm text-center p-3">
                    <div class="card-body">
                        <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="User" class="rounded-circle shadow" width="150">
                        <div class="mt-3">
                            <h4><i class="fas fa-user"></i>{{ $lecture->user->name }}</h4>
                            <p class="text-muted font-size-sm"><i class="fas fa-map-marker-alt"></i> {{ $lecture->user->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details Section -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0"><i class="fas fa-envelope"></i>Email</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">{{ $lecture->user->email }}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0"><i class="fas fa-phone"></i> Phone</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">{{ $lecture->user->phone }}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0"><i class="fas fa-map-marker-alt"></i> Address</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">{{ $lecture->user->address }}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0"><i class="fas fa-building"></i> Department</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">{{ $lecture->department ? $lecture->department->name : 'No Department Assigned' }}</div>
                        </div>
                        <hr>

                        <!-- Buttons Section -->
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#departmentModal">
                                    <i class="fas fa-plus"></i> Add Department
                                </button>
                                <button class="btn btn-outline-primary me-2">
                                    <i class="fas fa-envelope"></i> Message
                                </button>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                                    <i class="fas fa-edit"></i> Update Profile
                                </button>
                                <a href="{{ route('admin.lectures.availability', $lecture->id ) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-clock"></i> Availability
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Assign Lecturer Modal -->
<div class="modal fade" id="departmentModal" tabindex="-1" role="dialog" aria-labelledby="departmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.lecturers.department.update', $lecture->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Lecturer to Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="lecturer">Select Department:</label>
                    <input type="hidden" name="lecture_id" value="{{ $lecture->id }}">
                    <select name="department_id" class="form-control">
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
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

<!-- Modal for Update Profile -->
<div class="modal fade" id="updateProfileModal" tabindex="-1" role="dialog" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProfileModalLabel">Update Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.lecturers.profile.update', $lecture->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Laravel requires PUT for updates -->

                    <div class="form-group mb-3">
                        <label for="names">names</label>
                        <input type="text" class="form-control" id="names" name="names"
                            value="{{ old('names', $lecture->user->names) }}" required autocomplete="off">
                    </div>

                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address"
                            value="{{ old('address', $lecture->user->address) }}" required autocomplete="off">
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="{{ old('phone', $lecture->user->phone) }}" required autocomplete="off">
                    </div>
                    <div class="form-group mb-3">
                        <label for="lecturer">Select Department:</label>
                        <select name="department_id" class="form-control">
                            <option value="">Select Department</option>
                            @if($lecture->department)
                            <option value="{{ $lecture->department->id }}" selected>{{ $lecture->department->name }}</option>
                            @endif

                            @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Submit Button -->
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection