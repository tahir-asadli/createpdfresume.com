<?php

namespace App\Livewire;

use App\Models\Skill;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Skills extends Component
{
    public function updateOrder(array $items)
    {
        $order = collect($items)->pluck('value')->toArray();
        Skill::setNewOrder($order, 1, 'id', function (Builder $query) {
            $query->where('user_id', auth()->user()->id);
        });
        $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function render()
    {
        return view('livewire.skills.index');
    }

    public function delete(Skill $skill)
    {
        Gate::authorize('delete', $skill);
        $skill->delete();
        $this->dispatch('refresh');
    }
}
