@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Admin Document Management</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Upload Document --}}
    <div class="card mb-4">
        <div class="card-header">Upload Document</div>
        <div class="card-body">
            <form action="{{ route('admin.docs.uploadDocs') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>Company</label>
                    <select name="company_id" class="form-control">
                        <option value="">-- Select Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Directory Name</label>
                    <input type="text" name="directory_name" class="form-control" placeholder="Default: Company Name">
                </div>

                <div class="mb-3">
                    <label>Choose File</label>
                    <input type="file" name="file" class="form-control" required>
                </div>

                <button class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>

    {{-- Upload Excel --}}
    <div class="card mb-4">
        <div class="card-header">Upload Excel</div>
        <div class="card-body">
            <form action="{{ route('admin.docs.uploadExcel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>Company</label>
                    <select name="company_id" class="form-control" required>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Excel File</label>
                    <input type="file" name="excel_file" class="form-control" required>
                </div>

                <button class="btn btn-success">Import Excel</button>
            </form>
        </div>
    </div>

    {{-- Document List --}}
    <div class="card">
        <div class="card-header">Document List</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Directory</th>
                        <th>File Name</th>
                        <th>Preview</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                    <tr>
                        <td>{{ $doc->company?->name ?? 'N/A' }}</td>
                        <td>{{ $doc->directory_name }}</td>
                        <td>{{ $doc->file_name }}</td>
                        <td>
                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="btn btn-sm btn-info">Preview</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
