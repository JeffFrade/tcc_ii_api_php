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
 *     title="J3M Api",
 *     version="2.0.0"
 * )
 */
class MetricController extends Controller
{
    private $metricService;

    public function __construct(MetricService $metricService)
    {
        $this->metricService = $metricService;
    }

    /**
     * @OA\Get(
     *     path="/api/dispositivos",
     *     summary="Exibe dados vindos do Arduino.",
     *     @OA\Parameter(
     *          name="metrics[]",
     *          description="Array com as métricas que deseja exibir (Valores válidos e que podem ser combinados: alcool, benzeno, hexano, metano, fumaca, dioxido_carbono, tolueno, amonia, acetona, monoxido_carbono, hidrogenio, gases_inflamaveis, temperatura e umidade.)",
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="string"),
     *          ),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="id_arduino",
     *          description="ID do dispositivo J3M do qual deseja obter as métricas",
     *          in="query",
     *          @OA\Schema(
     *              type="int",
     *              example=1,
     *          ),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="period[]",
     *          description="Array com as datas de início e término (Formato: Y-m-d Ex: 2023-01-01), é obrigatório passar 2 datas para o funcionamento correto",
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="string"),
     *          ),
     *          style="form"
     *     ),
     *     @OA\Response(response="200", description="Há dados para os parâmetros informados"),
     *     @OA\Response(response="400", description="Erro na validação dos campos, seja por campos vazios ou formato inválido"),
     *     @OA\Response(response="500", description="Não há dados para os parâmetros informados"),
     * )
     */
    public function index(Request $request)
    {
        try {
            $params = $this->toValidateIndex($request);
            $metrics = $this->metricService->index($params);

            return response()->json([
                'data' => $metrics['metrics'],
                'condicao' => $metrics['condition'],
                'medias' => $metrics['medias'],
                'total' => count($metrics['metrics']),
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
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="id_arduino",
     *                      description="ID do dispositivo do J3M",
     *                      type="int",
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      property="alcool",
     *                      description="Total ppm de Álcool",
     *                      type="float",
     *                      example=0.686999976634979
     *                  ),
     *                  @OA\Property(
     *                      property="benzeno",
     *                      description="Total ppm de Benzeno",
     *                      type="float",
     *                      example=0.365000009536743
     *                  ),
     *                  @OA\Property(
     *                      property="hexano",
     *                      description="Total ppm de Hexano",
     *                      type="float",
     *                      example=0.153999999165535
     *                  ),
     *                  @OA\Property(
     *                      property="metano",
     *                      description="Total ppm de Metano",
     *                      type="float",
     *                      example=0.365000009536743
     *                  ),
     *                  @OA\Property(
     *                      property="fumaca",
     *                      description="Total ppm de Fumaça",
     *                      type="float",
     *                      example=0.254000008106232
     *                  ),
     *                  @OA\Property(
     *                      property="dioxido_carbono",
     *                      description="Total ppm de Dióxido de Carbono",
     *                      type="float",
     *                      example=5.03599977493286
     *                  ),
     *                  @OA\Property(
     *                      property="tolueno",
     *                      description="Total ppm de Tolueno",
     *                      type="float",
     *                      example=0.498699992895126
     *                  ),
     *                  @OA\Property(
     *                      property="amonia",
     *                      description="Total ppm de Amônia",
     *                      type="float",
     *                      example=0.598699986934662
     *                  ),
     *                  @OA\Property(
     *                      property="acetona",
     *                      description="Total ppm de Acetona",
     *                      type="float",
     *                      example=0.0
     *                  ),
     *                  @OA\Property(
     *                      property="monoxido_carbono",
     *                      description="Total ppm de Monóxido de Carbono",
     *                      type="float",
     *                      example=0.356000006198883
     *                  ),
     *                  @OA\Property(
     *                      property="hidrogenio",
     *                      description="Total ppm de Hidrogênio",
     *                      type="float",
     *                      example=0.123000003397465
     *                  ),
     *                  @OA\Property(
     *                      property="gases_inflamaveis",
     *                      description="Total ppm de Gases Inflamáveis",
     *                      type="float",
     *                      example=0.119999997317791
     *                  ),
     *                  @OA\Property(
     *                      property="temperatura",
     *                      description="Temperatura em Graus Celsius",
     *                      type="float",
     *                      example=27.5
     *                  ),
     *                  @OA\Property(
     *                      property="umidade",
     *                      description="Umidade em percentual",
     *                      type="float",
     *                      example=75.3
     *                  ),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(response="200", description="Armazenamento dos dados efetuado com sucesso"),
     *     @OA\Response(response="422", description="Erro na validação dos campos, seja por campos vazios ou formato inválido")
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
