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

/* Route::get('/', function () {
    return view('welcome');
}); */

//rutas de autenticacion
Auth::routes();

//rutas del home
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

//rutas de part
//Route::get('part/{part}', 'PartController@show')->name('part.show');

//rutas de item
Route::get('/item/confirmJournalBook','ItemController@confirmJournalBook');
Route::get('/item/{month}/JournalBook','ItemController@JournalBook');
Route::get('/item/{month}/allDocuments','ItemController@allDocuments');
Route::post('/item/setMonthJournalBook','ItemController@setMonthJournalBook');
Route::get('/item/{month}/pdf','ItemController@pdf');
Route::resource('/item','ItemController'); 
//rutas user
Route::put('/user/activeUpdate/{user}','UserController@activeUpdate');
Route::put('/user/roleUpdate/{user}','UserController@roleUpdate');
Route::resource('/user','UserController'); 
//rutas cost
Route::get('/cost/generate','CostController@generate');
Route::post('/cost/resultados','CostController@result')->name("resultados");
Route::resource('/cost','CostController');