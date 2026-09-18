<div class="container pt-8 pb-8" id="template">
    <h3 class="text-center pt-8 pb-8 max-w-[900px] mx-auto leading-[1.8]">{{ __('One template multiple styles') }}</h3>
    <div class="max-w-[500px] mx-auto">
        <div class="splide splide-templates">
            <div class="splide__track">
                <ul class="splide__list">
                    <li class="splide__slide flex justify-center">
                        <a href="/templates/bill/resume-bill-gates-blue.png-1.png" target="_blank"
                            data-fancybox="templates-styles">
                            <img src="/templates/bill/resume-bill-gates-blue.png-1.png" alt="">
                        </a>
                    </li>
                    <li class="splide__slide flex justify-center">
                        <a href="/templates/bill/resume-bill-gates-coffee.png-1.png" target="_blank"
                            data-fancybox="templates-styles">
                            <img src="/templates/bill/resume-bill-gates-coffee.png-1.png" alt="">
                        </a>
                    </li>
                    <li class="splide__slide flex justify-center">
                        <a href="/templates/bill/resume-bill-gates-cream.png-1.png" target="_blank"
                            data-fancybox="templates-styles">
                            <img src="/templates/bill/resume-bill-gates-cream.png-1.png" alt="">
                        </a>
                    </li>
                    <li class="splide__slide flex justify-center">
                        <a href="/templates/bill/resume-bill-gates-dark.png-1.png" target="_blank"
                            data-fancybox="templates-styles">
                            <img src="/templates/bill/resume-bill-gates-dark.png-1.png" alt="">
                        </a>
                    </li>
                    <li class="splide__slide flex justify-center">
                        <a href="/templates/bill/resume-bill-gates-default.png-1.png" target="_blank"
                            data-fancybox="templates-styles">
                            <img src="/templates/bill/resume-bill-gates-default.png-1.png" alt="">
                        </a>
                    </li>
                    <li class="splide__slide flex justify-center">
                        <a href="/templates/bill/resume-bill-gates-green.png-1.png" target="_blank"
                            data-fancybox="templates-styles">
                            <img src="/templates/bill/resume-bill-gates-green.png-1.png" alt="">
                        </a>
                    </li>
                    <li class="splide__slide flex justify-center">
                        <a href="/templates/bill/resume-bill-gates-pink.png-1.png" target="_blank"
                            data-fancybox="templates-styles">
                            <img src="/templates/bill/resume-bill-gates-pink.png-1.png" alt="">
                        </a>
                    </li>
                    <li class="splide__slide flex justify-center">
                        <a href="/templates/bill/resume-bill-gates-red.png-1.png" target="_blank"
                            data-fancybox="templates-styles">
                            <img src="/templates/bill/resume-bill-gates-red.png-1.png" alt="">
                        </a>
                    </li>
                    <li class="splide__slide flex justify-center">
                        <a href="/templates/bill/resume-bill-gates-tennis.png-1.png" target="_blank"
                            data-fancybox="templates-styles">
                            <img src="/templates/bill/resume-bill-gates-tennis.png-1.png" alt="">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.splide')) {
                var splide = new Splide('.splide', {
                    type: 'fade',
                    rewind: true,
                    autoplay: true,
                    interval: 1000
                });

                splide.mount();
            }
        });
    </script>
</div>
