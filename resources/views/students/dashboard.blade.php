@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="container">
    <div class="row justify-content-center">

        {{-- Left Column: Dashboard Info --}}
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Student Dashboard</h5>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <p>Welcome, <strong>{{ Auth::user()->name }}</strong>!</p>

                    <div class="row text-center mb-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <h6>Total Appointments</h6>
                                <h3 class="text-primary">{{ $totalAppointments }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <h6>Upcoming Appointments</h6>
                                <h3 class="text-success">{{ $upcomingAppointments }}</h3>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4">Appointments This Week</h5>
                    <canvas id="appointmentsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Right Column: About Us & User Info --}}
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">About Us</h6>
                </div>
                <div class="card-body">
                    <p>Welcome to the Lecture Availability Management System. We help students schedule appointments with lecturers efficiently and effectively.</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">Your Info</h6>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Role:</strong> Student</p>
                    <p><strong>Member Since:</strong> {{ Auth::user()->created_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('appointmentsChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'Appointments',
                data: {!! json_encode($counts) !!},
                borderColor: '#007bff',
                fill: false,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>
@endsection
