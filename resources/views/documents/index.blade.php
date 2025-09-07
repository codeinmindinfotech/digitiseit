@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Document List</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filter by Company --}}
    <form method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
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
    </form>

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
                <td>{{ $doc->directory }}</td>
                <td>{{ $doc->filename }}</td>
                <td>
                    <a href="{{ asset('storage/'.$doc->filepath) }}" target="_blank" class="btn btn-sm btn-info">Preview</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
