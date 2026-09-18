<?php

namespace App\Livewire;

use App\Models\Certification;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Certifications extends Component
{
    public function updateOrder(array $items)
    {
        $order = collect($items)->pluck('value')->toArray();
        Certification::setNewOrder($order, 1, 'id', function (Builder $query) {
            $query->where('user_id', auth()->user()->id);
        });
        $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function render()
    {
        return view('livewire.certifications.index');
    }

    public function delete(Certification $award)
    {
        Gate::authorize('delete', $award);
        $award->delete();
        $this->dispatch('refresh');
    }
}
