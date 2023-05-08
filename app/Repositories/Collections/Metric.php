<?php

namespace App\Repositories\Collections;

use App\Helpers\DateHelper;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Metric extends Model
{
    use HybridRelations, SoftDeletes;

    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var array
     */
    protected $fillable = [
        'id_arduino',
        'alcool',
        'benzeno',
        'hexano',
        'metano',
        'fumaca',
        'dioxido_carbono',
        'tolueno',
        'amonia',
        'acetona',
        'monoxido_carbono',
        'hidrogenio',
        'gases_inflamaveis',
        'temperatura',
        'umidade'
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s'
    ];

    public function getCreatedAtAttribute(string $date)
    {
	    return DateHelper::convertDate($date);
    }

    public function getUpdatedAtAttribute(string $date)
    {
	    return DateHelper::convertDate($date);
    }
}
