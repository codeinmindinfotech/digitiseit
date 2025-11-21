@extends('layouts.app')
@section('content')
@section('styles')
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> --}}
<style>
/* Tree container */
ul.list-group {
    list-style: none;
    padding-left: 0;
}

/* Folder & File items */
.folder-item, .file-item {
    padding: 6px 12px;
    margin: 2px 0;
    border-radius: 5px;
    transition: background 0.2s;
}
.folder-item,
.file-item {
    border: none !important;
    background-color: transparent;
    padding-left: 0; /* optional: adjust padding */
}
.folder-item:hover, .file-item:hover {
    background-color: #f0f8ff;
    cursor: pointer;
}

/* Folder toggle icon */
.folder-toggle::before {
    content: "▶"; /* right arrow */
    display: inline-block;
    width: 1em;
    transition: transform 0.2s;
    margin-right: 5px;
}

/* Open folder arrow */
.folder-item.open > .folder-toggle::before {
    transform: rotate(90deg);
}

/* Sub-tree */
.folder-item > ul.list-group {
    margin-top: 4px;
    margin-left: 20px;
    border-left: 1px dashed #ccc;
    padding-left: 10px;
    display: none; /* hidden by default */
}

/* Show sub-tree when folder is open */
.folder-item.open > ul.list-group {
    display: block;
}

/* File icon */
.file-item::before {
    content: "📄";
    margin-right: 5px;
}

/* Highlight selected file */
.file-item.selected {
    background-color: #d1e7dd;
    font-weight: 600;
}

/* Smooth hover effect */
.folder-item, .file-item {
    transition: background 0.3s, color 0.3s;
}
</style>

@endsection
<div class="container py-4">
    <h2 class="mb-4 text-center">Client Documents</h2>

    {{-- Search --}}
    <form method="GET" class="mb-4">
        <div class="row g-2 justify-content-center">
            <div class="col-md-6">
                <input type="hidden" name="company_id" value="{{ request('company_id') }}">
    
                <input type="text" name="search" class="form-control" placeholder="Search documents..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('client.documents', ['company_id' => request('company_id')]) }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </div>
    </form>
    

    <div class="row">
        {{-- Left sidebar: document list --}}
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">Documents</div>
                <ul class="list-group list-group-flush overflow-auto" style="max-height: 600px;">
                    @include('client.partials.folder-tree', ['tree' => $tree])
                </ul>
            </div>
        </div>

        
        {{-- Right panel: preview --}}
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">Preview</div>
                <div class="card-body p-0" id="document-preview" style="height: 600px;">
                    <p class="text-center mt-5 text-muted">Select a document to preview</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts') 
<script>

document.addEventListener('DOMContentLoaded', function() {
    // Folder toggle
    document.querySelectorAll('.folder-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            const folderItem = this.closest('.folder-item');
            folderItem.classList.toggle('open'); // add/remove class
        });
    });

    // File preview
    const preview = document.getElementById('document-preview');
    document.querySelectorAll('.file-item').forEach(file => {
        file.addEventListener('click', function() {
            document.querySelectorAll('.file-item').forEach(f => f.classList.remove('selected'));
            this.classList.add('selected');

            const filePath = this.dataset.file;
            const ext = filePath.split('.').pop().toLowerCase();
            let content = '';

            if (ext === 'pdf') {
                content = `<iframe src="${filePath}" style="width:100%;height:100%;"></iframe>`;
            } else if (['jpg','jpeg','png','gif'].includes(ext)) {
                content = `<img src="${filePath}" class="img-fluid mx-auto d-block" style="max-height:100%;">`;
            } else if (['doc','docx','xls','xlsx','ppt','pptx'].includes(ext)) {
                content = `<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(filePath)}"
                            style="width:100%;height:100%;"></iframe>`;
            } else {
                content = `<p class="text-center mt-5">Preview not available.
                            <br><a href="${filePath}" target="_blank" class="btn btn-primary btn-sm mt-3">Download</a></p>`;
            }

            preview.innerHTML = content;
        });
    });
});


</script>

 @endsection