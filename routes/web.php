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
    // Route::get('/', 'HomeController@index')->name('home');
    // Route::get('/home', 'HomeController@index')->name('home');

    Route::middleware(['auth'])->group(function(){
        Route::view('/',"home")->name('home');

        //rutas para empresas
        Route::prefix('empresa')->group(function(){
            Route::get('/', 'EmpresaController@index')->name('empresas');
            Route::post('/rol', 'EmpresaController@setRolEmpresa')->name('empresa.rol');
            Route::get('/getEmpresas', 'EmpresaController@getEmpresas')->name('empresa.getEmpresas');
        });
        

        //rutas de item
        Route::get('/item/confirmJournalBook','ItemController@confirmJournalBook');
        Route::get('/item/{month}/JournalBook','ItemController@JournalBook');
        Route::get('/item/{month}/allDocuments','ItemController@allDocuments');
        Route::post('/item/setMonthJournalBook','ItemController@setMonthJournalBook');
        Route::get('/item/{month}/pdf','ItemController@pdf');
        Route::resource('/item','ItemController'); 
        Route::post('/guardarEstados','ItemController@guardarRegistros')->name('estados.guardar');
        //rutas user
        Route::put('/user/activeUpdate/{user}','UserController@activeUpdate');
        Route::put('/user/roleUpdate/{user}','UserController@roleUpdate');
        Route::resource('/user','UserController'); 
        //rutas cost
        Route::get('/cost/generate','CostController@generate');
        Route::post('/cost/resultados','CostController@result');
        Route::put('/cost/{cost}/update','CostController@update')->name("cost.update");
        Route::resource('/cost','CostController');

        Route::get('/catalogo/crear','CatalogoController@create');
        Route::post('/catalogo/enviar','CatalogoController@store');
        Route::get('/catalogo/configurar', 'CatalogoController@configurar')->name('catalogo.configurar');
    });

    

    //rutas de part
    //Route::get('part/{part}', 'PartController@show')->name('part.show');

    

