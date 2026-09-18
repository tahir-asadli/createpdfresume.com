<?php

namespace App\Livewire;

use App\Models\Language;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Languages extends Component
{
    public function updateOrder(array $items)
    {
        $order = collect($items)->pluck('value')->toArray();
        Language::setNewOrder($order, 1, 'id', function (Builder $query) {
            $query->where('user_id', auth()->user()->id);
        });
        $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function render()
    {
        return view('livewire.languages.index');
    }

    public function delete(Language $language)
    {
        Gate::authorize('delete', $language);
        $language->delete();
        $this->dispatch('refresh');
    }
}
