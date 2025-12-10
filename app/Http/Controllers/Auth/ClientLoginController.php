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
        $request->validate([
            'email'        => 'required|email',
            'company_name' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->hasRole('client')) {
            return back()->withErrors(['email' => 'Client not found.']);
        }

        // Validate company name
        if (strcasecmp($user->company->name, $request->company_name) !== 0) {
            return back()->withErrors(['company_name' => 'Email does not belong to this company.']);
        }

        // STEP 1 — If no code is entered: send code
        if (!$request->filled('code')) {
            $code = rand(100000, 999999);

            LoginCode::create([
                'email'      => $user->email,
                'company'    => $user->company->name,
                'code'       => $code,
                'expires_at' => now()->addMinutes(5),
            ]);

            Mail::raw("Your login code is: $code", function ($msg) use ($user) {
                $msg->to($user->email)->subject('Your Login Code');
            });

            return back()->with([
                'email'        => $user->email,
                'company_name' => $user->company->name,
                'code_sent'    => true
            ]);
        }

        // STEP 2 — Verify code
        $loginCode = LoginCode::where('email', $user->email)
            ->where('company', $user->company->name)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
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
