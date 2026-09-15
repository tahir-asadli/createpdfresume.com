<div class="pt-5 pb-10 px-10">
    <div class="relative">
        <h2 class="font-semibold text-lg md:text-2xl pr-10">{{ __('Edit reference') }}</h2>
        <button wire:click="$dispatch('closeModal')"
            class="button absolute top-0 right-0 p-0 w-10 h-[40px] text-3xl font-normal flex justify-center items-baseline leading-[35px] min-h-[40px]">&times;</button>
    </div>
    <form class="flex flex-col gap-4 mt-4" wire:submit="submit">
        <div class="relative has-limit" limit="60">
            <label class="required cursor-pointer" for="reference">{{ __('Fullname') }}</label>
            <input id="reference" type="text" placeholder="{{ __('Fullname') }}" autofocus wire:model="form.name">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->name) }}</span>/60</span></div>
            @error('form.name')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="relative has-limit" limit="100">
            <label class="required cursor-pointer" for="company">{{ __('Company') }}</label>
            <input id="company" type="text" placeholder="{{ __('Company') }}" autofocus wire:model="form.company">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->company) }}</span>/100</span></div>
            @error('form.company')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="relative has-limit" limit="100">
            <label class="required cursor-pointer" for="position">{{ __('Position') }}</label>
            <input id="position" type="text" placeholder="{{ __('Position') }}" autofocus wire:model="form.position">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->position) }}</span>/100</span></div>
            @error('form.position')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="relative has-limit" limit="60">
            <label class="cursor-pointer" for="email">{{ __('E-mail') }}</label>
            <input id="email" type="email" placeholder="{{ __('E-mail') }}" autofocus wire:model="form.email">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->email) }}</span>/60</span></div>
            @error('form.email')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="relative has-limit" limit="30">
            <label class="cursor-pointer" for="phone">{{ __('Phone') }}</label>
            <input id="phone" type="text" placeholder="{{ __('Phone') }}" autofocus wire:model="form.phone">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->phone) }}</span>/30</span></div>
            @error('form.phone')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="cursor-pointer">{{ __('Enabled') }}</label>
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
