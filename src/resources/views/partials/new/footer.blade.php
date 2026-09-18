<footer>
    <div class="foot-top">
        <a href="{{ lroute('home') }}" class="logo">
            <img src="/logo-mobile.svg" alt="{{ __('logo') }}" style="width: 38px">
            <span>
                {{ config('app.name') }}
            </span>
        </a>
        <div class="foot-cols">
            <div class="foot-col">
                <h5>{{ __('Navigation') }}</h5>
                <a href="{{ lroute('home') }}#our-templates">{{ __('Templates') }}</a>
                <a href="{{ lroute('home') }}#features">{{ __('Features') }}</a>
                <a href="{{ route('import') }}">{{ __('Import resume') }}</a>
                <a href="#"></a>
            </div>
            <div class="foot-col">
                <h5>{{ __('Company') }}</h5>
                <a href="{{ lroute('about') }}">{{ __('About Us') }}</a>
                <a href="{{ lroute('contact') }}">{{ __('Contact') }}</a>
                <a href="{{ lroute('privacy-policy') }}">{{ __('Privacy') }}</a>
            </div>
            <div class="foot-col">
                <h5>{{ __('Account') }}</h5>
                @auth
                    <a href="{{ route('resumes') }}">{{ __('Account') }}</a>
                    <form action="{{ route('filament.app.auth.logout') }}" method="post">
                        @csrf
                        <button class="link">{{ __('Sign out') }}</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('filament.app.auth.login') }}">{{ __('Sign in') }}</a>
                    <a href="{{ route('filament.app.auth.register') }}">{{ __('Sign up') }}</a>
                @endguest
            </div>
        </div>
    </div>
    <div class="foot-bottom">
        <span>© {{ date('Y') }} {{ config('app.name') }}</span>
        <div class="langs">
            <a href="{{ route('home') }}/en" class="@if (app()->getLocale() == 'en') active @endif">EN</a>
            <a href="{{ route('home') }}/az" class="@if (app()->getLocale() == 'az') active @endif">AZ</a>
            <a href="{{ route('home') }}/tr" class="@if (app()->getLocale() == 'tr') active @endif">TR</a>
            <a href="{{ route('home') }}/es" class="@if (app()->getLocale() == 'es') active @endif">ES</a>
            <a href="{{ route('home') }}/ru" class="@if (app()->getLocale() == 'ru') active @endif">RU</a>
        </div>
        <div class="socials">
            <a target="_blank" href="https://www.facebook.com/createpdfresume/">
                <x-icons.facebook width="25" height="25" />
            </a>
            <a target="_blank" href="https://www.youtube.com/@createpdfresume">
                <x-icons.youtube width="25" height="25" />
            </a>
            <a target="_blank" href="https://api.whatsapp.com/send?phone=+994107290883&text={{ __('Hi') }},"">
                <x-icons.whatsapp width="25" height="25" />
            </a>
            <a target="_blank" href="https://x.com/createpdfresume">
                <x-icons.x width="25" height="25" />
            </a>
        </div>
    </div>
</footer>
<mobile-menu>
    <dialog closedby="any">
        <ul>
            <li><a href="{{ lroute('about') }}">{{ __('About Us') }}</a></li>
            <li><a href="{{ lroute('privacy-policy') }}">{{ __('Privacy') }}</a></li>
            <li><a href="{{ lroute('home') }}#our-templates">{{ __('Templates') }}</a></li>
            <li><a href="{{ lroute('home') }}#features">{{ __('Features') }}</a></li>
            <li><a href="{{ lroute('contact') }}">{{ __('Contact') }}</a></li>
            @auth
                <li><a href="{{ route('resumes') }}">{{ __('Account') }}</a></li>
                <li>
                    <form action="{{ route('filament.app.auth.logout') }}" method="post">
                        @csrf
                        <button>{{ __('Sign out') }}</button>
                    </form>
                </li>
            @endauth
            @guest
                <li><a href="{{ route('filament.app.auth.login') }}">{{ __('Sign in') }}</a></li>
                <li><a href="{{ route('filament.app.auth.register') }}">{{ __('Sign up') }}</a></li>
            @endguest
        </ul>
        </ul>
    </dialog>
</mobile-menu>
