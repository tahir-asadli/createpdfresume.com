<x-dashboard-layout>

    @section('title')
        {{ __('Templates') }}
    @endsection
    <div class="w-full my-5">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-4">
            @foreach ($templates as $template)
                <div class="flex flex-col">
                    <div class="font-bold text-center text-xl my-2">{{ $template->name }}</div>
                    <div class="p-1 border border-gray-300 rounded-md relative">
                        @if ($template->plan)
                            <div
                                class="{{ $template->plan->slug == 'standart' ? 'plan-standart' : 'plan-premium' }} absolute top-1 right-1 text-xs p-1 rounded-md flex justify-center items-center text-white">
                                {{ $template->plan->name }}</div>
                        @else
                            <div
                                class="absolute top-1 right-1 text-xs p-1 rounded-md flex justify-center items-center text-white bg-violet-500">
                                {{ __('Free') }}
                            </div>
                        @endif
                        <a href="/template-images/{{ $template->image }}" data-fancybox="gallery"
                            data-caption="{{ $template->name }}">
                            <img loading="lazy" class="w-full" src="/template-images/{{ $template->image }}"
                                alt="{{ __('template') }}">
                        </a>
                    </div>
                    <div class="text-center py-2 mt-auto">
                        @if ($template->available())
                            <button
                                onclick="Livewire.dispatch('openModal', { component: 'add-resume',arguments: {templateUuid: '{{ $template->uuid }}'} })"
                                class="button p-3.5">{{ __('Pick template') }}</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</x-dashboard-layout>
