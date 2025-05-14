<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-school"></i> {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                @auth
                @if(Auth::user()->type == 'admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard.index') }}"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.departments.index') }}"><i class="fas fa-building"></i> Departments</a>
                </li>
                <!-- Lecturers Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="lecturerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-chalkboard-teacher"></i> Lecturers
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="lecturerDropdown">
                        <li><a class="dropdown-item" href="{{ route('admin.lecturers.index') }}"><i class="fas fa-list"></i> All Lecturers</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.lecturers.available') }}"><i class="fas fa-users"></i> Lecture Available</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.lecturers.appointments') }}"><i class="fas fa-calendar"></i> Lecture Appointments</a></li>
                    </ul>
                </li>

                <!-- Students Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="studentDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-users"></i> Students
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="studentDropdown">
                        <li><a class="dropdown-item" href="{{ route('admin.students.index') }}"><i class="fas fa-list"></i> All Students</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.students.appointments') }}"><i class="fas fa-calendar"></i> Student Appointments</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="appointmentsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-calendar"></i> Appointments
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="appointmentsDropdown">
                        <li><a class="dropdown-item" href="{{ route('admin.appointments.index') }}"><i class="fas fa-list"></i> All Appointments</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.appointments.create') }}"><i class="fas fa-plus-circle"></i> Schedule Appointment</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.appointments.history') }}"><i class="fas fa-history"></i> Appointment History</a></li>
                    </ul>
                </li>

                @elseif(Auth::user()->type == 'lectures')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('lecturer.availability.index') }}"><i class="fas fa-calendar-check"></i> My Availability</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('lecturer.students.index') }}"><i class="fas fa-user-graduate"></i> Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('lecturer.students.all') }}"><i class="fas fa-calendar"></i> All Student</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('lecturer.appointments') }}"><i class="fas fa-book"></i> Appointments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('lecturer.profile') }}"><i class="fas fa-user-circle"></i> Profile</a>
                </li>
                @elseif(Auth::user()->type == 'students')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('student.lecture.available') }}"><i class="fas fa-calendar"></i> Lecture Available</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('student.appointments.index') }}"><i class="fas fa-calendar-check"></i> Student Appointments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('student.availability') }}"><i class="fas fa-clock"></i> Student Availability</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('student.history') }}"><i class="fas fa-history"></i> Booking History</a>
                </li>
                @endif
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a>
                </li>
                @endif

                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Register</a>
                </li>
                @endif
                @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>