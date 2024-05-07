<?php

namespace App;

use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Token;
use Tymon\JWTAuth\Exceptions\JWTException;

class CustomJWTAuth extends JWT
{
    private $applicationkey;

    protected $applicationKeys = [
        'application1' => Config::get('app_tokens.application1'),
        'application2' => Config::get('app_tokens.application2')
        // Add more applications and keys as needed
    ];

    public function encode(Token $token)
    {
        $application = $this->getApplication();
        $key = $this->applicationKeys[$application] ?? null;

        if (!$key) {
            throw new JWTException('Private key not found for application');
        }

        $this->setSecretKey($key);

        return parent::encode($token);
    }

    public function decode($token)
    {
        $application = $this->getApplication();
        $key = $this->applicationKeys[$application] ?? null;

        if (!$key) {
            throw new JWTException('Private key not found for application');
        }

        $this->setSecretKey($key);

        return parent::decode($token);
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function setApplication($application)
    {
        $this->application = $application;
    }
}