@extends('layouts.app')

@section('title', 'Student Profile')

@section('content')
<div class="container">
    <!-- Profile Content -->
    <div class="main-body">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb bg-light p-2 rounded">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-chalkboard-teacher"></i> Student</a></li>
                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-user"></i> Student Profile</li>
            </ol>
        </nav>

        <div class="row gutters-sm">
            <!-- Profile Image Section -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm text-center p-3">
                    <div class="card-body">
                        <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="User" class="rounded-circle shadow" width="150">
                        <div class="mt-3">
                            <h4><i class="fas fa-user"></i>{{ $student->user->name }}</h4>
                            <p class="text-muted font-size-sm"><i class="fas fa-map-marker-alt"></i> {{ $student->user->address }}</p>
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
                            <div class="col-sm-9 text-secondary">{{ $student->user->email }}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0"><i class="fas fa-phone"></i> Phone</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">{{ $student->phone }}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0"><i class="fas fa-map-marker-alt"></i> Address</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">{{ $student->address }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Department</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $student->department->name ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Buttons Section -->
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <!-- <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#courseModal">
                                    <i class="fas fa-plus"></i> Add Course
                                </button> -->
                                <button class="btn btn-outline-primary me-2">
                                    <i class="fas fa-envelope"></i> Message
                                </button>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                                    <i class="fas fa-edit"></i> Update Profile
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Assign course Modal -->
<div class="modal fade" id="courseModal" tabindex="-1" role="dialog" aria-labelledby="courseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.lecturers.department.update', $student->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Course to Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="student">Select Course:</label>
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <select name="course_id" class="form-control">
                        
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
                <form action="{{ route('admin.students.profile.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT') 
                    
                    <div class="form-group mb-3">
                        <label for="names">names</label>
                        <input type="text" class="form-control" id="names" name="names"
                            value="{{ old('names', $student->names) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address"
                            value="{{ old('address', $student->address) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="{{ old('phone', $student->phone) }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="department">Department</label>
                        <select class="form-control" id="department" name="department_id">
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $student->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
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