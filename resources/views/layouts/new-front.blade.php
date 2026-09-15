<!DOCTYPE html>
<html class="notranslate" translate="no" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @if (isset($pageuuid))
        <meta name="uuid-token" content="{{ $pageuuid }}" />
    @endif
    <meta name="google" content="notranslate" />

    <title>{{ config('app.name') }} | @yield('title')</title>
    <meta name="description"
        content="{{ __('Online resume builder. Easily create your resume in just minutes with a template you love') }}" />
    <meta name="keywords"
        content="{{ __('online resume, online resume, resume preparation, resume creation, ready resume forms, pre-designed resume templates, resume examples, sample resumes, create free pdf, generate free pdf, resume preparation service') }}" />
    <meta name="p:domain_verify" content="9cd18adec50d474dfcfb46b32e894c7b" />
    @yield('canonical')
    <link rel="manifest" href="/app.webmanifest" />
    <meta name="theme-color" content="#8b5cf6" />

    <!-- Scripts -->
    @vite(['resources/css/front-v2.css', 'resources/js/front-v2.js'])
    @if (!isAdmin() && App::environment('production'))
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-RB1YHMSXY4"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-RB1YHMSXY4');
        </script>
    @endif
    @if (App::environment('production'))
        {{-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?lient=ca-pub-9585764287862286"
            crossorigin="anonymous"></script> --}}
    @endif


    <meta property="og:image" content="{{ config('site.logo_png') }}" />
    <meta property="og:title"
        content="{{ __('Online resume builder. Easily create your resume in just minutes with a template you love') }}" />
    <meta property="og:description"
        content="{{ __('Online resume builder. Easily create your resume in just minutes with a template you love') }}" />
    <meta property="og:locale" content="{{ __('en_US') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    <meta property="og:logo" content="{{ config('site.logo_png') }}" />

    @yield('ldjson')
</head>

<body class="font-poppins antialiased {{ $frontPage ? 'front-page' : 'inner-page' }}">
    {{ $slot }}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then((reg) => {});
            });
        }
    </script>
</body>

</html>
