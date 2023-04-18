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
        $idArduino = $data['id_arduino'] ?? 0;
        $period = $this->mountPeriod($data['period'] ?? []);
        $metrics = $this->mountMetrics($data['metrics'] ?? []);

        $metrics = $this->metricRepository->index($idArduino, $period, $metrics);

        if (count($metrics) == 0) {
            throw new MetricNotFoundException("Não há dados.");
        }

        return $metrics;
    }

    public function store(array $data)
    {
        return $this->metricRepository->create($data);
    }

    private function mountPeriod(array $period)
    {
        if (count($period) > 0) {
            $period = $this->validatePeriod($period);

            $period[0] = DateHelper::parse($period[0]);
            $period[1] = DateHelper::parse($period[1]);

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
}
