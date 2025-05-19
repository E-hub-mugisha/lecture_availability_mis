@extends('layouts.app')

@section('title', 'My Schedule')

@section('content')
<div class="container">
    <h2 class="mb-4">My Upcoming Schedule</h2>

    @if($availabilities->isEmpty())
        <div class="alert alert-info">You have no upcoming availability slots.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($availabilities as $slot)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($slot->date)->format('l, F j, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}</td>
                        <td>
                            <span class="badge {{ $slot->status == 'available' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($slot->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
