<?php

namespace Illuminate\View;

use Illuminate\View\Engines\PhpEngine;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\FileEngine;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Compilers\BladeCompiler;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFactory();

        $this->registerViewFinder();

        $this->registerEngineResolver();
    }

    /**
     * Register the view environment.
     *
     * @return void
     */
    public function registerFactory()
    {
        $this->app->singleton('view', function ($app) {
            // Next we need to grab the engine resolver instance that will be used by the
            // environment. The resolver will be used by an environment to get each of
            // the various engine implementations such as plain PHP or Blade engine.
            $resolver = $app['view.engine.resolver'];