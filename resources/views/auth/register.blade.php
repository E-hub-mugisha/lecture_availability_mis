@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<style>
    body {
        background: url('assets/img/background.webp') no-repeat center center fixed;
        background-size: cover;
    }
    .overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.45);
        z-index: 0;
    }
    .card-container {
        position: relative;
        z-index: 1;
        padding-top: 60px;
        padding-bottom: 60px;
    }
</style>

<div class="overlay"></div>

<div class="container card-container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white text-center border-0 rounded-top-4">
                    <h4 class="fw-bold mb-0">{{ __('Register') }}</h4>
                </div>
                <div class="card-body px-4 py-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password" required autocomplete="new-password">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end">{{ __('Register As') }}</label>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="utype" id="admin" value="admin" required
                                        {{ old('utype') == 'admin' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="admin">Admin</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="utype" id="student" value="student" required
                                        {{ old('utype') == 'student' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="student">Student</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="utype" id="lectures" value="lectures"
                                        {{ old('utype') == 'lectures' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="lectures">Lecturer</label>
                                </div>
                                @error('utype')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-0 row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
