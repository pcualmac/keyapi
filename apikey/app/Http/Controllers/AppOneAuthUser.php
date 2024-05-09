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
        // dump('app_tokens.' . self::$applicationContext);
        // dd($this->secretKey);
        // config(['jwt.secret' => $this->secretKey]);
        // // Set the secret key for JWT authentication
        // JWTAuth::getJWTProvider()->setSecret($this->secretKey);
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->only('email', 'password');
        // Specify the guard
        $guard = Auth::guard('appOne'); 
        // dd($this->secretKey);
        // Set the custom secret key for the guard
        config(['jwt.secret' => $this->secretKey]);
        $customClaims = []; 

        if ($guard->claims($customClaims)->attempt($credentials)) {
            $token = JWTAuth::fromUser($guard->user(), ['email' => $guard->user()->email]);
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }


    public function registerUser(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create a new user record
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        // Specify the guard
        $guard = Auth::guard('appOne'); // Assuming you are using 'api' guard

        // Set the custom secret key for the guard
        config(['jwt.secret' => $this->secretKey]);


        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token], 200);
    }

    public function verifyToken(Request $request)
    {
        
        $token = $request->bearerToken(); // Get the token from the request
        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        // Specify the guard
        $guard = Auth::guard('appOne'); // Assuming you are using 'appOne' guard

        // Set the custom secret key for the guard
        config(['jwt.secret' => $this->secretKey]);
        $customClaims=[];
        try {
            // Verify the token
            $user = $guard->claims($customClaims)->authenticate($token);
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token is absent'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => $e->print], 500);
        }
        $payload = JWTAuth::setToken($token)->getPayload();
        // Retrieve specific claims from the payload
        $userId = $payload->get('sub'); // Retrieves the 'sub' claim (subject)
        $issuedAt = $payload->get('iat'); // Retrieves the 'iat' claim (issued at)
        $email = $payload->get('email'); // Retrieves the 'iat' claim (issued at)
        $response = [
            'user' => $user,
            'userId' => $userId,
            'issuedAt' => $issuedAt,
            'email' => $email,
            'custom_claim' => $user->getJWTCustomClaims(),
        ];
        // Token is valid
        return response()->json(['response' => $response], 200);
    }
}