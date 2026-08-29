<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Register;
use App\Filament\Pages\Dashboard as UserDashboard;
use App\Filament\Pages\MerchantStatisticsDashboard;
use App\Filament\Pages\SystemFeedbackPage;
use App\Filament\Pages\Tenancy\EditTeamProfile;
use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Filament\Resources\Accounts\Pages\ManageAccountsTree;
use App\Http\Middleware\ApplyTenantScopes;
use App\Models\Team;
use Filament\Enums\DatabaseNotificationsPosition;
use Filament\FontProviders\LocalFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Facades\Filament;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            // ->profile()
            ->registration(Register::class)
            ->emailVerification()
            ->passwordReset()
            ->tenant(Team::class, slugAttribute: 'slug')
            ->tenantRegistration(RegisterTeam::class)
            ->tenantProfile(EditTeamProfile::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css', 'build/filament')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                UserDashboard::class,
                ManageAccountsTree::class,
                SystemFeedbackPage::class,
            ])
            ->databaseNotifications(position: DatabaseNotificationsPosition::Topbar)
            ->databaseNotificationsPolling('30s')
            ->homeUrl(function (): string {
                $user = Auth::user();
                $tenant = self::resolvePanelTenant();

                if (! $tenant) {
                    return url('/admin');
                }

                if ($user?->isMerchant()) {
                    return MerchantStatisticsDashboard::getUrl(tenant: $tenant);
                }

                return UserDashboard::getUrl(tenant: $tenant);
            })
            ->maxContentWidth(Width::Full)
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
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
            ->maxContentWidth(Width::Full)
            ->sidebarFullyCollapsibleOnDesktop()
            ->authMiddleware([
                Authenticate::class,
            ])
            ->font(
                'JannaLT',
                url: asset('css/janna-font.css'),
                provider: LocalFontProvider::class
            )
            ->tenantMiddleware([
                ApplyTenantScopes::class,
            ], isPersistent: true)
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): string => \Illuminate\Support\Facades\Blade::render('<link rel="manifest" href="/site.webmanifest" /> <meta name="theme-color" content="#ffffff" />')
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::BODY_END,
                fn (): string => \Illuminate\Support\Facades\Blade::render("<script>if ('serviceWorker' in navigator) { window.addEventListener('load', () => { navigator.serviceWorker.register('/sw.js'); }); }</script>" . view('components.pwa-install-prompt')->render())
            )
            ->tenantMenuItems([
                'register' => fn ($action) => Auth::user()?->isMerchant()
                    ? $action->label('تسجيل فرع جديد')
                    : null,
            ])
            ->userMenuItems([
                \Filament\Navigation\MenuItem::make()
                    ->label('اقتراحات أو تبليغ عن أخطاء')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->url(fn (): string => self::feedbackPageUrl())
                    ->visible(fn (): bool => self::resolvePanelTenant() instanceof Team),
            ])
            ->plugins([
                FilamentApexChartsPlugin::make(),
            ])
            ->spa(hasPrefetching: true);
    }

    protected static function resolvePanelTenant(): ?Team
    {
        $tenant = Filament::getTenant();

        if ($tenant instanceof Team) {
            return $tenant;
        }

        $user = Auth::user();

        if (! $user) {
            return null;
        }

        return $user->getTenants(Filament::getCurrentPanel())->first();
    }

    protected static function feedbackPageUrl(): string
    {
        $tenant = self::resolvePanelTenant();

        if (! $tenant instanceof Team) {
            return url('/admin');
        }

        return SystemFeedbackPage::getUrl(['tenant' => $tenant], tenant: $tenant);
    }
}
