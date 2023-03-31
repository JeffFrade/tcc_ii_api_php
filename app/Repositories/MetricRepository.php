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
}
