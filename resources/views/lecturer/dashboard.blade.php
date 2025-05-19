@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Lecturer Dashboard</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary p-3 rounded">
                <h5>Total Appointments</h5>
                <h3>{{ $appointments->count() }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success p-3 rounded">
                <h5>Upcoming Appointments</h5>
                <h3>{{ $upcomingAppointments->count() }}</h3>
            </div>
        </div>
    </div>

    <div class="card mb-4 p-3">
        <h5>Appointments This Week</h5>
        <canvas id="appointmentsChart"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('appointmentsChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Appointments',
                data: {!! json_encode($data) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        },
        options: {
            responsive: true,
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
