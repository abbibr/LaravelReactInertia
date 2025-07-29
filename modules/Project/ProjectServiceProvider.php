<?php

namespace Modules\Project;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ProjectServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // this boot method is for: policy & gate, event listeners, custom blades

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/Views', 'project');
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

        // $this->mapWebRoutes();
    }

    /* protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->group(__DIR__ . '/Routes/web.php');
    } */
}
