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

    public function create() { return view('users.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'folder_path' => 'required|string|max:255',
            'password'    => 'required|string|max:255',
        ]);
    
        $password = $request->password;
    
        // Create user
        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt($password),
        ]);
    
        // Create company based on the user
        $company = Company::create([
            'name'        => $user->name,
            'email'       => $user->email,
            'folder_path' => $request->folder_path,
        ]);
    
        // Link user to the company
        $user->update([
            'company_id' => $company->id,
        ]);
    
        return redirect()->route('users.index')->with('success', "User created. Default login password: $password");
    }
    
    
    public function edit(User $user) { return view('users.edit', compact('user')); }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'folder_path' => 'required|string|max:255',
            'password'    => 'nullable|string|max:255', // allow empty to keep old password
        ]);
    
        // Update user fields
        $userData = $request->only('name', 'email');
    
        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }
    
        $user->update($userData);
    
        // Update corresponding company if exists
        if ($user->company_id) {
            $company = Company::find($user->company_id);
            if ($company) {
                $company->update([
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'folder_path' => $request->folder_path,
                ]);
            }
        }
    
        return redirect()->route('users.index')->with('success', 'User updated.');
    }
    

    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}
