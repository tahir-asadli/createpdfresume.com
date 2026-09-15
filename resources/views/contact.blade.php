<x-front-layout>
    @section('title')
        {{ __('Contact') }}
    @endsection
    @section('ldjson')
        <script type="application/ld+json">
          {
            "@context": "https://schema.org",
            "@type": "ContactPage",
            "name": "{{ __('Contact') }}",
            "url": "{{ config('app.url').'/contact' }}",
            "description": "{{ __('Contact') }}",
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
            "contactPoint": {
              "@type": "ContactPoint",
              "telephone": "+994107290883",
              "contactType": "{{ __('Online help') }}",
              "areaServed": "{{ app()->getLocale() }}",
              "availableLanguage": ["Azerbaijanian","English","Turkish","Russian","Spanish"]
            },
            "inLanguage": "{{ app()->getLocale() }}"
          }
    </script>
    @endsection
    @section('canonical')
        @if (app()->getLocale() != 'en')
            <link rel="canonical" href="{{ config('app.url') }}/contact" />
        @endif
    @endsection

    @include('partials.header')
    <div class="container pt-20 mb-20">
        <h1 class="text-center mb-5">{{ __('Contact') }}</h1>
        <div>
            <b>{{ __('Phone') }}: </b> +994 010 729 08 83
        </div>
        <div>
            <b>{{ __('E-mail') }}: </b> {{ config('app.mail') }}
        </div>
    </div>
    @include('partials.footer')

</x-front-layout>
