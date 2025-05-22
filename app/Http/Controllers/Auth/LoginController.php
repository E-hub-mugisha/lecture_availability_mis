<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        if ($user->type === 1) {
            return route('admin.dashboard.index'); // or '/admin/dashboard'
        } elseif ($user->type === 0) {
            return route('students.dashboard.index'); // or '/students/dashboard'
        } elseif ($user->type === 2) {
            return route('lecturer.dashboard.index'); // or '/lecturer/dashboard'
        }

        // Default redirect path if type does not match above
        return '/home';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
