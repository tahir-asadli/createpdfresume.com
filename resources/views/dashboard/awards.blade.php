<x-dashboard-layout>
    @section('title')
        {{ __('Awards') }}
    @endsection
    @section('add-new')
        <button onclick="Livewire.dispatch('openModal', { component: 'add-award' })" class="button p-3.5"><x-icons.plus
                class="ml-auto" width="20px" height="20px" /></button>
    @endsection
    <div class="w-full my-5">
        <livewire:awards />
    </div>

</x-dashboard-layout>
