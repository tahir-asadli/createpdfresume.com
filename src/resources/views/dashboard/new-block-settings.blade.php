<div class="pt-5 pb-10 px-10 flex-grow flex flex-col">
    <div class="relative">
        <h2 class="font-semibold text-lg md:text-2xl pr-10">{{ __('Block settings') }}</h2>
        <button
            class="close-block-settings button absolute top-0 bottom-0 right-0 m-auto p-0 w-10 h-[40px] text-3xl font-normal flex justify-center items-baseline leading-[35px] min-h-[40px]">&times;</button>
    </div>
    <h3 class="font-semibold text-lg  my-1 text-gray-600">{{ __('Block name:') }} {{ $block->widget->title }}</h3>
    <div class="mt-3">
        <form class="flex flex-col gap-4 mt-4" action="{{ route('update-block-settings') }}" method="post">
            <input type="hidden" name="blockId" value="{{ $block->id }}">
            @if (!in_array($block->widget->name, ['space', 'ul', 'ol', 'image', 'richtext']))
                <div>
                    <label for="title">{{ __('Title') }}</label>
                    <input type="text" placeholder="{{ __('Title') }}" autocomplete="off" autocomplete="off"
                        autofocus name="title" value="{{ $block->title }}">
                    <div id="title-settings-errors" class="settings-errors"></div>
                </div>
            @endif
            @if (in_array($block->widget->name, ['ul', 'ol']))
                <div>
                    <label for="title">{{ __('Content') }}</label>
                    <textarea placeholder="{{ __('Content') }}" rows="10" autofocus name="title">{{ $block->title }}</textarea>
                    <div id="title-settings-errors" class="settings-errors"></div>
                </div>
            @endif
            @if (in_array($block->widget->name, ['richtext']))
                <div class="relative has-limit" limit="1000">
                    <div wire:ignore>
                        <label for="title">{{ __('Rich Text') }}</label>
                        <textarea placeholder="{{ __('Rich Text') }}" id="richtext-setting" rows="5" name="title"
                            class="hugerte-editor">{{ $block->title }}</textarea>
                    </div>
                    <div class="absolute w-full flex justify-end text-gray-500 limit-indicator"><span><span
                                class="chars">{{ mb_strlen($block->title) }}</span>/1000</span></div>
                    <div id="title-settings-errors" class="settings-errors"></div>
                </div>
            @endif
            @if ($block->widget->name == 'space')
                <div>
                    <label for="height">{{ __('Height') }} (px)</label>
                    <input type="number" placeholder="Height: 0 - 100 px" min="1" max="100" name="height"
                        value="{{ (int) $block->height }}">
                    <div id="height-settings-errors" class="settings-errors"></div>
                </div>
            @endif
            @if ($block->widget->multiple)
                <div>
                    <label for="from">{{ __('Start') }}</label>
                    <input type="number" min="1" max="100" placeholder="{{ __('Start') }}" autofocus
                        name="from" value="{{ $block->from }}">
                    <div id="from-settings-errors" class="settings-errors"></div>
                </div>
                <div>
                    <label for="to">{{ __('End') }}</label>
                    <input type="number" min="1" max="100" placeholder="{{ __('End') }}" autofocus
                        name="to" value="{{ $block->to }}">
                    <div id="to-settings-errors" class="settings-errors"></div>
                </div>
            @else
                @if (in_array($block->widget->name, [
                        'fullname',
                        'job_title',
                        'slogan',
                        'about',
                        'contact',
                        'contacts',
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
                    <div class="checkbox-group">
                        <input name="bold" id="bold" {{ $block->bold ? 'checked' : '' }} type="checkbox">
                        <label for="bold"><x-icons.bold /></label>
                        <input name="italic" id="italic" {{ $block->italic ? 'checked' : '' }} type="checkbox">
                        <label for="italic"><x-icons.italic /></label>
                        <input name="underline" id="underline" {{ $block->underline ? 'checked' : '' }}
                            type="checkbox">
                        <label for="underline"><x-icons.underline /></label>
                        <input name="strikethrough" id="strikethrough" {{ $block->strikethrough ? 'checked' : '' }}
                            type="checkbox">
                        <label for="strikethrough"><x-icons.strikethrough /></label>
                        <input name="uppercase" id="uppercase" {{ $block->uppercase ? 'checked' : '' }}
                            type="checkbox">
                        <label for="uppercase"><x-icons.uppercase /></label>
                    </div>
                    <x-color-input value="{{ $block->color }}" colors="{{ $template->formattedColors() }}" />
                    <div id="color-settings-errors" class="settings-errors"></div>
                @endif
                @if ($block->widget->name == 'image')
                    <div class="checkbox-group">
                        <input value="9999" name="radius" id="radius-9999"
                            {{ $block->radius == 9999 ? 'checked' : '' }} type="radio">
                        <label for="radius-9999"><span class="circle"></span></label>
                        <input value="5" name="radius" id="radius-5" {{ $block->radius == 5 ? 'checked' : '' }}
                            type="radio">
                        <label for="radius-5"><span class="rounded-rectangle"></span></label>
                        <input value="0" name="radius" id="radius-0"
                            {{ $block->radius == 0 ? 'checked' : '' }} type="radio">
                        <label for="radius-0"><span class="rectangle"></span></label>
                    </div>
                    <label class="m-0">{{ __('Transparent') }}</label>
                    @if ($block->resume->transparentImageExists())
                        <div class="flex gap-2 flex-wrap">
                            <div class="checkbox-group">
                                <input name="transparent"
                                    {{ $block->resume->user->profile->transparent ? 'checked' : '' }}
                                    id="transparent-yes" {{ $block->transparent ? 'checked' : '' }} value="yes"
                                    type="radio">
                                <label for="transparent-yes">{{ __('Yes') }}</label>
                                <input name="transparent"
                                    {{ !$block->resume->user->profile->transparent ? 'checked' : '' }}
                                    id="transparent-no" {{ $block->transparent ? 'checked' : '' }} value="no"
                                    type="radio">
                                <label for="transparent-no">{{ __('No') }}</label>
                            </div>
                        </div>
                    @endif
                    <div class="flex gap-1 flex-wrap">
                        <div class="checkbox-group">
                            <input value="" name="filter" id="effect-null"
                                {{ $block->filter == null ? 'checked' : '' }} type="radio">
                            <label for="effect-null">{{ __('No effect') }}</label>

                            <input value="effect-1" name="filter" id="effect-1"
                                {{ $block->filter == 'effect-1' ? 'checked' : '' }} type="radio">
                            <label for="effect-1">{{ __('Grayscale') }}</label>

                            <input value="effect-2" name="filter" id="effect-2"
                                {{ $block->filter == 'effect-2' ? 'checked' : '' }} type="radio">
                            <label for="effect-2">{{ __('Light') }}</label>

                            <input value="effect-3" name="filter" id="effect-3"
                                {{ $block->filter == 'effect-3' ? 'checked' : '' }} type="radio">
                            <label for="effect-3">{{ __('Dark') }}</label>
                        </div>
                        <div class="checkbox-group">
                            <input value="effect-4" name="filter" id="effect-4"
                                {{ $block->filter == 'effect-4' ? 'checked' : '' }} type="radio">
                            <label for="effect-4">{{ __('Light red') }}</label>

                            <input value="effect-5" name="filter" id="effect-5"
                                {{ $block->filter == 'effect-5' ? 'checked' : '' }} type="radio">
                            <label for="effect-5">{{ __('Light green') }}</label>

                            <input value="effect-6" name="filter" id="effect-6"
                                {{ $block->filter == 'effect-6' ? 'checked' : '' }} type="radio">
                            <label for="effect-6">{{ __('Light blue') }}</label>
                        </div>
                        <div class="checkbox-group">
                            <input value="effect-7" name="filter" id="effect-7"
                                {{ $block->filter == 'effect-7' ? 'checked' : '' }} type="radio">
                            <label for="effect-7">{{ __('Dark red') }}</label>

                            <input value="effect-8" name="filter" id="effect-8"
                                {{ $block->filter == 'effect-8' ? 'checked' : '' }} type="radio">
                            <label for="effect-8">{{ __('Dark green') }}</label>

                            <input value="effect-9" name="filter" id="effect-9"
                                {{ $block->filter == 'effect-9' ? 'checked' : '' }} type="radio">
                            <label for="effect-9">{{ __('Dark blue') }}</label>
                        </div>
                    </div>
                    <label class="m-0">{{ __('Blend mode') }}</label>
                    <div class="flex gap-2 flex-wrap flex-col">
                        @foreach ($blendModes as $blendModeGroup)
                            <div class="checkbox-group">
                                @foreach ($blendModeGroup as $blendMode)
                                    <input value="{{ $blendMode }}" name="blend"
                                        id="blend-{{ $blendMode }}"
                                        {{ $block->blend == $blendMode ? 'checked' : '' }} type="radio">
                                    <label for="blend-{{ $blendMode }}">{{ blockName($blendMode) }}</label>
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
                    'contacts',
                    'phone',
                    'email',
                    'web',
                    'address',
                ]))
                <div class="flex gap-2 flex-wrap">
                    <div class="checkbox-group">
                        <input name="align" id="align-left" {{ $block->align == 'left' ? 'checked' : '' }}
                            type="radio" value="left">
                        <label for="align-left"><x-icons.align_left /></label>

                        <input name="align" id="align-center" {{ $block->align == 'center' ? 'checked' : '' }}
                            type="radio" value="center">
                        <label for="align-center"><x-icons.align_center /></label>

                        <input name="align" id="align-right" {{ $block->align == 'right' ? 'checked' : '' }}
                            type="radio" value="right">
                        <label for="align-right"><x-icons.align_right /></label>

                    </div>
                </div>
            @endif

            @if (in_array($block->widget->name, ['experiences', 'educations', 'projects', 'awards', 'certificates']))
                <div>
                    <label class="m-0">{{ __('Date format') }}</label>
                    <div class="checkbox-group">
                        <input name="dateformat" id="dateformat-year"
                            {{ $block->dateformat == 'year' ? 'checked' : '' }} type="radio" value="year">
                        <label class="text-sm !px-3"
                            for="dateformat-year">{{ $dateExample->translatedFormat('Y') }}</label>

                        <input name="dateformat" id="dateformat-month"
                            {{ $block->dateformat == 'month' ? 'checked' : '' }} type="radio" value="month">
                        <label class="text-sm !px-3"
                            for="dateformat-month">{{ $dateExample->translatedFormat('m/Y') }}</label>

                        <input name="dateformat" id="dateformat-day"
                            {{ $block->dateformat == 'day' ? 'checked' : '' }} type="radio" value="day">
                        <label class="text-sm !px-3"
                            for="dateformat-day">{{ $dateExample->translatedFormat('d/m/Y') }}</label>

                        <input name="dateformat" id="dateformat-monthname"
                            {{ $block->dateformat == 'monthname' ? 'checked' : '' }} type="radio"
                            value="monthname">
                        <label class="text-sm !px-3"
                            for="dateformat-monthname">{{ $dateExample->translatedFormat('M Y') }}</label>

                        <input name="dateformat" id="dateformat-daymonthname"
                            {{ $block->dateformat == 'daymonthname' ? 'checked' : '' }} type="radio"
                            value="daymonthname">
                        <label class="text-sm !px-3"
                            for="dateformat-daymonthname">{{ $dateExample->translatedFormat('d M Y') }}</label>



                    </div>
                </div>
            @endif
            <div>
                <label class="m-0">{{ __('Visible') }}</label>
                <x-switch id="active" name="active" value="{{ $block->active }}" />
            </div>
            <div class="my-2 flex justify-end">
                <button class="button save-block-settings">{{ __('Close') }}</button>
            </div>
        </form>
    </div>
</div>
