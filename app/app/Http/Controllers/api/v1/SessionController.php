<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\api\v1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;

class SessionController extends BaseController
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'email',
            'login' => 'string',
            'password' => 'required'
        ]);

        $credentials = $request->email != null ? $request->only(['email', 'password']) : $request->only(['login', 'password']);

        if (!Auth::attempt($credentials)) {
            return $this->sendError('Data provided incorrectly', [], 401);
        }

        if ($request->login) {
            $user = User::where('login', $request->login)->first();
        } else {
            $user = User::where('email', $request->email)->first();
        }

        $token = $user->createToken('jurid-auth')->plainTextToken;

        return $this->sendResponse(['token' => $token, 'user' => $user], 'User logged in successfully', 201);
    }

    public function remove(Request $request)
    {
        Auth::user()->tokens()->delete();
    }
}
