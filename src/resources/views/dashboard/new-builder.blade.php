<x-builder-layout class="new-builder-layout">
    @section('title')
        {{ __('New builder') }}
    @endsection
    @section('resume-uuid', $resume->uuid)
    <script>
        const newBuilderRenderTemplateURL = `{{ route('new-builder-render', [$resume->uuid, $page]) }}`;
        const newBuilderTemplatePage = {{ $page }};
        const are_you_sure = `{{ __('Are you sure?') }}`;
    </script>

    <div
        class="container h-[70px] border-b border-slate-200 shadow-sm flex justify-between items-center px-4 flex-wrap relative z-[3] bg-white">

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
            <button class="button download-resume" id="download-resume" data-resume-uuid="{{ $resume->uuid }}">
                <x-icons.download class="hidden md:block" />
                <span id="download-resume-idle-text">{{ __('Download PDF') }}</span>
                <span id="download-resume-wait-text" class="hidden">{{ __('Please wait...') }}</span>
            </button>
            <a href="{{ route('view', [$resume->uuid]) }}" title="{{ __('Preview') }}" target="_blank"
                class="button justify-center ml-2"><x-icons.eye2 /><span>{{ __('Preview') }}</span></a>
            <div class="v-seperator mx-4"></div>

            <form action="{{ route('filament.app.auth.logout') }}" method="post">
                @csrf
                <button title="{{ __('Sign out') }}"
                    class="button bg-white border border-gray-300 text-black flex gap-2 items-center p-4"><x-icons.logout
                        class="text-violet-800" width="20px" height="20px" /></button>
            </form>
        </div>
    </div>
    {{-- @if (count($resume->template->styleArray()) > 0)
        <button class="style toggle-styles-container ml-4"
            style="background-color: {{ $resume->template->styleColorBySlug($resume->style) }}"
            title="{{ $resume->styleName() }}">
        </button>
    @endif --}}

    {!! $resume->template->allCSSTags() !!}
    <div class="new-builder-canvas-container w-full">

        <div class="relative flex justify-center bg-transparent w-full">
            @if (count($resume->template->styleArray()) > 0)
                <div class="absolute top-[4px] w-full max-w-[21cm] h-[36px]">
                    <div class="relative z-[1] h-full flex items-center leading-none w-[600px] overflow-hidden">
                        <div
                            class="rounded-bl-md rounded-br-md pr-4 text-center w-full py-4 font-medium flex gap-2 styles-container">
                            {{-- <button data-style="default"
                                class="style cursor-pointer {{ $resume->style == 'default' ? 'active' : '' }}"
                                style="background-color: #fff" title="{{ __('Default') }}"></button> --}}
                            @foreach ($resume->template->styleArray() as $style)
                                <button data-style="{{ $style['slug'] }}"
                                    class="style cursor-pointer {{ $resume->style == $style['slug'] ? 'active' : '' }}"
                                    style="background-color: {{ $style['color'] }}"
                                    title="{{ $style['title'] }}"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            <livewire:pager :resume="$resume" :router="'new-builder'" :page="$page" />
        </div>
        {{--  --}}


        <div class="new-builder-canvas w-full">
            <div
                class="new-builder template html w-[100vw] {{ $resume->template->folder }} {{ $resume->style }} py-10 !pt-11 !preview ">
                {{--  flex justify-center  --}}
                <div class="template body p-2 w-full overflow-x-auto mx-auto {{ $resume->style }}">
                    {!! $resume->template->builderHTML($page) !!}
                </div>
            </div>
        </div>
    </div>
    <script>
        window.resumeEditorPage = {{ $page }};
        window.newBuilderWidgets = {};
        @foreach ($widgets as $key => $widget)
            window.newBuilderWidgets['{{ $key }}'] = {};
            @foreach ($widget as $blockKey => $blockHTML)
                window.newBuilderWidgets['{{ $key }}']['{{ $blockKey }}'] = `{!! $blockHTML !!}`;
            @endforeach
        @endforeach
    </script>
    <div class="block-settings-popup">
        <div class="block-settings-popup-overlay"></div>
        <div class="block-settings-popup-content"></div>
    </div>
    <div class="block-popup">
        <div class="block-popup-overlay"></div>
        <div class="block-popup-content pt-5 pb-10 px-10">
            <h2 class="font-semibold text-lg md:text-2xl pr-10">{{ __('Add widget') }}</h2>
            <div>
                <h3 class="font-semibold text-gray-900 text-md mt-5 mb-1">{{ __('Profile informations') }}</h3>
                <div class="grid md:grid-cols-3 gap-2 mt-3">
                    @foreach (App\Models\Widget::where('active', true)->get() as $widget)
                        @if (!$widget->multiple && !in_array($widget->name, config('site.typography_widgets', [])))
                            <div name="{{ $widget->name }}"
                                class="border rounded-lg p-2 border-slate-200 flex gap-4 items-center cursor-pointer add-new-block">
                                <span class="text-white bg-black rounded p-1">
                                    <x-dynamic-component component="icons.{{ $widget->name }}" />
                                </span>
                                <span class="text-sm font-semibold">{{ __($widget->title) }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>

                <h3 class="font-semibold text-gray-900 text-md mt-5 mb-1">{{ __('Additional informations') }}</h3>
                <div class="grid md:grid-cols-3 gap-2 mt-3">
                    @foreach (App\Models\Widget::all() as $widget)
                        @if ($widget->multiple)
                            <div name="{{ $widget->name }}"
                                class="border rounded-lg p-2 border-slate-200 flex gap-4 items-center cursor-pointer add-new-block">
                                <span class="text-white bg-black rounded p-1">
                                    <x-dynamic-component component="icons.{{ $widget->name }}" />
                                </span>
                                <span class="text-sm font-semibold">{{ __($widget->title) }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
                <h3 class="font-semibold text-gray-900 text-md mt-5 mb-1">{{ __('Text') }}</h3>
                <div class="grid md:grid-cols-3 gap-2 mt-3">
                    @foreach (App\Models\Widget::all() as $widget)
                        @if (!$widget->multiple && in_array($widget->name, config('site.typography_widgets', [])))
                            <div name="{{ $widget->name }}"
                                class="border rounded-lg p-2 border-slate-200 flex gap-4 items-center cursor-pointer add-new-block">
                                <span class="text-white bg-black rounded p-1">
                                    <x-dynamic-component component="icons.{{ $widget->name }}" />
                                </span>
                                <span class="text-sm font-semibold">{{ __($widget->title) }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    {{-- 
    
    Error
    
    
    
    
    
     --}}
    <script>
        const downloadButtonTranslations = {
            error: `{!! __('Server error!') !!}`,
            work_in_background: `{!! __(
                "The PDF is taking a little longer than usual to generate. We're still working on it in the background! You don't need to stay on this page — we'll send an email to your inbox as soon as it's ready for download.",
            ) !!}`
        }
    </script>
    @vite(['resources/css/new-builder.scss', 'resources/js/new-builder.js', 'resources/js/resume-downloader.js'])

</x-builder-layout>
