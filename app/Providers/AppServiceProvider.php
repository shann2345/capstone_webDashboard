<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    // public const HOME = '/home'; // Or whatever your home route is

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // This maps your API routes
            Route::prefix('api')
                ->middleware('api')
                // ->namespace($this->namespace) // This might be removed in newer Laravel versions, but often present
                ->group(base_path('routes/api.php'));

            // This maps your web routes
            Route::middleware('web')
                // ->namespace($this->namespace) // This might be removed in newer Laravel versions, but often present
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    // In older Laravel versions, you might see mapWebRoutes() and mapApiRoutes() as separate methods
    // called within the boot() method. The 'routes' closure is more common in recent versions.
    // Ensure whichever structure you have, it's correctly loading the files.
}
