@include('partials.mobile-menu')
<div class="container">
    <header class="py-6 lg:py-9 flex flex-wrap items-center gap-4 lg:gap-9 justify-between">

        <a href="{{ lroute('home') }}">
            <img loading="lazy" src="{{ config('site.logo') }}" class="w-[150px]" alt="{{ __('logo') }}">
        </a>
        <ul class="hidden lg:flex items-center gap-4 lg:gap-7 mt-0.5">
            {{-- <li><a href="{{ lroute('home') }}#plans">{{ __('Prices') }}</a></li> --}}
            <li><a href="{{ lroute('about') }}">{{ __('About Us') }}</a></li>
            @if (app()->getLocale() == 'az')
                <li><a href="{{ lroute('home') }}#videos">{{ __('Videos') }}</a></li>
            @endif
            <li><a href="{{ lroute('contact') }}">{{ __('Contact') }}</a></li>
        </ul>
        <ul class="hidden items-center gap-4 lg:gap-10 mt-0.5 ml-auto md:flex">
            @auth
                <li><a href="{{ route('resumes') }}" class="button bg-violet flex gap-2 items-center"><x-icons.account2
                            class="w-5 h-5" />{{ __('Account') }}</a></li>
                <li>
                    <form action="{{ route('filament.app.auth.logout') }}" method="post">
                        @csrf
                        <button title="{{ __('Sign out') }}"
                            class="button bg-white border border-gray-300 text-black flex gap-2 items-center p-4"><x-icons.logout
                                class="w-5 h-5 text-gray-500" /></button>
                    </form>
                </li>

            @endauth
            @guest
                <li><a href="{{ route('filament.app.auth.login') }}"
                        class="button bg-white text-black">{{ __('Sign in') }}</a>
                </li>
                <li><a href="{{ route('filament.app.auth.register') }}" class="button">{{ __('Sign up') }}</a></li>
            @endguest
        </ul>

        <div class="relative ml-auto md:ml-0 mr-2 language-switcher">
            <span class="uppercase absolute text-[11px] top-1 right-2 text-white z-[3]">
                {{ app()->getLocale() }}
            </span>
            <button class="button bg-gray-800 relative flex gap-2 items-center p-4 z-[2]">
                <x-icons.language class="pointer-events-none cursor-pointer" />
            </button>
            <div class="language-list bg-white absolute top-7 shadow text-sm w-full rounded-b-full flex flex-col gap-2">
                <a href="{{ config('app.url') }}/en"
                    class="mt-8 flex justify-center uppercase @if (app()->getLocale() == 'en') font-[700] @endif">en</a>
                <a href="{{ config('app.url') }}/az"
                    class="flex justify-center uppercase @if (app()->getLocale() == 'az') font-[700] @endif">az</a>
                <a href="{{ config('app.url') }}/tr"
                    class="flex justify-center uppercase @if (app()->getLocale() == 'tr') font-[700] @endif">tr</a>
                <a href="{{ config('app.url') }}/es"
                    class="flex justify-center uppercase @if (app()->getLocale() == 'es') font-[700] @endif">es</a>
                <a href="{{ config('app.url') }}/ru"
                    class="flex justify-center uppercase @if (app()->getLocale() == 'ru') font-[700] @endif">ru</a>
            </div>
        </div>
        <button class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>
</div>
