@extends('layouts.app')

@section('title', 'Lectures | Admin Dashboard')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-chalkboard-teacher"></i> Lectures</h4>
                    
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
                                <td>{{ $lecture->lecturer->staff_number }}</td>
                                <td>{{ $lecture->lecturer->names }}</td>
                                <td>{{ $lecture->lecturer->user->email ?? 'No Email Assigned' }}</td>
                                <td>{{ $lecture->lecturer->phone }}</td>
                                <td>{{ $lecture->lecturer->department ? $lecture->lecturer->department->name : 'No Department Assigned' }}</td>
                                <td>
                                    <a href="{{ route('admin.lecturers.profile', $lecture->lecturer->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ url('admin/lectures/delete/'.$lecture->lecturer->id) }}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                    <a href="{{ route('admin.lectures.availability', $lecture->lecturer->id ) }}" class="btn btn-warning btn-sm">
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
    </div>
</div>
@endsection
