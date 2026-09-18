<?php

namespace App\Livewire;

use App\Models\Education;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Educations extends Component
{
    public function updateOrder(array $items)
    {
        $order = collect($items)->pluck('value')->toArray();
        Education::setNewOrder($order, 1, 'id', function (Builder $query) {
            $query->where('user_id', auth()->user()->id);
        });
        $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function render()
    {
        return view('livewire.educations.index');
    }

    public function delete(Education $education)
    {
        Gate::authorize('delete', $education);
        $education->delete();
        $this->dispatch('refresh');
    }
}
