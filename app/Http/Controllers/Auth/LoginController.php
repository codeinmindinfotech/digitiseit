<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
     * @var string
     */
    protected function redirectTo()
    {
        $user = auth()->user();
        if ($user->hasRole('client')) {
            return route('client.documents'); // client dashboard
        }
    
        if ($user->hasRole('admin')) {
            return route('users.index'); // admin dashboard
        }
    }    

    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required|string|email',
            'password' => 'required|string',
        ];
        $user = User::where('email', $request->email)->first();
        if ($user && $user->hasRole('client')) {
            $rules['company_id'] = 'required|exists:companies,id';
        }

        $request->validate($rules);
    }


    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');

        // Only add company_id if selected
        if ($request->filled('company_id')) {
            $credentials['company_id'] = $request->company_id;
        }

        return $credentials;
    }

    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
