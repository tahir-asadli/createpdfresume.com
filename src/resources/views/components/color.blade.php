@props(['value', 'colors' => ''])
<div class="relative" x-data="{ open: false, color: '{{ $value }}' }" x-modelable="color" @click.outside="open = false">
    <input class="relative z-[1]" type="text" name="color" :style="{ 'color': '{{ $value }}' }"
        placeholder="{{ __('Color') }}" :value="color" {{ $attributes->whereStartsWith('wire:model') }}>
    <button type="button" x-on:click="open = !open"
        class="absolute top-0 right-0 cursor-pointer w-11 h-11 flex justify-center items-center z-[2] focus:ring-0"><x-icons.palette /></button>
    <div x-show="open"
        class="absolute flex gap-2 flex-wrap p-2 pt-3 top-14 right-0 shadow-cs2 rounded-bl-md rounded-br-md bg-white max-w-36">
        <button type="button" :class="{ 'selected': '' == '{{ $value }}' }"
            x-on:click="color = '';$wire.set('{{ $attributes->wire('model')->value() }}', '')"
            class="color-button w-6 h-6 rounded-full none"></button>
        @foreach (explode(',', $colors) as $color)
            <button type="button" :class="{ 'selected': '{{ $color }}' == '{{ $value }}' }"
                x-on:click="color = '{{ strtoupper(trim($color)) }}';$wire.set('{{ $attributes->wire('model')->value() }}', '{{ strtoupper(trim($color)) }}')"
                style="background-color: {{ strtoupper(trim($color)) }}"
                class="color-button w-6 h-6 rounded-full"></button>
        @endforeach
    </div>
</div>

{{-- 
box-shadow: 0 1px 6px -1px rgba(0, 0, 0, 0.2);
  top: 50px !important;
  width: 140px !important;
  right: 5px !important;
  position: relative !important;
  position: absolute !important;
  background: white !important; --}}
