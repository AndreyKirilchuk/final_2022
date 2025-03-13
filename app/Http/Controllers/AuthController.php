<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $v = validator($request->all(), [
            "email" => "required|string",
            "password" => "required|string"
        ]);

        if ($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        if(!auth()->attempt($v->validated()))
        {
            return $this->errors(errors: "Login failed", code: 401);
        }

        $user = auth()->user();
        $token = Str::uuid();
        $user->update(["token" => $token]);

        return $this->success(data: [
            "profile" => UserResource::make($user),
            "credentials" => [
                "token" => $token,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(request $request)
    {
        $user = auth()->user();
        $user->update(['token' => null]);

        return $this->success();
    }
}
