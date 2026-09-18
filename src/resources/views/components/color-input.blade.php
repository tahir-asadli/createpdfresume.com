@props(['value', 'colors' => ''])
<div class="relative" x-data="{ open: false, color: '{{ $value }}' }" x-modelable="color" @click.outside="open = false">
    <input class="relative z-[1]" type="text" name="color" :style="{ 'color': color }" x-ref="colorInputRef"
        placeholder="{{ __('Color') }}" :value="color">
    <button type="button" x-on:click="open = !open"
        class="absolute top-0 right-0 cursor-pointer w-11 h-11 flex justify-center items-center z-[2] focus:ring-0"><x-icons.palette /></button>
    <div x-show="open"
        class="absolute flex gap-2 flex-wrap p-2 pt-3 top-14 right-0 shadow-cs2 rounded-bl-md rounded-br-md bg-white max-w-36">
        <button type="button" :class="{ 'selected': '' == color }"
            x-on:click="color = '';$refs.colorInputRef.value=color;$refs.colorInputRef.dispatchEvent(new Event('change'))"
            class="color-button w-6 h-6 rounded-full none"></button>
        @foreach (explode(',', $colors) as $color)
            <button type="button" :class="{ 'selected': color == '{{ $color }}' }"
                x-on:click="color = '{{ strtoupper(trim($color)) }}';$refs.colorInputRef.value=color;$refs.colorInputRef.dispatchEvent(new Event('change'))"
                style="background-color: {{ strtoupper(trim($color)) }}"
                class="color-button w-6 h-6 rounded-full"></button>
        @endforeach
    </div>
</div>
