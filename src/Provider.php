<?php

namespace Akaunting\Money;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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

    protected function registerBladeDirectives(): self
    {
        if (! $this->app->has('blade.compiler')) {
            return $this;
        }

        Blade::directive('money', function ($expression) {
            return "<?php money($expression); ?>";
        });

        Blade::directive('currency', function ($expression) {
            return "<?php currency($expression); ?>";
        });

        return $this;
    }

    public function registerBladeComponents()
    {
        Blade::component('money', \Akaunting\Money\View\Components\Money::class);
        Blade::component('currency', \Akaunting\Money\View\Components\Currency::class);
    }
}
