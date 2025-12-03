@extends('layouts.app')

@section('content')
@php
    $Authuser = auth()->user();
@endphp
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i> Edit User
            </div>
            <div>
                <a class="btn btn-sm btn-success" type="button" href="{{ route('users.index') }}">
                    <i class="fas fa-filter me-1"></i> User List
                </a>
            </div>
        </div>
        <div class="card-body">

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.update', $user->id) }}" novalidate>
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>User Name <span class="txt-error">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="mb-3">
            <label>User Email <span class="txt-error">*</span></label>
            <input type="email" name="email" class="form-control"  value="{{ $user->email }}" required>
        </div>
        <div class="mb-3">
            <label>Company <span class="txt-error">*</span></label>
            <select name="company_id" id="company_id" class="form-control select-2" {{ ($user->role === 'admin' && $user->id === $Authuser->id) ? 'disabled' : 'required' }}>
                <option value="">-- Select Company --</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" data-folder-path="{{ $company->folder_path }}"
                         {{($company->id == $user->company_id) ? "selected" : "";}}
                        >{{ $company->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Directory Name</label>
            <input type="text" class="form-control" id="directory_name" value="{{$user->company->folder_path ?? ''}}" readonly  placeholder="Default: Company Name">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="" >
        </div>
        <div class="mb-3">
            <label>Role <span class="txt-error">*</span></label>
            <select name="role" class="form-control select-2" required>
                <option value="">-- Select Role --</option>
                <option value="admin" {{($user->role == 'admin') ? "selected" : "";}}>Admin</option>
                <option value="client" {{($user->role == 'client') ? "selected" : "";}}>Client</option>
            </select>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</div>
</div>
@endsection