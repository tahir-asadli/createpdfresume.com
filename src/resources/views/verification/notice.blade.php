<x-front-layout>
    @section('title')
        {{ __('Please verify your email!') }}
    @endsection
    @include('partials.header')
    <div class="container py-20">
        <h3 class="text-center">{{ __('Please verify your email!') }}</h3>
        @session('message')
            <div class="text-violet-500 text-center p-2 my-2">{{ $value }}</div>
        @endsession
        @if (!session()->has('message'))
            <form class="mt-4" action="{{ route('verification.send') }}" method="post">
                @csrf
                <div class="text-center my-1 p-1">
                    <button class="button">{{ __('Resend email') }}</button>
                </div>
            </form>
        @endif
    </div>
    @include('partials.footer')

</x-front-layout>
