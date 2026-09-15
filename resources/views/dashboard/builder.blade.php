<x-builder-layout>
    @section('title')
        {{ __('Modify resume') }}
    @endsection
    <div class="container h-[70px] border-b border-slate-200 shadow-sm flex justify-between items-center px-4 flex-wrap">
        <div class="flex gap-3 items-center mr-auto max-xl:hidden">
            <a href="{{ route('resumes') }}" class="button">
                <x-icons.arrow_left class="text-violet-800" />
                <span>{{ __('Back') }}</span></a>
            <div class="v-seperator h-9 border-r-2 opacity-55 ml-3 mr-1"></div>
            <div class="font-semibold text-xl text-gray-700">{{ __('Template') }}: {{ $resume->template->name }}</div>
        </div>
        <div class="flex justify-center max-xl:hidden">
            <a href="{{ route('templates') }}" class="button "><x-icons.template /><span>{{ __('Templates') }}</span></a>
        </div>
        <div class="flex items-center gap-2 sidebar-logo xl:hidden">
            <a href="{{ route('templates') }}" class="rounded link block"><img loading="lazy" class="w-[150px]"
                    src="{{ config('site.logo') }}" alt="{{ __('logo') }}"></a>
        </div>
        <button
            class="ml-auto button bg-white border border-gray-300 text-black flex gap-2 items-center p-4 dashboard-settings-menu-toggle xl:hidden"><x-icons.settings
                width="20px" height="20px" /></button>
        <button
            class="ml-2 button bg-white border border-gray-300 text-black flex gap-2 items-center p-4 dashboard-menu-toggle xl:hidden"><x-icons.hamburger
                width="20px" height="20px" /></button>

        <div class="ml-auto flex items-center gap-2 max-xl:hidden">
            {{-- <a class="button" href="{{ route('download', $resume->uuid) }}"
                title="{{ __('There is no limit. This will download the last generated resume version.') }}">
                <x-icons.download class="hidden md:block" />
                {{ __('Download') }}
            </a> --}}
            <livewire:download :resume="$resume" />
            <a href="{{ route('view', [$resume->uuid]) }}" title="{{ __('Preview') }}" target="_blank"
                class="button justify-center ml-2"><x-icons.eye2 /><span>{{ __('Preview') }}</span></a>
            {{-- @if (auth()->user()->shouldUpgrade())
                <div class="v-seperator h-9 border-r-2 opacity-55 ml-3 mr-3"></div>
                <a href="/#plans" title="{{ __('Change plan') }}" title="{{ __('Change plan') }}"
                    class="button bg-violet-100 border-none shadow-none text-violet-900"><x-icons.rocket
                        class="text-violet-600" /><span>{{ __('Change plan') }}</span></a>
            @endif --}}
            <div class="v-seperator mx-4"></div>

            <form action="{{ route('filament.app.auth.logout') }}" method="post">
                @csrf
                <button title="{{ __('Sign out') }}"
                    class="button bg-white border border-gray-300 text-black flex gap-2 items-center p-4"><x-icons.logout
                        class="text-violet-800" width="20px" height="20px" /></button>
            </form>
        </div>
    </div>

    <main class="container flex px-0">
        <div class="w-full grid xl:grid-cols-[auto_21cm]">
            <div class="min-w-0">
                <div class="canvas-container">
                    <livewire:style-names :resume="$resume" />
                    <livewire:builder-widgets :resume="$resume" :page="$page" />
                </div>
            </div>
            <div class="min-w-0">
                <div class="h-[1200px] min-h-full preview relative group">
                    <livewire:pager :resume="$resume" :page="$page" />
                    <livewire:previewer url="{{ $resume->getPreviewURL($page) }}" />
                </div>
            </div>
        </div>
        </div>
    </main>


    <div class="dashboard-settings-menu fixed top-0 left-0 bg-white w-full h-full z-10 hidden ">

        <button
            class=" absolute top-2 right-4 ml-auto button bg-white border border-gray-300 text-black flex gap-2 items-center p-4 dashboard-settings-menu-toggle xl:hidden"><x-icons.times
                width="20px" height="20px" /></button>
        <div class="p-2 h-full overflow-scroll">
            <div class="max-w-[300px] mx-auto flex flex-col gap-2 justify-center h-full">

                {{-- @if (auth()->user()->shouldUpgrade() || 1)
                    <a href="/#plans" title="{{ __('Change plan') }}" title="{{ __('Change plan') }}"
                        class="w-full flex justify-center items-center button bg-violet-100 border-none shadow-none text-violet-900"><x-icons.rocket
                            class="text-violet-600" /><span class="">{{ __('Change plan') }}</span></a>
                @endif --}}

                <div>

                    <a href="{{ route('resumes') }}" class="w-full button flex justify-center gap-2 items-center">
                        <x-icons.arrow_left class="text-violet-800 flex-shrink-0" />
                        <span>{{ __('Back') }}</span></a>
                </div>
                <div>

                    <a href="{{ route('templates') }}" class="w-full button flex justify-center gap-2 items-center">
                        <x-icons.template class="flex-shrink-0" />
                        <span>{{ __('Templates') }}</span></a>
                </div>
                <div>
                    <a href="{{ route('view', [$resume->uuid]) }}"
                        class="w-full button flex justify-center gap-2 items-center" target="_blank">
                        <x-icons.eye2 class="flex-shrink-0" />
                        <span>{{ __('Preview') }}</span></a>
                </div>
                <livewire:download :forMobile="true" :resume="$resume" />


            </div>
        </div>

    </div>

    <div class="dashboard-menu fixed top-0 left-0 bg-white w-full h-full z-10 hidden ">
        <button
            class=" absolute top-2 right-4 ml-auto button bg-white border border-gray-300 text-black flex gap-2 items-center p-4 dashboard-menu-toggle xl:hidden"><x-icons.times
                width="20px" height="20px" /></button>
        <div class="p-2 h-full overflow-scroll">
            <div class="max-w-[300px] mx-auto flex flex-col gap-2 justify-center h-full">
                <div>
                    <a href="{{ route('dashboard') }}"
                        class="w-full button flex justify-center gap-2 items-center {{ request()->segment(1) == 'dashboard' && request()->segment(2) == '' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"><x-icons.dashboard
                            class="flex-shrink-0" width="20px" height="20px" />{{ __('Dashboard') }}</a>
                </div>
                <div>
                    <a href="{{ route('templates') }}"
                        class="w-full button flex justify-center gap-2 items-center {{ request()->segment(2) == 'templates' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"><x-icons.template
                            class="flex-shrink-0" width="20px" height="20px" />{{ __('Templates') }}</a>
                </div>
                <div>
                    <a class="w-full button flex justify-center gap-2 items-center {{ request()->segment(2) == 'resumes' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"
                        href="{{ route('resumes') }}">
                        <x-icons.resumes class="flex-shrink-0" width="20px" height="20px" />
                        <span class="">{{ __('Resumes') }}</span>
                    </a>
                </div>
                {{-- <div>
                    <a class="w-full button flex justify-center gap-2 items-center {{ request()->segment(2) == 'subscription' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"
                        href="{{ route('subscription') }}">
                        <x-icons.badge class="flex-shrink-0" width="20px" height="20px" />
                        <span class="">{{ __('Subscription') }}</span>
                    </a>
                </div> --}}
                <div>
                    <a class="w-full button flex justify-center gap-2 items-center {{ request()->segment(2) == 'informations' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"
                        href="{{ route('informations') }}">
                        <x-icons.informations class="flex-shrink-0" width="20px" height="20px" />
                        <span class="">{{ __('Informations') }}</span>
                    </a>
                </div>
                <div>
                    <a class="w-full button flex justify-center gap-2 items-center {{ request()->segment(2) == 'billing' ? 'text-violet-500 border-violet-500' : 'text-[#09162f]' }}"
                        href="{{ route('billing') }}">
                        <x-icons.account class="flex-shrink-0" width="20px" height="20px" />
                        <span class="">{{ __('Account') }}</span>
                    </a>
                </div>
                <div>
                    <button onclick="Livewire.dispatch('openModal', { component: 'add-resume' })"
                        class="w-full button flex justify-center gap-2 items-center">
                        <x-icons.plus class="flex-shrink-0" width="20px" height="20px" />
                        <span class="inline">{{ __('New resume') }}</span>
                    </button>
                </div>
                <div class="flex">
                    <form action="{{ route('filament.app.auth.logout') }}" method="post" class="w-full">
                        @csrf
                        <button
                            class="w-full button bg-white border border-gray-300 text-black flex justify-center gap-2 items-center p-4"><x-icons.logout
                                class="text-violet-800 flex-shrink-0" width="20px"
                                height="20px" />{{ __('Sign out') }}</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    @livewire('wire-elements-modal')

</x-builder-layout>
