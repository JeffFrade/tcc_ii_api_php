<?php

namespace App\Http;

use App\Core\Support\Controller;
use App\Exceptions\InvalidLoginException;
use App\Exceptions\UserNotFoundException;
use App\Services\UserService;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * @OA\Server(url=API_HOST)
 */
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

    /**
     * @OA\Get(
     *     path="/api/users/arduinos",
     *     summary="Exibe Dispositivos Cadastrados.",
     *     @OA\Response(response="200", description="Há dados cadastrados"),
     *     @OA\Response(response="404", description="Não há dados cadastrados"),
     * )
     */
    public function getArduinos()
    {
        try {
            return response()->json([
                'data' => $this->userService->getArduinos()
            ], 200);
        } catch (UserNotFoundException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
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
