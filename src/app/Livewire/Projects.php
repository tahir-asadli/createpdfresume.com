<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Projects extends Component
{
    public function updateOrder(array $items)
    {
        $order = collect($items)->pluck('value')->toArray();
        Project::setNewOrder($order, 1, 'id', function (Builder $query) {
            $query->where('user_id', auth()->user()->id);
        });
        $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function render()
    {
        return view('livewire.projects.index');
    }

    public function delete(Project $project)
    {
        Gate::authorize('delete', $project);
        $project->delete();
        $this->dispatch('refresh');
    }
}
