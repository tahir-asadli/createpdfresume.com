<?php

namespace App\Livewire;

use App\Models\Block;
use App\Models\Hobby;
use App\Models\Resume;
use App\Models\Section;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class BuilderWidgets extends Component
{

  public $page = 1;

  public Resume $resume;

  // public function next()
  // {
  //   if ($this->page < 3) {
  //     $this->page++;
  //   }
  // }
  // public function prev()
  // {
  //   if ($this->page > 0) {
  //     $this->page--;
  //   }
  // }

  public function moved($items)
  {
    collect($items)->recursive()->each(function ($section) {
      $order = $section->get('items')->pluck('value')->toArray();
      $sectionName = $section->get('value');
      $blocks = Block::find($order)->where(
        'section',
        '!=',
        $sectionName
      )->each->update(['section' => $sectionName]);

      Block::setNewOrder($order, 1, 'id');

      Block::setNewOrder($order, 1, 'id', function (Builder $query) {
        $query->where('resume_page', $this->page);
        //     $query->where('user_id', auth()->id());
      });
    });
    $this->dispatch('refresh');
  }
  public function sorted(array $items)
  {
    $order = collect($items)->pluck('value')->toArray();
    // Block::setNewOrder($order, 1, 'id');
    Block::setNewOrder($order, 1, 'id', function (Builder $query) {
      $query->where('resume_page', $this->page);
      // $query->whereBelongsTo(auth()->user());
    });
    $this->dispatch('refresh');
  }


  public function delete(Block $block)
  {
    Gate::authorize('delete', $block);
    $block->delete();
    $this->dispatch('closeModal');
    $this->dispatch('refresh');
  }

  public function toggleVisibility(Block $block)
  {
    Gate::authorize('delete', $block);
    $block->active = !$block->active;
    $block->save();
    $this->dispatch('closeModal');
    $this->dispatch('refresh');
  }

  public function duplicate(Block $block)
  {
    Gate::authorize('delete', $block);
    $newBlock = $block->replicate();
    $newBlock->save();
    $this->dispatch('closeModal');
    $this->dispatch('refresh');
  }


  #[On('refresh')]
  public function render()
  {
    return view('livewire.builder-widget-blocks');
  }
}
