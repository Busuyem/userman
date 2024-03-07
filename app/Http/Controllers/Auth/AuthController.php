<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Throwable;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        try{

            $validatedData = $request->validated();

            $validatedData['password'] = Hash::make($request->password);
            $user = User::create($validatedData);
            $token = $user->createToken('userToken')->accessToken;

            return response()->json([
                'status_code' => 201,
                'status' => 'Success!',
                'token' => $token
            ]);

        }catch(Throwable $e){
            return response()->json([
                'status' => 'failed!',
                'message' => $e->getMessage()
            ]);
        }
    
    }

    public function login(LoginUserRequest $request)
    {
        try{

            $validatedData = $request->validated();

            $user = User::where('email', $request->email)->first();

            if (!$user && !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'The provided credentials do not match.'
                ]);
            } else {
                $token = $user->createToken('userToken')->accessToken;
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Login successful!',
                    'token' => $token
                ]);
            }

        }catch(Throwable $e){
            return response()->json([
                'status' => 'Failed',
                'message' -> $e->getMessage()
            ]);
        }

        
    }

    public function logout(Request $request)
    {
        try{

            $request->user()->token()->revoke();
            return response()->json(['message' => 'Log out successful!']);
        }catch(Throwable $e){
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
