<?php

namespace App\Http;

use App\Core\Support\Controller;
use App\Exceptions\InvalidLoginException;
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

    /**
     * @OA\Post(
     *     summary="Gera o token para utilizar a API.",
     *     path="/api/login",
     *     @OA\Response(response="200", description="Login efetuado com sucesso"),
     *     @OA\Response(response="403", description="Usuário ou senha inválidos"),
     *     @OA\Response(response="422", description="Erro de validação nos campos username ou password"),
     *     @OA\Parameter(in="body", name="username", description="Usuário", required=true, example="admin"),
     *     @OA\Parameter(in="body", name="password", description="Senha", required=true, example="password")
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
