<x-new-front-layout>
    @section('title')
        {{ __('The site is under maintenance') }}
    @endsection
    @include('partials.new.header')
    <main>
        <h1 class="text-center mb-5 text-[30px]">{{ __('The site is under maintenance') }}</h1>
        <div class="p-4 flex justify-center items-center border border-violet-200 bg-violet-100/50 rounded-md">
            {{ __('Please check back shortly') }}</div>
    </main>
    @include('partials.new.footer')
</x-new-front-layout>
