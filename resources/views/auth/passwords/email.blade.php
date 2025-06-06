@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<style>
    body {
        background: url('/images/reset-password-bg.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    .overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 0;
    }

    .card-container {
        position: relative;
        z-index: 1;
    }
</style>

<div class="overlay"></div>

<div class="container py-5 card-container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white text-center border-0 rounded-top-4">
                    <h4 class="fw-bold mb-0">{{ __('Reset Password') }}</h4>
                    <p class="text-muted small mt-1">Enter your email to receive reset instructions</p>
                </div>

                <div class="card-body px-4 py-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
