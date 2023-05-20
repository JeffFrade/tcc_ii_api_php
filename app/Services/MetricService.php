<?php

namespace App\Services;
use App\Exceptions\InvalidDateException;
use App\Exceptions\InvalidPeriodException;
use App\Exceptions\MetricNotFoundException;
use App\Repositories\MetricRepository;
use App\Helpers\DateHelper;

class MetricService
{
    private $metricRepository;

    public function __construct()
    {
        $this->metricRepository = new MetricRepository();
    }

    public function index(array $data)
    {
        $conditions = [];

        $idArduino = $data['id_arduino'] ?? 0;
        $period = $this->mountPeriod($data['period'] ?? []);
        $metrics = $this->mountMetrics($data['metrics'] ?? []);

        $metrics = $this->metricRepository->index($idArduino, $period, $metrics);

        if (count($metrics) == 0) {
            throw new MetricNotFoundException("Não há dados.");
        }

        $avgHumidity = $this->metricRepository->avg($idArduino, $period, 'umidade');
        $avgCo2 = $this->metricRepository->avg($idArduino, $period, 'dioxido_carbono');
        $avgCo = $this->metricRepository->avg($idArduino, $period, 'monoxido_carbono');
        $avgTemperature = $this->metricRepository->avg($idArduino, $period, 'temperatura');
        $avgSmoke = $this->metricRepository->avg($idArduino, $period, 'fumaca');

        $conditions[] = $this->checkHumidity($avgHumidity);
        $conditions[] = $this->checkCo2($avgCo2);
        $conditions[] = $this->checkCo($avgCo);
        $conditions[] = $this->checkTemperature($avgTemperature);

        $verified = $this->verifyConditions($conditions);

        return [
            'metrics' => $metrics,
            'condition' => $verified,
            'medias' => [
                'avg_humidity' => $avgHumidity,
                'avg_co2' => $avgCo2,
                'avg_co' => $avgCo,
                'avg_temperature' => $avgTemperature,
                'avg_smoke' => $avgSmoke
            ]

        ];
    }

    public function store(array $data)
    {
        return $this->metricRepository->create($data);
    }

    private function mountPeriod(array $period)
    {
        if (count($period) > 0) {
            $period = $this->validatePeriod($period);

            $period[0] = DateHelper::parse($period[0])->startOfDay();
            $period[1] = DateHelper::parse($period[1])->endOfDay();

            $period = $this->compareDates($period);
        }

        return $period;
    }

    private function validatePeriod(array $period)
    {
        if (count($period) != 2) {
            throw new InvalidPeriodException('Para o período são necessárias 2 datas.');
        }

        $period[0] = DateHelper::validateDate($period[0]);
        $period[1] = DateHelper::validateDate($period[1]);

        if (in_array(false, $period)) {
            throw new InvalidDateException('Uma ou mais datas inválidas.');
        }

        return $period;
    }

    private function compareDates(array $period)
    {
        $diff = DateHelper::diffDates($period[0], $period[1]);

        if ($diff < 0) {
            $startDate = $period[0];
            $period[0] = $period[1];
            $period[1] = $startDate;
        }

        return $period;
    }

    private function mountMetrics(array $metrics)
    {
        if (count($metrics) > 0) {
            $metrics[] = 'id_arduino';
            $metrics[] = 'created_at';
        }

        return $metrics;
    }

    private function checkHumidity(float $humidity)
    {
        $status = 'yellow';

        if ($humidity >= 40 && $humidity <= 70) {
            $status = 'green';
        } elseif ($humidity <= 20) {
            $status = 'red';
        }

        return $status;
    }

    private function checkCo2(float $co2)
    {
        $status = 'red';

        if ($co2 < 1) {
            $status = 'green';
        }

        return $status;
    }

    private function checkCo(float $co)
    {
        $status = 'red';

        if ($co < 0.9) {
            $status = 'green';
        } elseif ($co >= 0.9 && $co <= 1.1) {
            $status = 'yellow';
        }

        return $status;
    }

    private function checkTemperature(float $temperature)
    {
        $status = 'red';

        if ($temperature >= 10 && $temperature <= 30) {
            $status = 'green';
        }

        return $status;
    }

    private function verifyConditions(array $conditions)
    {
        if (in_array('red', $conditions)) {
            return [
                'status' => 'Ruim',
                'message' => 'Recomendável não treinar'
            ];
        }

        if (in_array('yellow', $conditions)) {
            return [
                'status' => 'Moderado',
                'message' => 'Treino permitido, com cautela'
            ];
        }

        return [
            'status' => 'Bom',
            'message' => 'Treino permitido'
        ];
    }
}
