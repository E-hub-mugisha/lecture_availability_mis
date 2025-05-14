<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Lecture;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'utype' => ['required', 'in:students,admin,lectures'], // Validate user type
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    // Create a new user instance after a valid registration

    protected function create(array $data)
    {
        // Map user type to database integer values
        $typeMapping = [
            'students' => 0,
            'admin' => 1,
            'lectures' => 2
        ];

        $type = $typeMapping[$data['utype']] ?? 0; // Default to students if invalid

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'type' => $type,
        ]);


        // Automatically create a student or lecturer based on selected role
        if ($data['utype'] === 'students') {
            Student::create([
                'user_id' => $user->id,
                'names' => $data['name'],
                'student_number' => 'STD' . str_pad($user->id, 6, '0', STR_PAD_LEFT), // Generate student number
            ]);
        } elseif ($data['utype'] === 'lectures') {
            Lecture::create([
                'user_id' => $user->id,
                'staff_number' => 'LEC' . str_pad($user->id, 6, '0', STR_PAD_LEFT), // Generate staff number
                'names' => $data['name'],
                'department_id' => null, // Can be updated later
            ]);
        }

        return $user;
    }
}
