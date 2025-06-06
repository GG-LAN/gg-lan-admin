<?php
namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use Croustibat\FilamentJobsMonitor\FilamentJobsMonitorPlugin;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Facades\FilamentIcon;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentLaravelLog\FilamentLaravelLogPlugin;

class AdminPanelProvider extends PanelProvider
{
    private array $colors = [
        "gray"    => [
            50  => "#F9FAFB",
            100 => "#F3F4F6",
            200 => "#E5E7EB",
            300 => "#D1D5DB",
            400 => "#9CA3AF",
            500 => "#6B7280",
            600 => "#4B5563",
            700 => "#374151",
            800 => "#1F2937",
            900 => "#111827",
            950 => "#111827",
        ],
        "danger"  => [
            50  => "#FDF2F2",
            100 => "#FDE8E8",
            200 => "#FBD5D5",
            300 => "#F8B4B4",
            400 => "#F98080",
            500 => "#F05252",
            600 => "#E02424",
            700 => "#C81E1E",
            800 => "#9B1C1C",
            900 => "#771D1D",
            950 => "#771D1D",
        ],
        "warning" => [
            50  => "#FDFDEA",
            100 => "#FDF6B2",
            200 => "#FCE96A",
            300 => "#FACA15",
            400 => "#E3A008",
            500 => "#C27803",
            600 => "#9F580A",
            700 => "#8E4B10",
            800 => "#723B13",
            900 => "#633112",
            950 => "#633112",
        ],
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
        "primary" => [
            50  => "#EBF5FF",
            100 => "#E1EFFE",
            200 => "#C3DDFD",
            300 => "#A4CAFE",
            400 => "#76A9FA",
            500 => "#3F83F8",
            600 => "#1C64F2",
            700 => "#1A56DB",
            800 => "#1E429F",
            900 => "#233876",
            950 => "#233876",
        ],
        "indigo"  => [
            50  => "#F0F5FF",
            100 => "#E5EDFF",
            200 => "#CDDBFE",
            300 => "#B4C6FC",
            400 => "#8DA2FB",
            500 => "#6875F5",
            600 => "#5850EC",
            700 => "#5145CD",
            800 => "#42389D",
            900 => "#362F78",
            950 => "#362F78",
        ],
        "violet"  => [
            50  => "#F6F5FF",
            100 => "#EDEBFE",
            200 => "#DCD7FE",
            300 => "#CABFFD",
            400 => "#AC94FA",
            500 => "#9061F9",
            600 => "#7E3AF2",
            700 => "#6C2BD9",
            800 => "#5521B5",
            900 => "#4A1D96",
            950 => "#4A1D96",
        ],
        "pink"    => [
            50  => "#FDF2F8",
            100 => "#FCE8F3",
            200 => "#FAD1E8",
            300 => "#F8B4D9",
            400 => "#F17EB8",
            500 => "#E74694",
            600 => "#D61F69",
            700 => "#BF125D",
            800 => "#99154B",
            900 => "#751A3D",
            950 => "#751A3D",
        ],
    ];

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
            ->colors($this->colors)
            ->viteTheme("resources/css/filament/admin/theme.css")
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
    }
}
