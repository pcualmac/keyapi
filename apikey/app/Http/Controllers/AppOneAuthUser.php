<?php

namespace App\Http\Controllers;

use App\Models\AppOne\AppUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;

class AppOneAuthUser extends Controller
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

    public function loginUser(Request $request)
    {

        $credentials = $request->only('email', 'password');
        try {
            $token = JWTAuth::setToken(JWTAuth::attempt($credentials, [], ['guard' => 'appOne']));

            // $token = JWTAuth::guard('appOne')->attempt($credentials);

            if (!$token) {
                return response()->json(['success' => false, 'error' => 'Some Error Message'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'error' => 'Failed to login, please try again.'], 500);
        }
        $user = Auth::guard('appOne')->user();
        $response =[
            'token' => $token,
            'secretKey' => $this->secretKey,
            'user' => $user 
        ]; 
        return $this->finalResponse($response);
    }


    public function registerUser(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $request->merge(['password' => Hash::make($request->password)]);
        $username = explode('@', $request->email)[0];
        $user = AppUser::create([
            'name' => $username,
            'username' => $username,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        return response()->json('success' . ' ' . $user->name . '  ' . $user->email . ' ');
    }

    public function verifyToken(Request $request)
    {
        try {
            $token = $request->bearerToken() ?: $request->query('token');
            JWTAuth::setToken($token, ['secret' => $this->secretKey]);
            $user = Auth::guard('appOne')->user();
            $customClaims = $user->getJWTCustomClaims();
            $response =[
                'user' => $user,
                'customClaims' => $customClaims,
                'claims' => JWTAuth::claims($customClaims)->fromUser($user),
                'secretKey' => $this->secretKey,
                'used secretKey' =>JWTAuth::getJWTProvider()->getSecret()
            ]; 
            return response()->json(['response' => $response], 200);
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