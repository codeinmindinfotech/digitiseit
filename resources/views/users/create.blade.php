@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i> Add User
            </div>
            <div>
                <a class="btn btn-sm btn-success" type="button" href="{{ route('users.index') }}">
                    <i class="fas fa-filter me-1"></i> User List
                </a>
            </div>
        </div>
        <div class="card-body">

            {{-- Display Laravel validation errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('users.store') }}" novalidate >
                @csrf

                <div class="mb-3">
                    <label>User Name <span class="txt-error">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>User Email <span class="txt-error">*</span></label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label>Company <span class="txt-error">*</span></label>
                    <select name="company_id" id="company_id" class="form-control select-2" required>
                        <option value="">-- Select Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" data-folder-path="{{ $company->folder_path }}">
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Directory Name</label>
                    <input type="text" class="form-control" id="directory_name" readonly placeholder="Default: Company Name">
                </div>

                <div class="mb-3">
                    <label>Password <span class="txt-error">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Role <span class="txt-error">*</span></label>
                    <select name="role" class="form-control select-2" required>
                        <option value="">-- Select Role --</option>
                        <option value="admin">Admin</option>
                        <option value="client">Client</option>
                    </select>
                </div>

                <button class="btn btn-primary">Save</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>        
</div>
@endsection
