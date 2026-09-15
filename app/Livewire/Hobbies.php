<?php

namespace App\Livewire;

use App\Models\Hobby;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Hobbies extends Component
{
    public function updateOrder(array $items)
    {
        $order = collect($items)->pluck('value')->toArray();
        Hobby::setNewOrder($order, 1, 'id', function (Builder $query) {
            $query->where('user_id', auth()->user()->id);
        });
        $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function render()
    {
        return view('livewire.hobbies.index');
    }

    public function delete(Hobby $hobby)
    {
        Gate::authorize('delete', $hobby);
        $hobby->delete();
        $this->dispatch('refresh');
    }
}
