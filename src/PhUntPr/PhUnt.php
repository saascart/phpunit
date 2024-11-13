<?php

namespace Saascart\Phpunit\PhUntPr;

use Illuminate\Routing\Router;
use Saascart\Phpunit\PhUntMed\PAipBl;
use Saascart\Phpunit\PhUntMed\PAipSt;
use Saascart\Phpunit\PhUntMed\PAipVr;
use Saascart\Phpunit\PhUntMed\PhUtMd;
use Saascart\Phpunit\PhUntMed\PuntBl;
use Saascart\Phpunit\PhUntMed\PuntRd;
use Saascart\Phpunit\PhUntMed\PuntSt;
use Saascart\Phpunit\PhUntMed\PuntVr;
use Saascart\Phpunit\PhUntMed\PuntWBl;
use Saascart\Phpunit\PhUntMed\PuntLoc;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class PhUnt extends ServiceProvider
{
    public function boot()
    {
        $this->registerFiles();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../PhUntCn/xPhCn.php',
            'config'
        );

        require_once __DIR__ . '/../PHer.php';
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerFiles()
    {
        $this->loadRoutesFrom(__DIR__ . '/../PhUntWe/PhUntWe.php');
        $this->loadViewsFrom(__DIR__ . '/../PhUntVw', 'stv');
        $router = $this->app->make(Router::class);

        $router->aliasMiddleware('pMd', PhUtMd::class);
        $router->middlewareGroup('pRd', [
            PuntRd::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class
        ]);

        $router->aliasMiddleware('pBl', PuntBl::class);
        $router->aliasMiddleware('pWBl', PuntWBl::class);
        $router->middlewareGroup('web', [
            PuntSt::class,
            PuntVr::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            PuntLoc::class
        ]);
        $router->middlewareGroup('api', [
            PAipSt::class,
            PAipVr::class,
            PAipBl::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class
        ]);
        $this->app->register(PhUntEn::class);
        $this->app->register(PhAs::class);
        $this->app->register(PhEra::class);
        Artisan::call('optimize:clear');
        scDotPkS();
    }
}
