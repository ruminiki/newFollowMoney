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

/*Route::post('categories/search', [
  'as' => 'categories.search', 
  'uses' => 'CategoryController@search'
]);*/
/*Route::post('categories', 'CategoryController', [
    'search'  => 'datatables.search',
]);*/
/*Route::get('categories/search', [
  'as' => 'categories.search', 
  'uses' => 'CategoryController@search'
]);*/
Route::resource('categories', 'CategoryController');

Route::get('bankAccounts/next_month/{id}', ['as' => 'bankAccounts.next_month', 'uses' => 'BankAccountController@next_month']);
Route::get('bankAccounts/previous_month/{id}', ['as' => 'bankAccounts.previous_month', 'uses' => 'BankAccountController@previous_month']);
Route::get('bankAccounts/account_statement/{id}', ['as' => 'bankAccounts.account_statement', 'uses' => 'BankAccountController@account_statement']);
Route::resource('bankAccounts', 'BankAccountController');

Route::resource('creditCards', 'CreditCardController');

Route::resource('creditCardInvoices', 'CreditCardInvoiceController');

Route::resource('paymentForms', 'PaymentFormController');

Route::get('movements/next_month', ['as' => 'movements.next_month', 'uses' => 'MovementController@next_month']);
Route::get('movements/previous_month', ['as' => 'movements.previous_month', 'uses' => 'MovementController@previous_month']);
Route::resource('movements', 'MovementController');