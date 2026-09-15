<?php

namespace App\Livewire;

use App\Enums\Section;
use App\Models\Block;
use App\Models\Resume;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class AddWidget extends ModalComponent
{
    public Section $section;
    public Resume $resume;
    public int $page;
    public $sectionSlug;

    public function add($id)
    {
        Block::create([
            'widget_id' => $id,
            'resume_id' => $this->resume->id,
            'resume_page' => $this->page,
            'section' => $this->section,
            'active' => true,
        ]);
        $this->dispatch('refresh');
        $this->dispatch('closeModal');
    }
    public function render()
    {
        return view('livewire.add-widget');
    }
}
