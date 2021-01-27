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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/group/{group}', 'ConversationController@view')->name('conversation');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

//Route::resource('groups', 'GroupController');


Route::group([
    'middleware' => ['auth'],
    'prefix' => 'api'
],function (){
    Route::post('group','GroupController@store');
    Route::get('groups','GroupController@index');
    Route::get('group/{group}/conversation','ConversationController@show');
    Route::post('group/{group}/conversation','ConversationController@store');
});
