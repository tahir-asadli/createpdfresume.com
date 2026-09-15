<div class="bg-slate-100 pt-28 pb-10 mt-auto">
    <div class="container">
        <div class="grid md:grid-cols-2 gap-4 pb-10 md:pb-20">
            <div class="mt-2 flex flex-col">
                <div>
                    <a href="{{ lroute('home') }}">
                        <img loading="lazy" src="{{ config('site.logo') }}" class="w-[150px]" alt="{{ __('logo') }}"></a>
                </div>
                <div class="mt-8 max-w-[500px] mb-8">
                    <p>{{ __("Don't waste time creating a resume in Word, create your resume using our application and save time.") }}
                    </p>
                </div>
                @if (app()->getLocale() == 'az')
                    <div class="mt-auto flex flex-col items-baseline">
                        <a href="https://emlaksatisi.az/az" target="_blank">
                            <span class="block text-[11px] text-right text-gray-500 relative top-2">Ad</span>
                            <img loading="lazy" style="width: 190px;height: 50px;" src="/images/emlaksatisi.svg"
                                alt="{{ __('emlaksatisi.az') }}">
                        </a>
                    </div>
                @endif
                <div class="py-4 flex flex-col gap-1">
                    <div class="flex gap-2 items-center"><x-icons.language /><span>{{ __('Language') }}</span></div>
                    <div class="flex gap-2 items-center">
                        <a class="@if (app()->getLocale() == 'en') font-medium text-gray-800 @else font-light text-gray-600 @endif uppercase text-[13px]"
                            href="{{ route('home') }}">EN</a>
                        <span>&middot;</span>
                        <a class="@if (app()->getLocale() == 'az') font-medium text-gray-800 @else font-light text-gray-600 @endif uppercase text-[13px]"
                            href="{{ route('home') }}/az">AZ</a><span>&middot;</span>
                        <a class="@if (app()->getLocale() == 'tr') font-medium text-gray-800 @else font-light text-gray-600 @endif uppercase text-[13px]"
                            href="{{ route('home') }}/tr">TR</a><span>&middot;</span>
                        <a class="@if (app()->getLocale() == 'es') font-medium text-gray-800 @else font-light text-gray-600 @endif uppercase text-[13px]"
                            href="{{ route('home') }}/es">ES</a><span>&middot;</span>
                        <a class="@if (app()->getLocale() == 'ru') font-medium text-gray-800 @else font-light text-gray-600 @endif uppercase text-[13px]"
                            href="{{ route('home') }}/ru">RU</a>
                    </div>
                </div>
            </div>
            <div class="grid sm:grid-cols-3 gap-16 md:gap-0 mt-2 pl-1">
                <div>
                    <h6 class="text-[16px] font-bold">{{ __('Navigation') }}</h6>
                    <ul class="mt-3 flex flex-col gap-4">
                        <li><a href="{{ lroute('home') }}#features" class="font-normal">{{ __('Features') }}</a></li>
                        {{-- <li><a href="{{ lroute('home') }}#plans" class="font-normal">{{ __('Prices') }}</a></li> --}}
                        @if (app()->getLocale() == 'az')
                            <li><a href="{{ lroute('home') }}#videos" class="font-normal">{{ __('Videos') }}</a></li>
                        @endif
                        {{-- <li><a href="{{ lroute('home') }}#faq-section" class="font-normal">{{ __('FAQ') }}</a> --}}
                        </li>
                        <li><a href="{{ lroute('contact') }}" class="font-normal">{{ __('Contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-[16px] font-bold">{{ __('Information') }}</h6>
                    <ul class="mt-3 flex flex-col gap-4">
                        <li><a href="{{ lroute('privacy-policy') }}" class="font-normal">{{ __('Privacy') }}</a></li>
                        <li><a href="{{ lroute('about') }}" class="font-normal">{{ __('About Us') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-[16px] font-bold">{{ __('User') }}</h6>
                    <ul class="mt-3 flex flex-col gap-4">
                        @auth
                            <li><a href="{{ route('templates') }}" class="font-normal">{{ __('Account') }}</a></li>
                            <li>

                                <form action="{{ route('filament.app.auth.logout') }}" method="post">
                                    @csrf
                                    <button title="{{ __('Sign out') }}" class="font-normal">{{ __('Sign out') }}</button>
                                </form>
                            </li>
                        @endauth
                        @guest

                            <li><a href="{{ route('filament.app.auth.login') }}"
                                    class="font-normal">{{ __('Sign in') }}</a>
                            </li>
                            <li><a href="{{ route('filament.app.auth.register') }}"
                                    class="font-normal">{{ __('Sign up') }}</a></li>
                        @endguest
                    </ul>
                </div>
            </div>
        </div>
        <div class="flex justify-between text-darkGrey text-sm font-normal pt-10 md:pt-20 mt-2">
            <div class="">
                © {{ date('Y') }} {{ config('app.name') }}
            </div>
            <div class="flex gap-4">
                <a target="_blank" href="https://www.facebook.com/createpdfresume/"><x-icons.facebook
                        class="transition text-[#333] hover:text-[#1877F2]" width="25" height="25" /></a>
                <a target="_blank" href="https://www.youtube.com/@createpdfresume"><x-icons.youtube
                        class="transition text-[#333] hover:text-[#FF0000]" width="25" height="25" /></a>
                <a target="_blank"
                    href="https://api.whatsapp.com/send?phone=+994107290883&text={{ __('Hi') }},"><x-icons.whatsapp
                        class="transition text-[#333] hover:text-[#25D366]" width="25" height="25" /></a>
                <a target="_blank" href="https://x.com/createpdfresume"><x-icons.x
                        class="transition text-[#333] hover:text-[#000000]" width="25" height="25" /></a>
            </div>
        </div>
    </div>
    <a class="hidden lg:flex fixed transition-all bottom-2 right-2 group p-1 bg-[#25D366]/20 hover:bg-[#25D366]/20 hover:p-1.5 rounded-full z-[3]"
        target="_blank" href="https://api.whatsapp.com/send?phone=+994107290883&text={{ __('Hi') }}">
        <div
            class="transition-all rounded-[60px] w-[60px] h-[60px] hover:w-[250px] bg-[#25D366] text-white p-3 overflow-hidden flex gap-3 items-center">
            <x-icons.whatsapp width="35px" height="35px" class="flex-shrink-0" />

            <span class="transition-all flex flex-col gap-0 opacity-0 group-hover:opacity-100">
                <span class="leading-tight text-lg">{{ __('Need help?') }}</span>
                <span class="leading-tight">{{ __('Contact Us') }}</span>
            </span>
        </div>
    </a>
</div>
