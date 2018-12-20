<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Navbar</a>
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" href={{url("telescope")}}>Telescope</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href={{route('admin')}}>Admin Panel</a>
        </li>
    </ul>


    @if (Route::has('login'))
        <ul class="nav justify-content-end">
            @auth
                <li class="nav-item">
                    <a class="nav-link"><strong> | </strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href={{ route('home')}}><strong>Home</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href={{ route('logout') }}><strong>{{Auth::user()->name}} | Logout</strong></a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link"><strong> | </strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href={{ route('login') }}><strong>Login</strong></a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link active" href={{ route('register') }}><strong>Register</strong></a>
                    </li>
                @endif
            @endauth
        </ul>
    @endif

</nav>


