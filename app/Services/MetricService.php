<?php

namespace App\Services;
use App\Exceptions\MetricNotFoundException;
use App\Repositories\MetricRepository;

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
        $period = $data['period'] ?? [];

        if (count($data['metrics']) > 0) {
            $data['metrics'][] = 'created_at';
        }

        $metrics = $data['metrics'] ?? ['*'];

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
}
