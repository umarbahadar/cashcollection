<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center">

        <!-- Logo -->
        <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto">
            <img src="{{ asset('front-theme/assets/img/logo.jpg') }}" alt="Cash Collection Logo">
        </a>

        <!-- Navigation Menu -->
        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ route('home') }}" class="active">Home</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        {{-- Desktop Auth Buttons --}}
        <div class="d-none d-xl-block">
            @if(auth()->check())
                <a href="{{ route('dashboard') }}" class="order_online btn-getstarted">
                    <i class="fa fa-user"></i>&nbsp; My Account
                </a>
            @else
                <div class="dropdown">
                    <button 
                        class="btn btn-getstarted btn-get-started dropdown-toggle" 
                        type="button" 
                        id="authDropdown" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                        <i class="fa fa-user" aria-hidden="true"></i>&nbsp; Login / Register
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="authDropdown">
                        <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                        <li><a class="dropdown-item" href="{{ route('register') }}">Register</a></li>
                    </ul>
                </div>
            @endif
        </div>

    </div>
</header>