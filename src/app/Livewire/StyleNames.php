<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Gate;

use App\Models\Resume;
use Livewire\Component;

class StyleNames extends Component
{

    public Resume $resume;

    public function render()
    {
        return view('livewire.style-names');
    }

    public function updateStyle($style)
    {
        Gate::authorize('update', $this->resume);
        if ($style == 'default') {
            $this->resume->style = 'default';
            $this->resume->save();
        }
        $styleInfo = config('site.styles', []);
        if (!empty($styleInfo[$style])) {
            $this->resume->style = $style;
            $this->resume->save();
        }
        $this->dispatch('refresh');
    }
}
