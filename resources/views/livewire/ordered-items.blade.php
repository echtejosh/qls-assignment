<div class="space-y-4">
    {{-- Scrollable container for items --}}
    <div class="h-[270px] overflow-y-auto space-y-4 border border-gray-300 rounded-md p-3 bg-gray-50">

        @foreach ($items as $index => $item)
            <div class="grid grid-cols-[1fr_1fr_1fr_1fr_auto] gap-3 items-end bg-white p-3 rounded">

                <div>
                    <label class="block text-xs font-semibold text-gray-600">Aantal</label>
                    <input type="text" wire:model="items.{{ $index }}.amount_ordered"
                           name="items[{{ $index }}][amount_ordered]"
                           class="w-full border border-gray-300 rounded p-1 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600">Naam</label>
                    <input type="text" wire:model="items.{{ $index }}.name"
                           name="items[{{ $index }}][name]"
                           class="w-full border border-gray-300 rounded p-1 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600">SKU</label>
                    <input type="text" wire:model="items.{{ $index }}.sku"
                           name="items[{{ $index }}][sku]"
                           class="w-full border border-gray-300 rounded p-1 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600">EAN</label>
                    <input type="text" wire:model="items.{{ $index }}.ean"
                           name="items[{{ $index }}][ean]"
                           class="w-full border border-gray-300 rounded p-1 text-sm">
                </div>

                <div class="flex items-end justify-center">
                    <button type="button"
                            wire:click="remove({{ $index }})"
                            class="text-red-600 hover:text-red-800 p-1 rounded focus:outline-none"
                            aria-label="Delete item">
                        <i class='bx bx-trash text-lg'></i>
                    </button>
                </div>

            </div>
        @endforeach

        @if (empty($items))
            <p class="text-sm text-gray-500">Geen producten om te weergeven.</p>
        @endif
    </div>

    {{-- Add item button --}}
    <button type="button"
            wire:click="add"
            class="mt-2 px-4 py-2 text-blue-600 border border-blue-600 rounded hover:bg-blue-600 hover:text-white transition">
        Product toevoegen
    </button>
</div>
