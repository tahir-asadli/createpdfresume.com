<x-dashboard-layout>
    @section('title')
        {{ __('Informations') }}
    @endsection
    <div class="w-full my-5">
        <div class="grid md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-1 my-2 w-full">
            @foreach (navigations() as $key => $navigation)
                <div class="relative flex justify-center items-center">
                    {{--  justify-center xl: --}}
                    <a class="link font-semibold p-1.5 focus:ring-0 focus:outline-none flex gap-2 justify-center items-center  border border-gray-300 rounded-md py-5 px-3 w-full {{ request()->routeIs($navigation['route']) ? ' text-violet-500 ' : 'text-gray-700' }}"
                        href="{{ route($navigation['route']) }}"><x-dynamic-component
                            component="{{ $navigation['icon'] }}"
                            class="text-gray-600  {{ request()->routeIs($navigation['route']) ? ' text-violet-500 ' : 'text-gray-700' }}"
                            width="20px" height="20px" /><span class="inline">{{ $navigation['name'] }}</span></a>
                </div>
            @endforeach
        </div>
    </div>
</x-dashboard-layout>
