<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index() {
        
        $query = User::with('company');

        if (Auth::check() && Auth::user()->company_id) {
            $query->where('company_id', Auth::user()->company_id);
        }
        $users = $query->get();
        return view('users.index', compact('users'));
    }

    public function create() 
    { 
        $companies = Company::companyOnly()->get();
        return view('users.create',compact('companies')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'company_id' => 'required|integer|exists:companies,id',
            'password'    => 'required|string|max:255',
            'role'        => 'required|in:admin,client',
        ]);
    
        $password = $request->password;
    
        // Create user
        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt($password),
            'company_id' => $request->company_id,
            'role' => $request->role,
        ]);
    
        return redirect()->route('users.index')->with('success', "User created. Default login password: $password");
    }
    
    
    public function edit(User $user)
    { 
        $user = User::with('company')->find($user->id);
        $companies = Company::companyOnly()->get();
        return view('users.edit', compact('user','companies')); 
    }

    public function update(Request $request, User $user)
    {
        $Authuser = auth()->user();

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'company_id'  => ($user->role === 'admin' && $user->id === $Authuser->id) 
            ? 'nullable|integer|exists:companies,id'
            : 'required|integer|exists:companies,id',
            'password'    => 'nullable|string|max:255', // allow empty to keep old password
            'role'        => 'required|in:admin,client',
        ]);
    
        // Update user fields
        $userData = $request->only('name', 'email','company_id','role');
    
        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }
    
        $user->update($userData);
    
        return redirect()->route('users.index')->with('success', 'User updated.');
    }
    

    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}
