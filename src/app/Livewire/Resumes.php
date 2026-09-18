<?php

namespace App\Livewire;

use App\Models\Resume;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Resumes extends Component
{
    #[On('refresh')]
    public function render()
    {
        return view('livewire.resumes.index');
    }

    public function delete(Resume $resume)
    {
        Gate::authorize('delete', $resume);
        $resume->deleteFiles();
        $resume->delete();
        $this->dispatch('refresh');
    }
}
