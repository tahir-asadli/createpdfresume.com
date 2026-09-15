<?php

namespace App\Livewire;

use App\Livewire\Forms\BlockSettingsForm;
use App\Models\Block;
use App\Models\Template;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use LivewireUI\Modal\ModalComponent;

class BlockSettings extends ModalComponent
{
  public Block $block;
  public Template $template;

  public BlockSettingsForm $form;

  public $dateExample;
  public static function behavior(): array
  {

    return [
      'trap-focus' => false,
    ];

  }
  // #[On('refresh')]
  public function render()
  {
    return view('livewire.block-settings');
  }


  public function mount()
  {

    $this->dateExample = Carbon::now();

    $this->form->title = $this->block->title;
    $this->form->from = $this->block->from;
    $this->form->to = $this->block->to;
    $this->form->active = $this->block->active;
    $this->form->height = $this->block->height;
    $this->form->bold = $this->block->bold;
    $this->form->italic = $this->block->italic;
    $this->form->underline = $this->block->underline;
    $this->form->strikethrough = $this->block->strikethrough;
    $this->form->color = $this->block->color;
    $this->form->radius = $this->block->radius;
    $this->form->filter = $this->block->filter;
    $this->form->align = $this->block->align;
    $this->form->uppercase = $this->block->uppercase;
    $this->form->dateformat = $this->block->dateformat;
    $this->form->blendmode = $this->block->blend;
  }

  public function submit()
  {
    $this->form->update($this->block);
    $this->dispatch('refresh');
    $this->dispatch('closeModal');
  }

  public function setDateFormat($format)
  {
    $this->form->dateformat = $format;
  }

  public function toggleBold()
  {
    $this->form->bold = !$this->form->bold;
  }
  public function toggleItalic()
  {
    $this->form->italic = !$this->form->italic;
  }
  public function toggleUnderline()
  {
    $this->form->underline = !$this->form->underline;
    if ($this->form->underline) {
      $this->form->strikethrough = false;
    }
  }
  public function toggleStrikethrough()
  {
    $this->form->strikethrough = !$this->form->strikethrough;
    if ($this->form->strikethrough) {
      $this->form->underline = false;
    }
  }
  public function toggleUppercase()
  {
    $this->form->uppercase = !$this->form->uppercase;
    if ($this->form->uppercase) {
      $this->form->underline = false;
    }
  }

  public function hasTransparentImage()
  {
    return $this->block->resume->transparentImageExists();
  }

  public function toggleTransparent()
  {
    if ($this->block->resume->user->profile->transparent) {
      $this->block->resume->user->profile->transparent = false;
      $this->block->resume->user->profile->save();
    } else {
      $this->block->resume->user->profile->transparent = true;
      $this->block->resume->user->profile->save();
    }
    $this->dispatch('refresh');
  }

  public function transparentEnabled()
  {
    return $this->block->resume->user->profile->transparent;
  }

  public function toggleCircle()
  {
    $this->form->radius = '9999';
  }
  public function toggleRounded()
  {
    $this->form->radius = '5';
  }
  public function toggleRectangle()
  {
    $this->form->radius = '0';
  }
  public function setFilter($filter)
  {
    $this->form->filter = $filter ? $filter : null;
  }
  public function setAlignment($align)
  {
    if ($this->form->align == $align) {
      $this->form->align = null;
    } else {
      $this->form->align = $align ? $align : null;
    }
  }

  public function setBlendMode($blendMode)
  {
    $this->form->blendmode = $blendMode;
  }

  public function blendModes()
  {
    return [
      [
        'normal',
        'multiply',
        'screen',
      ],
      [
        'overlay',
        'darken',
        'lighten',
      ],
      [
        'color-burn',
        'saturation',
        'luminosity'
      ],
    ];
  }
  // public function delete(Block $block)
  // {
  //   Gate::authorize('delete', $block);
  //   $block->delete();
  //   $this->block = Block::make();
  //   $this->dispatch('closeModal');
  //   $this->dispatch('refresh');
  // }
}
