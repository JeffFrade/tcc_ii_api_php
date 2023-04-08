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

    public function index()
    {
        $metrics = $this->metricRepository->allNoTrashed();

        if (empty($metrics)) {
            throw new MetricNotFoundException("Não há dados.");
        }

        return $metrics;
    }

    public function store(array $data)
    {
        return $this->metricRepository->create($data);
    }
}
