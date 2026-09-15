<div class="pt-5 pb-10 px-10">
    <div class="relative">
        <h2 class="font-semibold text-lg md:text-2xl pr-10">{{ __('Edit resume') }}</h2>
        <button wire:click="$dispatch('closeModal')"
            class="button absolute top-0 right-0 p-0 w-10 h-[40px] text-3xl font-normal flex justify-center items-baseline leading-[35px] min-h-[40px]">&times;</button>
    </div>

    <form class="flex flex-col gap-4 mt-4" wire:submit="submit">
        <div>
            <label class="required" for="project">{{ __('Resume name') }}</label>
            <input id="project" type="text" placeholder="{{ __('Resume name') }}" autocomplete="off" autofocus wire:model="form.name">
            @error('form.name')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="my-2 flex justify-end">
            <button class="button">{{ __('Save') }}</button>
        </div>
    </form>
</div>
