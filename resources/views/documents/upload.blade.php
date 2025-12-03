@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i> Upload Document
            </div>
            <div>
                {{-- <a class="btn btn-sm btn-success" type="button" href="{{ route('users.index') }}">
                    <i class="fas fa-filter me-1"></i> User List
                </a> --}}
            </div>
        </div>
        <div class="card-body">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <p><strong>Note:</strong> If you upload using an Excel file, make sure all related PDF documents are placed inside the <code>public/allFiles</code> folder.</p>

    <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm" novalidate>
        @csrf
        <div class="mb-3">
            <label>Company <span class="txt-error">*</span></label>
            <select name="company_id" id="company_id" class="form-control select-2" required>
                <option value="">-- Select Company --</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" data-folder-path="{{ $company->folder_path }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Directory Name</label>
            <input type="text" name="directory_name" id="directory_name" class="form-control" placeholder="Default: Company Name">
        </div>

        <div class="mb-3">
            <label>File <span class="txt-error">*</span></label>
            <input type="file" name="files[]" id="files" class="form-control" multiple required>
        </div>

        <button class="btn btn-primary">Upload</button>
        <a href="{{ route('documents.main.index') }}" class="btn btn-secondary">Back to List</a>
    </form>
</div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/scripts.js') }}"></script>
@endsection
