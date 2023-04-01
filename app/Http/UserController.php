<?php

namespace App\Http;

use App\Core\Support\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $params = $request->all();
        //$params['password'] = bcrypt($params['password'] ?? '');

        if (!Auth::attempt(['username' => $params['username'], 'password' => $params['password']])) {
            throw new HttpResponseException(response()->json([
                'data' => [
                    'message' => 'Usuário ou senha incorretos.'
                ]
            ], 400));
        }

        $user = JWTAuth::fromUser(Auth::user());

        return response()->json([
            'data' => [
                'type' => 'Bearer',
                'token' => $user,
                'user' => Auth::user()
            ]
        ]);
  }
}
