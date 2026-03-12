<x-filament-panels::page>
    <div class="grid grid-cols-3 gap-4">
        <div class="col-span-3">
            @if ($this->record->type == "team")
            @livewire("list-tournament-teams", ["tournament" => $this->record])
            @else
            @livewire("list-tournament-players", ["tournament" => $this->record])
            @endif
        </div>
    </div>
</x-filament-panels::page>
