<x-dashboard-layout>
    @section('title')
        {{ __('Account') }}
    @endsection
    <div class="w-full my-5">
        <livewire:account />
        {{-- <livewire:cards />
        <livewire:orders /> --}}
    </div>
</x-dashboard-layout>
