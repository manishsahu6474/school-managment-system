<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // user check for security API side
    public function login(Request $request) {
        
    $request->validate(
        [
            'email' => 'required|email',
            'password' => 'required'
        ]
    );

    $user = User::where('email',$request->email)->first();

    if(!$user || !Hash::check($request->password,$user->password))
        {
            return response()->json([
                'message' => 'Email and password did not match'
            ],401);

        }
        
        $user->tokens()->delete();

        $token = $user->createToken('school_api_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => $user
        ], 200);
    }
}
