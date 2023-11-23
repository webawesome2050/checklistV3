<?php

namespace App\Providers;

use App\Filament\Resources\ChecklistsResource;
use App\Filament\Resources\EntriesMasterResource;
use App\Filament\Resources\RoleResource;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // date_default_timezone_set('Australia/Sydney');
        // Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
        //     return $builder->items([
        //         NavigationItem::make('Dashboard')
        //             ->icon('heroicon-o-home')
        //             ->activeIcon('heroicon-s-home')
        //             ->isActiveWhen(fn (): bool => request()->routeIs('filament.pages.dashboard'))
        //             ->url(route('filament.pages.dashboard')),
        //         ...ChecklistsResource::getNavigationItems(),
        //         ...EntriesMasterResource::getNavigationItems(),
        //         ...RoleResource::getNavigationItems(),

        //     ]);
        // });

        // Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
        //     return $builder
        //     ->groups([
        //         NavigationGroup::make('admin')
        //         ->items([
        //             NavigationItem::make('Dashboard')
        //                 ->icon('heroicon-o-home')
        //                 ->activeIcon('heroicon-s-home')
        //                 ->isActiveWhen(fn (): bool => request()->routeIs('filament.pages.dashboard'))
        //                 ->url(route('filament.pages.dashboard')),
        //                      ...ChecklistsResource::getNavigationItems(),
        //                     ...EntriesMasterResource::getNavigationItems(),
        //                     ...RoleResource::getNavigationItems(),
        //         ]),
        //     ]);
        // });

    }
}
