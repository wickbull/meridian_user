<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::bind('news', function ($slug) {
    return \Packages\News::whereTranslation('slug', $slug)->firstOrFail();
});

Route::bind('annonce', function ($slug) {
    return \Packages\Annonce::whereTranslation('slug', $slug)->firstOrFail();
});




Route::get('/', [
    'as'   => 'home',
    'uses' => 'HomeController@getIndex'
]);

// NEWS

Route::get('/news', [
    'as'   => 'news.list',
    'uses' => 'NewsController@getList'
]);

Route::get('/news/{news}', [
    'as'   => 'news.view',
    'uses' => 'NewsController@getView'
]);

// NEWS END

// ANNONCE

Route::get('/annonce', [
    'as'   => 'annonce.list',
    'uses' => 'AnnoncesController@getList'
]);

Route::get('/annonce/{annonce}', [
    'as'   => 'annonce.view',
    'uses' => 'AnnoncesController@getView'
]);

// ANNONCE END


