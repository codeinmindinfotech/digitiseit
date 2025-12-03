@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Upload Result</h4>
        </div>
        <div class="card-body">            
                @if(empty($overallLog))
                    <p>No Excel files processed.</p>
                @else
                    @foreach($overallLog as $excelFile => $log)
                        <div class="card mb-3">
                            <div class="card-header">
                                <strong>{{ $excelFile }}</strong>
                            </div>
                            <div class="card-body">
                                <h5>Uploaded Files ({{ count($log['uploaded']) }})</h5>
                                @if(count($log['uploaded']) > 0)
                                    <ul>
                                        @foreach($log['uploaded'] as $file)
                                            <li>
                                                {{ $file['filename'] }} → <em>{{ $file['target'] }}</em>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No files uploaded.</p>
                                @endif
            
                                <h5>Missing Files ({{ count($log['missing']) }})</h5>
                                @if(count($log['missing']) > 0)
                                    <ul>
                                        @foreach($log['missing'] as $file)
                                            <li>
                                                {{ $file['filename'] }}
                                                @if(isset($file['directory']))
                                                    (expected in: {{ $file['directory'] }})
                                                @endif
                                                @if(isset($file['reason']))
                                                    - {{ $file['reason'] }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No missing files.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
        </div>
    </div>
</div>
@endsection