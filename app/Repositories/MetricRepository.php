<?php

namespace App\Repositories;

use App\Core\Support\AbstractRepository;
use App\Repositories\Collections\Metric;

class MetricRepository extends AbstractRepository
{
    public function __construct()
    {
        $this->model = new Metric();
    }

    public function index(int $idArduino = 0, array $period = [], array $metrics = ['*'])
    {
        $data = $this->model;

        if ($idArduino > 0) {
            $data = $data->where('id_arduino', $idArduino);
        }

        if (count($period) > 0) {
            $data = $data->where('created_at', '>=', $period[0]);
            $data = $data->where('created_at', '<=', $period[1]);
        }

        return $data->get($metrics);
    }
}
