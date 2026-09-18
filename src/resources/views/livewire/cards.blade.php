<div>
    @if (auth()->user()->cards)
        <div>
            <h2 class="mb-3 text-lg text-gray-600">{{ __('Cards') }}</h2>
            @if ($error)
                {{ $error }}
            @endif
            <table class="w-full mb-10">
                <thead>
                    <tr>
                        <th class="text-lg text-left bg-slate-100 p-3 rounded-tl-md">{{ __('End date') }}</th>
                        <th class="text-lg text-left bg-slate-100 p-3">{{ __('Approved') }}</th>
                        <th class="text-lg text-left bg-slate-100 p-3">{{ __('Status') }}</th>
                        <th class="text-lg text-center w-10 bg-slate-100 p-3 rounded-tr-md"><span
                                class="hidden md:inline">{{ __('Delete') }}</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (auth()->user()->cards()->oldest()->get() as $card)
                        <tr>
                            <td class="p-2 border-b border-gray-100 font-bold">
                                {{ $card->name ? $card->name . ' - ' : '' }}{{ $card->expires_at ? $card->expires_at->format('m/y') : '' }}
                            </td>
                            <td class="p-2 border-b border-gray-100">
                                {{ $card->verified ? __('Approved') : __('Not approved') }}</td>
                            <td class="p-2 border-b border-gray-100">{{ $card->active ? __('Active') : __('Deactive') }}</td>
                            <td class="p-2 border-b border-gray-100 text-right">
                                <button wire:click="delete('{{ $card->id }}')" wire:confirm="{{ __('Are you sure?') }}"
                                    class="button p-3 bg-violet-100/50 shadow-none border-none text-violet-800"><x-icons.delete
                                        class="md:hidden" /><span class="hidden md:inline">{{ __('Delete') }}</span></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-end"><button class="button" wire:click="addCard">{{ __('Add a new card') }}</button></div>
        </div>
    @else
    @endif
</div>
