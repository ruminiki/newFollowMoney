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
            $table->date('emission_date');
            $table->date('maturity_date');
            $table->decimal('value', 9, 2);
            $table->decimal('amount_paid', 9, 2);
            $table->string('reference_month', 20);
            $table->integer('reference_year');
            $table->string('status', 20);
            $table->integer('credit_card_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('credit_card_id')->references('id')->on('credit_cards');
            $table->foreign('user_id')->references('id')->on('users');
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
