<x-filament-panels::page>
    <div class="grid grid-cols-3 gap-4">
        <div class="col-span-3">
            @livewire("list-tournament-prices", ["tournament" => $this->record])
        </div>

        <div class="col-span-3">
            <hr class="border-gray-400 dark:border-white/5">
        </div>

        <div class="col-span-3">
            @livewire("list-tournament-payments", ["tournament" => $this->record])
        </div>
    </div>
</x-filament-panels::page>
