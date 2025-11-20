@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Upload Document</h4>
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

    <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf
        <div class="mb-3">
            <label>Company</label>
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
            <label>File</label>
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
<script>
    $(function() {
        $('#company_id').select2({
            tags: true,
            placeholder: "-- Select or Add Company --",
            width: '100%'
        });
    
        $('#company_id').on('change', function() {
            let selectedOption = $("#company_id option:selected");
            let folderPath = selectedOption.data('folder-path') || selectedOption.text();
            let currentDir = $('#directory_name').val();

            // Only set if input is empty
            if (currentDir === '') {
                $('#directory_name').val(folderPath);
            }
        });


        // $('#company_id').on('change', function() {
        //     let selectedText = $("#company_id option:selected").text();
        //     let currentDir = $('#directory_name').val();
        //     if (currentDir === '') {
        //         $('#directory_name').val(selectedText);
        //     }
        // });
    });
</script>
@endsection
