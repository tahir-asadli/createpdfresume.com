<x-dashboard-layout>
    @section('title')
        {{ __('Resumes') }}
    @endsection
    @section('add-new')
        <button onclick="Livewire.dispatch('openModal', { component: 'add-resume' })" class="button p-3.5"><x-icons.plus
                class="ml-auto" width="20px" height="20px" /></button>
    @endsection
    <div class="w-full my-5">
        <livewire:resumes />
    </div>

</x-dashboard-layout>
