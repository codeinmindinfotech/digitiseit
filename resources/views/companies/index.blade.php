@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Company List</h4>
        </div>
        <div class="card-body">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('companies.create') }}" class="btn btn-success mb-3">Add Company</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>CompanyId</th>
                <th>Name</th>
                <th>Email</th>
                <th>Folder Path</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
            <tr>
                <td>{{ $company->id }}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $company->email }}</td>
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
