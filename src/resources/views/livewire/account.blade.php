<div>
    <div>
        <h2 class="mb-3 text-lg text-gray-600">{{ __('Account') }}</h2>
        <div class=" mt-3">
            @if ($this->form->success == true)
                <div x-data="{}" class="text-green-700 font-medium italic" x-init="setTimeout(() => {
                    $wire.hide()
                }, 1000)">
                    {{ __('Changes saved!') }}
                </div>
            @endif
            <form class="grid md:grid-cols-2 xl:grid-cols-4 gap-4 mt-" wire:submit="submit" x-data="{ showPassword: false }">
                <div>
                    <label for="title">{{ __('Fullname') }}</label>
                    <input type="text" placeholder="{{ __('Fullname') }}" autocomplete="off"
                        wire:model="form.fullname">
                    @error('form.fullname')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="title">{{ __('Password') }}</label>
                    <div class="relative">
                        <div class="absolute right-0 top-0 flex justify-center items-center cursor-pointer opacity-50 w-[45px] h-[45px]"
                            :class="{ '!opacity-100': showPassword }">
                            <x-icons.eye2 x-on:click="showPassword = !showPassword" />
                        </div>
                        <input x-bind:type="showPassword ? 'text' : 'password'" placeholder="{{ __('Password') }}"
                            wire:model="form.password">
                    </div>
                    @error('form.password')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="title" class="flex items-center gap-4">{{ __('E-mail') }} - <small
                            class="text-sm font-normal text-rose-800">{{ __('Cannot be changed') }}</small></label>
                    <input type="text" class="disabled:opacity-55" placeholder="{{ __('E-mail') }}"
                        wire:model="form.email" disabled>
                    @error('form.email')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="">
                    <label class="cursor-pointer" for="language">{{ __('Language') }}</label>
                    <select name="language" id="language" wire:model="form.language">
                        <option value="">{{ __('Language') }}</option>
                        <option value="en">English</option>
                        <option value="az">Azerbaijani</option>
                        <option value="tr">Turkish</option>
                        <option value="es">Spanish</option>
                        <option value="ru">Russian</option>
                        {{-- <option value="es">Spanish</option>
                        <option value="ru">Russian</option> --}}
                    </select>
                    @error('language')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="my-2 flex justify-end md:col-span-2 xl:col-span-4">
                    <button class="button">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
        <div class="my-3 flex flex-col md:flex-row justify-between">
            <div>
                <button wire:click="download()" x-bind:disabled="{{ $this->canDownload() ? 'false' : 'true' }}"
                    class="button color-button  disabled:cursor-not-allowed disabled:opacity-40"
                    disab><x-icons.download />{{ __('Download all my resumes') }}</button>
                <div class="text-sm py-1">{!! __('You can do this once a day! This will only download the <b>generated</b> PDFs') !!}</div>
            </div>
            <div class="flex flex-col">
                <button wire:click="deleteAccount()" wire:confirm="{{ __('Are you sure?') }}"
                    class=" text-center justify-center button focus:ring-red-500/20 bg-red-100 border-none shadow-none text-red-900">{{ __('Delete my account') }}</button>
                <div class="text-sm py-1 text-red-700">{{ __('This action is irreversible!') }}</div>
            </div>
        </div>
    </div>
</div>
