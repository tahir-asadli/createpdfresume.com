<div class="p-10 overflow-auto h-[100vh]">

    <div class="relative">
        <h2 class="font-semibold text-lg md:text-2xl pr-10">{{ __('Add new place of education') }}</h2>
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
            <label class="cursor-pointer required" for="school">{{ __('Place of education') }}</label>
            <input type="text" placeholder="{{ __('Place of education') }}" id="school" autocomplete="off" autofocus wire:model="form.school">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->school) }}</span>/120</span></div>
            @error('form.school')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="relative has-limit" limit="120">
            <label class="cursor-pointer required" for="degree">{{ __('Degree') }}</label>
            <input type="text" placeholder="{{ __('Degree') }}" autocomplete="off" id="degree" wire:model="form.degree">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->degree) }}</span>/120</span></div>
            @error('form.degree')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="relative has-limit" limit="120">
            <label class="cursor-pointer" for="field">{{ __('Field') }}</label>
            <input type="text" placeholder="{{ __('Field') }}" autocomplete="off" id="field" wire:model="form.field">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->field) }}</span>/120</span></div>
            @error('form.field')
                <p class="text-rose-600 italic pt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="relative has-limit" limit="120">
            <label class="cursor-pointer" for="location">{{ __('Address') }}</label>
            <input type="text" placeholder="{{ __('Address') }}" autocomplete="off" id="location" wire:model="form.location">
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->location) }}</span>/120</span></div>
            @error('form.location')
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
            <textarea rows="10" placeholder="{{ __('Description') }}" id="description" wire:model="form.description" class="hidden"></textarea>
            <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                        class="chars">{{ mb_strlen($this->form->description) }}</span>/1000</span></div>
            @error('form.description')
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
                <input placeholder="01-01-{{ date('Y') }}"type="text" id="endDate" wire:model="form.endDate">
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
