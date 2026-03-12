<x-filament-panels::page>
    <div class="grid grid-cols-3 gap-4">
        <x-filament::section class="col-span-3">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4"> {{ __("Faceit Account") }}</h2>

            <form wire:submit.prevent="update">
                {{ $this->form }}

                {{-- <iframe src="https://faceit-widget.pages.dev/widget?nickname={{ optional($this->record->faceitAccount)->nickname }}&show-kd=true&show-ranking=true" class="mt-4 " allowTransparency="true" style="color-scheme: auto">
                </iframe> --}}

                <div class="flex items-center justify-end">
                    <x-filament::button color="success" type="submit" size="lg">
                        {{ __("Update") }}
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>
    </div>
</x-filament-panels::page>
