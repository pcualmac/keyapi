<?php

namespace App\Http\Controllers\AppTwo;

use App\Http\Controllers\Controller;
use App\Models\AppTwo\UserAppTwo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AppTwoAuthController extends Controller
{
    static $applicationContext = 'application2';
    private $secretKey;

    public function __construct()
    {
        // Get the secret key for the application context from configuration
        $this->secretKey = Config::get('app_tokens.' . self::$applicationContext);
        // Set the secret key for JWT authentication
        JWTAuth::getJWTProvider()->setSecret($this->secretKey);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }
}
