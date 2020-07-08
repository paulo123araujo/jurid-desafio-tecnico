<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\api\v1\BaseController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends BaseController
{
    public function index(Request $request)
    {
        $users = User::all();
        return $this->sendResponse($users, 'success');
    }

    public function show(Request $request, User $user)
    {
        return $this->sendResponse($user, 'success');
    }

    public function store(Request $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->login = $request->login;
            $user->password = Hash::make($request->password);
            $user->save();

            return $this->sendResponse($user, 'success', 201);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            if ($request->password) {
                $request->password = Hash::make($request->password);
            }
            $user->update($request->all());

            return $this->sendResponse($user, 'success');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function remove(Request $request, User $user)
    {
        $user->delete();
        return $this->sendResponse([], 'success', 200);
    }
}
