<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'mobile\loginController@logout');

    //penerimaan barang
    Route::post('penerimaan-barang/get-data-penerimaan', 'mobile\PenerimaanBarangController@getData');
    Route::post('penerimaan-barang/get-data-penerimaan/nota', 'mobile\PenerimaanBarangController@getDataNota');
    Route::post('penerimaan-barang/get-data-penerimaan/nota-item', 'mobile\PenerimaanBarangController@getDataNotaItem');
    Route::post('penerimaan-barang/get-data-penerimaan/terima-item', 'mobile\PenerimaanBarangController@TerimaItem');
});
