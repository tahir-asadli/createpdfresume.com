<div class="pt-5 pb-10 px-10">

    <div class="relative">
        <h2 class="font-semibold text-lg md:text-2xl pr-10">{{ __('Edit hobby') }}</h2>
        <button wire:click="$dispatch('closeModal')"
            class="button absolute top-0 right-0 p-0 w-10 h-[40px] text-3xl font-normal flex justify-center items-baseline leading-[35px] min-h-[40px]">&times;</button>
    </div>
    <form class="flex flex-col gap-4 mt-4" wire:submit="submit">
        <div class="relative has-limit" limit="60">
            <label class="required cursor-pointer" for="hobby">{{ __('Hobby name') }}</label>
            <input id="hobby" type="text" placeholder="{{ __('Hobby name') }}" autocomplete="off" autofocus wire:model="form.name">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->name) }}</span>/60</span></div>
            @error('form.name')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="required cursor-pointer">{{ __('Icon') }}</label>
            <div x-data="{ selected: '{{ $form->icon }}' }">
                <div class="flex flex-wrap gap-4 hobby-icons">
                    @foreach (config('site.hobbies') as $name => $hobby)
                        <button x-on:click="selected = '{{ $hobby }}';$wire.form.icon = '{{ $hobby }}'"
                            :class="{ 'selected': selected == '{{ $hobby }}' }"
                            class="flex flex-col-reverse justify-center items-center border border-slate-200 gap-2 p-2 text-xs shrink-0 w-20 hobby-icon"
                            type="button">
                            <span>{{ $name }}</span>
                            <x-dynamic-component component="hobbies.{{ $hobby }}" />
                        </button>
                    @endforeach

                </div>
            </div>
            @error('form.icon')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>{{ __('Enabled') }}</label>
            <x-switch id="active" wire:model.live="form.active" />
            @error('form.active')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="my-2 flex justify-end">
            <button class="button">{{ __('Save') }}</button>
        </div>
    </form>
    @script
        <script>
            window.initInputLimitCounter();
        </script>
    @endscript
</div>
