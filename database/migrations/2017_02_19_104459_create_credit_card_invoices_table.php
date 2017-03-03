<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCreditCardInvoicesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_card_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->date('maturity_date');
            $table->decimal('value', 9, 2)->nullable();
            $table->decimal('amount_paid', 9, 2)->nullable();
            $table->integer('reference_month');
            $table->integer('reference_year');
            $table->string('status', 20);
            $table->integer('credit_card_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('credit_card_id')->references('id')->on('credit_cards');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unique(['credit_card_id', 'reference_year', 'reference_month', 'user_id'], 'credit_card_invoice_user_index_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('credit_card_invoices');
    }
}
