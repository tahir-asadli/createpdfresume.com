<div class="p-10 overflow-auto h-[100vh]">
    <div class="relative">
        <h2 class="font-semibold text-lg md:text-2xl pr-10">{{ __('Add new workplace') }}</h2>
        <button wire:click="$dispatch('closeModal')"
            class="button absolute top-0 right-0 p-0 w-10 h-[40px] text-3xl font-normal flex justify-center items-baseline leading-[35px] min-h-[40px]">&times;</button>
    </div>
    <form class="flex flex-col gap-4 mt-4" wire:submit="submit" x-init="new AirDatepicker('#startDate', {
        locale: localeGlobal,
        position: 'top right',
        onSelect({ formattedDate }) {
            $wire.form.startDate = formattedDate ? formattedDate : null;
        }
    });
    new AirDatepicker('#endDate', {
        locale: localeGlobal,
        position: 'top right',
        onSelect({ formattedDate }) {
            $wire.form.endDate = formattedDate ? formattedDate : null;
        }
    })">
        <div class="relative has-limit" limit="120">
            <label class="cursor-pointer required" for="company">{{ __('Company') }}</label>
            <input type="text" placeholder="{{ __('Company') }}" autocomplete="off" id="company" autofocus wire:model="form.company">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->company) }}</span>/120</span></div>
            @error('form.company')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="relative has-limit" limit="120">
            <label class="cursor-pointer required" for="position">{{ __('Position') }}</label>
            <input type="text" placeholder="{{ __('Position') }}" autocomplete="off" id="position" wire:model="form.position">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->position) }}</span>/120</span></div>
            @error('form.position')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div wire:ignore>
                <label class="cursor-pointer" for="about">{{ __('About company') }}</label>
                <textarea
                x-data
                x-ref="tinymceEditor"
                x-init="hugerte.get('about-editor')?.destroy();hugerte.init({
                    selector: '#about-editor',
                    skin_url: 'default',
                     plugins: 'advlist lists link',
                    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist|link|removeformat',
                    menubar: false,
                    content_css: 'default',
                    setup: function (editor) {
                    console.log('editor',editor);
                        editor.on('init', function () {
                            editor.setContent(@this.get('form.about') || '');
                        });
                        editor.on('change', function () {
                            @this.set('form.about', editor.getContent());
                        });
                        editor.on('blur', function () {
                            @this.set('form.about', editor.getContent());
                        });
                    }
                });"
                placeholder="{{ __('About company') }}" rows="5" id="about-editor"></textarea>
        </div>
        <div class="relative has-limit" limit="1000">
            <textarea rows="10" placeholder="{{ __('About company') }}" id="about" wire:model="form.about" class="hidden"></textarea>
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->about) }}</span>/1000</span></div>
            @error('form.about')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="relative has-limit" limit="120">
            <label class="cursor-pointer " for="location">{{ __('Address') }}</label>
            <input type="text" placeholder="{{ __('Address') }}" autocomplete="off" id="location" wire:model="form.location">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->location) }}</span>/120</span></div>
            @error('form.location')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="cursor-pointer" for="startDate">{{ __('Start date') }}</label>
                <input placeholder="01-01-{{ date('Y') }}" type="text" id="startDate"
                    wire:model="form.startDate">
                @error('form.startDate')
                    <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="cursor-pointer" for="endDate">{{ __('End date') }}</label>
                <input type="text" id="endDate" wire:model="form.endDate">
                @error('form.endDate')
                    <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                @enderror
            </div>
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
