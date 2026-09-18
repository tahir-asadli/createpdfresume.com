<section id="our-templates">
    <div class="section-head">
        <span class="kicker">{{ __('Templates') }}</span>
        <h2>{{ __('Job-winning layouts, ready to fill in') }}</h2>
        <p>{{ __(':count templates built around how hiring managers actually scan a page — and we\'re adding more.', ['count' => freePlanTemplateCount()]) }}
        </p>
    </div>
    <div class="tplgrid">
        @foreach (App\Models\Template::where('active', true)->orderBy('created_at', 'desc')->latest()->get() as $template)
            @if ($template->cover())
                <div class="tplcard">
                    <a href="{{ $template->cover() }}" class=" w-full block shadow rounded-md bg-white relative"
                        title="{{ $template->name }}" data-fancybox="templates">
                        <img class="rounded-md w-full" src="{{ $template->thumb() }}" alt="{{ __('template cover') }}">
                    </a>
                </div>
            @endif
        @endforeach
    </div>
</section>
