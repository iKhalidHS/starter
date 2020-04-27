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
    return view('welcome');
});

Auth::routes(['verify'=>True]);

Route::get('/home', 'HomeController@index')->name('home') -> middleware('verified');

Route::get('/redirect/{service}' , 'SocialController@redirect');
Route::get('/callback/{service}' , 'SocialController@callback');

Route::get('fillable', 'CrudController@getOffers');



Route::group([
    'prefix'     =>LaravelLocalization::setLocale(),   // LaravelLocalization::setLocale() will set your current language
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ] //if you didn't add the middleware, it will work but will not redirect to the current lang. if someone removed the lang from the link/route
    ], function (){
        Route::group(['prefix'=>'offers'], function(){
            Route::get('create','CrudController@create'); // form create
            Route::post('store','CrudController@store') -> name('offers.store'); // insert data //route post because we are posting data received from post

            Route::get('all','CrudController@getAllOffers') -> name('offers.all'); //show all

            Route::get('edit/{offer_id}','CrudController@editOffer'); // form edit
            Route::post('update/{offer_id}','CrudController@updateOffer') -> name('offers.update'); // update data // you can use patch instead of post
            Route::get('delete/{offer_id}','CrudController@delete') -> name('offers.delete'); //
        });

        Route::get('youtube','CrudController@getVideo');

});
