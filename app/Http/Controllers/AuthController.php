<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Validator;
use Dingo\Api\Http\Request;

class AuthController extends Controller
{
    /**
     * @param Request $request
     */
    public function login(Request $request)
    {
        $rules = [
            'email'    => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            $this->response->errorUnauthorized($validator->errors()->first());
        }

        if ($token = JWTAuth::attempt($request->only('email', 'password'))) {
            $user = User::whereEmail($request->email)->first();

            $data = (object) [];
            $data->user = $user;
            $data->token = $token;
            return response()->json(['data' => $data]);
        }

        $this->response->errorUnauthorized('Invalid credentials!');
    }

    public function me()
    {
        $user = $this->user;

        return response()->json([
            'data' => $user,
        ]);
    }
}
