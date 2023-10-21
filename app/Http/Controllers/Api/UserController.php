<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'response' => 101,
            'data' => $users
        ]);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'sometimes|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => 102,
                'message' => 'Bad Request',
                'validation_errors' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'response' => 103,
                'message' => 'User not found',
                'validation_errors' => null,
                'data' => null,
            ], 404);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->has('password') && !empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return response()->json([
            'response' => 101,
            'message' => 'User updated successfully',
            'validation_errors' => null,
            'data' => $user,
        ]);
    }
    
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'response' => 103,
                'message' => 'User not found',
                'data' => null,
            ], 404);
        }
        $user->delete();

        return response()->json([
            'response' => 104,
            'message' => 'User deleted successfully',
            'data' => null,
        ]);
    }
    
}
