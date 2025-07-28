<?php

namespace App\Livewire;

use App\Services\QlsService;
use Livewire\Component;

class OrderedItems extends Component
{
    public array $items = [];

    public function mount(array $items = []): void
    {
        $this->items = $items;
    }

    public function add(): void
    {
        $this->items[] = [
            'amount_ordered' => null,
            'name' => null,
            'sku' => null,
            'ean' => null,
        ];
    }

    public function remove(int $index): void
    {
        unset($this->items[$index]);

        $this->items = array_values($this->items);
    }

    public function render()
    {
        return view('livewire.ordered-items');
    }
}
