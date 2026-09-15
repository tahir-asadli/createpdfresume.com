<div class="container" id="has-orange-line">
    <div class="grid lg:grid-cols-2 mt-10 lg:mt-40">
        <div>
            <div class="flex justify-center lg:justify-start">
                <span class="badge">{{ __('Our application will help you find a job') }}</span>
            </div>
            <div class="mt-11"></div>
            <h3 class="text-center lg:text-left">{{ __('Simply create your account, and your pre-filled resume is ready to download') }}</h3>

            <p class="mt-9 w-full md:w-10/12 mx-auto lg:mx-0 leading-[1.8] text-center lg:text-left"></p>

            <div class="flex gap-6 mt-12 pt-1 mb-10 justify-center lg:justify-start">
                <span class="flex gap-2 text-[15px] font-semibold leading-none items-center"><i class="check"></i>{{ __('Free :count templates', ['count' =>  freePlanTemplateCount()]) }}</span>
                <span class="flex gap-2 text-[15px] font-semibold leading-none items-center"><i class="check"></i>{{__('Online help')}}</span>
            </div>
            @if(0)
                <div class="flex justify-center lg:justify-start">
                    <div class="mt-[50px] inline-flex border border-gray p-4 lg:p-6 lg:w-11/12 rounded-full">
                        <div class="flex items-center flex-shrink-0">
                            <img loading="lazy" class="shrink-0 relative w-[36px] h-[36px] lg:w-[72px] lg:h-[72px]"
                                src="/images/template/adam.webp" alt="{{ __('customer adam') }}">
                            <img loading="lazy" class="shrink-0 relative -left-4 w-[36px] h-[36px] lg:w-[72px] lg:h-[72px]"
                                src="/images/template/danny.webp" alt="{{ __('customer danny') }}">
                        </div>
                        <div class="flex flex-col justify-center gap-0 lg:gap-3">
                            <div class="font-bold text-lg">800+ People</div>
                            <div>
                                <p class="text-lg font-semibold">Use Collabo for manage their project</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
        </div>
        <div class="flex justify-center lg:justify-start -mt-28">
            <img loading="lazy" src="/images/template/testimonial.webp" alt="{{ __('testimonial') }}" class="object-contain">
        </div>
    </div>
</div>
