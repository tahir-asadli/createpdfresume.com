<?php

namespace App\Livewire\Forms;

use App\Models\Resume;
use App\Models\Template;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;
use Livewire\Form;

class EditResumeForm extends Form
{
    // #[Validate('required|max:64', 'Template')]
    // public string $uuid;

    #[Validate('required|max:32', 'Resume name')]
    public string $name;

    public function update(Resume $resume)
    {
        $templateUuids = Template::availableTemplates()->pluck('uuid')->toArray();
        $this->validate(rules: [
            'name' => 'required|max:32',
            // 'uuid' => "required|max:64|" . Rule::in($templateUuids),
        ], attributes: [
            // 'uuid' => __('Template'),
            'name' => __('Resume name'),
        ]);
        // $template = Template::where('uuid', $this->uuid)->first();
        $resume->update([
            'name' => cl($this->name),
            // 'template_id' => $template->id,
        ]);
    }
}
