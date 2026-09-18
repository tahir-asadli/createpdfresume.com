<x-front-layout>
    @section('title')
        {{ __('Order') }}
    @endsection
    @include('partials.header')
    <div class="container">
        @if ($hasSubscription)
        <div class="pb-10">
            <h1 class="text-center">{{ __('Order') }}</h1>
            <p class="text-center">{!! __('Please :link your subscription first', ['link' => '<a class="underline text-violet-500" href="'.route('cancel').'">'.__('cancel').'</a>.']) !!}</p>
        </div>
        @else
            <h1 class="text-center">{{ __('Order') }}</h1>
            <livewire:checkout :plan="$plan" />
        @endif

    </div>
    @include('partials.footer')

</x-front-layout>
