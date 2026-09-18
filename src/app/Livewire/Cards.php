<?php

namespace App\Livewire;

use App\Models\Card;
use App\Services\EpointHelper;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Cards extends Component
{

    public $error = null;

    #[On('refresh')]
    public function render()
    {
        return view('livewire.cards');
    }

    public function addCard()
    {
        $epoint = app('epoint');
        $payload = [
            'public_key' => config('epoint.public_key'),
            'language' => EpointHelper::lang(),
            'description' => 'Adding new card',
            "success_redirect_url" => route('billing'),
            "error_redirect_url" => route('billing'),
        ];
        $response = $epoint->request('card-registration', $epoint->payload($payload));
        if ($response) {
            $json_data = json_decode($response, true);
            if ($json_data) {
                if ($json_data['status'] == 'success' && $json_data['redirect_url'] != '') {
                    if (isset($json_data['card_id'])) {
                        Card::create([
                            'card_id' => $json_data['card_id'],
                            'verified' => false,
                            'active' => false,
                            'user_id' => auth()->user()->id,
                        ]);
                    }
                    return redirect($json_data['redirect_url']);
                } else {
                    $this->error = __('Error occured, code: 1');
                }
            } else {
                $this->error = __('Error occured, code: 2');
            }
        } else {
            $this->error = __('Error occured, code: 3');
        }
        // 
    }

    public function delete(Card $card)
    {
        Gate::authorize('delete', $card);
        $card->delete();
        $this->dispatch('refresh');
    }
}
