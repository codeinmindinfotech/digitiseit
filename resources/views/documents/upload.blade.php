@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i> Upload Document
            </div>
            <div>
                <a class="btn btn-sm btn-success" type="button" href="{{ route('documents.main.index') }}">
                    <i class="fas fa-filter me-1"></i> Document List
                </a>
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
            <p><strong>Note:</strong> If you upload using an Excel file, make sure all related PDF documents are placed
                inside the <code>public/allFiles</code> folder.</p>

            <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm"
                novalidate>
                @csrf

                <div class="row g-1">

                    <!-- Company -->
                    <div class="col-md-6">
                        <label class="form-label m-0 p-0">
                            Company <span class="text-danger">*</span>
                        </label>
                        <select name="company_id" id="company_id" class="form-select select-2" required>
                            <option value="">-- Select Company --</option>
                            @foreach($companies as $company)
                            <option value="{{ $company->id }}" data-folder-path="{{ $company->folder_path }}">
                                {{ $company->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Directory -->
                    <div class="col-md-6">
                        <label class="form-label m-0 p-0">Directory Name</label>
                        <input type="text" name="directory_name" id="directory_name" class="form-control"
                            placeholder="Default: Company Name">
                    </div>

                    <!-- File Upload -->
                    <div class="col-12">
                        <label class="form-label m-0 p-0">
                            File <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="files[]" id="files" class="form-control" multiple required>
                    </div>

                    <!-- Excel Format Info -->
                    <div class="col-12">
                        <div class="card bg-light border-0">
                            <div class="card-body py-2 px-3">
                                <label class="form-label m-0 p-0">
                                    Excel Format:
                                </label>
                                <div class="row row-cols-7 row-cols-md-7 g-2 text-center small">

                                    <div class="col">
                                        <span class="badge bg-primary w-100">DataID</span>
                                    </div>

                                    <div class="col">
                                        <span class="badge bg-primary w-100">UsersID</span>
                                    </div>

                                    <div class="col">
                                        <span class="badge bg-primary w-100">DataSearchField</span>
                                    </div>

                                    <div class="col">
                                        <span class="badge bg-primary w-100">DocumentName</span>
                                    </div>

                                    <div class="col">
                                        <span class="badge bg-primary w-100">DocumentDirectory</span>
                                    </div>

                                    <div class="col">
                                        <span class="badge bg-primary w-100">DateUploaded</span>
                                    </div>

                                    <div class="col">
                                        <span class="badge bg-primary w-100">MainParent</span>
                                    </div>

                                </div>

                                <div class="mt-2 small text-muted">
                                    Only the above columns will be read during Excel upload.
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-primary px-4">
                            Upload
                        </button>
                        <a href="{{ route('documents.main.index') }}" class="btn btn-secondary">
                            Back to List
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/scripts.js') }}"></script>
@endsection