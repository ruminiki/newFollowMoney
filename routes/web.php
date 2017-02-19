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

Route::resource('bankAccounts', 'BankAccountController');