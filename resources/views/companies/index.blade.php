@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>Company List
            </div>
            <div>
                <a class="btn btn-sm btn-success" type="button" href="{{ route('companies.create') }}">
                    <i class="fas fa-filter me-1"></i> Add Company 
                </a>
            </div>
        </div>
        <div class="card-body">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="companyTable" class="table table-bordered table-striped " style="width:100%">
        <thead>
            <tr>
                <th>CompanyId</th>
                <th>Name</th>
                <th>Folder Path</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
            <tr>
                <td>{{ $company->id }}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $company->folder_path }}</td>
                <td>
                    <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this company?')">Delete</button>
                    </form>
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
    var table = $('#companyTable').DataTable({
        responsive: true,
        order: [[ 0, 'desc' ]] // Sort by uploaded_at descending
    });
});
</script>
@endsection