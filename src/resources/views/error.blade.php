<x-front-layout>
    @section('title')
        {{ __('Error occured!') }}
    @endsection

    @include('partials.header')
    <div class="container py-20">
        <h1 class="text-center">{{ __('Error occured!') }}</h1>
        <p class="text-center mt-4">{{ __('For additional information, please contact us with your Order Code') }} <b
                class="text-violet-500">{{ __('Order number') }}: #{{ request('order_id') }}</b></p>

    </div>
    @include('partials.footer')

</x-front-layout>
