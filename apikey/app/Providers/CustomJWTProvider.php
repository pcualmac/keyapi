<?php
namespace App\Providers;

use Tymon\JWTAuth\Contracts\Providers\JWT as JWTContract;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class CustomJWTProvider implements JWTContract
{
    protected $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function encode(array $payload): string
    {
        try {
            return JWTAuth::encode($payload, $this->key)->get();
        } catch (JWTException $e) {
            // Handle encoding exception
            // For example: Log the error or throw a custom exception
        }
    }

    public function decode($token): array
    {
        try {
            return (array) JWTAuth::decode($token, $this->key);
        } catch (JWTException $e) {
            // Handle decoding exception
            // For example: Log the error or throw a custom exception
        }
    }
}