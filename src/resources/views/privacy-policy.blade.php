<x-front-layout>
    @section('title')
        {{ __('Privacy Policy') }}
    @endsection
    @section('ldjson')
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "WebPage",
                "name": "{{ __('Privacy Policy') }}",
                "url": "{{ config('app.url').'/privacy-policy' }}",
                "description": "{{ __('The Privacy Policy reflects how your personal data is collected, used, and disclosed (under applicable terms)') }}",
                "publisher": {
                    "@type": "Organization",
                    "name": "{{ config('app.name') }}",
                    "url": "{{ config('app.url') }}",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "{{ config('site.logo') }}",
                        "width": 200,
                        "height": 38
                    }
                },
                "inLanguage": "{{ app()->getLocale() }}"
            }
    </script>
    @endsection
    @section('canonical')
        @if (app()->getLocale() != 'en')
            <link rel="canonical" href="{{ config('app.url') }}/privacy-policy" />
        @endif
    @endsection
    @include('partials.header')
    <div class="container pt-20">
        <h1 class="text-center mb-5">{{ __('Privacy Policy') }}</h1>

        @if (app()->getLocale() == 'az')
            @include('partials.privacy-az')
        @else
            @include('partials.privacy')
        @endif

    </div>
    @include('partials.footer')

</x-front-layout>
