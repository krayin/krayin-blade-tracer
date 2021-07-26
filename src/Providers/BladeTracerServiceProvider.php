<?php

namespace Webkul\BladeTracer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Webkul\BladeTracer\View\Compilers\BladeCompiler;
use Webkul\Core\ViewRenderEventManager;

class BladeTracerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'blade_tracer');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'blade_tracer');

        Event::listen('admin.layout.head', static function(ViewRenderEventManager $viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('blade_tracer::blade.tracer.style');
        });

        $this->publishes([
            dirname(__DIR__) . '/Config/blade_tracer.php' => config_path('blade_tracer.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBladeCompiler();
    }

    /**
     * Register the Blade compiler implementation.
     *
     * @return void
     */
    public function registerBladeCompiler()
    {
        $this->app->singleton('blade.compiler', function ($app) {
            return new BladeCompiler($app['files'], $app['config']['view.compiled']);
        });
    }
}