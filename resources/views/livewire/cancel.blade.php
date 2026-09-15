<div>
    @if ($hasSubscription)
        <form wire:submit="submit" wire:confirm="{{ __('Are you sure?') }}">
            @if ($successMessage != '')
                <div class="text-green-800 font-medium bg-green-100 p-4 rounded-lg border border-green-200 mb-4">
                    {{ __('Subscription cancelled!') }}
                </div>
            @endif
            @if ($errorMessage != '')
                <div class="text-violet-800 font-medium bg-violet-100 p-4 rounded-lg border border-violet-200 mb-4">
                    {{ __('Error occured, please refresh the page and try again') }}
                </div>
            @endif
            <div class="flex justify-end">
                <button class="button bg-violet-600 border !border-violet-700 text-white">{{ __('Cancel') }}</button>
            </div>
        </form>
    @else
        <div>{{ __('You don\'t have subscription') }}</div>
    @endif
</div>
