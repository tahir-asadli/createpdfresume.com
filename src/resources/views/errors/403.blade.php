<x-new-front-layout>
    @section('title')
        {{ __('You don\'t have permission to view this page') }}
    @endsection
    @include('partials.new.header')
    <main>
        <h1 class="text-center mb-5 text-[30px]">{{ __('You don\'t have permission to view this page') }}</h1>
    </main>
    @include('partials.new.footer')
</x-new-front-layout>
