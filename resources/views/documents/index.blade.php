@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Document List</h2>

    {{-- Filter by Company --}}
    <form method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col-md-6">
                <select name="company_id" class="form-control">
                    <option value="">-- All Companies --</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }} ({{ $company->documents_count }} documents)
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
                <th>Type</th>
                <th>Count</th>
                <th>Client Preview</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documentsGrouped as $companyId => $docs)
                @php
                    // Get the company for this group
                    $company = $companies->firstWhere('id', $companyId);

                    // Initialize counts for different document types
                    $pdfCount = 0;
                    $excelCount = 0;
                    $wordCount = 0;

                    // Loop through the documents and categorize by file extension
                    foreach ($docs as $doc) {
                        $fileExtension = strtolower(pathinfo($doc->filename, PATHINFO_EXTENSION)); // Get file extension

                        if ($fileExtension == 'pdf') {
                            $pdfCount++;
                        } elseif (in_array($fileExtension, ['xls', 'xlsx'])) {
                            $excelCount++;
                        } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                            $wordCount++;
                        }
                    }
                @endphp

                <tr>
                    <td>{{ $company->name }}</td>
                    <td>
                        @if($pdfCount) PDF ({{ $pdfCount }})<br>@endif
                        @if($excelCount) Excel ({{ $excelCount }})<br>@endif
                        @if($wordCount) Word ({{ $wordCount }})<br>@endif
                        @if(!$pdfCount && !$excelCount && !$wordCount) Other ({{ $docs->count() }}) @endif
                    </td>
                    <td>{{ $docs->count() }}</td>
                    <td>
                        <a href="{{ route('client.documents', ['company_id' => base64_encode($company->id)]) }}" class="btn btn-sm btn-info">
                            View Documents for {{ $company->name }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
