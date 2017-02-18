<?php

namespace App\Repositories;

use App\Models\BankAccount;
use InfyOm\Generator\Common\BaseRepository;

class BankAccountRepository extends BaseRepository
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
        return BankAccount::class;
    }
}
