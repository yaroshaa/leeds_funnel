<header id="app-header" class="sticky-top bg-white fw-bold">
    <div class="container-fluid">
        <ul class="nav align-items-center">
            <li class="nav-item me-5">
                <a href="{{ url('/') }}" class="nav-link">
                    <img class="logo" src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}">
                </a>
            </li>
            @foreach(\App\Services\Navigation::main() as $nav)
                <li class="nav-item">
                    <a href="{{ $nav['route'] }}" class="h6 m-0 nav-link{{ $nav['match'] ? ' active' : '' }}">
                        {{ $nav['name'] }}
                    </a>
                </li>
            @endforeach
            <li class="nav-item ms-auto">
                <a href="{{ route('logout') }}" class="h6 m-0 nav-link"
                   onclick="event.preventDefault(); document.getElementById('logout').submit();">
                    {{ __('Logout') }}
                </a>
            </li>
        </ul>
    </div>
    <form id="logout" action="{{ route('logout') }}" method="post">@csrf</form>
</header>
