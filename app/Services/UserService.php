<?php

namespace App\Services;

use App\Exceptions\InvalidLoginException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login(array $data)
    {
        if (!Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) {
            throw new InvalidLoginException('Usuário ou senha incorretos.');
        }

        return [
            'token' => [
                'type' => env('JWT_TOKEN_TYPE', 'Bearer'),
                'value' => JWTAuth::fromUser(Auth::user())
            ],
            'user' => Auth::user()
        ];
    }

    public function getArduinos()
    {
        return $this->userRepository->allNoTrashed();
    }
}
