<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">SuperMarketPlus</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="bi bi-person"></i> My Profile
                            </a>
                            <a class="dropdown-item" href="{{ route('profile.orders') }}">
                                <i class="bi bi-bag"></i> My Orders
                            </a>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-gear"></i> Account Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart"></i> Cart 
                            @auth
                                @php
                                    $cartCount = DB::table('cart_items')
                                        ->where('user_id', auth()->id())
                                        ->count();
                                @endphp
                                @if($cartCount > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $cartCount }}</span>
                                @endif
                            @else
                                @if(count(session('cart', [])) > 0)
                                    <span class="badge bg-danger rounded-pill">{{ count(session('cart', [])) }}</span>
                                @endif
                            @endauth
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart"></i> Cart 
                            @if(count(session('cart', [])) > 0)
                                <span class="badge bg-danger rounded-pill">{{ count(session('cart', [])) }}</span>
                            @endif
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>