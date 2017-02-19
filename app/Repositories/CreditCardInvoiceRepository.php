<?php

namespace App\Repositories;

use App\Models\CreditCardInvoice;
use InfyOm\Generator\Common\BaseRepository;

class CreditCardInvoiceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CreditCardInvoice::class;
    }
}
