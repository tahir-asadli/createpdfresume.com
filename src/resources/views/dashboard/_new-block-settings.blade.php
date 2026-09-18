<div class="pt-5 pb-10 px-10 flex-grow flex flex-col">
    <div class="relative">
        <h2 class="font-semibold text-lg md:text-2xl pr-10">{{ __('Block settings') }}</h2>
        <button
            class="close-block-settings button absolute top-0 bottom-0 right-0 m-auto p-0 w-10 h-[40px] text-3xl font-normal flex justify-center items-baseline leading-[35px] min-h-[40px]">&times;</button>
    </div>
    <h3 class="font-semibold text-lg  my-1 text-gray-600">{{ __('Block name:') }} {{ $block->widget->title }}</h3>
    <div class="mt-3">
        <form class="flex flex-col gap-4 mt-4" action="{{ route('update-block-settings') }}" method="post">
            @if (!in_array($block->widget->name, ['space', 'ul', 'ol', 'image', 'richtext']))
                <div>
                    <label for="title">{{ __('Title') }}</label>
                    <input type="text" placeholder="{{ __('Title') }}" autocomplete="off" autocomplete="off"
                        autofocus name="title">
                    {{-- @error('form.title')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror --}}
                </div>
            @endif
            @if (in_array($block->widget->name, ['ul', 'ol']))
                <div>
                    <label for="title">{{ __('Content') }}</label>
                    <textarea placeholder="{{ __('Content') }}" rows="10" autofocus name="title"></textarea>
                    {{-- @error('form.title')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror --}}
                </div>
            @endif
            @if (in_array($block->widget->name, ['richtext']))
                <div class="relative has-limit" limit="1000">
                    <div wire:ignore>
                        <label for="title">{{ __('Rich Text') }}</label>
                        <textarea x-data x-ref="tinymceEditor" x-init="hugerte.get('title-editor')?.destroy();
                        hugerte.init({
                            selector: '#title-editor',
                            skin_url: 'default',
                            plugins: 'advlist lists link',
                            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist|link|removeformat',
                            menubar: false,
                            content_css: 'default',
                            setup: function(editor) {
                                console.log('editor', editor);
                                editor.on('init', function() {
                                    editor.setContent(@this.get('form.title') || '');
                                });
                                editor.on('change', function() {
                                    @this.set('form.title', editor.getContent());
                                });
                                editor.on('blur', function() {
                                    @this.set('form.title', editor.getContent());
                                });
                            }
                        });" placeholder="{{ __('Rich Text') }}" rows="5"
                            id="title-editor"></textarea>
                    </div>
                    <textarea placeholder="{{ __('Rich Text') }}" rows="10" autofocus wire:model="form.title" class="hidden"></textarea>
                    <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                                class="chars">{{ 'mb_strlen($this->form->title)' }}</span>/1000</span></div>
                    @error('form.title')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror
                </div>
                @script
                    <script>
                        window.initInputLimitCounter();
                    </script>
                @endscript
            @endif

            @if ($block->widget->name == 'space')
                <div>
                    <label for="height">{{ __('Height') }} (px)</label>
                    <input type="number" placeholder="Height: 0 - 100 px" min="1" max="100"
                        wire:model="form.height">
                    @error('form.height')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif
            @if ($block->widget->multiple)
                <div>
                    <label for="from">{{ __('Start') }}</label>
                    <input type="number" min="1" max="100" placeholder="{{ __('Start') }}" autofocus
                        wire:model="form.from">
                    @error('form.from')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="to">{{ __('End') }}</label>
                    <input type="number" min="1" max="100" placeholder="{{ __('End') }}" autofocus
                        wire:model="form.to">
                    @error('form.to')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror
                </div>
            @else
                @if (in_array($block->widget->name, [
                        'fullname',
                        'job_title',
                        'slogan',
                        'about',
                        'contact',
                        'phone',
                        'email',
                        'web',
                        'address',
                        'h1',
                        'h2',
                        'h3',
                        'h4',
                        'h5',
                        'h6',
                        'p',
                        'ul',
                        'ol',
                        'richtext',
                    ]))
                    <div class="flex gap-2 flex-wrap">
                        <div class="button-group">
                            <button type="button" _wire:click="toggleBold()"
                                :class="{ '!shadow-cs-inset': {{ '$form->bold' }} }"
                                class="button"><x-icons.bold /></button>
                            <button type="button" _wire:click="toggleItalic()"
                                :class="{ '!shadow-cs-inset': {{ '$form->italic' }} }"
                                class="button"><x-icons.italic /></button>
                            <button type="button" _wire:click="toggleUnderline()"
                                :class="{ '!shadow-cs-inset': {{ '$form->underline' }} }"
                                class="button"><x-icons.underline /></button>
                            <button type="button" _wire:click="toggleStrikethrough()"
                                :class="{ '!shadow-cs-inset': {{ '$form->strikethrough' }} }"
                                class="button"><x-icons.strikethrough /></button>
                            <button type="button" _wire:click="toggleUppercase()"
                                :class="{ '!shadow-cs-inset': {{ '$form->uppercase' }} }"
                                class="button"><x-icons.uppercase /></button>
                        </div>
                    </div>
                    <x-color value="{{ '$form->color' }}" colors="{{ $template->formattedColors() }}"
                        wire:model="form.color" />
                    @error('form.color')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror
                @endif
                @if ($block->widget->name == 'image')
                    <div class="flex gap-2 flex-wrap">
                        <div class="button-group">
                            <button type="button" _wire:click="toggleCircle()"
                                :class="{ '!shadow-cs-inset': {{ '$form->radius == 9999' }} }" class="button"><span
                                    class="circle"></span></button>
                            <button type="button" _wire:click="toggleRounded()"
                                :class="{ '!shadow-cs-inset': {{ '$form->radius == 5' }} }" class="button"><span
                                    class="rounded-rectangle"></span></button>
                            <button type="button" _wire:click="toggleRectangle()"
                                :class="{ '!shadow-cs-inset': {{ '$form->radius == 0' }} }" class="button"><span
                                    class="rectangle"></span></button>
                        </div>
                    </div>
                    @if ('$this->hasTransparentImage()')
                        <div class="flex gap-2 flex-wrap">
                            <div class="button-group">
                                <button type="button" _wire:click="toggleTransparent()"
                                    :class="{ '!shadow-cs-inset': {{ '$this->transparentEnabled()' }} }"
                                    class="button">Transparent</button>
                            </div>
                        </div>
                    @endif
                    @error('form.radius')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror
                    <div class="flex gap-2 flex-wrap">
                        <div class="button-group">
                            <button type="button" _wire:click="setFilter('')"
                                :class="{ '!shadow-cs-inset': {{ '$form->filter' == null }} }"
                                class="button">{{ __('No effect') }}</button>
                            <button type="button" _wire:click="setFilter('effect-1')"
                                :class="{ '!shadow-cs-inset': {{ '$form->filter' == 'effect-1' }} }"
                                class="button">{{ __('Grayscale') }}</button>
                            <button type="button" _wire:click="setFilter('effect-2')"
                                :class="{ '!shadow-cs-inset': {{ '$form->filter' == 'effect-2' }} }"
                                class="button">{{ __('Light') }}</button>
                            <button type="button" _wire:click="setFilter('effect-3')"
                                :class="{ '!shadow-cs-inset': {{ '$form->filter' == 'effect-3' }} }"
                                class="button">{{ __('Dark') }}</button>
                        </div>
                        <div class="button-group">
                            <button type="button" _wire:click="setFilter('effect-4')"
                                :class="{ '!shadow-cs-inset': {{ '$form->filter' == 'effect-4' }} }"
                                class="button">{{ __('Light red') }}</button>
                            <button type="button" _wire:click="setFilter('effect-5')"
                                :class="{ '!shadow-cs-inset': {{ '$form->filter' == 'effect-5' }} }"
                                class="button">{{ __('Light green') }}</button>
                            <button type="button" _wire:click="setFilter('effect-6')"
                                :class="{ '!shadow-cs-inset': {{ '$form->filter' == 'effect-6' }} }"
                                class="button">{{ __('Light blue') }}</button>
                        </div>
                        <div class="button-group">
                            <button type="button" _wire:click="setFilter('effect-7')"
                                :class="{ '!shadow-cs-inset': {{ '$form->filter' == 'effect-7' }} }"
                                class="button">{{ __('Dark red') }}</button>
                            <button type="button" _wire:click="setFilter('effect-8')"
                                :class="{ '!shadow-cs-inset': {{ '$form->filter' == 'effect-8' }} }"
                                class="button">{{ __('Dark green') }}</button>
                            <button type="button" _wire:click="setFilter('effect-9')"
                                :class="{ '!shadow-cs-inset': {{ '$form->filter' == 'effect-9' }} }"
                                class="button">{{ __('Dark blue') }}</button>
                        </div>
                    </div>
                    <label>{{ __('Blend mode') }}</label>
                    <div class="flex gap-2 flex-wrap flex-col">
                        @foreach ($blendModes as $blendModeGroup)
                            <div class="button-group">
                                @foreach ($blendModeGroup as $blendMode)
                                    <button type="button" _wire:click="setBlendMode('{{ $blendMode }}')"
                                        :class="{ '!shadow-cs-inset': {{ '$form->blendmode == $blendMode' }} }"
                                        class="button">{{ blockName($blendMode) }}</button>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    @error('form.filter')
                        <p class="text-rose-600 italic pt-1">{{ $message }}</p>
                    @enderror
                @endif
            @endif
            @if (in_array($block->widget->name, [
                    'h1',
                    'h2',
                    'h3',
                    'h4',
                    'h5',
                    'h6',
                    'p',
                    'ul',
                    'ol',
                    'image',
                    'fullname',
                    'job_title',
                    'slogan',
                    'about',
                    'contact',
                    'phone',
                    'email',
                    'web',
                    'address',
                ]))
                <div class="flex gap-2 flex-wrap">
                    <div class="button-group">
                        <button type="button" _wire:click="setAlignment('left')"
                            :class="{
                                '!shadow-cs-inset': {{ '$form->align' == 'left' }}
                            }"
                            class="button"><x-icons.align_left /></button>
                        <button type="button" _wire:click="setAlignment('center')"
                            :class="{
                                '!shadow-cs-inset': {{ '$form->align' == 'center' }}
                            }"
                            class="button"><x-icons.align_center /></button>
                        <button type="button" _wire:click="setAlignment('right')"
                            :class="{
                                '!shadow-cs-inset': {{ '$form->align' == 'right' }}
                            }"
                            class="button"><x-icons.align_right /></button>
                    </div>
                </div>
            @endif
            @if (in_array($block->widget->name, ['experiences', 'educations', 'projects', 'awards', 'certificates']))
                <div>
                    <label>{{ __('Date format') }}</label>
                    <div class="button-group">
                        <button type="button" _wire:click="setDateFormat('year')"
                            :class="{
                                '!shadow-cs-inset': {{ '$form->dateformat' == 'year' }}
                            }"
                            class="button">{{ $dateExample->translatedFormat('Y') }}</button>
                        <button type="button" _wire:click="setDateFormat('month')"
                            :class="{
                                '!shadow-cs-inset': {{ '$form->dateformat' == 'month' }}
                            }"
                            class="button">{{ $dateExample->translatedFormat('m/Y') }}</button>

                        <button type="button" _wire:click="setDateFormat('day')"
                            :class="{
                                '!shadow-cs-inset': {{ '$form->dateformat' == 'day' }}
                            }"
                            class="button">{{ $dateExample->translatedFormat('d/m/Y') }}</button>
                        <button type="button" _wire:click="setDateFormat('monthname')"
                            :class="{
                                '!shadow-cs-inset': {{ '$form->dateformat' == 'monthname' }}
                            }"
                            class="button">{{ ucwords($dateExample->translatedFormat('M Y')) }}</button>
                        <button type="button" _wire:click="setDateFormat('daymonthname')"
                            :class="{
                                '!shadow-cs-inset': {{ '$form->dateformat' == 'daymonthname' }}
                            }"
                            class="button">{{ ucwords($dateExample->translatedFormat('d M Y')) }}</button>
                    </div>
                </div>
            @endif
            <div>
                <label>{{ __('Visible') }}</label>
                <x-switch id="active" wire:model.live="form.active" />
            </div>

            <div class="my-2 flex justify-end">
                <button class="button">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>
