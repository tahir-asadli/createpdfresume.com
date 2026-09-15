<div>

    @if (count(auth()->user()->availableResumes()))
        <table class="w-full">
            <thead class="max-xl:hidden">
                <tr>
                    <th class="text-lg text-left bg-slate-100 p-3 rounded-tl-md"><span
                            class="hidden md:inline">{{ __('Resume name') }}</span>
                    </th>
                    <th class="text-lg text-left bg-slate-100 p-3 max-lg:hidden"><span
                            class="hidden md:inline">{{ __('Template') }}</span></th>
                    <th class="text-lg text-center lg:w-10 bg-slate-100 p-3"><span
                            class="hidden md:inline">{{ __('Preview') }}</span>
                    </th>
                    <th class="text-lg text-center lg:w-10 bg-slate-100 p-3"><span
                            class="hidden md:inline">{{ __('Modify') }}</span></th>
                    <th class="text-lg text-center lg:w-40 bg-slate-100 p-3"><span
                            class="hidden md:inline">{{ __('Edit') }}</span>
                    </th>
                    <th class="text-lg text-center w-10 bg-slate-100 p-3 rounded-tr-md"><span
                            class="hidden md:inline">{{ __('Delete') }}</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach (auth()->user()->availableResumes() as $resume)
                    <tr>
                        <td class="p-2 border-b border-gray-100 font-bold">{{ $resume->name }}</td>
                        <td class="p-2 border-b border-gray-100 font-bold max-lg:hidden">{{ $resume->template->name }}
                        </td>
                        <td class="p-2 border-b border-gray-100 font-bold text-center"><a
                                href="{{ route('view', [$resume->uuid, $resume->style]) }}" target="_blank"
                                class="button p-3 bg-green-100/50 shadow-none border-none text-green-800"><x-icons.eye /><span
                                    class="hidden lg:inline">{{ __('Preview') }}</span></a>
                        </td>
                        <td class="p-2 border-b border-gray-100 font-bold text-center"><a
                                href="{{ route('new-builder', [$resume->uuid, 1]) }}"
                                class="button p-3 bg-blue-100/50 shadow-none border-none text-blue-800"><x-icons.build /><span
                                    class="hidden lg:inline">{{ __('Modify') }}</span></a>
                        </td>

                        <td class="p-2 border-b border-gray-100 lg:w-40 text-center"><button
                                class="button p-3 bg-indigo-100/50 shadow-none border-none focus:ring-indigo-100 text-indigo-500"
                                onclick="Livewire.dispatch('openModal', { component: 'edit-resume',arguments: {resume: '{{ $resume->id }}'} })"><x-icons.edit /><span
                                    class="hidden lg:inline">{{ __('Edit') }}</span>
                            </button>
                        </td>
                        <td class="p-2 border-b border-gray-100 text-center">
                            <button wire:click="delete('{{ $resume->id }}')"
                                wire:confirm="{{ __('Are you sure?') }}"
                                class="button p-3 bg-violet-100/50 shadow-none border-none text-violet-800"><x-icons.delete
                                    class="md:hidden" /><span
                                    class="hidden md:inline">{{ __('Delete') }}</span></button>
                        </td>
                    </tr>
                @endforeach


            </tbody>
        </table>
    @else
        {{ __('There is no resume') }}
    @endif
</div>
