<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginCode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ClientLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->hasRole('client')) {
            return back()->withErrors(['email' => 'Client not found.']);
        }

        $request->validate([
            'email' => 'required|email',
            'company_id' => 'required|exists:companies,id'
        ]);

        if ($user->company_id != $request->company_id) {
            return back()->withErrors(['company_id' => 'Email does not belong to this company.']);
        }

        // Step 1: send code if not submitted
        if (!$request->filled('code')) {
            $code = rand(100000,999999);

            LoginCode::create([
                'email' => $user->email,
                'company' => $user->company_id,
                'code' => $code,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]);

            Mail::raw("Your login code is: $code", function($msg) use ($user){
                $msg->to($user->email)->subject('Your Login Code');
            });

            return back()->with([
                'email' => $user->email,
                'company_id' => $user->company_id,
                'code_sent' => true
            ])->withInput($request->except('password'));
        }

        // Step 2: verify code
        $loginCode = LoginCode::where('email', $user->email)
            ->where('company', $user->company_id)
            ->where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$loginCode) {
            return back()->withErrors(['code' => 'Invalid or expired code.']);
        }

        Auth::login($user);
        LoginCode::where('email', $user->email)->delete();

        return redirect()->route('client.documents');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.login');
    }
}
