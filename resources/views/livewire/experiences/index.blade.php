<table class="w-full">
    <thead>
        <tr>
            <th class="text-lg text-left bg-slate-100 p-3 rounded-tl-md">{{ __('Company') }}</th>
            <th class="text-lg text-left bg-slate-100 p-3 hidden md:table-cell">{{ __('Position') }}</th>
            <th class="text-lg text-left bg-slate-100 p-3 hidden md:table-cell">{{ __('Years') }}</th>
            <th class="text-lg text-center w-40 bg-slate-100 p-3"><span class="hidden md:inline">{{ __('Enabled') }}</span></th>
            <th class="text-lg text-center w-40 bg-slate-100 p-3"><span class="hidden md:inline">{{ __('Edit') }}</span></th>
            <th class="text-lg text-center w-10 bg-slate-100 p-3 rounded-tr-md"><span class="hidden md:inline">{{ __('Delete') }}</span>
            </th>
        </tr>
    </thead>
    <tbody wire:sortable="updateOrder">
        @foreach (auth()->user()->experiences()->orderBy('order')->get() as $experience)
            <tr wire:sortable.item="{{ $experience->id }}" wire:key="experience-{{ $experience->id }}" >
                <td class="p-2 border-b border-gray-100 font-bold">
                 <span wire:sortable.handle class="cursor-pointer"><x-icons.move /></span><span>{{ $experience->company }}</span>
                </td>
                <td class="p-2 border-b border-gray-100 hidden md:table-cell">{{ $experience->position }}</td>
                <td class="p-2 border-b border-gray-100 hidden md:table-cell">
                    {{ $experience->start_date ? $experience->start_date->format('d/m/Y') : '' }}
                    {{ $experience->end_date ? ' - ' . $experience->end_date->format('d/m/Y') : '' }}</td>
                <td class="p-2 border-b border-gray-100 text-center">
                    @if ($experience->active)
                        <x-icons.check class="w-5 text-green-700 mx-auto" />
                    @endif
                </td>
                <td class="p-2 border-b border-gray-100 text-center"><button
                        class="button p-3 bg-indigo-100/50 shadow-none border-none focus:ring-indigo-100 text-indigo-500"
                        onclick="Livewire.dispatch('openModal', { component: 'edit-experience',arguments: {experience: '{{ $experience->id }}'} })"><x-icons.edit
                            class="md:hidden" /><span class="hidden md:inline">{{ __('Edit') }}</span>
                </td>
                <td class="p-2 border-b border-gray-100 text-right">
                    <button wire:click="delete('{{ $experience->id }}')" wire:confirm="{{ __('Are you sure?') }}"
                        class="button p-3 bg-violet-100/50 shadow-none border-none text-violet-800"><x-icons.delete
                            class="md:hidden" /><span class="hidden md:inline">{{ __('Delete') }}</span></button>
                </td>
            </tr>
        @endforeach



    </tbody>
</table>
