<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CreditCardInvoice
 * @package App\Models
 * @version February 19, 2017, 10:44 am UTC
 */
class CreditCardInvoice extends Model
{
    use SoftDeletes;

    public $table = 'credit_card_invoices';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'emission_date',
        'maturity_date',
        'value',
        'amount_paid',
        'reference_month',
        'reference_year',
        'status',
        'credit_card_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'emission_date' => 'date',
        'maturity_date' => 'date',
        'reference_month' => 'string',
        'reference_year' => 'integer',
        'status' => 'string',
        'credit_card_id' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'emission_date' => 'required',
        'maturity_date' => 'required',
        'value' => 'required',
        'reference_month' => 'required',
        'reference_year' => 'required',
        'status' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function creditCard()
    {
        return $this->belongsTo(\App\Models\CreditCard::class, 'credit_card_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
