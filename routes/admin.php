<?php

use Illuminate\Support\Facades\Route;

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
define('page', 5);
Route::group(['namespace' => 'Admin', 'middleware' => 'auth:admin'], function () {
    Route::get('/', 'DashboardController@index')->name('admin.dashboard');
    ############################### language ###############################################################################
    Route::group(['prefix' => 'languages'], function () {
        Route::get('/', 'LanguageController@index')->name('admin.languages');
        Route::get('/create', 'LanguageController@create')->name('admin.languages.create');
        Route::post('/save', 'LanguageController@save')->name('admin.languages.save');
        Route::get('/edit/{id}', 'LanguageController@edit')->name('admin.languages.edit');
        Route::post('/update/{id}', 'LanguageController@update')->name('admin.languages.update');
        Route::get('/delete/{id}', 'LanguageController@destroy')->name('admin.languages.delete');
    });

##################################################################################################################################
################################ main categories ###############################################################################

    Route::group(['prefix' => 'main_categories'], function () {
        Route::get('/', 'MainCategoriesController@index')->name('admin.maincategories');
        Route::get('/create', 'MainCategoriesController@create')->name('admin.maincategories.create');
        Route::post('/save', 'MainCategoriesController@save')->name('admin.maincategories.save');
        Route::get('/edit/{id}', 'MainCategoriesController@edit')->name('admin.maincategories.edit');
        Route::post('/update/{id}', 'MainCategoriesController@update')->name('admin.maincategories.update');
        Route::get('/delete/{id}', 'MainCategoriesController@destroy')->name('admin.maincategories.delete');
        Route::get('/changestatus/{id}', 'MainCategoriesController@changestatus')->name('admin.maincategories.changestatus');
    });

############################################################################################################################
################################ vendors ###############################################################################

    Route::group(['prefix' => 'vendors'], function () {
        Route::get('/', 'VendorsController@index')->name('admin.vendors');
        Route::get('/create', 'VendorsController@create')->name('admin.vendors.create');
        Route::post('/save', 'VendorsController@save')->name('admin.vendors.save');
        Route::get('/edit/{id}', 'VendorsController@edit')->name('admin.vendors.edit');
        Route::post('/update/{id}', 'VendorsController@update')->name('admin.vendors.update');
        Route::get('/delete/{id}', 'VendorsController@destroy')->name('admin.vendors.delete');
    });

############################################################################################################################
});

    Route::group(['namespace' => 'Admin', 'middleware' => 'guest:admin'], function () {
        Route::get('login', 'LoginController@getLogin')->name('get.admin.login');
        Route::post('login', 'LoginController@Login')->name('admin.login');

    });

