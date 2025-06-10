@extends('layouts.app')

@section('title', 'Lecturer Profile')

@section('content')
<div class="container">
    <!-- Profile Content -->
    <div class="main-body">
        <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Lecture</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lecture Profile</li>
            </ol>
        </nav>

        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="User" class="rounded-circle" width="150">
                            <div class="mt-3">
                                <h4>{{ $lecture->user->name }}</h4>
                                <p class="text-secondary mb-1">{{ $lecture->department->name ?? 'N/A' }}</p>
                                <p class="text-muted font-size-sm">{{ $lecture->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Full Name</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $lecture->user->name }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Email</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $lecture->user->email }}
                            </div>
                        </div>
                        <hr>
                        <!-- Other Profile Details -->
                        <div class="row">
                            <div class="col-sm-3">
                                    <h6 class="mb-0">Phone</h6>
                                </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $lecture->phone ?? 'N/A' }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Address</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $lecture->address ?? 'N/A' }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Department</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $lecture->department->name ?? 'N/A' }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateProfileModal">Update Profile</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

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
                <form action="{{ route('lecturer.profile.update', $lecture->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Laravel requires PUT for updates -->
                    <div class="form-group mb-3">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $lecture->user->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $lecture->user->email }}" required>
                    </div>
                    <!-- Other fields to update -->
                    <div class="form-group mb-3">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" >
                    </div>
                    <div class="form-group mb-3">
                        <label for="department">Department</label>
                        <select class="form-control" id="department" name="department_id">
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $lecture->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection