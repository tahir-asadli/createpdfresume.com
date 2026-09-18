<table class="w-full">
    <thead>
        <tr>
            <th class="text-lg text-left bg-slate-100 p-3 rounded-tl-md">{{ __('Hobby name') }}</th>
            <th class="text-lg text-center w-40 bg-slate-100 p-3"><span class="hidden md:inline">{{ __('Enabled') }}</span></th>
            <th class="text-lg text-center w-40 bg-slate-100 p-3"><span class="hidden md:inline">{{ __('Edit') }}</span></th>
            <th class="text-lg text-center w-10 bg-slate-100 p-3 rounded-tr-md"><span class="hidden md:inline">{{ __('Delete') }}</span>
            </th>
        </tr>
    </thead>
    <tbody wire:sortable="updateOrder">
        @foreach (auth()->user()->hobbies()->orderBy('order')->get() as $hobby)
            <tr wire:sortable.item="{{ $hobby->id }}" wire:key="hobby-{{ $hobby->id }}">
                <td class="p-2 border-b border-gray-100 font-bold">
                    <span class="flex gap-4">
                        <span wire:sortable.handle class="cursor-pointer"><x-icons.move /></span>
                        <span class="small-hobby hidden md:table-cell"><x-dynamic-component
                                component="hobbies.{{ $hobby->icon }}" /></span>
                        <span>
                            {{ $hobby->name }}
                </td>
                <td class="p-2 border-b border-gray-100 text-center">
                    @if ($hobby->active)
                        <x-icons.check class="w-5 text-green-700 mx-auto" />
                    @endif
                </td>
                <td class="p-2 border-b border-gray-100 text-center"><button
                        class="button p-3 bg-indigo-100/50 shadow-none border-none focus:ring-indigo-100 text-indigo-500"
                        onclick="Livewire.dispatch('openModal', { component: 'edit-hobby',arguments: {hobby: '{{ $hobby->id }}'} })"><x-icons.edit
                            class="md:hidden" /><span class="hidden md:inline">{{ __('Edit') }}</span>
                </td>
                <td class="p-2 border-b border-gray-100 text-right">
                    <button wire:click="delete('{{ $hobby->id }}')" wire:confirm="{{ __('Are you sure?') }}"
                        class="button p-3 bg-violet-100/50 shadow-none border-none text-violet-800"><x-icons.delete
                            class="md:hidden" /><span class="hidden md:inline">{{ __('Delete') }}</span></button>
                </td>
            </tr>
        @endforeach


    </tbody>
</table>


{{-- Drawing
Painting
Photography
Music
Crafting
Graphic Design
Hiking
Camping
Cycling
Cycling2
Swimming
Swimming2
Swimming3
Gardening
Fishing
Fishing2
Fishing3
Climbing
Running
Running2
Martial Arts
Martial Arts2
Martial Arts3
Yoga
Meditation
Coding --}}
{{-- -Dance
Video Gaming
3D Modeling
Animation
Robotics
Tech Reviews
Reading
Language Learning
Puzzle Solving
Chess
DIY Electronics
Cooking
Baking
Wine Tasting
Food Blogging
Coffee Brewing
Fermentation
Traveling
Volunteering
Event Planning
Theater
Acting
Board Games
Collecting
Birdwatching
Astrophotography
Aquascaping
Journaling --}}
