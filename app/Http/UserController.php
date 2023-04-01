<?php

namespace App\Http;

use App\Core\Support\Controller;
use App\Exceptions\InvalidLoginException;
use App\Services\UserService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class UserController extends Controller
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function login(Request $request)
    {
        try {
            $params = $this->toValidateLogin($request);

            $user = $this->userService->login($params);

            return response()->json([
                'data' => $user
            ]);
        } catch (InvalidLoginException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 403);
        }
    }

    protected function toValidateLogin(Request $request)
    {
        $toValidateArr = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validation = $this->validate($request, $toValidateArr);

        if (empty($validation) === true) {
            throw new InvalidArgumentException('Parâmetros vazios');
        }

        if (empty($validation['error']) === false) {
            throw new InvalidArgumentException($validation['error']);
        }

        return $validation;
    }
}
