<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\JWTAuthServiceProvider as BaseJWTAuthServiceProvider;
use Tymon\JWTAuth\Factory as JWTFactory;

class JWTAuthServiceProvider extends BaseJWTAuthServiceProvider
{
    /**
     * Override the default JWTFactory with custom claims.
     *
     * @return JWTFactory
     */
    protected function getJWTFactory()
    {
        $factory = parent::getJWTFactory();

        // Add custom claims, such as 'prv'
        $factory->addClaim('prv', 'your_custom_value');

        return $factory;
    }
}