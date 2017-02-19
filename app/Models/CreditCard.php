<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CreditCard
 * @package App\Models
 * @version February 19, 2017, 12:58 am UTC
 */
class CreditCard extends Model
{
    use SoftDeletes;

    public $table = 'credit_cards';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'description',
        'limit',
        'invoice_day',
        'closing_day',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'description' => 'string',
        'limit' => 'decimal',
        'invoice_day' => 'integer',
        'closing_day' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'description' => 'required',
        'limit' => 'required',
        'invoice_day' => 'required',
        'closing_day' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
