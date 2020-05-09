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

define('PAGINATION_COUNT', 3);

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

            Route::get('get-all-inactive-offer','CrudController@getAllInactiveOffers'); //

        });

        Route::get('youtube','CrudController@getVideo')->middleware('auth');

});


######################## Begin ajax routes ##################
Route::group(['prefix' => 'ajax-offers'], function(){
    Route::get('create','OfferController@create');
    Route::post('store','OfferController@store')-> name('ajax.offers.store');

    Route::get('all','OfferController@all') -> name('ajax.offers.all');

    Route::post('delete','OfferController@delete') -> name('ajax.offers.delete'); //
    Route::get('edit/{offer_id}','OfferController@edit') -> name('ajax.offers.edit'); //
    Route::post('update','OfferController@update') -> name('ajax.offers.update'); //
});
######################## End ajax routes ##################


######################## Begin Authentication && Guards ##################

//Route::group(['namespace'=>'Auth','middleware' => 'CheckAge'], function (){
Route::group(['namespace'=>'Auth','middleware' => ['auth','CheckAge']], function (){

    Route::get('adults','CustomAuthController@adults') ->name('adult');
});

Route::get('site','Auth\CustomAuthController@adults') -> middleware('auth:web')-> name('site'); // middleware('auth:web') is the default guard and you can write it as [  -> middleware('auth')->   ]
Route::get('admin','Auth\CustomAuthController@admin') -> middleware('auth:admin')-> name('admin');

Route::get('admin/login','Auth\CustomAuthController@adminLogin') -> name('admin.login');
Route::post('admin/login','Auth\CustomAuthController@checkAdminLogin') -> name('save.admin.login');


Route::get('dashborad',function (){
    return 'Not adult';
}) -> name('not.adult'); //test route


######################## END Authentication && Guards ##################


################# Begin relations routes #################

Route::get('has-one', 'Relation\RelationsController@hasOneRelation');
Route::get('has-one-reverse', 'Relation\RelationsController@hasOneRelationReverse');
Route::get('get-user-has-phone', 'Relation\RelationsController@getUserHasPhone');
Route::get('get-user-not-has-phone', 'Relation\RelationsController@getUserNotHasPhone');
Route::get('get-user-has-phone-with-condition','Relation\RelationsController@getUserWhereHasPhoneWithCondition');


################## Begin one To many Relationship #####################

Route::get('hospital-has-many','Relation\RelationsController@getHospitalDoctors');

Route::get('hospitals','Relation\RelationsController@hospitals') -> name('hospital.all');

Route::get('doctors/{hospital_id}','Relation\RelationsController@doctors')-> name('hospital.doctors');

Route::get('hospitals/{hospital_id}','Relation\RelationsController@deleteHospital')-> name('hospital.delete');

Route::get('hospitals_has_doctors','Relation\RelationsController@hospitalsHasDoctor');

Route::get('hospitals_has_doctors_male','Relation\RelationsController@hospitalsHasOnlyMaleDoctors');

Route::get('hospitals_not_has_doctors','Relation\RelationsController@hospitals_not_has_doctors');


################## End one To many Relationship #####################


################## Begin  Many To many Relationship #####################

Route::get('doctors-services','Relation\RelationsController@getDoctorServices');
Route::get('service-doctors','Relation\RelationsController@getServiceDoctors');

Route::get('doctors/services/{doctor_id}','Relation\RelationsController@getDoctorServicesById')-> name('doctors.services');
Route::post('saveServices-to-doctor','Relation\RelationsController@saveServicesToDoctors')-> name('save.doctors.services');


################## End Many To many Relationship #####################


######################### has one through ##########################


Route::get('has-one-through','Relation\RelationsController@getPatientDoctor');

Route::get('has-many-through','Relation\RelationsController@getCountryDoctor');


################# End relations routes #################


################### Beginaccessors and mutators #############

Route::get('accessors','Relation\RelationsController@getDoctors'); //get data

################### End accessors and mutators #############
