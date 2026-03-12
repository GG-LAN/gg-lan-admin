<?php
namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use App\Models\Tournament;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class TournamentPage extends Page
{
    protected static string $resource = TournamentResource::class;

    public Tournament $record;

    public function getTitle(): string | Htmlable
    {
        return $this->record->name;
    }

    public function getSubNavigationParameters(): array
    {
        return [
            'record' => $this->record,
        ];
    }

    public function getSubNavigation(): array
    {
        return static::getResource()::getRecordSubNavigation($this);
    }

    public function getSubheading(): string | Htmlable | null
    {
        $statusLabel = __(Str::ucfirst($this->record->status));

        switch ($this->record->status) {
            case 'open':
                return new HtmlString("
                    <div class='flex w-max'>
                        <span
                            style='--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);'
                            class='fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-success'
                        >
                            <span class='grid'>
                                <span class='truncate'>
                                    {$statusLabel}
                                </span>
                            </span>
                        </span>
                    </div>
                ");
                break;

            case 'closed':
                return new HtmlString("
                    <div class='flex w-max'>
                        <span
                            style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);'
                            class='fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-danger'
                        >
                            <span class='grid'>
                                <span class='truncate'>
                                    {$statusLabel}
                                </span>
                            </span>
                        </span>
                    </div>
                ");
                break;

            case 'finished':
                return new HtmlString("
                    <div class='flex w-max'>
                        <span
                            style='--c-50:var(--warning-50);--c-400:var(--warning-400);--c-600:var(--warning-600);'
                            class='fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-warning'
                        >
                            <span class='grid'>
                                <span class='truncate'>
                                    {$statusLabel}
                                </span>
                            </span>
                        </span>
                    </div>
                ");
                break;

            default:
                return null;
                break;
        }
    }
}
