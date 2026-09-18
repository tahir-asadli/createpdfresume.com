<div class="pt-5 pb-10 px-10">

    <div class="relative">
        <h2 class="font-semibold text-lg md:text-2xl pr-10">{{ __('Edit certificate') }}</h2>
        <button wire:click="$dispatch('closeModal')"
            class="button absolute top-0 right-0 p-0 w-10 h-[40px] text-3xl font-normal flex justify-center items-baseline leading-[35px] min-h-[40px]">&times;</button>
    </div>
    <form class="flex flex-col gap-4 mt-4" wire:submit="submit" x-init="new AirDatepicker('#date', {
        locale: localeGlobal,
        position: 'top right',
        selectedDates: {{ $certification->date ? '["' . $certification->date->format('d-m-Y') . '"]' : '[]' }},
        onSelect({ formattedDate }) {
            $wire.form.date = formattedDate ? formattedDate : null;
        }
    });">
        <div class="relative has-limit" limit="100">
            <label class="required cursor-pointer" for="certification">{{ __('Certificate name') }}</label>
            <input id="certification" type="text" placeholder="{{ __('Certificate name') }}" autocomplete="off" autofocus wire:model="form.name">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->name) }}</span>/100</span></div>
            @error('form.name')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="relative has-limit" limit="100">
            <label class="required cursor-pointer" for="organization">{{ __('Organization') }}</label>
            <input id="organization" type="text" placeholder="{{ __('Organization') }}" autocomplete="off" autofocus wire:model="form.organization">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->organization) }}</span>/100</span></div>
            @error('form.organization')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="cursor-pointer" for="date">{{ __('Date') }}</label>
            <input id="date" type="text" placeholder="01-01-{{ date('Y') }}" autocomplete="off" autofocus
                wire:model="form.date">
            @error('form.date')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div wire:ignore>
            <label class="cursor-pointer" for="description">{{ __('Description') }}</label>
                <textarea
                x-data
                x-ref="tinymceEditor"
                x-init="hugerte.get('description-editor')?.destroy();hugerte.init({
                    selector: '#description-editor',
                    skin_url: 'default',
                     plugins: 'advlist lists link',
                    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist|link|removeformat',
                    menubar: false,
                    content_css: 'default',
                    setup: function (editor) {
                    console.log('editor',editor);
                        editor.on('init', function () {
                            editor.setContent(@this.get('form.description') || '');
                        });
                        editor.on('change', function () {
                            @this.set('form.description', editor.getContent());
                        });
                        editor.on('blur', function () {
                            @this.set('form.description', editor.getContent());
                        });
                    }
                });"
                placeholder="{{ __('Description') }}" rows="5" id="description-editor"></textarea>
        </div>
        <div class="relative has-limit" limit="1000">
            <textarea rows="10" id="description" placeholder="{{ __('Description') }}" autofocus wire:model="form.description" class="hidden"></textarea>
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->description) }}</span>/1000</span></div>
            @error('form.description')
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
