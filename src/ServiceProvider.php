<?php
declare(strict_types=1);

namespace SevenLinX\Validation;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use SevenLinX\Validation\Contracts\ValidationContract;

final class ServiceProvider extends IlluminateServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(ValidationContract::class, function ($app) {
            return new Validation($app['validator']);
        });
    }

    public function provides(): array
    {
        return [
            ValidationContract::class,
        ];
    }
}