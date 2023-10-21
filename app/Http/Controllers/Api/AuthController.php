<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'response' => 102,
                'message' => 'Bad Request',
                'validation_errors' => $validator->errors(),
                'data' => null,
            ], 422);
        }
    
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->api_token = Str::random(60);
        $user->save();
    
        return response()->json([
            'response' => 101,
            'message' => 'User registered successfully',
            'validation_errors' => null,
            'data' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => 102,
                'message' => 'Bad Request',
                'validation_errors' => $validator->errors(),
                'data' => null
            ]);
        }
        
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
        
            if (!$user->api_token) {
                $user->api_token = Str::random(60);
                $user->save();
            }
        
            return response()->json([
                'response' => 101,
                'message' => 'User logged in successfully',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'response' => 100,
                'message' => 'Invalid email or password.',
            ]);
        }
        
    }
}
