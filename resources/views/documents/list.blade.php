@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Document List</h4>
        </div>
        <div class="card-body">

        {{-- Filter by Company --}}
        <form method="GET" class="mb-3">
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
        </form>

        {{-- Table to display documents with counts --}}
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
                @foreach($documents as $key => $doc)
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
    </div>
</div>
@endsection
