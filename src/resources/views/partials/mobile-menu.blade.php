<div class="mobile-menu">
    <div class="mobile-menu-content">
        <div class="mobile-menu-items">
            <ul>
                {{-- <li><a class="close-mobile-menu inline-block p-2 text-lg font-semibold" href="{{ lroute('home') }}#plans">{{ __('Prices') }}</a></li> --}}
                @if (app()->getLocale() == 'az')
                    <li><a class="close-mobile-menu inline-block p-2 text-lg font-semibold"
                            href="{{ lroute('home') }}#videos">{{ __('Videos') }}</a></li>
                @endif
                <li><a class="inline-block p-2 text-lg font-semibold"
                        href="{{ lroute('about') }}">{{ __('About Us') }}</a></li>
                <li><a class="inline-block p-2 text-lg font-semibold"
                        href="{{ lroute('privacy-policy') }}">{{ __('Privacy') }}</a></li>
                <li><a class="inline-block p-2 text-lg font-semibold"
                        href="{{ lroute('contact') }}">{{ __('Contact') }}</a></li>
                @auth
                    <li><a href="{{ route('templates') }}"
                            class="inline-block p-2 text-lg  font-bold text-violet-500">{{ __('Templates') }}</a></li>
                    <li>
                        <form action="{{ route('filament.app.auth.logout') }}" method="post">
                            @csrf
                            <button title="{{ __('Sign out') }}"
                                class="inline-block p-2 text-lg  font-bold text-violet-500">{{ __('Sign out') }}</button>
                        </form>
                    </li>

                @endauth
                @guest
                    <li><a class="inline-block p-2 text-lg font-bold text-violet-500"
                            href="{{ route('filament.app.auth.login') }}">{{ __('Sign in') }}</a></li>
                    <li><a class="inline-block p-2 text-lg font-bold text-violet-500"
                            href="{{ route('filament.app.auth.register') }}">{{ __('Sign up') }}</a></li>
                @endguest
            </ul>
        </div>
    </div>
    <div class="mobile-menu-overlay" id="mobile-menu-overlay"></div>
</div><!-- .mobile-menu -->
