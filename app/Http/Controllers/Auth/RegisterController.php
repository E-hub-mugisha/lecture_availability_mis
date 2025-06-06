<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Lecture;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'], // only letters and spaces
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase() // at least one upper and one lower case
                    ->letters()   // ensure letters
                    ->numbers(),  // ensure numbers
            ],
            'utype' => ['required', 'in:students,admin,lectures'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        $typeMapping = [
            'students' => 0,
            'admin' => 1,
            'lectures' => 2
        ];

        $type = $typeMapping[$data['utype']] ?? 0;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'type' => $type,
        ]);

        if ($data['utype'] === 'students') {
            Student::create([
                'user_id' => $user->id,
                'names' => $data['name'],
                'student_number' => 'STD' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
            ]);
        } elseif ($data['utype'] === 'lectures') {
            Lecturer::create([
                'user_id' => $user->id,
                'names' => $data['name'],
                'staff_number' => 'LEC' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                'department_id' => null,
            ]);
        }

        return $user;
    }

    /**
     * Redirect users after registration based on type.
     */
    protected function redirectTo()
    {
        $user = auth()->user();

        return match ($user->type) {
            1 => route('admin.dashboard.index'),
            2 => route('lecturer.availability.index'),
            default => route('students.dashboard.index'),
        };
    }
}
