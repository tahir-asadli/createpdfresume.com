<div class="container pt-8 pb-8" id="templates">
    <h3 class="text-center pt-8 pb-8 max-w-[900px] mx-auto leading-[1.8]">{{ __('Job-Winning Resume Templates') }}</h3>

    <div class="glide">
        <div data-glide-el="track" class="glide__track">
            <ul class="glide__slides !py-3">
                @foreach (App\Models\Template::where('active', true)->inRandomOrder()->latest()->get() as $template)
                    @if ($template->cover())
                        <li class="glide__slide flex justify-center">
                            <a href="{{ $template->cover() }}"
                                class=" w-[290px] block shadow rounded-md bg-white relative"
                                title="{{ $template->name }}" data-fancybox="templates">
                                <img class="rounded-md w-full" src="{{ $template->thumb() }}"
                                    alt="{{ __('template cover') }}">
                                {{-- @if ($template->plan)
                                    <div
                                        class="{{ $template->plan->slug == 'standart' ? 'plan-standart' : 'plan-premium' }} absolute top-1 right-1 text-xs p-1 rounded-md flex justify-center items-center text-white">
                                        {{ $template->plan->name }}</div>
                                @else
                                    <div
                                        class="absolute top-1 right-1 text-xs p-1 rounded-md flex justify-center items-center text-white bg-violet-500">
                                        {{ __('Free') }}
                                    </div>
                                @endif --}}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
            <div class="glide__arrows absolute top-0 left-0 w-full h-full flex justify-between items-center pointer-events-none"
                data-glide-el="controls">
                <span
                    class="_glide__arrow glide__arrow--left rounded-full bg-black/30 w-12 h-12 text-white text-3xl flex justify-center items-center relative !left-2 pointer-events-auto"
                    data-glide-dir="<">‹</span>
                <span
                    class="_glide__arrow glide__arrow--right rounded-full  bg-black/30 w-12 h-12 text-white text-3xl flex justify-center items-center relative !right-2 pointer-events-auto"
                    data-glide-dir=">">›</span>
            </div>
        </div>
    </div>

    <p class="text-center max-w-[600px] mx-auto leading-7 mt-10">{{ __('We will add more templates in the future') }}
    </p>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        new window.Glide('.glide', {
            type: 'carousel',
            autoplay: 3000,
            hoverpause: true,
            perView: 4,
            focusAt: 'center',
            breakpoints: {
                1200: {
                    perView: 4
                },
                1024: {
                    perView: 3
                },
                800: {
                    perView: 2
                },
                480: {
                    perView: 1,
                }
            }
        }).mount()

    });
</script>
