<?php

namespace App\Services;
use App\Repositories\MetricRepository;

class MetricService
{
    private $metricRepository;

    public function __construct()
    {
        $this->metricRepository = new MetricRepository();
    }

    public function store(array $data)
    {
        return $this->metricRepository->create($data);
    }
}
