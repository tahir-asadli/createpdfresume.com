<x-front-layout>
    @section('title')
        {{ __('Unsubscribe Successful!') }}
    @endsection

    @include('partials.header')
    <div class="container py-20">
        <h1 class="text-center">{{ __('Unsubscribe Successful!') }}</h1>
        <p class="text-center mt-4">{{ __('You have been successfully unsubscribed from our email list') }}</p>
        <p class="text-center mt-4">{{ __('You will no longer receive marketing or promotional emails from us') }}</p>

    </div>
    @include('partials.footer')

</x-front-layout>