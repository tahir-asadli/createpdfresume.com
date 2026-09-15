<div>
    @if (count($resume->template->styleArray()) > 0)
        <div class="text-center w-full py-4 px-2 bg-white font-medium shadow-sm flex gap-2 justify-center">
            <button wire:click="updateStyle('default')" class="style {{ $resume->style == 'default' ? 'active' : '' }}"
                style="background-color: #fff" title="{{ __('Default') }}"></button>
            @foreach ($resume->template->styleArray() as $style)
                <button wire:click="updateStyle('{{ $style['slug'] }}')"
                    class="style {{ $resume->style == $style['slug'] ? 'active' : '' }}"
                    style="background-color: {{ $style['color'] }}" title="{{ $style['title'] }}"></button>
            @endforeach
        </div>
        <div class="text-center w-full py-4 px-2 bg-white font-medium shadow-sm">{{ $resume->name }},
            {{ __('Style') }}: {{ $resume->styleName() }}</div>
    @else
        <div class="text-center w-full py-4 px-2 bg-white font-medium shadow-sm">{{ $resume->name }}</div>
    @endif
</div>
