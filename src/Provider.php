<?php

namespace Akaunting\Money;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class Provider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/money.php' => config_path('money.php'),
        ], 'money');

        Money::setLocale($this->app->make('translator')->getLocale());
        Currency::setCurrencies($this->app->make('config')->get('money'));

        $this->registerBladeDirectives();
        $this->registerBladeComponents();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/money.php', 'money');

        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'money');
    }

    public function registerBladeDirectives()
    {
        // Register blade directives
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('money', function ($expression) {
                return "<?php echo money($expression); ?>";
            });
        });

        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('currency', function ($expression) {
                return "<?php echo currency($expression); ?>";
            });
        });
    }

    public function registerBladeComponents()
    {
        Blade::component('money', \Akaunting\Money\View\Components\Money::class);
        Blade::component('currency', \Akaunting\Money\View\Components\Currency::class);
    }
}
