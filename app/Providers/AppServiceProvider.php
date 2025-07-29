<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    // great modular design for medium size of applications
    public function register(): void
    {
        // $this->loadViewsFrom(base_path('Modules/Project/Views'), 'project');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // great modular design for medium size of applications  (manual)
        // foreach (glob(base_path('Modules/*/Routes/web.php')) as $routeFile) {
        //    Route::middleware('web')
        //        ->group($routeFile);
        // }


        // register all serviceproviers inside main appserviceprovider -> great for large applications
        $modules = glob(base_path('Modules/*'), GLOB_ONLYDIR);

        foreach ($modules as $module) {
            $moduleName = basename($module);
            $provider = "Modules\\{$moduleName}\\{$moduleName}ServiceProvider";

            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }
}
