<table class="w-full">
    <thead>
        <tr>
            <th class="text-lg text-left bg-slate-100 p-3 rounded-tl-md">{{ __('Skill') }}</th>
            <th class="text-lg text-left bg-slate-100 p-3 hidden md:table-cell">{{ __('Percentage') }}</th>
            <th class="text-lg text-center w-40 bg-slate-100 p-3"><span class="hidden md:inline">{{ __('Enabled') }}</span></th>
            <th class="text-lg text-center w-40 bg-slate-100 p-3"><span class="hidden md:inline">{{ __('Edit') }}</span></th>
            <th class="text-lg text-center w-10 bg-slate-100 p-3 rounded-tr-md"><span class="hidden md:inline">{{ __('Delete') }}</span>
            </th>
        </tr>
    </thead>
    <tbody wire:sortable="updateOrder">
        @foreach (auth()->user()->skills()->orderBy('order')->get() as $skill)
            <tr  wire:sortable.item="{{ $skill->id }}" wire:key="skill-{{ $skill->id }}">
                <td class="p-2 border-b border-gray-100 font-bold">
                    <span wire:sortable.handle class="cursor-pointer"><x-icons.move /></span><span>{{ $skill->name }}</span>
                </td>
                <td class="p-2 border-b border-gray-100 hidden md:table-cell text-center">{{ $skill->level }}%</td>
                <td class="p-2 border-b border-gray-100 text-center">
                    @if ($skill->active)
                        <x-icons.check class="w-5 text-green-700 mx-auto" />
                    @endif
                </td>
                <td class="p-2 border-b border-gray-100 text-center"><button
                        class="button p-3 bg-indigo-100/50 shadow-none border-none focus:ring-indigo-100 text-indigo-500"
                        onclick="Livewire.dispatch('openModal', { component: 'edit-skill',arguments: {skill: '{{ $skill->id }}'} })"><x-icons.edit
                            class="md:hidden" /><span class="hidden md:inline">{{ __('Edit') }}</span></button>
                </td>
                <td class="p-2 border-b border-gray-100 text-right">
                    <button wire:click="delete('{{ $skill->id }}')" wire:confirm="{{ __('Are you sure?') }}"
                        class="button p-3 bg-violet-100/50 shadow-none border-none text-violet-800"><x-icons.delete
                            class="md:hidden" /><span class="hidden md:inline">{{ __('Delete') }}</span></button>
                </td>
            </tr>
        @endforeach


    </tbody>
</table>
