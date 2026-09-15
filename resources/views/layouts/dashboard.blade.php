<!DOCTYPE html>
<html class="notranslate" translate="no" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google" content="notranslate">

    <title>{{ config('app.name') }} | @yield('title')</title>

    @vite(['resources/css/dashboard.css', 'resources/js/app.js'])
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="manifest" href="/app.webmanifest">
    <meta name="theme-color" content="#8b5cf6" />
    <!-- Scripts -->
</head>

<body class="font-raleway dashboard-layout bg-white text-gray-900">
    <div class="container h-[70px] border-b border-slate-200 shadow-sm flex items-center px-4">
        <div
            class="border-r border-slate-200 h-full max-xl:hidden flex items-center w-72 flex-shrink-0 sidebar-top justify-start">
            <div class="flex items-center gap-2 sidebar-logo">
                <a href="{{ route('templates') }}" class="rounded link block"><img loading="lazy" class="w-[150px]"
                        src="{{ config('site.logo') }}" alt="{{ __('logo') }}"></a>
            </div>
        </div>
        <div class="flex items-center gap-2 sidebar-logo xl:hidden">
            <a href="{{ route('templates') }}" class="rounded link block"><img loading="lazy" class="w-[60px] h-[52px]"
                    src="{{ config('site.logo-mobile') }}" alt="{{ __('logo') }}"></a>
        </div>
        <div class="xl:hidden mx-2 inline-block">
            <a href="{{ route('import') }}" title="{{ __('Import your old resume') }}"
                class="leading-tight button bg-white border border-gray-300 text-black flex gap-2 items-center p-4 mr-auto"><x-icons.import
                    class="text-violet-800 shrink-0" width="20px" height="20px" /><span
                    class="md:hidden">{{ __('Import') }}</span><span
                    class="hidden md:inline">{{ __('Import your old resume') }}</span></a>
        </div>
        <button
            class="ml-auto button bg-white border border-gray-300 text-black flex gap-2 items-center p-4 dashboard-menu-toggle xl:hidden"><x-icons.hamburger
                width="20px" height="20px" /></button>
        <div class="pl-4 flex items-center w-full max-xl:hidden">
            <div>
                <a href="{{ route('import') }}" title="{{ __('Import your old resume') }}"
                    class="button bg-white border border-gray-300 text-black flex gap-2 items-center p-4 mr-auto"><x-icons.import
                        class="text-violet-800" width="20px" height="20px" />{{ __('Import your old resume') }}</a>
            </div>

            <div class="ml-auto flex items-center gap-2 ">
                <form action="{{ route('filament.app.auth.logout') }}" method="post">
                    @csrf
                    <button title="{{ __('Sign out') }}"
                        class="button bg-white border border-gray-300 text-black flex gap-2 items-center p-4"><x-icons.logout
                            class="text-violet-800" width="20px" height="20px" /></button>
                </form>
            </div>
        </div>
    </div>
    <main class="container grid xl:grid-cols-[288px_auto] max-xl:!p-0 pr-0">
        <div class=" min-h-0 min-w-0 max-w-full  max-xl:hidden">
            <div class="sidebar-bottom border-r border-slate-200 h-full flex flex-col w-72 flex-shrink-0 pr-4">
                <h4 class="pt-3 p-2 text-md font-semibold">{{ __('Resume Content') }}</h4>
                <ul class="flex flex-col mb-2 w-full navigation-group">
                    @foreach ($navigations as $key => $navigation)
                        @if ($navigation['group'] == 'resume-content')
                            <li class="relative block group" x-data="{ open: true }">
                                <a class="link text-sm {{ isset($navigation['class']) ? $navigation['class'] : '' }} justify-start font-medium p-1.5 border-none group-hover:text-violet-700 focus:ring-0 focus:outline-none flex gap-2 items-center {{ request()->routeIs($navigation['route']) ? ' text-violet-700  !font-bold' : 'text-gray-700' }}"
                                    href="{{ route($navigation['route']) }}"><x-dynamic-component
                                        component="{{ $navigation['icon'] }}"
                                        class="text-gray-600 group-hover:text-violet-700 {{ request()->routeIs($navigation['route']) ? ' text-violet-700' : 'text-gray-700' }} {{ !empty($navigation['class']) ? $navigation['class'] : '' }}"
                                        width="20px" height="20px" /><span
                                        class="inline">{{ $navigation['name'] }}</span></a>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <h4 class="pt-1 p-2 text-md font-semibold">{{ __('Resume Management') }}</h4>
                <ul class="flex flex-col mb-2 w-full navigation-group">
                    @foreach ($navigations as $key => $navigation)
                        @if ($navigation['group'] == 'resume-management')
                            @if ($navigation['route'] == 'new-resume')
                                <li class="relative block" x-data="{ open: true }">
                                    <span onclick="Livewire.dispatch('openModal', { component: 'add-resume' })"
                                        class="link cursor-pointer text-sm {{ isset($navigation['class']) ? $navigation['class'] : '' }} justify-start font-medium p-1.5 focus:ring-0 focus:outline-none flex gap-2 items-center "><x-dynamic-component
                                            component="{{ $navigation['icon'] }}"
                                            class="text-gray-700 {{ request()->routeIs($navigation['route']) ? ' text-violet-500 ' : 'text-gray-700' }} {{ !empty($navigation['class']) ? $navigation['class'] : '' }}"
                                            width="20px" height="20px" /><span
                                            class="inline">{{ $navigation['name'] }}</span></span>
                                </li>
                            @else
                                <li class="relative block group" x-data="{ open: true }">
                                    <a class="link text-sm {{ isset($navigation['class']) ? $navigation['class'] : '' }} justify-start font-medium p-1.5 border-none group-hover:text-violet-700 focus:ring-0 focus:outline-none flex gap-2 items-center {{ request()->routeIs($navigation['route']) ? ' text-violet-700  !font-bold' : 'text-gray-700' }}"
                                        href="{{ route($navigation['route']) }}"><x-dynamic-component
                                            component="{{ $navigation['icon'] }}"
                                            class="text-gray-700 group-hover:text-violet-700 {{ request()->routeIs($navigation['route']) ? ' text-violet-700' : 'text-gray-700' }} {{ !empty($navigation['class']) ? $navigation['class'] : '' }}"
                                            width="20px" height="20px" /><span
                                            class="inline">{{ $navigation['name'] }}</span></a>
                                </li>
                            @endif
                        @endif
                    @endforeach
                </ul>
                <h4 class="pt-1 p-2 text-md font-semibold">{{ __('Sections & Extras') }}</h4>
                <ul class="flex flex-col mb-2 w-full navigation-group">
                    @foreach ($navigations as $key => $navigation)
                        @if ($navigation['group'] == 'sections-extras')
                            <li class="relative block group" x-data="{ open: true }">
                                <a class="link text-sm {{ isset($navigation['class']) ? $navigation['class'] : '' }} justify-start font-medium p-1.5 border-none group-hover:text-violet-700 focus:ring-0 focus:outline-none flex gap-2 items-center {{ request()->routeIs($navigation['route']) ? ' text-violet-700  !font-bold' : 'text-gray-700' }}"
                                    href="{{ route($navigation['route']) }}"><x-dynamic-component
                                        component="{{ $navigation['icon'] }}"
                                        class="text-gray-700 group-hover:text-violet-700 {{ request()->routeIs($navigation['route']) ? ' text-violet-700' : 'text-gray-700' }} {{ !empty($navigation['class']) ? $navigation['class'] : '' }}"
                                        width="20px" height="20px" /><span
                                        class="inline">{{ $navigation['name'] }}</span></a>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <h4 class="pt-1 p-2 text-md font-semibold">{{ __('Account') }}</h4>
                <ul class="flex flex-col mb-2 w-full navigation-group">
                    @foreach ($navigations as $key => $navigation)
                        @if ($navigation['group'] == 'account')
                            @if ($navigation['route'] == 'signout')
                                <li class="relative block group" x-data="{ open: true }">
                                    <form action="{{ route('filament.app.auth.logout') }}" method="post">
                                        @csrf

                                        <button title="{{ __('Sign out') }}"
                                            class="w-full link text-sm justify-start font-medium p-1.5 border-none group-hover:text-violet-700 focus:ring-0 focus:outline-none flex gap-2 items-center text-gray-700"><x-icons.logout
                                                class="text-gray-700 group-hover:text-violet-700" width="20px"
                                                height="20px" /><span
                                                class="inline">{{ __('Sign out') }}</span></button>

                                    </form>
                                </li>
                            @else
                                <li class="relative block group" x-data="{ open: true }">
                                    <a class="link text-sm {{ isset($navigation['class']) ? $navigation['class'] : '' }} justify-start font-medium p-1.5 border-none group-hover:text-violet-700 focus:ring-0 focus:outline-none flex gap-2 items-center {{ request()->routeIs($navigation['route']) ? ' text-violet-700  !font-bold' : 'text-gray-700' }}"
                                        href="{{ route($navigation['route']) }}"><x-dynamic-component
                                            component="{{ $navigation['icon'] }}"
                                            class="text-gray-700 group-hover:text-violet-700 {{ request()->routeIs($navigation['route']) ? ' text-violet-700' : 'text-gray-700' }} {{ !empty($navigation['class']) ? $navigation['class'] : '' }}"
                                            width="20px" height="20px" /><span
                                            class="inline">{{ $navigation['name'] }}</span></a>
                                </li>
                            @endif
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="p-3 w-full bg-gray-100 min-h-0 min-w-0 max-w-full basis-full">
            <div class=" bg-white p-4 h-full rounded-md">

                <div class="flex justify-between items-center">
                    <h1 class="font-bold flex items-center gap-2 text-2xl"><span>@yield('title')</span>
                    </h1>
                    @yield('add-new')
                </div>

                <main class="mt-4">
                    @if (session('success'))
                        <div class="p-3 text-green-700 bg-green-100 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    {{ $slot }}
                </main>
            </div>
        </div>

    </main>


    <div class="dashboard-menu fixed top-0 left-0 bg-white w-full h-full z-10 hidden ">
        <button
            class=" absolute top-2 right-4 ml-auto button bg-white border border-gray-300 text-black flex gap-2 items-center p-4 dashboard-menu-toggle xl:hidden"><x-icons.times
                width="20px" height="20px" /></button>
        <div class="p-2 h-full overflow-scroll">
            <div class="max-w-[300px] mx-auto flex flex-col gap-2 justify-center h-full">
                <div>
                    <a href="{{ route('dashboard') }}"
                        class="w-full button flex justify-center gap-2 items-center {{ request()->segment(1) == 'dashboard' && request()->segment(2) == '' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"><x-icons.dashboard
                            class="flex-shrink-0" width="20px" height="20px" />{{ __('Dashboard') }}</a>
                </div>
                <div>
                    <a href="{{ route('templates') }}"
                        class="w-full button flex justify-center gap-2 items-center {{ request()->segment(2) == 'templates' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"><x-icons.template
                            class="flex-shrink-0" width="20px" height="20px" />{{ __('Templates') }}</a>
                </div>
                <div>
                    <a class="w-full button flex justify-center gap-2 items-center {{ request()->segment(2) == 'resumes' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"
                        href="{{ route('resumes') }}">
                        <x-icons.resumes class="flex-shrink-0" width="20px" height="20px" />
                        <span class="">{{ __('Resumes') }}</span>
                    </a>
                </div>
                <div>
                    <a href="{{ route('import') }}"
                        class="w-full button flex justify-center gap-2 items-center {{ request()->segment(2) == 'import' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"><x-icons.template
                            class="flex-shrink-0" width="20px" height="20px" />{{ __('Import') }}</a>
                </div>
                {{-- <div>
                    <a class="w-full button flex justify-center gap-2 items-center {{ request()->segment(2) == 'subscription' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"
                        href="{{ route('subscription') }}">
                        <x-icons.badge class="flex-shrink-0" width="20px" height="20px" />
                        <span class="">{{ __('Subscription') }}</span>
                    </a>
                </div> --}}
                <div>
                    <a class="w-full button flex justify-center gap-2 items-center {{ request()->segment(2) == 'informations' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"
                        href="{{ route('informations') }}">
                        <x-icons.informations class="flex-shrink-0" width="20px" height="20px" />
                        <span class="">{{ __('Informations') }}</span>
                    </a>
                </div>
                <div>
                    <a class="w-full button flex justify-center gap-2 items-center {{ request()->segment(2) == 'billing' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"
                        href="{{ route('billing') }}">
                        <x-icons.account class="flex-shrink-0" width="20px" height="20px" />
                        <span class="">{{ __('Account') }}</span>
                    </a>
                </div>
                <div>
                    <button onclick="Livewire.dispatch('openModal', { component: 'add-resume' })"
                        class="w-full button flex justify-center gap-2 items-center">
                        <x-icons.plus class="flex-shrink-0" width="20px" height="20px" />
                        <span class="inline">{{ __('New resume') }}</span>
                    </button>
                </div>
                <div class="flex">
                    <form action="{{ route('filament.app.auth.logout') }}" method="post" class="w-full">
                        @csrf
                        <button
                            class="w-full button bg-white border border-gray-300 text-black flex justify-center gap-2 items-center p-4"><x-icons.logout
                                class="flex-shrink-0" width="20px" height="20px" />{{ __('Sign out') }}</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    @livewire('wire-elements-modal')
</body>

</html>
