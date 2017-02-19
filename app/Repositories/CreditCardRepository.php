<?php

namespace App\Repositories;

use App\Models\CreditCard;
use InfyOm\Generator\Common\BaseRepository;

class CreditCardRepository extends BaseRepository
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
        return CreditCard::class;
    }
}
