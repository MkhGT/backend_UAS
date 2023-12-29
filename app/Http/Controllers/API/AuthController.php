<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 500);
                
            }

            $user = User::where('email', $request->email)->first();
            if ( ! Hash::check($request->password, $user->password, [])) {
                throw new Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ],200);
        } catch (Exception $error) {
            return response()->json([
                'message' => 'Unauthorized',
                'error' => $error,
            ], 500);
        }
    }

    //
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user = User::where('email', $request->email)->first();

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'User Berhasil Daftar');

        } catch (Exception $error) {
           return response()->json([
                'message' => 'Unauthorized',
                'error' => $error,
           ],500);
        }
    }

    public function fetch(Request $request)
    {
        return response()->json([
            'data'=> $request->user,
        ],200 );
    }
}