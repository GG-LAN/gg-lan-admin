<?php
namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use Croustibat\FilamentJobsMonitor\FilamentJobsMonitorPlugin;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentLaravelLog\FilamentLaravelLogPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->profile(isSimple: true)
            ->login(Login::class)
            ->revealablePasswords(false)
            ->favicon("/favicon.png")
            ->brandLogo("/logo.png")
            ->viteTheme("resources/css/filament/gg-lan/theme.css")
            ->maxContentWidth(MaxWidth::Full)
            ->simplePageMaxContentWidth(MaxWidth::ExtraLarge)
            ->font('Inter')
            ->discoverResources(app_path('Filament/Resources'), 'App\\Filament\\Resources')
            ->discoverPages(app_path('Filament/Pages'), 'App\\Filament\\Pages')
            ->pages([])
            ->discoverWidgets(app_path('Filament/Widgets'), 'App\\Filament\\Widgets')
            ->widgets([])
            ->discoverClusters(app_path('Filament/Clusters'), 'App\\Filament\\Clusters')
            ->plugins([
                FilamentJobsMonitorPlugin::make()
                    ->navigationGroup(__("Monitoring"))
                    ->navigationIcon("fas-microchip"),
                FilamentLaravelLogPlugin::make()
                    ->navigationGroup(__("Monitoring"))
                    ->navigationIcon("fas-file-pen"),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseTransactions()
            ->spa()
            ->spaUrlExceptions(fn(): array=> [
                url("/logs"),
                url("/settings/location"),
            ]);
    }

    public function boot(): void
    {
        Filament::registerNavigationGroups([
            __("Players"),
            __("Tournaments"),
            __("Settings"),
        ]);

        FilamentIcon::register([
            "tables::header-cell.sort-button"          => "fas-sort",
            "tables::header-cell.sort-asc-button"      => "fas-sort-up",
            "tables::header-cell.sort-desc-button"     => "fas-sort-down",
            "tables::actions.filter"                   => "fas-filter",
            "tables::actions.toggle-columns"           => "fas-table-columns",
            "tables::search-field"                     => "fas-magnifying-glass",
            "tables::filters.remove-all-button"        => "fas-xmark",
            "tables::empty-state"                      => "fas-xmark",
            "tables::filters.remove-all-button"        => "fas-xmark",

            "forms::components.builder.actions.delete" => "fas-trash-can",

            "actions::delete-action"                   => "fas-trash-can",

            "notifications::notification.close-button" => "fas-xmark",
            "notifications::notification.danger"       => "fas-circle-xmark",
            "notifications::notification.info"         => "fas-circle-info",
            "notifications::notification.success"      => "fas-circle-check",
            "notifications::notification.warning"      => "fas-circle-exclamation",

            "modal.close-button"                       => "fas-xmark",
            "badge.delete-button"                      => "fas-xmark",
        ]);

        FilamentColor::register([
            "primary" => Color::hex('#ff0000'),
            "success" => [
                50  => "#F3FAF7",
                100 => "#DEF7EC",
                200 => "#BCF0DA",
                300 => "#84E1BC",
                400 => "#31C48D",
                500 => "#0E9F6E",
                600 => "#057A55",
                700 => "#046C4E",
                800 => "#03543F",
                900 => "#014737",
                950 => "#014737",
            ],
            "info"    => Color::hex("#3b82f6"),
            "warning" => Color::hex("#C27803"),
            "danger"  => Color::Red,
            "primary" => Color::hex('#ff0000'),

        ]);

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_NAV_END,
            fn(): View => view('doc-api-link'),
        );

        Page::formActionsAlignment(Alignment::Start);
    }
}
