@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i> User List Management
            </div>
            <div>
                <a class="btn btn-sm btn-success" type="button" href="{{ route('users.create') }}">
                    <i class="fas fa-filter me-1"></i> Add user
                </a>
            </div>
        </div>
        <div class="card-body">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="usersTable" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
            <tr>
                <th>UserId</th>
                <th>Name</th>
                <th>Email</th>
                <th>CompanyName</th>
                <th>Folder Path</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->company?->name ?? ''}}</td>
                <td>{{ $user->company?->folder_path??"-" }}</td>
                <td>{{ $user->role?? '' }}</td>
                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this user?')">Delete</button>
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
    var table = $('#usersTable').DataTable({
        responsive: true,
        order: [[ 5, 'desc' ]] // Sort by uploaded_at descending
    });
});
</script>
@endsection