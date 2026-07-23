<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="fas fa-code me-2"></i>TechNews
        </a>
        
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left Menu -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="fas fa-folder-open me-1"></i>Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tags.*') ? 'active' : '' }}" href="{{ route('tags.index') }}">
                        <i class="fas fa-tags me-1"></i>Tags
                    </a>
                </li>
                @auth
                    @if(auth()->user()->isAuthor())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('articles.create') ? 'active' : '' }}" 
                               href="{{ route('articles.create') }}">
                                <i class="fas fa-pen me-1"></i>Write
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
            
            <!-- Search Form -->
            <form class="d-flex me-3" action="{{ route('articles.search') }}" method="GET">
                <div class="input-group input-group-sm">
                    <input class="form-control" type="search" name="search" 
                           placeholder="Search articles..." 
                           aria-label="Search" 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <!-- Right Menu (Auth) -->
            <ul class="navbar-nav">
                @auth
                    <!-- User Info -->
                    <li class="nav-item">
                        <span class="navbar-text text-white-50 me-2">
                            <img src="{{ auth()->user()->profile_image }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="rounded-circle me-1" width="28" height="28">
                            {{ auth()->user()->name }}
                        </span>
                    </li>
                    
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                        </a>
                    </li>
                    
                    <!-- Write Article -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('articles.create') }}">
                            <i class="fas fa-pen text-success"></i>
                        </a>
                    </li>
                    
                    <!-- LOGOUT BUTTON - Direct -->
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-danger" style="border: none; background: none;">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary btn-sm text-white px-3 ms-1" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>