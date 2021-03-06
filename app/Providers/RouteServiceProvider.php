<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        $idPattern = '[0-9]{1,18}';

        Route::pattern('id', $idPattern);
        Route::pattern('parentId', $idPattern);
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();

        $this->mapModeratorRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $namespace = 'App\Http\API\Controllers';
        Route::prefix('api')
            ->middleware('api')
            ->namespace($namespace)
            ->group(base_path('routes/api.php'));

        Route::prefix('api/admin')
            ->middleware('api')
            ->namespace($namespace. '\\Admin')
            ->group(base_path('routes/api_admin.php'));

        Route::middleware('api')
            ->prefix('api/moderator')
            ->namespace($namespace . '\\Moderator')
            ->group(base_path('routes/api_moderator.php'));
    }

    public function mapAdminRoutes()
    {
        Route::middleware('web')
            ->prefix('admin')
            ->namespace($this->namespace . '\Admin')
            ->group(base_path('routes/admin.php'));
    }

    public function mapModeratorRoutes()
    {
        Route::middleware('web')
            ->prefix('moderator')
            ->namespace($this->namespace . '\Moderator')
            ->group(base_path('routes/moderator.php'));
    }
}
