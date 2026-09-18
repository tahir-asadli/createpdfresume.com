<x-new-front-layout>
    @section('title')
        {{ __('Online resume builder') }}
    @endsection

    @section('ldjson')
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "WebSite",
                "name": "{{ __('Online resume builder') }}",
                "url": "{{ config('app.url') }}",
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
                }
            }
        </script>
    @endsection
    @section('canonical')
        @if (app()->getLocale() != 'en')
            <link rel="canonical" href="{{ config('app.url') }}" />
        @endif
    @endsection
    @include('partials.new.header')
    @include('partials.new.hero')
    @include('partials.new.templates')
    @include('partials.new.moods')
    @include('partials.new.features')
    @include('partials.new.cta-banner')
    @include('partials.new.footer')
</x-new-front-layout>
