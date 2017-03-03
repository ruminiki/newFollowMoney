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

    const OPEN = 'OPEN';
    const CLOSED = 'CLOSED';

    public $table = 'credit_card_invoices';
    
    public $fillable = [
        'maturity_date',
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
        'maturity_date' => 'date',
        'reference_month' => 'integer',
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
        'maturity_date' => 'required',
        'reference_month' => 'required',
        'reference_year' => 'required',
        'credit_card_id' => 'required',
        'status' => 'required'
    ];

    public function isOpen(){
        return $this->status == CreditCardInvoice::OPEN;
    }

    public function isClosed(){
        return $this->status == CreditCardInvoice::CLOSED;
    }

    public function close(){
        $this->status = CreditCardInvoice::CLOSED;
    }

    public function reopen(){
        $this->status = CreditCardInvoice::OPEN;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function creditCard()
    {
        return $this->belongsTo(\App\Models\CreditCard::class, 'credit_card_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function movements()
    {
        return $this->hasMany(\App\Models\Movement::class, 'credit_card_invoice_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
