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

Route::get('admin',function (){
    return view('admin/layout/index');
});

//Route group admin

Route::group(['prefix'=>'admin'],function (){

   Route::get('trangchu',function (){
       return view('admin/trangchu/index');
   });

    Route::group(['prefix'=>'menu'],function (){
       Route::get('them','MenuController@getThem');
    });
});
