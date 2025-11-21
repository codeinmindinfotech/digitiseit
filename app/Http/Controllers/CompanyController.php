<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index() {
        $companies = Company::companyOnly()->get();
        return view('companies.index', compact('companies'));
    }

    public function create() { return view('companies.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'folder_path'=>'required|string|max:255',
        ]);
        $data = $request->only('name', 'email', 'folder_path');

        // Clean folder_path
        if (!empty($data['folder_path'])) {
            $data['folder_path'] = rtrim($data['folder_path'], "/\\");
        }
        
        $company = Company::create($data);
        
        $companyUser = User::where('company_id', $company->id)->first();
        if ($companyUser) {
            $companyUser->update([
                'name'  => $company->name,
                'email' => $company->email,
            ]);
        }
        $defaultPassword = Str::random(10);

        $user = User::create([
            'company_id' => $company->id,
            'name'       => $company->name,
            'email'      => $company->email,
            'password'   => bcrypt($defaultPassword),
        ]);

        return redirect()->route('companies.index')->with('success', "Company created. Default login password: $defaultPassword");

    }
    
    public function edit(Company $company) { return view('companies.edit', compact('company')); }

    public function update(Request $request, Company $company) {
        $request->validate([
            'name'=>'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'folder_path'=>'required|string|max:255',
        ]);
        $data = $request->only('name', 'email', 'folder_path');

        // Clean folder_path
        if (!empty($data['folder_path'])) {
            $data['folder_path'] = rtrim($data['folder_path'], "/\\");
        }

        $company->update($data);
        return redirect()->route('companies.index')->with('success', 'Company updated.');
    }

    public function destroy(Company $company) {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted.');
    }
}
