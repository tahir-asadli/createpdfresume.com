<x-dashboard-layout>
    @section('title')
        {{ __('Certificates') }}
    @endsection
    @section('add-new')
        <button onclick="Livewire.dispatch('openModal', { component: 'add-certification' })"
            class="button p-3.5"><x-icons.plus class="ml-auto" width="20px" height="20px" /></button>
    @endsection
    <div class="w-full my-5">
        <livewire:certifications />
    </div>

</x-dashboard-layout>
