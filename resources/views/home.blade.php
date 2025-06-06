@extends('layouts.app')

@section('title', 'Home')

@section('content')
<style>
    body {
        background: url('{{ asset('assets/img/background.webp') }}') no-repeat center center fixed;
        background-size: cover;
        background-attachment: fixed;
    }

    .custom-card {
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(3px);
        border-radius: 1rem;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- Welcome Section --}}
            <div class="card shadow-sm border-0 mb-4 custom-card">
                <div class="card-body text-center">
                    <h3 class="fw-bold mb-3">Welcome to the Lecturer Availability Management System</h3>
                    <p class="text-muted">Easily schedule appointments and manage availability between lecturers and students.</p>

                    {{-- Guest Buttons --}}
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-primary mt-3 px-4">
                            Login to Get Started
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary mt-3 px-4 ms-2">
                            Register
                        </a>
                    @endguest

                    {{-- Authenticated Buttons --}}
                    @auth
                        @if (Auth::user()->role == 'lecturer')
                            <a href="{{ route('lecturer.availability') }}" class="btn btn-success mt-3 px-4">
                                Manage Availability
                            </a>
                        @elseif (Auth::user()->role == 'student')
                            <a href="{{ route('student.appointments') }}" class="btn btn-primary mt-3 px-4">
                                Book Appointment
                            </a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="btn btn-dark mt-3 px-4">
                                Go to Dashboard
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- User Info --}}
            @auth
                <div class="card mb-4 shadow-sm border-0 custom-card">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-person-check-fill me-2"></i> User Information
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <h5>Hello, <strong>{{ Auth::user()->name }}</strong>!</h5>
                        <p class="mb-0">You are logged in as a <strong>{{ ucfirst(Auth::user()->role) }}</strong>.</p>
                    </div>
                </div>
            @endauth

            {{-- About Us --}}
            <div class="card shadow-sm border-0 custom-card">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-info-circle me-2"></i> About Us
                </div>
                <div class="card-body">
                    <p>
                        The <strong>Lecturer Availability Management System</strong> helps simplify academic scheduling between students and lecturers.
                    </p>
                    <p>
                        Whether you're booking an appointment or managing availability, this platform offers a centralized, easy-to-use experience.
                    </p>
                    <p class="mb-0">
                        Have questions? Contact support—we’re here to help.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
