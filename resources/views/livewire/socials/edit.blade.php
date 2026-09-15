<div class="pt-5 pb-10 px-10">

    <div class="relative">
        <h2 class="font-semibold text-lg md:text-2xl pr-10">{{ __('Edit social site') }}</h2>
        <button wire:click="$dispatch('closeModal')"
            class="button absolute top-0 right-0 p-0 w-10 h-[40px] text-3xl font-normal flex justify-center items-baseline leading-[35px] min-h-[40px]">&times;</button>
    </div>
    <form class="flex flex-col gap-4 mt-4" wire:submit="submit">
        <div class="relative has-limit" limit="60">
            <label for="name" class="cursor-pointer">{{ __('Site name') }}</label>
            <input type="text" placeholder="{{ __('Site name') }}" autocomplete="off" autofocus wire:model="form.name">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->name) }}</span>/60</span></div>
            @error('form.name')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="relative has-limit" limit="100">
            <label for="url" class="cursor-pointer">{{ __('URL') }}</label>
            <input type="text" placeholder="URL" autocomplete="off" autofocus wire:model="form.url">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->url) }}</span>/100</span></div>
            @error('form.url')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="relative has-limit" limit="60">
            <label for="handle" class="cursor-pointer">{{ __('Username') }}</label>
            <input type="text" placeholder="{{ __('Username') }}" autocomplete="off" autofocus wire:model="form.handle">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->handle) }}</span>/60</span></div>
            @error('form.handle')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <div x-data="{ selected: '{{ $form->site }}' }">
                <div class="flex flex-wrap gap-4 social-icons">
                    @foreach (config('site.socials') as $social => $color)
                        <button
                            x-on:click="selected = '{{ strtolower($social) }}';$wire.form.site = '{{ strtolower($social) }}'"
                            :class="{ 'selected': selected == '{{ strtolower($social) }}' }"
                            class="flex flex-col-reverse justify-center items-center border border-slate-200 gap-2 p-2 text-xs shrink-0 w-16 social-{{ strtolower($social) }}"
                            type="button">
                            <span>{{ $social }}</span>
                            <x-dynamic-component component="socials.{{ strtolower($social) }}" />
                        </button>
                    @endforeach

                </div>
            </div>
            @error('form.site')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>{{ __('Enabled') }}</label>
            <x-switch id="active" wire:model.live="form.active" />
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
