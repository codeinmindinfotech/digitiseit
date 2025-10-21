@extends('layouts.app')

@section('content')
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
                <ul class="list-group list-group-flush overflow-auto" style="max-height: 600px;" id="document-list">
                    @forelse($documents as $doc)
                    <li class="list-group-item list-group-item-action" data-file="{{ asset('storage/' . $doc->filepath) }}">
                        {{ $doc->filename }}
                    </li>
                    @empty
                    <li class="list-group-item">No documents found.</li>
                    @endforelse
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
        const items = document.querySelectorAll('#document-list li[data-file]');
        const preview = document.getElementById('document-preview');
        items.forEach(item => {
            item.addEventListener('click', function() {
                const file = this.dataset.file;
                const ext = file.split('.').pop().toLowerCase();
                let content = '';
                if (['pdf'].includes(ext)) {
                    content = `<iframe src="${file}" frameborder="0" style="width:100%;height:100%;"></iframe>`;
                } else if (['jpg','jpeg','png','gif','bmp'].includes(ext)) {
                    content = `<img src="${file}" class="img-fluid" style="max-height:100%; display:block; margin:auto;"/>`;
                } else if (['doc','docx','xls','xlsx','ppt','pptx'].includes(ext)) {
                    const encodedUrl = encodeURIComponent(file);
                   content = `<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodedUrl}" frameborder="0" style="width:100%;height:100%;"></iframe>`;
                } else {
                    content = `<p class="text-center mt-5">Preview not available. 
                                <a href="${file}" target="_blank" class="btn btn-sm btn-primary">Download</a></p>`;
                }
                preview.innerHTML = content;
            });
        });
    });

</script>
 @endsection