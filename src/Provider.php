<?php

namespace Akaunting\Money;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Support\Facades\Validator;

class Provider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/money.php', 'money');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'money');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/money.php' => config_path('money.php'),
        ], 'money');

        Money::setLocale($this->app->make('translator')->getLocale());

        /** @var array|null */
        $currencies = $this->app->make('config')->get('money.currencies');

        Currency::setCurrencies($currencies ?? []);

        $this->registerValidationRules();
        $this->registerBladeDirectives();
        $this->registerBladeComponents();
    }

    public function registerValidationRules(): void
    {
        $currency_code = null;

        Validator::extend('currency_code', function ($attribute, $value, $parameters, $validator) use(&$currency_code) {
            $status = false;

            $currency_code = $value;

            $currencies = config('money.currencies');

            if (array_key_exists($value, $currencies)) {
                $status = true;
            }

            return $status;
        },
            trans('validation.custom.invalid_currency', ['attribute' => $currency_code])
        );
    }

    public function registerBladeDirectives(): void
    {
        $this->callAfterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('money', function (?string $expression) {
                return "<?php echo money($expression); ?>";
            });

            $bladeCompiler->directive('currency', function (?string $expression) {
                return "<?php echo currency($expression); ?>";
            });
        });
    }

    public function registerBladeComponents(): void
    {
        Blade::component('money', \Akaunting\Money\View\Components\Money::class);
        Blade::component('currency', \Akaunting\Money\View\Components\Currency::class);
    }
}
