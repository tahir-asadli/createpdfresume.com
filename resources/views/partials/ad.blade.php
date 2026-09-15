<div class="flex justify-center">
    @if (App::environment('production'))
        <div class="hidden md:block">
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9585764287862286"
                crossorigin="anonymous"></script>
            <!-- leaderboard -->
            <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px"
                data-ad-client="ca-pub-9585764287862286" data-ad-slot="2065384266"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
        <div class="block md:hidden">
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9585764287862286"
                crossorigin="anonymous"></script>
            <!-- mobile -->
            <ins class="adsbygoogle" style="display:inline-block;width:300px;height:250px"
                data-ad-client="ca-pub-9585764287862286" data-ad-slot="7126139250"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    @else
        <div class="hidden md:block w-[728px] h-[90px] bg-green-300"></div>
        <div class="block md:hidden w-[300px] h-[250px] bg-green-300"></div>
    @endif
</div>
