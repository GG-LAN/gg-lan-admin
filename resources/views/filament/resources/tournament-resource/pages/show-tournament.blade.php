<x-filament-panels::page>
    <div class="grid grid-cols-3 gap-4">
        <x-filament::section class="col-span-2">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4"> {{ __("Tournament Info") }}</h2>

            <form wire:submit.prevent="updateInfos" id="formInfos">
                {{ $this->formInfos }}

                <div class="flex items-center justify-end">
                    <x-filament::button color="success" type="submit" size="lg" class="mt-4">
                        {{ __("Update") }}
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        <x-filament::section class="col-span-1">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4"> {{ __("Tournament Dates") }}</h2>

            <form wire:submit.prevent="updateDates" id="formDates">
                {{ $this->formDates }}

                <div class="flex items-center justify-end">
                    <x-filament::button color="success" type="submit" size="lg" class="mt-4">
                        {{ __("Update") }}
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>
    </div>
</x-filament-panels::page>
