<?php

namespace App\Http;

use App\Core\Support\Controller;
use App\Exceptions\InvalidDateException;
use App\Exceptions\InvalidPeriodException;
use App\Exceptions\MetricNotFoundException;
use App\Services\MetricService;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * @OA\Info(
 *     title="TCC II Api",
 *     version="1.1.4"
 * )
 */
class MetricController extends Controller
{
    private $metricService;

    public function __construct(MetricService $metricService)
    {
        $this->metricService = $metricService;
    }

    public function index(Request $request)
    {
        try {
            $params = $this->toValidateIndex($request);

            return response()->json([
                'data' => $this->metricService->index($params),
                'message' => 'Métricas encontradas!'
            ]);
        } catch (MetricNotFoundException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Info(title="TCC II Api", version="0.1")
     */

    /**
     * @OA\Post(
     *     path="/api/dispositivos",
     *     summary="Insere dados vindos do Arduino.",
     *     @OA\Response(response="200", description="Armazenamento dos dados efetuado com sucesso"),
     *     @OA\Response(response="400", description="Erro na validação dos campos, seja por campos vazios ou formato inválido"),
     *     @OA\Response(response="401", description="Erro de login/expiração do token")
     * )
     */
    public function store(Request $request)
    {
        try {
            $params = $this->toValidate($request);
            $data = $this->metricService->store($params);

            return response()->json([
                'data' => $data,
                'message' => 'Métrica Cadastrada Com Sucesso!'
            ], 200);
        } catch (InvalidArgumentException | InvalidPeriodException | InvalidDateException $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    protected function toValidate(Request $request)
    {
        $toValidateArr = [
            'id_arduino' => 'required|numeric',
            'alcool' => 'required|numeric',
            'benzeno' => 'required|numeric',
            'hexano' => 'required|numeric',
            'metano' => 'required|numeric',
            'fumaca' => 'required|numeric',
            'dioxido_carbono' => 'required|numeric',
            'tolueno' => 'required|numeric',
            'amonia' => 'required|numeric',
            'acetona' => 'required|numeric',
            'monoxido_carbono' => 'required|numeric',
            'hidrogenio' => 'required|numeric',
            'gases_inflamaveis' => 'required|numeric',
            'temperatura' => 'required|numeric',
            'umidade' => 'required|numeric'
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

    protected function toValidateIndex(Request $request)
    {
        $toValidateArr = [
            'id_arduino' => 'nullable|numeric',
            'metrics' => 'nullable|array',
            'period' => 'nullable|array'
        ];

        $validation = $this->validate($request, $toValidateArr);

        if (empty($validation['error']) === false) {
            throw new InvalidArgumentException($validation['error']);
        }

        return $validation;
    }
}
