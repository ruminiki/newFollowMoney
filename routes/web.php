<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('categories', 'CategoryController');

Route::get('bankAccounts/{id}/account_statement/{month}', 
	['as' => 'bankAccounts.account_statement', 'uses' => 'BankAccountController@account_statement']);
Route::resource('bankAccounts', 'BankAccountController');

Route::get('creditCards/{id}/invoices/{year}', 
	['as' => 'creditCards.invoices', 'uses' => 'CreditCardController@invoices']);
Route::resource('creditCards', 'CreditCardController');

Route::get('creditCardInvoices/{id}/pay', 
	['as' => 'creditCardInvoices.pay', 'uses' => 'CreditCardInvoiceController@pay']);
Route::get('creditCardInvoices/{id}/unpay', 
	['as' => 'creditCardInvoices.unpay', 'uses' => 'CreditCardInvoiceController@unpay']);
Route::resource('creditCardInvoices', 'CreditCardInvoiceController');

Route::resource('paymentForms', 'PaymentFormController');

Route::get('movements/next_month', ['as' => 'movements.next_month', 'uses' => 'MovementController@next_month']);
Route::get('movements/previous_month', ['as' => 'movements.previous_month', 'uses' => 'MovementController@previous_month']);
Route::resource('movements', 'MovementController');