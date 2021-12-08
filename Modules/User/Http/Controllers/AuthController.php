<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;

        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->username,
                    'password' => $request->password,
                ],
                'verify' => false
            ]);
            $json = json_decode($response->getBody(), true);

            return response()->json($json);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }

            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'integer|unique:users',
            'email' => 'string|email|max:255|unique:users',
            'passport' => 'string|max:25|unique:users',
            'password' => 'required|confirmed|string|min:5',
        ]);

        return User::create([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'email' => $request->email??($request->phone .'@mail.uz'),
            'passport' => $request->passport,
            'password' => Hash::make($request->password),
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }
    public function me(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }
}