<x-new-front-layout>
    @section('title')
        {{ __('Error occured!') }}
    @endsection

    @include('partials.new.header')
    <main>
        <h1 class="text-center">{{ __('Error occured!') }}</h1>
        <p class="text-center mt-4">{{ __('For additional information, please contact us with your Order Code') }} <b
                class="text-violet-500">{{ __('Order number') }}: #{{ request('order_id') }}</b></p>

    </main>
    @include('partials.new.footer')

</x-new-front-layout>
