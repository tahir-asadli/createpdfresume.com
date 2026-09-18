<div class="container mt-10">
    <div class="flex flex-col md:flex-row items-center md:items-stretch gap-7 mt-5 pb-20 justify-center">
        <div class="bg-white w-full pt-10 max-w-[300px] pb-[2px] px-[2px] rounded-[20px] flex flex-col items-stretch">
            <div class="bg-white w-full rounded-[16px] h-full">
                <h5 class="pt-4 px-6">{{ __('Free') }}</h5>
                <h5 class="px-6 text-3xl font-semibold mt-7">
                    {{ __('Free') }}
                </h5>
                <a href="{{ route('resumes') }}"
                    class="block button text-center border border-grey/50 bg-white text-black mx-6 mt-5 mb-5">{{ __('Select') }}</a>
                <div class="px-6 pr-3 flex flex-col gap-6 pb-8">
                    <div class="flex items-center gap-2 text-[12px]"><span class="check bg-violet-100"></span>{{ __('Template limit') }}: {{ freePlanTemplateCount() }}
                    </div>
                    <div class="flex items-center gap-2 text-[12px]"><span class="check bg-violet-100"></span>{{ __('Resume limit') }}: {{ setting('freePlanCVLimit', config('site.freePlanCVLimit')) }}
                    </div>
                    <div class="flex items-center gap-2 text-[12px]"><span class="check bg-violet-100"></span>{{ __('PDF generation limit') }}: {{ setting('freePlanPdfLimit', config('site.freePlanPdfLimit')) }}
                    </div>
                    <div class="flex items-center gap-2 text-[12px]"><span class="check bg-violet-100"></span>{{ __('Page limit') }}: {{ setting('freePlanPageLimit', config('site.freePlanPageLimit')) }}
                    </div>
                </div>
            </div>
        </div>
        @foreach ($plans as $plan)
            <div
                class="{{ $plan->slug == 'premium' ? 'bg-violet-500' : 'bg-white' }} w-full pt-10 max-w-[300px] pb-[2px] px-[2px] rounded-[20px] flex flex-col items-stretch">
                <div class="bg-white w-full rounded-[16px] h-full">
                    <h5 class="pt-4 px-6">{{ $plan->name }}</h5>
                    @if ($plan->description)
                        <p class="text-[12px] pt-4 px-6 pb-5">{{ $plan->description }}</p>
                        <div class="border-b border-grey/50 mb-2 mx-6"></div>
                    @endif
                    <h5 class="flex flex-col px-6 text-3xl font-semibold mt-7">
                        <span>{{ $plan->usd > 0 ? priceUSD($plan->usd) . ' / ' . __('month') : __('Free') }}</span>
                        <span class="text-[13px] font-normal">{{ priceUSD($plan->usd) }} = {{ priceAZNLong($plan->price) }}</span>
                    </h5>
                    <a href="{{ route('checkout', $plan->slug) }}"
                        class="block button text-center {{ $plan->slug == 'premium' ? 'bg-violet-500 text-white' : 'border border-grey/50 bg-white text-black' }}   mx-6 mt-5 mb-5">{{ __('Select') }}</a>

                    <div class="px-6 pr-3 flex flex-col gap-6 pb-8">
                        <div class="flex items-center gap-2 text-[12px]"><span class="check bg-violet-100"></span>{{ __('Template limit') }}: {{ $plan->templates()->active()->count() }}
                        </div>
                        <div class="flex items-center gap-2 text-[12px]"><span class="check bg-violet-100"></span>{{ __('Resume limit') }}: {{ $plan->resume_count }}
                        </div>
                        <div class="flex items-center gap-2 text-[12px]"><span class="check bg-violet-100"></span>{{ __('PDF generation limit') }}: {{ $plan->generation_count }}
                        </div>
                        <div class="flex items-center gap-2 text-[12px]"><span class="check bg-violet-100"></span>{{ __('Page limit') }}: {{ $plan->page_count }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
