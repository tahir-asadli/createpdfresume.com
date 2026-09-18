<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateResumeForm;
use App\Models\Template;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class AddResume extends ModalComponent
{

    public $error = '';

    public CreateResumeForm $form;
    public $templateUuid;

    public function mount()
    {
        if ($this->templateUuid) {
            $template = Template::where('uuid', $this->templateUuid)->first();
            if ($template && $template->available()) {
                $this->form->uuid = $this->templateUuid;
            }
        }
    }

    public function render()
    {
        return view('livewire.resumes.add');
    }

    public function submit()
    {
        $this->error = '';
        $resumeLimit = auth()->user()->PDFResumeLimit();
        $availableResumeCount = count(auth()->user()->availableResumes());
        if ($availableResumeCount > ($resumeLimit - 1)) {
            $this->error = __('You\'ve reached your resume limit');
            return;
        }

        $resume = $this->form->save();

        $this->dispatch('refresh');
        $this->dispatch('closeModal');
        if ($resume) {
            redirect(route('new-builder', [$resume->uuid, 1]));
        }
    }
}
