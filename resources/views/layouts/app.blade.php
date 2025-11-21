<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigitiseIt</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    @yield('styles')

</head>
<body>
    @if (!Route::is(['login']))
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('companies.index') }}">
                <img src="{{ asset('images/logo.jpg') }}" alt="DigitiseIt" style="height:40px;">
            </a>
    
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        {{-- Admin Menu --}}
                        @if(request()->routeIs('companies.*') || request()->is('documents*') || request()->is('users*') || request()->routeIs('client.documents'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                                href="{{ route('users.index') }}">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" 
                                href="{{ route('companies.index') }}">Companies</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('documents.uploadForm') ? 'active' : '' }}" 
                                href="{{ route('documents.uploadForm') }}">Upload Documents</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('documents.main.index') ? 'active' : '' }}" 
                                href="{{ route('documents.main.index') }}">View Documents</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('documents.index') ? 'active' : '' }}" 
                                href="{{ route('documents.index') }}">Client all Documents</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('client.documents') ? 'active' : '' }}"
                                href="{{ route('client.documents') }}">
                                    Client Documents
                                </a>
                            </li>
                        @endif
                    @endauth
    
                    
    
                </ul>
    
                <!-- Right Side -->
                <ul class="navbar-nav ms-auto">
                    @auth
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
@endif


<main class="py-4">
    @yield('content')
</main>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- FIXED Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>

@yield('scripts')



</body>
</html>
