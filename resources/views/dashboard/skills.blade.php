<x-dashboard-layout>
    @section('title')
        {{ __('Skills') }}
    @endsection
    @section('add-new')
        <button onclick="Livewire.dispatch('openModal', { component: 'add-skill' })" class="button p-3.5"><x-icons.plus
                class="ml-auto" width="20px" height="20px" /></button>
    @endsection
    <div class="w-full my-5">
        <livewire:skills />
    </div>

</x-dashboard-layout>
