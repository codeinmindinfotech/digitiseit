@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>Document List
            </div>
            <div>
                <a class="btn btn-sm btn-success" type="button" href="{{ route('documents.uploadForm') }}">
                    <i class="fas fa-filter me-1"></i> Upload Document 
                </a>
            </div>
        </div>
        <div class="card-body">

        {{-- Filter by Company --}}
        {{-- <form method="GET" class="mb-3" novalidate>
            <div class="row g-2">
                <div class="col-md-6">
                    <select name="company_id" class="form-control">
                        <option value="">-- All Companies --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form> --}}

        {{-- Table to display documents with counts --}}
        <table id="documentsTable" class="table table-bordered table-striped nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Company</th>
                    <th>Directory</th>
                    <th>File Name</th>
                    <th>Search Field</th>
                    <th>Uploaded At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $key => $doc)
                    <tr>
                        <td>{{ $doc->id }}</td>
                        <td>{{ $doc->company?->name ?? 'N/A' }}</td>
                        <td>{{ $doc->directory }}</td>
                        <td>{{ $doc->filename }}</td>
                        <td>{{ $doc->search_field }}</td>
                        <td>{{ $doc->uploaded_at }}</td>
                        
                        <td>
                            <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            
                            <a href="{{ asset('storage/'.$doc->filepath) }}" target="_blank" class="btn btn-sm btn-info">Preview</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    var table = $('#documentsTable').DataTable({
        responsive: true,
        order: [[ 5, 'desc' ]] // Sort by uploaded_at descending
    });

    // Company filter
    // $('#companyFilter').change(function() {
    //     var val = $(this).val();
    //     table.column(3).search(val ? '^' + val + '$' : '', true, false).draw();
    // });

    // $('.select-2').select2({ width: '100%' });
});
</script>
@endsection