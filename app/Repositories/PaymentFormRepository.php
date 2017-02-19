<?php

namespace App\Repositories;

use App\Models\PaymentForm;
use InfyOm\Generator\Common\BaseRepository;

class PaymentFormRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PaymentForm::class;
    }
}
