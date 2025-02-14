<x-filament-panels::page>
    <form wire:submit.prevent="update">
        {{ $this->form }}

        <div class="flex items-center justify-end">
            <x-filament::button color="success" type="submit" size="lg" class="mt-4">
                {{ __("Update") }}
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
