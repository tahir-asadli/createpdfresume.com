<div class="container" id="features">
    <div class="flex justify-center mt-10 pt-10 lg:mt-[7rem] lg:pt-[7.5rem]">
        <span class="badge py-2 text-violet-800">{{ __('Features') }}</span>
    </div>
    <h3 class="text-center py-8">{{ __('Main features') }}</h3>
    <div class="flex justify-center gap-14 flex-col md:flex-row">
        <div
            class="rounded-3xl bg-white px-8 pt-[1.35rem] pb-7 lg:w-1/3 max-w-[300px] mx-auto lg:mx-0 flex flex-col items-center mt-14">
            <span class="bg-violet-500/10 rounded-full p-2">
                <img loading="lazy" src="/images/template/realtime-blue.svg?v=2" alt="{{ __('real time') }}">
            </span>
            <h6 class="w-full text-center mt-8">{{ __('Drag & Drop editor') }}</h6>
            <p class="text-center mt-5">{{ __('You can create your own design with the Resume builder') }}</p>
        </div>
        <div
            class="rounded-3xl bg-white px-8 pt-[1.35rem] pb-7 lg:w-1/3 max-w-[300px] mx-auto lg:mx-0 flex flex-col items-center mt-14">
            <span class="bg-violet-500/10 rounded-full p-2">
                <img loading="lazy" src="/images/template/theming-blue.svg?v=2" alt="{{ __('theming') }}">
            </span>
            <h6 class="w-full text-center mt-8">{{ __('Profile image filters') }}</h6>
            <p class="text-center mt-5">{{ __('You can add different filters to your profile picture') }}</p>
        </div>
        <div
            class="rounded-3xl bg-white px-8 pt-[1.35rem] pb-7 lg:w-1/3 max-w-[300px] mx-auto lg:mx-0 flex flex-col items-center mt-14">
            <span class="bg-violet-500/10 rounded-full p-2">
                <img loading="lazy" src="/images/template/multiple-resumes-blue.svg?v=2"
                    alt="{{ __('multiple resumes') }}">
            </span>
            <h6 class="w-full text-center mt-8">{{ __('Easily export your Resume to PDF') }}</h6>
            <p class="text-center mt-5">{{ __('You can convert your Resume to a PDF file with one click') }}</p>
        </div>
    </div>
</div>
{{-- <div class="container pt-8 pb-8" id="templates">
    <h3 class="text-center pt-8 pb-8 max-w-[900px] mx-auto leading-[1.8]">{{ __('Job-Winning Resume Templates') }}</h3>
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach (App\Models\Template::where('active', true)->orderBy('created_at', 'desc')->latest()->get() as $template)
            @if ($template->cover())
                <a href="{{ $template->cover() }}" class="block shadow-sm rounded-md bg-white p-3 relative"
                    title="{{ $template->name }}" data-fancybox="gallery">
                    <img loading="lazy" class="rounded-md w-full" src="{{ $template->thumb() }}"
                        alt="{{ __('template cover') }}">
                    @if ($template->plan)
                        <div
                            class="{{ $template->plan->slug == 'standart' ? 'plan-standart' : 'plan-premium' }} absolute top-1 right-1 text-xs p-1 rounded-md flex justify-center items-center text-white">
                            {{ $template->plan->name }}</div>
                    @else
                        <div
                            class="absolute top-1 right-1 text-xs p-1 rounded-md flex justify-center items-center text-white bg-violet-500">
                            {{ __('Free') }}
                        </div>
                    @endif
                </a>
            @endif
        @endforeach
    </div>
    <p class="text-center max-w-[600px] mx-auto leading-7 mt-10">{{ __('We will add more templates in the future') }}
    </p>
</div> --}}
{{-- <div class="container mt-10" id="plans">
    <div class="flex justify-center mt-24 pt-5">
        <span class="badge py-2 px-12 text-violet-800">{{ __('Prices') }}</span>
    </div>
    <div>
        <h3 class="text-center pt-2 pb-6 max-w-[900px] mx-auto leading-[1.8]">{{ __('Our plans') }}</h3>
    </div>
</div>
<x-plans /> --}}
