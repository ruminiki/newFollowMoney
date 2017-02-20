<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreditCardInvoiceToMovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movements', function($table) {
            $table->integer('credit_card_invoice_id')->unsigned()->nullable();
            $table->foreign('credit_card_invoice_id')->references('id')->on('credit_card_invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movements', function($table) {
            $table->dropColumn('credit_card_invoice_id');
            $table->dropForeign('credit_card_invoice_id');
        });
    }
}
