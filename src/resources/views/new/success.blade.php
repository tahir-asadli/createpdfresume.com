<x-new-front-layout>
    @section('title')
        {{ __('Congratulations!') }}
    @endsection

    @include('partials.new.header')
    <main>
        <h1 class="text-center">{{ __('Congratulations!') }}</h1>
        <p class="text-center mt-4">{!! __('Go to the :link to view your resume', [
            'link' => '<a class="underline text-violet-500" href="' . route('templates') . '">' . __('Dashboard') . '</a>',
        ]) !!}</p>

    </main>
    @include('partials.new.footer')

</x-new-front-layout>
