<?php
namespace App\Services;

class JwtSecretResolver
{
    public function resolve()
    {
        // Your logic to dynamically retrieve the JWT secret key
        return env('JWT_SECRET_APP1');
    }
}