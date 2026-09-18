<div class="container pt-0 pb-8" id="templates">
    <h3 class="text-center pt-8 pb-8 max-w-[900px] mx-auto leading-[1.8]">{{ __('Job-Winning Resume Templates') }}</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5  gap-3">
        @foreach (App\Models\Template::where('active', true)->orderBy('created_at', 'desc')->latest()->get() as $template)
            @if ($template->cover())
                <a href="{{ $template->cover() }}" class=" w-full block shadow rounded-md bg-white relative"
                    title="{{ $template->name }}" data-fancybox="templates">
                    <img class="rounded-md w-full" src="{{ $template->thumb() }}" alt="{{ __('template cover') }}">
                </a>
            @endif
        @endforeach
    </div>
    <p class="text-center max-w-[600px] mx-auto leading-7 mt-10">{{ __('We will add more templates in the future') }}
    </p>
</div>
