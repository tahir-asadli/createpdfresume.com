<nav>
    <a href="{{ lroute('home') }}" class="logo">
        <img src="/logo-mobile.svg" alt="{{ __('logo') }}" style="width: 38px">
        <span>
            {{ config('app.name') }}
        </span>
    </a>
    <div class="navlinks">
        <a href="{{ lroute('home') }}#our-templates">{{ __('Templates') }}</a>
        <a href="{{ lroute('home') }}#features">{{ __('Features') }}</a>
        <a href="{{ lroute('about') }}">{{ __('About Us') }}</a>
        <a href="{{ lroute('contact') }}">{{ __('Contact') }}</a>
    </div>
    <div class="navcta">
        @auth
            <a href="{{ route('resumes') }}" class="btn btn-accent">{{ __('Account') }}</a>
            <form action="{{ route('filament.app.auth.logout') }}" method="post">
                @csrf
                <button title="{{ __('Sign out') }}" class="btn btn-ghost"><x-icons.logout
                        class="w-5 h-5 text-gray-500" /></button>
            </form>
        @endauth
        @guest
            <a href="{{ route('filament.app.auth.login') }}">{{ __('Sign in') }}</a>
            <a href="{{ route('filament.app.auth.register') }}" class="btn btn-accent">{{ __('Create my resume') }}</a>
        @endguest
    </div>
    <button id="mobile-button" class="mobile-button">
        <svg stroke="currentColor" fill="none" stroke-width="0" viewBox="0 0 15 15" height="30px" width="30px"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M13.6006 11.0098C13.8286 11.0563 14 11.2583 14 11.5C14 11.7417 13.8286 11.9437 13.6006 11.9902L13.5 12H1.5C1.22386 12 1 11.7761 1 11.5C1 11.2239 1.22386 11 1.5 11H13.5L13.6006 11.0098ZM13.6006 7.00977C13.8286 7.05629 14 7.25829 14 7.5C14 7.74171 13.8286 7.94371 13.6006 7.99023L13.5 8H1.5C1.22386 8 1 7.77614 1 7.5C1 7.22386 1.22386 7 1.5 7H13.5L13.6006 7.00977ZM13.6006 3.00977C13.8286 3.05629 14 3.25829 14 3.5C14 3.74171 13.8286 3.94371 13.6006 3.99023L13.5 4H1.5C1.22386 4 1 3.77614 1 3.5C1 3.22386 1.22386 3 1.5 3H13.5L13.6006 3.00977Z"
                fill="currentColor"></path>
        </svg>
    </button>
</nav>
