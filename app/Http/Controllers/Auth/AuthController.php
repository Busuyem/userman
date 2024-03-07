<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Throwable;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        try{

            $validatedData = $request->validated();

            $user = User::create($validatedData);

            $token = $user->createToken('userman')->accessToken;

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

    public function login(Request $request)
    {
        // Validation code for login

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = Auth::user()->createToken('userman')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
