<x-dashboard-layout>
    @section('title')
        {{ __('Subscription') }}
    @endsection
    <div class="w-full my-5">
        @if (auth()->user()->hasSubscription())
            <div class="flex flex-col gap-3">
                <h3 class="font-medium text-lg"><b>{{ __('Subscription plan') }}:</b> {{ auth()->user()->subscription->plan->name }}
                </h3>
                <h3 class="font-medium text-lg"><b>{{ __('Subscription price') }}:</b>
                    {{auth()->user()->subscription->priceStrUSDAZN() }}
                </h3>
                <h3 class="font-medium text-lg"><b>{{ __('Subscription end date') }}:</b>
                    {{ auth()->user()->subscription->ends_at->format('d/m/Y') }}
                </h3>
                <h3 class="font-medium text-lg"><b>{{ __('Subscription will renew') }}:</b>
                    <livewire:subscription-renew />
                </h3>
                <div>
                    <h3 class="font-medium text-lg"><b>{{ __('Plan') }}:</b>
                        <br />
                        {{ __('Template limit') }}: <b>{{ auth()->user()->subscription->plan->templates()->active()->count() }}</b><br />
                        {{ __('Resume limit') }}: <b>{{ auth()->user()->subscription->plan->resume_count }}</b><br />
                        {{ __('PDF generation limit') }}: <b>{{ auth()->user()->subscription->plan->generation_count }}</b><br />
                        {{ __('Page limit') }}: <b>{{ auth()->user()->subscription->plan->page_count }}</b><br />
                    </h3>
                    <br>
                </div>
            </div>
            <div>
                <a href="{{ route('cancel') }}"
                    class="button p-3 bg-violet-100/50 shadow-none border-none text-violet-800">{{ __('Cancel subscription') }}</a>
            </div>
    </div>
    @else
    <h3 class="font-medium text-lg">{{ __('You have no active subscription') }}</h3>
    <h4 class="font-medium text-md">{!! __('You can buy a new subscription :link.', ['link' => '<a class="underline text-violet-800" href="'.lroute('home').'/#plans">'.__('here').'</a>']) !!}</h4>
    <div class="mt-3">
        <h3 class="font-medium text-lg"><b>{{ __('Free plan') }}:</b>
            <br />
            {{ __('Template limit') }}: <b>{{ freePlanTemplateCount() }}</b><br />
            {{ __('Resume limit') }}: <b>{{ setting('freePlanCVLimit', config('site.freePlanCVLimit')) }}</b><br />
            {{ __('PDF generation limit') }}: <b>{{ setting('freePlanPdfLimit', config('site.freePlanPdfLimit')) }}</b><br />
            {{ __('Page limit') }}: <b>{{ setting('freePlanPageLimit', config('site.freePlanPageLimit')) }}</b><br />
        </h3>
        <br>
    </div>
    @endif
    </div>

</x-dashboard-layout>
