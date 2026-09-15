<div>
    <form wire:submit="submit">
        @if ($error)
            <div class="text-rose-600 mt-2 py-2">
                {!! $error !!}
            </div>
        @endif
        <h5 class="font-normal border-b border-b-slate-200 leading-10 py-1 flex justify-between items-center">{{ __('Amount to be paid') }}:
            <div class="flex flex-col gap-0 leading-none items-end">
                <b>{{ priceUSD($plan->usd) }}</b>
                <span class="text-[13px] font-normal">{{ priceUSD($plan->usd) }} = {{ priceAZNLong($plan->price) }}</span>
            </div>
        </h5>
         

        <h5 class="font-normal border-b border-b-slate-200 leading-10 py-1 flex justify-between items-center">{{ __('Payment method') }}</h5>
        <div class="flex flex-col">
            @foreach (auth()->user()->cards()->verified()->get() as $key => $card)
                <label for="card-{{ $card->id }}"
                    class="py-3 border-b border-x-gray-300 cursor-pointer flex gap-2 items-center">
                    <input type="radio" name="payment" wire:model="payment" value="{{ $card->id }}"
                        id="card-{{ $card->id }}" />
                    <span>{{ $card->name }} - {{ $card->date() }}
                        @if ($key == 0)
                            - <a href="{{ route('billing') }}" class="underline" target="_blank">{{ __('My cards') }}</a>
                        @endif
                    </span>
                </label>
            @endforeach

            <label for="bank" class="py-3 border-b border-x-gray-300 cursor-pointer flex gap-2 items-center">
                <input type="radio" name="payment" wire:model="payment" value="bank" id="bank" />
                <span>{{ __('With a bank card') }}</span>
            </label>
        </div>
        <h5 class="text-sm font-normal border-b border-b-slate-200 leading-10 py-1 flex justify-between items-center"><label
                    class="cursor-pointer select-none grow" for="subscription">{{ __('Automatically renew subscription every month') }}</label><input id="subscription" wire:model="renew" type="checkbox"></h5>
        <div class="flex justify-end pt-5 mb-5">
            <button class="button small">{{ __('Pay') }}</button>
        </div>
    </form>
</div>
