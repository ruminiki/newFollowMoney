<?php

namespace App\Repositories;

use App\Models\Movement;
use InfyOm\Generator\Common\BaseRepository;

class MovementRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Movement::class;
    }
}
