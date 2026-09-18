<x-new-front-layout>
    @section('title')
        {{ __('Page not found!') }}
    @endsection
    @include('partials.new.header')
    <main>
        <h1 class="text-center mb-5 text-[30px]">{{ __('Page not found!') }}</h1>
    </main>
    @include('partials.new.footer')
</x-new-front-layout>
