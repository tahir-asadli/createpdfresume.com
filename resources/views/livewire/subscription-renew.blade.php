<span>
    {{ auth()->user()->subscription->renew ? __('Yes') : __('No') }}
    @if (auth()->user()->subscription->renew)
        - <form class="inline" action="{{ route('disableRenew') }}" method="post">
            @csrf
            <button class="underline text-tomato font-medium">{{ __('Turn off automatic renewal') }}</button>
        </form>
        <div>
            @if (!auth()->user()->cards()->verified()->count())
                <small>{!! __('You do not have an active card. A :card must be added for automatic subscription renewal. Otherwise, resumes (or CVs) associated with the plan will be deactivated after the subscription expires.', ['card' => '<a href="'.route('subscription').'" class="underline text-tomato font-bold">'.__('card').'</a>']) !!} </small>
            @endif
        </div>
    @else
        - <form class="inline" action="{{ route('enableRenew') }}" method="post">
            @csrf
            <button class="underline text-tomato font-medium cursor-pointer">{{ __('Turn on automatic renewal') }}</button>
        </form>
        <div>
            @if (!auth()->user()->cards()->verified()->count())
                <small>{!! __('You do not have an active card. A :card must be added for automatic subscription renewal. Otherwise, resumes (or CVs) associated with the plan will be deactivated after the subscription expires.', ['card' => '<a href="'.route('subscription').'" class="underline text-tomato font-bold">'.__('card').'</a>']) !!}</small>
                <div>
                    <small>{{ __('Resumes (or CVs) associated with the plan will be deactivated after the subscription expires.') }}</small>
                </div>
            @endif
        </div>
    @endif
</span>
