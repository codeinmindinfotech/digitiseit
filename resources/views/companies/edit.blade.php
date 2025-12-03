@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i> Edit Company
            </div>
            <div>
                <a class="btn btn-sm btn-success" type="button" href="{{ route('companies.index') }}">
                    <i class="fas fa-filter me-1"></i> Company List
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

    <form method="POST" action="{{ route('companies.update', $company->id) }}" novalidate>
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Company Name <span class="txt-error">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ $company->name }}" required>
        </div>
        <div class="mb-3">
            <label>Company Folder Path <span class="txt-error">*</span></label>
            <input type="text" name="folder_path" class="form-control" value="{{ $company->folder_path }}"  required>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('companies.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</div>
</div>
@endsection
