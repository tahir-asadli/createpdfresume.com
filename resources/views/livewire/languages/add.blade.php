<div class="pt-5 pb-10 px-10">

    <div class="relative">
        <h2 class="font-semibold text-lg md:text-2xl pr-10">{{ __('Add a new language') }}</h2>
        <button wire:click="$dispatch('closeModal')"
            class="button absolute top-0 right-0 p-0 w-10 h-[40px] text-3xl font-normal flex justify-center items-baseline leading-[35px] min-h-[40px]">&times;</button>
    </div>
    <form class="flex flex-col gap-4 mt-4" wire:submit="submit">
        <div class="relative has-limit" limit="60">
            <label for="language" class="required">{{ __('Language') }}</label>
            <input type="text" placeholder="{{ __('Language') }}" autocomplete="off" autofocus wire:model="form.name">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->name) }}</span>/60</span></div>
            @error('form.name')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="default-range" class="required">{{ __('Percentage') }}</label>
            <select wire:model="form.level">
                @foreach (range(5, 100, 5) as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
            @error('form.level')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>{{ __('Enabled') }}</label>
            <x-switch id="active" wire:model.live="form.active" />
        </div>

        <div class="my-2 flex justify-end">
            <button class="button">{{ __('Add') }}</button>
        </div>
    </form>
    @script
        <script>
            window.initInputLimitCounter();
        </script>
    @endscript
</div>
