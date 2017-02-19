<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Movement
 * @package App\Models
 * @version February 19, 2017, 11:31 am UTC
 */
class Movement extends Model
{
    use SoftDeletes;

    public $table = 'movements';
    
    protected $dates = ['deleted_at, emission_date, maturity_date'];

    public $fillable = [
        'description',
        'emission_date',
        'maturity_date',
        'value',
        'operation',
        'status',
        'payment_form_id',
        'category_id',
        'bank_account_id',
        'credit_card_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'description' => 'string',
        'emission_date' => 'date',
        'maturity_date' => 'date',
        'value' => 'decimal',
        'operation' => 'string',
        'status' => 'string',
        'portion_number' => 'string',
        'transfer_hash' => 'string',
        'portion_hash' => 'string',
        'payment_form_id' => 'integer',
        'category_id' => 'integer',
        'bank_account_id' => 'integer',
        'credit_card_id' => 'integer',
        'movement_origin_id' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'description' => 'required',
        'emission_date' => 'required',
        'maturity_date' => 'required',
        'category_id' => 'required',
        'value' => 'required',
        'operation' => 'required',
        'status' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function paymentForm()
    {
        return $this->belongsTo(\App\Models\PaymentForm::class, 'payment_form_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function bankAccount()
    {
        return $this->belongsTo(\App\Models\BankAccount::class, 'bank_account_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function movementOrigin()
    {
        return $this->belongsTo(\App\Models\Movement::class, 'movement_origin_id', 'id');
    }

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
