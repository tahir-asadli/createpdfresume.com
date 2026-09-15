<div class="canvas grid {{ $resume->template->layout }}" wire:sortable="sorted" wire:sortable-group="moved">
    @foreach ($resume->template->sections as $section)
        <div class="section-{{ $section->section }} relative min-w-0" wire:key="{{ $section->section }}"
            wire:sortable.item="{{ $section->section }}">
            <div class="title">
                <span>{{ $section->nameAz() }}</span>
                <span class="button small"
                    onclick="Livewire.dispatch('openModal', { component: 'add-widget', arguments: {section: '{{ $section->section }}', resume: '{{ $resume->id }}', page: '{{ $page }}'} })">+</span>
                <span wire:sortable.handle class="hidden">
                    {{ $section->section }}
                </span>
            </div>
            <div class="builder-section" wire:sortable-group.item-group="{{ $section->section }}">
                @foreach ($resume->blocks()->where('resume_page', $page)->orderBy('order')->get() as $block)
                    @if ($block->section == $section->section->value)
                        <div class="wblock group  {{ $block->active ? 'is-visible' : '' }}"
                            wire:key="{{ $block->id . $block->widget->id }}"
                            wire:sortable-group.item="{{ $block->id }}">
                            <div class="wblock-title">

                                <span class="wblock-icon">
                                    <x-dynamic-component component="icons.{{ $block->widget->name }}" />
                                </span>
                                {{-- <span class="wblock-title-text">
                                </span> --}}
                                @if ($block->widget->name == 'image' && auth()->user()->profile && auth()->user()->profile->image_path())
                                    <img loading="lazy" src="{{ auth()->user()->profile->image_url() }}"
                                        alt="{{ __('image') }}" class="w-[25px] h-[25px] rounded-full">
                                @endif
                                <span class="mr-auto wblock-title-text" wire:sortable-group.handle>
                                    <x-icons.move />
                                    @if ($block->title)
                                        @if ($block->title == ' ')
                                            {{ $block->widget->title }}
                                        @elseif($block->widget->name == 'richtext')
                                            {{ __('Rich Text') }}
                                        @else
                                            {{ $block->title }}
                                        @endif
                                    @else
                                        {{ __($block->widget->title) }}
                                    @endif
                                </span>
                                <button class="wblock-expand"
                                    onclick="Livewire.dispatch('openModal', { component: 'block-settings', arguments: {block: '{{ $block->id }}', 'template': '{{ $resume->template->id }}'} })">
                                    <x-icons.gear />
                                </button>
                                <button class="wblock-expand ml-0" wire:click="duplicate('{{ $block->id }}')">
                                    <x-icons.duplicate />
                                </button>
                                <button class="wblock-expand ml-0"
                                    wire:click="toggleVisibility('{{ $block->id }}')">
                                    <x-icons.eye />
                                </button>
                                <button class="wblock-expand ml-0 !bg-rose-100"
                                    wire:click="delete('{{ $block->id }}')"
                                    wire:confirm="{{ __('Are you sure?') }}"><x-icons.delete
                                        class=" text-rose-600" /></button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>
