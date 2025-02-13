<x-filament-panels::page>
    <div class="grid grid-cols-3 gap-4">
        <x-filament::section class="col-span-3">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4"> {{ __("Team Info") }}</h2>

            <form wire:submit.prevent="update" id="form">
                {{ $this->form }}

                <div class="flex items-center justify-end">
                    <x-filament::button color="success" type="submit" size="lg" class="mt-4">
                        {{ __("Update") }}
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        <div class="col-span-3 my-4">
            <span class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-gray-400">
                {{ __("Relative Informations") }}
            </span>
        </div>

        <div class="col-span-3">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4"> {{ __("Team members") }}</h2>

            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
