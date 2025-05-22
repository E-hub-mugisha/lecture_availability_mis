@extends('layouts.app')
@section('title', 'Home')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        
            {{-- User Information Card --}}
            <div class="card mb-4">
                <div class="card-header">{{ __('User Information') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Account Type:</strong> 
                        @if(Auth::user()->type == 1)
                            Admin
                        @elseif(Auth::user()->type == 2)
                            Lecturer
                        @else
                            Student
                        @endif
                    </p>

                    <p class="mt-3">You are successfully logged in.</p>
                </div>
            </div>

            {{-- About Us Card --}}
            <div class="card">
                <div class="card-header">{{ __('About Us') }}</div>

                <div class="card-body">
                    <p>
                        Welcome to the Lecturer Availability Management System. This platform is designed to streamline appointment scheduling between students and lecturers. 
                        Our goal is to improve academic engagement and minimize scheduling conflicts by providing a centralized, easy-to-use interface for all users.
                    </p>
                    <p>
                        If you have any questions or feedback, please don't hesitate to reach out to our support team. We’re here to help you make the most of your academic journey.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
