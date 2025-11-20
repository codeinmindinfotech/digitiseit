@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Edit Company</h4>
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

    <form method="POST" action="{{ route('companies.update', $company->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Company Name</label>
            <input type="text" name="name" class="form-control" value="{{ $company->name }}" required>
        </div>
        <div class="mb-3">
            <label>Company Email</label>
            <input type="email" name="email" class="form-control"  value="{{ $company->email }}" required>
        </div>
        <div class="mb-3">
            <label>Company Folder Path</label>
            <input type="text" name="folder_path" class="form-control" value="{{ $company->folder_path }}"  required>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('companies.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</div>
</div>
@endsection
