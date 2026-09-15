<div wire:click="download" x-data="{}" class="{{ $isButton ? '' : 'w-full' }}">

    <button x-on:click="setTimeout(() => { $wire.reload() }, 5000);"
        title="{{ __('You can download the PDF yourself with Chrome or Firefox when the limit is reached.') }}"
        class="@if ($isButton)
button
@endif @if ($forMobile)
w-full
@endif justify-center disabled:cursor-not-allowed disabled:opacity-40 relative gap-0 2xl:gap-3"
        x-bind:disabled="{{ $remaining && !$disabled ? 'false' : 'true' }}"><x-icons.chip />
        <span
            class="absolute -top-2 -right-2 text-xs bg-violet-500 text-white w-5 h-5 rounded-full flex justify-center items-baseline leading-4">{{ $remaining }}</span>
        <div>
            <span>{{ $text }}</span>
        </div>
        @if ($download_error)
            <div class="absolute bottom-0 w-full">
                <small class="text-rose-500">{{ $download_error }}</small>
            </div>
        @endif
    </button>
</div>
