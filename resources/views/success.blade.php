<x-front-layout>
    @section('title')
        {{ __('Congratulations!') }}
    @endsection

    @include('partials.header')
    <div class="container py-20">
        <h1 class="text-center">{{ __('Congratulations!') }}</h1>
        <p class="text-center mt-4">{!! __('Go to the :link to view your resume', ['link' => '<a class="underline text-violet-500" href="'.route('templates').'">'.__('Dashboard').'</a>']) !!}</p>

    </div>
    @include('partials.footer')

</x-front-layout>
