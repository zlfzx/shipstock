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

Auth::routes([
    'register' => false,
    'confirm' => false,
    'reset' => false
]);

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('pemakaian/data', 'PemakaianController@data')->name('pemakaian.data');
    Route::resource('pemakaian', 'PemakaianController');

    Route::get('sparepart/data', 'SparepartController@data')->name('sparepart.data');
    Route::get('sparepart/select', 'SparepartController@select')->name('sparepart.select');
    Route::resource('sparepart', 'SparepartController');

    Route::get('warehouse/data', 'WarehouseController@data')->name('warehouse.data');
    Route::get('warehouse/select', 'WarehouseController@select')->name('warehouse.select');
    Route::resource('warehouse', 'WarehouseController');

    Route::get('kapal/data', 'KapalController@data')->name('kapal.data');
    Route::get('kapal/select', 'KapalController@select')->name('kapal.select');
    Route::resource('kapal', 'KapalController');

    Route::get('user/data', 'UserController@data')->name('user.data');
    Route::resource('user', 'UserController');
});

Route::get('artisan', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate --seed');
});
