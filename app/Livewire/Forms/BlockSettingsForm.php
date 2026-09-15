<?php

namespace App\Livewire\Forms;

use App\Models\Block;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BlockSettingsForm extends Form
{
    #[Validate('max:1024')]
    public $title = '';
    #[Validate('numeric|nullable|max:100')]
    public $from = 1;
    #[Validate('numeric|nullable|max:100')]
    public $to = 100;
    #[Validate('required|boolean')]
    public $active = false;
    #[Validate('numeric|nullable|max:100')]
    public $height = 1;
    #[Validate('required|boolean')]
    public $bold = false;
    #[Validate('required|boolean')]
    public $italic = false;
    #[Validate('required|boolean')]
    public $underline = false;
    #[Validate('required|boolean')]
    public $strikethrough = false;
    #[Validate('nullable|hex_color|max:64')]
    public $color = null;
    #[Validate('numeric|nullable|max:9999')]
    public $radius = null;
    #[Validate('nullable|max:64')]
    public $filter = null;
    #[Validate('nullable|max:64')]
    public $align = null;
    #[Validate('required|boolean')]
    public $uppercase = false;
    #[Validate('nullable|max:64')]
    public $blendmode = null;

    public $dateformat = 'year';


    public function update(Block $block)
    {
        $this->validate();
        $block->update([
            'title' => cl($this->title),
            'from' => $this->from ? cl($this->from) : null,
            'to' => $this->to ? cl($this->to) : null,
            'height' => $this->height ? cl($this->height) : 0,
            'active' => $this->active ? true : false,
            'bold' => $this->bold ? true : false,
            'italic' => $this->italic ? true : false,
            'underline' => $this->underline ? true : false,
            'uppercase' => $this->uppercase ? true : false,
            'strikethrough' => $this->strikethrough ? true : false,
            'color' => $this->color ? cl($this->color) : null,
            'align' => $this->align ? cl($this->align) : null,
            'radius' => $this->radius ? cl($this->radius) : null,
            'filter' => $this->filter ? cl($this->filter) : null,
            'blend' => $this->blendmode ? cl($this->blendmode) : 'normal',
            'dateformat' => in_array($this->dateformat, ['year', 'month', 'day', 'monthname', 'daymonthname']) ? $this->dateformat : null,
        ]);
    }
}
