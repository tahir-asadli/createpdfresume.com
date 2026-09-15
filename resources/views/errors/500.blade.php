<x-new-front-layout>
    @section('title')
        {{ __('Error occured!') }}
    @endsection
    @include('partials.new.header')
    <main>
        <h1 class="text-center mb-5 text-[30px]">{{ __('Error occured!') }}</h1>
        <div class="p-4 flex justify-center items-center border border-violet-200 bg-violet-100/50 rounded-md">
            {{ __('Please check again in a few minutes') }}</div>
    </main>
    @include('partials.new.footer')
</x-new-front-layout>
