@extends('layouts.app')

@section('title', 'Lecturer Appointments')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Lecturer Appointments
                </div>
                <div class="card-body">
                    <table id="dataTables" class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Lecturer</th>
                                <th scope="col">Student</th>
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr>
                                <th scope="row">{{ $appointment->id }}</th>
                                <td>{{ $appointment->lecturer->names }}</td>
                                <td>{{ $appointment->student->names }}</td>
                                <td>{{ $appointment->date }}</td>
                                <td>{{ $appointment->time }}</td>
                                <td>
                                    @if($appointment->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($appointment->status == 'confirmed')
                                        <span class="badge bg-success">Confirmed</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.lecturerAppointments', ['lecture' => $appointment->lecturer_id]) }}" class="btn btn-info">View</a>
                                    <form action="{{ route('admin.lecturers.appointments', ['lecture' => $appointment->lecturer_id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Cancel</button>
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
</div>

@endsection
