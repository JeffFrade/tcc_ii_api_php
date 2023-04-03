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

    /**
     * @OA\Info(title="TCC II Api", version="0.1")
     */

    /**
     * @OA\Post(
     *     path="/api/login",
     *     @OA\Response(response="200", description="Login efetuado com sucesso"),
     *     @OA\Response(response="403", description="Usuário ou senha inválidos")
     * )
     *
     * @OA\Schema(
     *      @OA\Property(
     *          property="username",
     *          type="string",
     *          description="Usuário"
     *      ),
     *
     *      @OA\Property(
     *          property="password",
     *          type="string",
     *          description="Senha"
     *      )
     * )
     */
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
