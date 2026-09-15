<div class="">
    <form class="grid md:grid-cols-2 gap-4 mt-4" wire:submit="submit">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="cursor-pointer" for="fullname">{{ __('Fullname') }}</label>
                <input type="text" placeholder="{{ __('Fullname') }}" autocomplete="off" id="fullname" wire:model="fullname">
                @error('fullname')
                    <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="cursor-pointer" for="job_title">{{ __('Job title') }}</label>
                <input type="text" placeholder="{{ __('Job title') }}" autocomplete="off" id="job_title" wire:model="job_title">
                @error('job_title')
                    <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="cursor-pointer" for="slogan">{{ __('Slogan') }}</label>
                <input type="text" placeholder="{{ __('Slogan') }}" autocomplete="off" id="slogan" wire:model="slogan">
                @error('slogan')
                    <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="cursor-pointer" for="phone">{{ __('Phone') }}</label>
                <input type="text" placeholder="{{ __('Phone') }}" autocomplete="off" id="phone" wire:model="phone">
                @error('phone')
                    <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="cursor-pointer" for="email">{{ __('E-mail') }}</label>
                <input type="text" placeholder="{{ __('E-mail') }}" autocomplete="off" id="email" wire:model="email">
                @error('email')
                    <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="cursor-pointer" for="web">{{ __('Website') }}</label>
                <input type="text" placeholder="{{ __('Website') }}" autocomplete="off" id="web" wire:model="web">
                @error('web')
                    <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="md:col-span-2">
                <label class="cursor-pointer" for="address">{{ __('Address') }}</label>
                <input type="text" placeholder="{{ __('Address') }}" autocomplete="off" id="address" wire:model="address">
                @error('address')
                    <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="md:col-span-2">
                <label class="cursor-pointer" for="gender">{{ __('Gender') }}</label>
                <select name="gender" id="gender" wire:model="gender">
                    <option value="">{{ __('Gender') }}</option>
                    <option value="male">{{ __('Male') }}</option>
                    <option value="female">{{ __('Female') }}</option>
                </select>
                @error('gender')
                    <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="grid">
            <div wire:ignore>
                <label class="cursor-pointer" for="about">{{ __('About me') }}</label>
                <textarea
                x-data
                x-ref="tinymceEditor"
                x-init="hugerte.init({
                    selector: '#about-editor',
                    skin_url: 'default',
                     plugins: 'advlist lists link',
                    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist|link|removeformat',
                    menubar: false,
                    content_css: 'default',
                    setup: function (editor) {
                    console.log('editor',editor);
                        editor.on('init', function () {
                            editor.setContent(@this.get('about') || ''); // Set initial content from Livewire
                        });
                        editor.on('change', function () {
                            @this.set('about', editor.getContent()); // Update Livewire property on change
                        });
                        editor.on('blur', function () {
                            @this.set('about', editor.getContent()); // Ensure content is set on blur
                        });
                    }
                });"
                placeholder="{{ __('About me') }}" rows="5" id="about-editor"></textarea>
            </div>
            <div class="relative has-limit" limit="2000">
                <textarea placeholder="{{ __('About me') }}" rows="5" id="about" wire:model="about" class="hidden"></textarea>
                <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->about) }}</span>/2000</span></div>
                @error('about')
                    <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>

                <label class="cursor-pointer required" for="profile_image">{{ __('Profile image') }}</label>
                <div
                    class="border-4 border-dashed rounded-xl pt-6 pb-4 flex justify-center flex-col items-center gap-4 w-12/12 mx-auto border-gray-100">

                    @if ($this->profile_image)
                    @if(in_array($this->profile_image->getMimeType(), ['image/png', 'image/jpg','image/jpeg']))
                        <img loading="lazy" class="w-20 h-20 object-cover rounded-full"
                            src="{{ $this->profile_image->temporaryUrl() }}" alt="{{ __('profile image') }}">
                            @endif
                    @elseif($profile?->profile_image)
                        <img loading="lazy" class="w-20 h-20 object-cover rounded-full" src="{{ $profile?->image_url() }}"
                            alt="{{ __('profile image') }}">
                    @endif
                    <label for="profile_image"
                        class="button bg-violet-100 border-none shadow-none text-violet-900 cursor-pointer"><x-icons.upload
                            class="text-violet-600" />{{ __('Pick an image...') }}</label>

                    <input class="hidden" type="file" id="profile_image" name="profile_image"
                        wire:model="profile_image">

                </div>
                @error('profile_image')
                    <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>


        <div class="my-2 flex justify-end md:col-span-2">
            <button class="button">{{ __('Save') }}</button>
        </div>

    </form>
    @script
        <script>
            window.initInputLimitCounter();
        </script>
    @endscript
</div>
