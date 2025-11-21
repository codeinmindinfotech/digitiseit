@foreach ($tree as $name => $node)

    @if ($name === '__files')
        @foreach ($node as $file)
            <li class="list-group-item file-item"
                data-file="{{ asset('storage/' . $file['path']) }}">
                {{ $file['name'] }}
            </li>
        @endforeach

    @else
        <li class="list-group-item folder-item">
            📁 <span class="folder-toggle">{{ $name }}</span>
            <ul class="list-group ms-3 mt-2 ">
                @include('client.partials.folder-tree', ['tree' => $node])
            </ul>
        </li>
    @endif

@endforeach