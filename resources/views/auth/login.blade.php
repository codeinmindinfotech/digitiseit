@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                
                <!-- Logo -->
                <div class="text-center mt-4">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="width:150px;">
                </div>

                <h3 class="card-header text-center">Login</h3>

                <div class="card-body">

                    {{-- Show Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Code Sent Notice --}}
                    @if(session('code_sent'))
                        <div class="alert alert-success">
                            Verification code sent to {{ session('email') }}. Please enter below.
                        </div>
                    @endif

                    {{-- LOGIN FORM --}}
                    <form method="POST" action="{{ route('auth.loginPost') }}" novalidate>
                        @csrf

                        <!-- Company (Client Only) -->
                        <div class="row mb-3">
                            <label for="company_name" class="col-md-4 col-form-label text-md-end">Company</label>
                            <div class="col-md-6">
                                <input type="text" name="company_name" id="company_name"
                                       class="form-control @error('company_name') is-invalid @enderror"
                                       value="{{ old('company_name', session('company_name')) }}"
                                       required>
                        
                                @error('company_name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>                        

                        <!-- Email -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email', session('email')) }}"
                                       required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Verification Code (Client Only, shows if code sent) -->
                        @if(session('code_sent'))
                            <div class="row mb-3">
                                <label for="code" class="col-md-4 col-form-label text-md-end">Verification Code</label>
                                <div class="col-md-6">
                                    <input type="text" name="code" class="form-control" required>
                                    @error('code')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <!-- Submit -->
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ session('code_sent') ? 'Verify & Login' : 'Login' }}
                                </button>

                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- JavaScript for Dynamic UI Logic --}}
<script>
document.getElementById('company').addEventListener('change', function () {
    const isClient = this.value !== ""; // company selected => client login
    document.getElementById('password-row').style.display = isClient ? 'none' : 'flex';
    document.getElementById('remember-row').style.display = isClient ? 'none' : 'flex';
    document.getElementById('forgot-link').style.display = isClient ? 'none' : 'inline';
    document.getElementById('password').disabled = isClient;
});
</script>

@endsection
