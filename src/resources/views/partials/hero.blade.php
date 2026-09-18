<div class="container">
    @if (App::environment('production'))
        <div class="w-full">
            <!-- Leaderboard -->
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-9585764287862286"
                data-ad-slot="2158810079" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    @endif
    <div>
        <h1 class="max-w-[890px] mt-16 mx-auto text-center leading-[1.3]">
            {{ __('Get hired fast with a powerful resume') }}</h1>
        <p class="max-w-[650px] mt-6 mx-auto text-center">
            {{ __('Easily create your resume in just minutes with a template you love') }}</p>
    </div>
    <div class="flex-col lg:flex-row flex justify-center items-center gap-4 mt-9">
        <a href="{{ route('templates') }}"
            class="w-full sm:w-auto text-center sm:text-left max-w-[340px] sm:max-w-[auto] button py-5 px-14 font-medium text-xl button-large">{{ __('Create my resume') }}</a>
        {{-- <a href="/register-final.mp4" data-fancybox="video-gallery"
            class="w-full sm:w-auto text-center sm:text-left max-w-[300px] sm:max-w-[auto] button py-5 px-14 font-medium text-xl border border-gray bg-white text-black">{{ __('Play video') }}</a> --}}

    </div>
</div>
<div class="flex justify-center mt-10 px-10 lg:px-2">
    <a href="/images/template/createpdfresume-screen-4.webp" data-fancybox="gallery">
        <img loading="lazy" class="lg:max-w-[1000px] rounded-lg shadow-lg border-[10px] border-gray-200/50"
            src="/images/template/createpdfresume-screen-4.webp" alt="{{ __('dashboard') }}">
    </a>
</div>

<div class="container">
    <h4 class="mt-10 lg:mt-20 text-center max-w-[980px] mx-auto">
        {{ __('You don\'t have to fill out all your information just to play around with the builder') }}</h4>
</div>
