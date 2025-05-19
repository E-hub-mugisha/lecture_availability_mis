<?php

use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Protected routes that require authentication
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Auth::routes();




/*------------------------------------------
--------------------------------------------
All Normal Users Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:students'])->group(function () {
    Route::get('/students/dashboard', [App\Http\Controllers\StudentController::class, 'dashboard'])->name('students.dashboard.index');
    Route::get('/student/profile', [StudentController::class, 'edit'])->name('student.profile.edit');
    Route::post('/student/profile', [StudentController::class, 'update'])->name('student.profile.update');
    Route::get('/student/lecture/available', [StudentController::class, 'lectureAvailable'])->name('student.lecture.available');
    Route::get('/student/appointment/booking', [StudentController::class, 'appointment'])->name('student.appointments.index');
    Route::post('/student/appointment/{id}/store', [StudentController::class, 'bookAppointment'])->name('student.lecture.appointments.store');
    Route::delete('/student/appointment/delete/{id}', [StudentController::class, 'destroyAppointment'])->name('student.appointments.destroy');
    Route::put('/student/appointment/{id}/cancel', [StudentController::class, 'cancelAppointment'])->name('student.appointments.cancel');

    Route::get('/student/availability', [StudentController::class, 'studentAvailablity'])->name('student.availability');
    Route::post('/student/availability', [StudentController::class, 'storeAvailability'])->name('student.availability.store');
    Route::delete('/student/availability/{id}', [StudentController::class, 'destroy'])->name('student.availabilityDelete');
    Route::get('/student/history', [StudentController::class, 'history'])->name('student.history');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/student/lecturers', [StudentController::class, 'lecturers'])->name('student.lecturers');
});

/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard.index');
    Route::get('/admin/departments', [DepartmentController::class, 'index'])->name('admin.departments.index');
    Route::post('/admin/departments/store', [DepartmentController::class, 'store'])->name('departments.store');
    Route::post('/admin/departments/{department}/assign', [DepartmentController::class, 'assignLecturer'])->name('departments.assign');
    Route::delete('/admin/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    // Lecturer Routes
    Route::get('/admin/lecturers', [App\Http\Controllers\Admin\LectureController::class, 'index'])->name('admin.lecturers.index');
    Route::get('/admin/lecturers/create', [App\Http\Controllers\Admin\LectureController::class, 'create'])->name('admin.lecturers.create');
    Route::post('/admin/lecturers/store', [App\Http\Controllers\Admin\LectureController::class, 'store'])->name('admin.lecturers.store');
    Route::get('/admin/lecturers/{lecture}/edit', [App\Http\Controllers\Admin\LectureController::class, 'edit'])->name('admin.lecturers.edit');
    Route::put('/admin/lecturers/{lecture}', [App\Http\Controllers\Admin\LectureController::class, 'update'])->name('admin.lecturers.update');
    Route::delete('/admin/lecturers/{lecture}', [App\Http\Controllers\Admin\LectureController::class, 'destroy'])->name('admin.lecturers.destroy');
    Route::get('/admin/lecturers/available', [App\Http\Controllers\Admin\LectureController::class, 'availableLecture'])->name('admin.lecturers.available');
    Route::get('/admin/lecturers/{id}/availability', [App\Http\Controllers\Admin\LectureController::class, 'availability'])->name('admin.lectures.availability');
    Route::post('/admin/lecturers/{id}/availability', [App\Http\Controllers\Admin\LectureController::class, 'storeAvailability'])->name('admin.lecturer.availability.store');
    Route::delete('/admin/lecturers/{lecture}/availability/{id}', [App\Http\Controllers\Admin\LectureController::class, 'destroy'])->name('admin.lecturers.availabilityDelete');

    Route::get('/admin/lecturers/appointments', [App\Http\Controllers\Admin\LectureController::class, 'appointments'])->name('admin.lecturers.appointments');
    Route::get('/admin/lecturers/{lecture}/appointments', [App\Http\Controllers\Admin\LectureController::class, 'lectureAppointments'])->name('admin.lecturerAppointments');
    Route::get('/admin/lecturers/{lecture}/students', [App\Http\Controllers\Admin\LectureController::class, 'students'])->name('admin.lecturers.students');
    Route::post('/admin/lecturers/{lecture}/students/{student}', [App\Http\Controllers\Admin\LectureController::class, 'bookAppointment'])->name('admin.lecturers.book');
    Route::get('/admin/lecturers/{lecture}/profile', [App\Http\Controllers\Admin\LectureController::class, 'showLecture'])->name('admin.lecturers.profile');
    Route::put('/admin/lecturersInfo/{lecture}', [App\Http\Controllers\Admin\LectureController::class, 'updateLecture'])->name('admin.lecturers.profile.update');
    Route::post('/admin/lecturers/{lecture}/department', [App\Http\Controllers\Admin\LectureController::class, 'updateDepartment'])->name('admin.lecturers.department.update');

    Route::post('/admin/lecturer/bookAppointment/{id}', [App\Http\Controllers\Admin\LectureController::class, 'bookLecturerAppointment'])->name('admin.lecturer.bookAppointment');
    
    // Student Routes
    Route::get('/admin/students', [App\Http\Controllers\Admin\StudentsController::class, 'index'])->name('admin.students.index');
    Route::get('/admin/students/create', [App\Http\Controllers\Admin\StudentsController::class, 'create'])->name('admin.students.create');
    Route::post('/admin/students/store', [App\Http\Controllers\Admin\StudentsController::class, 'store'])->name('admin.students.store');
    Route::get('/admin/students/{student}/edit', [App\Http\Controllers\Admin\StudentsController::class, 'edit'])->name('admin.students.edit');
    Route::put('/admin/students/{student}', [App\Http\Controllers\Admin\StudentsController::class, 'update'])->name('admin.students.update');
    Route::delete('/admin/students/{student}', [App\Http\Controllers\Admin\StudentsController::class, 'destroy'])->name('admin.students.destroy');
    Route::get('/admin/students/appointments', [App\Http\Controllers\Admin\StudentsController::class, 'studentAppointments'])->name('admin.students.appointments');
    Route::get('/admin/students/{student}/profile', [App\Http\Controllers\Admin\StudentsController::class, 'studentProfile'])->name('admin.students.profile');
    Route::put('/admin/students/profile/{id}', [App\Http\Controllers\Admin\StudentsController::class, 'updateStudent'])->name('admin.students.profile.update');

    // View all appointments
    Route::get('appointments', [App\Http\Controllers\Admin\AppointmentController::class, 'index'])->name('admin.appointments.index');

    // Create a new appointment
    Route::get('appointments/create', [App\Http\Controllers\Admin\AppointmentController::class, 'create'])->name('admin.appointments.create');
    Route::post('appointments', [App\Http\Controllers\Admin\AppointmentController::class, 'store'])->name('admin.appointments.store');

    // View appointment history (if required)
    Route::get('appointments/history', [App\Http\Controllers\Admin\AppointmentController::class, 'history'])->name('admin.appointments.history');

    
    Route::put('/admin/lecturer/appointments/{id}/reschedule', [App\Http\Controllers\Admin\LectureController::class, 'reschedule'])->name('admin.lecturer.appointments.reschedule');
    Route::put('/admin/lecturer/appointments/{id}/cancel', [App\Http\Controllers\Admin\LectureController::class, 'cancelAppointment'])->name('admin.lecturer.appointments.cancel');
    Route::put('/admin/lecturer/appointments/approve/{id}', [App\Http\Controllers\Admin\LectureController::class, 'approveAppointment'])->name('admin.lecturer.appointments.approve');
});

/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:lectures'])->group(function () {
    Route::get('/lecturer/dashboard', [App\Http\Controllers\LectureController::class, 'dashboard'])->name('lecturer.dashboard.index');
    Route::get('/lecturer/availability', [LectureController::class, 'index'])->name('lecturer.availability.index');
    Route::post('/lecturer/availability/store', [LectureController::class, 'store'])->name('lecturer.availability.store');
    Route::get('/lecturer/appointments', [LectureController::class, 'appointment'])->name('lecturer.appointments');
    Route::delete('/lecturer/availability/{id}', [LectureController::class, 'destroy'])->name('lecturer.availabilityDelete');
    Route::get('lecturer/students', [LectureController::class, 'students'])->name('lecturer.students.index');
    Route::get('lecturer/students/all', [LectureController::class, 'allStudent'])->name('lecturer.students.all');
    Route::post('lecturer/book/{student}', [LectureController::class, 'bookAppointment'])->name('lecturer.book.student');
    Route::get('/manager/home', [HomeController::class, 'managerHome'])->name('manager.home');
    Route::get('/lecturer/profile', [LectureController::class, 'show'])->name('lecturer.profile');
    Route::post('/lecturer/profile', [LectureController::class, 'updateLecture'])->name('lecturer.profile.update');
    Route::post('/lecturer/department', [LectureController::class, 'updateDepartment'])->name('lecturer.department.update');
    Route::put('/lecturer/appointments/{id}/reschedule', [LectureController::class, 'reschedule'])->name('lecturer.appointments.reschedule');
    Route::put('/lecturer/appointments/{id}/cancel', [LectureController::class, 'cancelAppointment'])->name('lecturer.appointments.cancel');
    Route::put('/lecturer/appointments/approve/{id}', [LectureController::class, 'approveAppointment'])->name('lecturer.appointments.approve');
    Route::put('/lecturer/appointments/request/{id}', [LectureController::class, 'requestAppointment'])->name('appointments.request');

    Route::get('/lecturer/schedule', [LectureController::class, 'viewSchedule'])->name('lecturer.schedule');

});
