<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMovementsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description', 100);
            $table->date('emission_date');
            $table->date('maturity_date');
            $table->decimal('value', 9, 2);
            $table->string('operation', 30);
            $table->string('status', 20);
            $table->string('portion_number', 20)->nullable();
            $table->string('transfer_hash', 100)->nullable();
            $table->string('portion_hash', 100)->nullable();
            $table->integer('payment_form_id')->unsigned()->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('bank_account_id')->unsigned()->nullable();
            $table->integer('credit_card_id')->unsigned()->nullable();
            $table->integer('movement_origin_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('payment_form_id')->references('id')->on('payment_forms');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts');
            $table->foreign('movement_origin_id')->references('id')->on('movements');
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
        Schema::drop('movements');
    }
}
