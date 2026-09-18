<x-front-layout>
    @section('title')
        {{ __('About Us') }}
    @endsection

    @section('ldjson')
        <script type="application/ld+json">
          {
          "@context": "https://schema.org",
          "@type": "AboutPage",
          "name": "{{ __('About Us') }}",
          "url": "{{ config('app.url') .'/about' }}",
          "description": "{{ __("The Resume Guru website serves to facilitate resume preparation.") }}",
          "publisher": {
            "@type": "Organization",
            "name": "{{ config('app.name') }}",
            "url": "{{ config('app.url') .'/about' }}",
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
            <link rel="canonical" href="{{ config('app.url') }}/about" />
        @endif
    @endsection
    @include('partials.header')
    <div class="container pt-20 mb-20">
        <h1 class="text-center mb-5">{{ __('About Us') }}</h1>
        <p class="mb-4">
            {!! __('The :site website provides a service to facilitate resume preparation.', [
                'site' => '<a href="' . config('app.url') . '" class="text-violet-500">' . config('app.name') . '</a>',
            ]) !!}
        </p>
        <p class="mb-4">
            {!! __(
                'You can prepare a resume using the site for free or for a fee. There are currently 2 :plans, in the future the number of templates will be increased according to the plan.',
                ['plans' => '<a href="' . config('app.url') . '/#plans" class="text-violet-500">' . __('plans') . '</a>'],
            ) !!}
        </p>
    </div>
    @include('partials.footer')

</x-front-layout>
