<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser()
    {
        $user = Auth::user();

        return response()->json(['data' => $user], 200);
    }

    public function createUser(Request $request)
    {
        $input = $request->validate([
            'firstname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'tg_id' => 'required|integer'
        ]);

        $user = User::query()->updateOrCreate(['tg_id' => $input['tg_id']], [
            'firstname' => $input['firstname'],
            'username' => $input['username'],
        ]);

        if (!$user) {
            return response()->json(['error' => 'cant create user'], 400);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token
        ], 201);
    }
}
