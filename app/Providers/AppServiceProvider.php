<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Column;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // Disable guards
        Model::unguard();

        // Filament
        Filament::serving(fn() => $this->filament());
        Schema::defaultStringLength(191);
    }


    private function filament() : void
    {
        // Navigation group ordering
        //Filament::registerNavigationGroups([
        //    'Content',
        //    'Stammdaten',
        //]);

        // Add column show on all pages
        Column::configureUsing(function($column) {
            $column->toggleable()->sortable();
        });

        // Theme
        Filament::registerTheme(mix('mix/css/app.css'));

        // Show actions as icons
        Action::configureUsing(fn ($action) => $action->iconButton());

        // Add: livewire-ui-modal
        //Filament::registerRenderHook(
        //    'head.start',
        //    fn (): string => Blade::render('@wireUiScripts'), // livewire(\'livewire-ui-modal\')
        //);
    }
}
