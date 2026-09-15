<div class="pt-5 pb-10 px-10" x-data="{ selected: '' }">
    <div class="relative">
        <h2 class="font-semibold text-lg md:text-2xl pr-10">{{ __('Add widget to :section section', ['section' => sectionName($section)]) }}</h2>
        <button wire:click="$dispatch('closeModal')"
            class="button absolute top-0 bottom-0 right-0 m-auto p-0 w-10 h-[40px] text-3xl font-normal flex justify-center items-baseline leading-[35px] min-h-[40px]">&times;</button>
    </div>
    <h3 class="font-semibold text-gray-900 text-md mt-5 mb-1">{{ __('Profile informations') }}</h3>
    <div class="grid md:grid-cols-3 gap-2 mt-3">
        @foreach (App\Models\Widget::all() as $widget)
            @if (!$widget->multiple && !in_array($widget->name, config('site.typography_widgets', [])))
                <div wire:click="add('{{ $widget->id }}')" wire:key="{{ $widget->id }}"
                    x-on:click="selected='{{ $widget->name }}';"
                    class="border rounded-lg p-2 border-slate-200 flex gap-4 items-center cursor-pointer"
                    :class="{ 'bg-violet-50 border-violet-200': '{{ $widget->name }}' == selected }">
                    <span class="text-white bg-black rounded p-1">
                        <x-dynamic-component component="icons.{{ $widget->name }}" />
                    </span>
                    <span class="text-sm font-semibold">{{__($widget->title) }}</span>
                </div>
            @endif
        @endforeach
    </div>
    <h3 class="font-semibold text-gray-900 text-md mt-5 mb-1">{{ __('Additional informations') }}</h3>
    <div class="grid md:grid-cols-3 gap-2 mt-3">
        @foreach (App\Models\Widget::all() as $widget)
            @if ($widget->multiple)
                <div wire:click="add('{{ $widget->id }}')" wire:key="{{ $widget->id }}"
                    x-on:click="selected='{{ $widget->name }}';"
                    class="border rounded-lg p-2 border-slate-200 flex gap-4 items-center cursor-pointer"
                    :class="{ 'bg-violet-50 border-violet-200': '{{ $widget->name }}' == selected }">
                    <span class="text-white bg-black rounded p-1">
                        <x-dynamic-component component="icons.{{ $widget->name }}" />
                    </span>
                    <span class="text-sm font-semibold">{{__($widget->title) }}</span>
                </div>
            @endif
        @endforeach
    </div>
    <h3 class="font-semibold text-gray-900 text-md mt-5 mb-1">{{ __('Text') }}</h3>
    <div class="grid md:grid-cols-3 gap-2 mt-3">
        @foreach (App\Models\Widget::all() as $widget)
            @if (!$widget->multiple && in_array($widget->name, config('site.typography_widgets', [])))
                <div wire:click="add('{{ $widget->id }}')" wire:key="{{ $widget->id }}"
                    x-on:click="selected='{{ $widget->name }}';"
                    class="border rounded-lg p-2 border-slate-200 flex gap-4 items-center cursor-pointer"
                    :class="{ 'bg-violet-50 border-violet-200': '{{ $widget->name }}' == selected }">
                    <span class="text-white bg-black rounded p-1">
                        <x-dynamic-component component="icons.{{ $widget->name }}" />
                    </span>
                    <span class="text-sm font-semibold">{{__($widget->title) }}</span>
                </div>
            @endif
        @endforeach
    </div>
</div>
