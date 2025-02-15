<x-filament-panels::page>
    <div class="grid grid-cols-3 gap-4">
        <x-filament::section class="col-span-3">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4"> {{ __("Cover Image") }}</h2>

            <form wire:submit.prevent="update">
                {{ $this->form }}

                <div class="flex items-center justify-end">
                    <x-filament::button color="success" type="submit" form="update" size="lg" class="mt-4">
                        <span>{{ __("Update") }}</span>
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>
    </div>
</x-filament-panels::page>
