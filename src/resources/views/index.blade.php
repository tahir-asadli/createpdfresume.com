<x-front-layout>
    @section('title', __('Online resume builder'))

    @section('ldjson')
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "WebSite",
                "name": "{{ __('Online resume builder') }}",
                "url": "{{ config('app.url') }}",
                "publisher": {
                    "@type": "Organization",
                    "name": "{{ config('app.name') }}",
                    "url": "{{ config('app.url') }}",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "{{ config('site.logo') }}",
                        "width": 200,
                        "height": 38
                    }
                }
            }
        </script>
    @endsection
    @section('canonical')
        @if (app()->getLocale() != 'en')
            <link rel="canonical" href="{{ config('app.url') }}" />
        @endif
    @endsection
    @include('partials.header')
    @include('partials.ad')
    @include('partials.import')
    @include('partials.templates')
    @include('partials.ad')
    @include('partials.template-slider')
    @include('partials.ad')
    @include('partials.hero')
    @include('partials.ad')
    @include('partials.customers')
    @include('partials.ad')
    {{-- Relative --}}
    {{-- Features --}}
    {{--  mb-20 --}}
    <div class="bg-slate-100 pb-4">
        @include('partials.features')
    </div>

    @vite(['resources/css/glide-core.css', 'resources/css/glide-theme.css'])
    {{-- <div id="faq-section">
        <div class="container pt-6 md:pt-12 pb-10">
            <div class="grid md:grid-cols-2 gap-20">
                <div class="flex justify-center mt-10 md:mt-24">
                    <div class="ml-16 mt-2">
                        <img loading="lazy" src="/images/template/faq2.webp" alt="{{ __('faq') }}">
                    </div>
                </div>
                <div>
                        <div>
                            <span class="badge py-2 px-5 text-violet-800">FAQ</span>
                        </div>
                    <div class="mt-11 pt-0.5">
                        <h2>{{ __('Frequently Asked Questions') }}</h2>
                    </div>
                    <div class="flex flex-col mt-12 gap-5">
                        <div x-data="{ open: false }">
                            <div x-on:click="open=!open" :class="open ? 'text-purple' : ''"
                                class=" text-lg pt-3 pb-4 border-b border-grey/20 flex justify-between cursor-pointer transition-all">
                                <div>{{ __('Is the application free?') }}</div>
                                <button :class="open ? '' : 'rotate-45'"
                                    class="w-6 h-6 text-4xl leading-none flex items-center transition-all">&times;</button>
                            </div>
                            <div class=" overflow-hidden h-0 transition-all "
                                :style="open && { height: $el.scrollHeight + `px` }">
                                <p class="text-lg leading-8 font-normal py-7">{{__('You can create a resume using :count free templates. To use other templates and features, you\'ll need to purchase a :plan plan', ['count' => freePlanTemplateCount(), 'plan' => 'Premium'])}}</p>
                            </div>
                        </div>
                        <div x-data="{ open: false }">
                            <div x-on:click="open=!open" :class="open ? 'text-purple' : ''"
                                class=" text-lg pt-3 pb-4 border-b border-grey/20 flex justify-between cursor-pointer transition-all">
                                <div>{{ __('Do I have to pay every month?') }}</div>
                                <button :class="open ? '' : 'rotate-45'"
                                    class="w-6 h-6 text-4xl leading-none flex items-center transition-all">&times;</button>
                            </div>
                            <div class=" overflow-hidden h-0 transition-all "
                                :style="open && { height: $el.scrollHeight + `px` }">
                                <p class="text-lg leading-8 font-normal py-7">{{__('You can choose whether your chosen plan is a recurring or one-time payment on the payment page')}}</p>
                            </div>
                        </div>

                        <div x-data="{ open: false }">
                            <div x-on:click="open=!open" :class="open ? 'text-purple' : ''"
                                class=" text-lg pt-3 pb-4 border-b border-grey/20 flex justify-between cursor-pointer transition-all">
                                <div>{{ __('Can I download the resumes after my subscription ends?') }}</div>
                                <button :class="open ? '' : 'rotate-45'"
                                    class="w-6 h-6 text-4xl leading-none flex items-center transition-all">&times;</button>
                            </div>
                            <div class=" overflow-hidden h-0 transition-all "
                                :style="open && { height: $el.scrollHeight + `px` }">
                                <p class="text-lg leading-8 font-normal py-7">{{ __('After the subscription ends, only resumes created using free features can be downloaded') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    @if (app()->getLocale() == 'az')
        @include('partials.videos')
    @endif

    @include('partials.footer')


    {{-- <div id="orange-line" class="hidden lg:block w-full absolute overflow-hidden pointer-events-none h-[1600px]">
        <div
            class="bg-violet-500 w-[2000px] -z-10 h-[345px] rounded-bl-full rounded-tl-full -rotate-[35deg] absolute left-[50%] bottom-[31%] m-auto">
        </div>
    </div> --}}
    {{-- <div id="light-orange-line" class="hidden lg:block w-full absolute overflow-hidden pointer-events-none h-[1600px]">
        <div
            class="bg-violet-200 w-[2000px] -z-10 h-[345px] rounded-br-full rounded-tr-full rotate-[35deg] absolute right-[55%] bottom-[31%] m-auto">
        </div>
    </div> --}}
    <script>
        function positionLines() {
            const collaborate = document.getElementById('has-orange-line');
            const faqSection = document.getElementById('faq-section');
            const orangeLine = document.getElementById('orange-line');
            const lightOrangeLine = document.getElementById('light-orange-line');

            if (collaborate && orangeLine) {
                const collaborateBottom = collaborate.offsetTop;
                orangeLine.style.top = `calc(1700px - ${collaborateBottom}px)`;
            }
            if (faqSection && lightOrangeLine) {
                const faqSectionBottom = faqSection.offsetTop;
                lightOrangeLine.style.top = `calc(${faqSectionBottom}px - 900px)`;
            }
        }
        document.addEventListener('DOMContentLoaded', function() {

            positionLines();
            window.addEventListener('resize', function(e) {
                positionLines();
            })



        });
    </script>
</x-front-layout>
