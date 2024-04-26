<?php

namespace App\Http\Controllers\AppOne;

use App\Models\AppOne\AppOneAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AppOneAuthAdmin extends Controller
{

    static $applicationContext = 'application1';
    private $secretKey;

    public function __construct()
    {
        // Get the secret key for the application context from configuration
        $this->secretKey = Config::get('app_tokens.' . self::$applicationContext);
        // Set the secret key for JWT authentication
        JWTAuth::getJWTProvider()->setSecret($this->secretKey);
    }

    public function registerAdmin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $request->merge(['password' => Hash::make($request->password)]);
        $username = explode('@', $request->email)[0];
        $user = AppOneAdmin::create([
            'name' => $username,
            'username' => $username,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        return response()->json('success');
    }



    public function loginAdmin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            $token = auth()->guard('appadmin')->attempt($credentials, ['key' => $this->secretKey]);
            if (!$token) {
                return response()->json(['success' => false, 'error' => 'Some Error Message'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'error' => 'Failed to login, please try again.'], 500);
        }
        return $this->finalResponse($token);
    }

    public function verifyToken(Request $request)
    {
        try {
            $token = $request->bearerToken() ?: $request->query('token');
            JWTAuth::setToken($token, ['key' => $this->secretKey]);
            $user = Auth::guard('appadmin')->user();
            return response()->json(['user' => $user], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            // Token has expired
            return response()->json(['error' => 'Token expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            // Token is invalid
            return response()->json(['error' => 'Token invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // Token is absent from the request
            return response()->json(['error' => 'Token absent'], 401);
        }
    }

}