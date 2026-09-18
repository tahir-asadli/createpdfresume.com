<div>
    <h2 class="mb-3 text-lg text-gray-600">{{ __('Orders') }}</h2>
    <table class="w-full mb-10">
        <thead>
            <tr>
                <th class="text-lg text-left bg-slate-100 p-3 rounded-tl-md">{{ __('Plan') }}</th>
                <th class="text-lg text-left bg-slate-100 p-3">{{ __('Price') }}</th>
                <th class="text-lg text-left bg-slate-100 p-3">{{ __('Status') }}</th>
                <th class="text-lg text-center w-10 bg-slate-100 p-3 rounded-tr-md">{{ __('Date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach (auth()->user()->orders()->latest()->get() as $order)
                <tr>
                    <td class="p-2 border-b border-gray-100 font-bold">{{ $order->name() }}</td>
                    <td class="p-2 border-b border-gray-100">{{ $order->amountStr() }}</td>
                    <td class="p-2 border-b border-gray-100">{{ __(ucfirst($order->status)) }}</td>
                    <td class="p-2 border-b border-gray-100">{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
