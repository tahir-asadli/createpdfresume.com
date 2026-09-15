<section>
    <div class="cta-banner">
        <div>
            <h3>{{ __('Stop fighting Word\'s formatting. Start applying.') }}</h3>
            <p>{{ __(':count templates, free to start, with online help if you get stuck.', ['count' => freePlanTemplateCount()]) }}
            </p>
        </div>
        <a href="{{ route('resumes') }}" class="btn btn-accent btn-lg">{{ __('Create my resume') }}</a>
    </div>
</section>
