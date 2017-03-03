<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Movement;
use App\User;
use DB;
use Log;
use Carbon\Carbon;
use Exception;

/**
 * Class CreditCard
 * @package App\Models
 * @version February 19, 2017, 12:58 am UTC
 */
class CreditCard extends Model
{
    public $table = 'credit_cards';
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

    public function addToInvoice(Movement $movement){

        $invoice_date = Carbon::createFromDate($movement->maturity_date->year,
                                               $movement->maturity_date->month,
                                               $movement->creditCard->invoice_day);
        //calcula a data de vencimento da fatura
        //se a data do movimento for depois da data de fechamento da fatura ou se 
        //a data do movimento for menor que a data de fechamento e também menor que a data de vencimento da fatura

        Log::info("Maturity Day: " . $movement->maturity_date->day . " Closing day: " . $this->closing_day);

        if ( $movement->maturity_date->day > $this->closing_day ||
             ($movement->maturity_date->day <= $this->closing_day && $movement->maturity_date->day > $this->invoice_day) ){

            if ( $movement->maturity_date->month == 12 ){
                $invoice_date = Carbon::createFromDate($movement->maturity_date->year + 1,
                                                       $movement->maturity_date->month + 1,
                                                       $movement->creditCard->invoice_day);
            }else{
                $invoice_date = Carbon::createFromDate($movement->maturity_date->year,
                                                       $movement->maturity_date->month + 1,
                                                       $movement->creditCard->invoice_day);
            }
            
        }

        //load invoice
        $credit_card_invoice = CreditCardInvoice::whereRaw('credit_card_id = ? and reference_month = ? and reference_year = ?', 
                                         [$this->id, $invoice_date->month, $invoice_date->year])->first();

        //$credit_card_invoice = CreditCardInvoice::find(1);
            
        if ( $credit_card_invoice == null || $credit_card_invoice->id <= 0 ){

            $credit_card_invoice = new CreditCardInvoice();
            $credit_card_invoice->credit_card_id = $movement->credit_card_id;
            $credit_card_invoice->maturity_date = $invoice_date;
            $credit_card_invoice->reference_month = $invoice_date->month;
            $credit_card_invoice->reference_year = $invoice_date->year;
            $credit_card_invoice->user_id = $movement->user_id;
            $credit_card_invoice->status = CreditCardInvoice::OPEN;

            Log::info('======== Creating invoice to ' . $movement->creditCard->description . ' ' . $credit_card_invoice->reference_month . '-' . $credit_card_invoice->reference_year);

            //create invoice
            $credit_card_invoice->save(); //= CreditCardInvoice::create($credit_card_invoice->toArray());
            $movement->credit_card_invoice_id = $credit_card_invoice->id;
        }else{
            if ( $credit_card_invoice->isOpen() ){
                $movement->credit_card_invoice_id = $credit_card_invoice->id;
            }else{
                throw new Exception("The invoice is closed. Please cancel payment to reopen invoice.");
            }
        }
    }

    /**
     * Get the comments for the blog post.
     */
    public function creditCardInvoices()
    {
        return $this->hasMany(App\Models\Movement::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
