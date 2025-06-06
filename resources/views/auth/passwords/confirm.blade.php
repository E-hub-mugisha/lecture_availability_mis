@extends('layouts.auth')
@section('title', 'Confirm Password')

@section('content')
<style>
    body {
        background: url('/images/confirm-password-bg.jpg') no-repeat center center fixed;
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
                    <h4 class="fw-bold mb-0">{{ __('Confirm Password') }}</h4>
                    <p class="text-muted small mt-1">Please confirm your password before continuing.</p>
                </div>

                <div class="card-body px-4 py-4">
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password" required autocomplete="current-password" autofocus>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Confirm Password') }}
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="mt-3 text-center">
                                <a class="btn btn-link p-0" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
