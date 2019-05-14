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

    //pembayaran nota order produksi
    Route::post('pembayaran-nota/get-data-supplier', 'mobile\PembayaranNotaController@getSupplier');
    Route::post('pembayaran-nota/get-data-termin', 'mobile\PembayaranNotaController@getTermin');
    Route::post('pembayaran-nota/update-data-termin', 'mobile\PembayaranNotaController@updatePembayaran');

    //return produksi
    Route::post('return-produksi/get-data-supplier', 'mobile\ReturnProduksiController@getSupplier');
    Route::post('return-produksi/get-nota-produksi', 'mobile\ReturnProduksiController@getNotaProduksi');
    Route::post('return-produksi/get-item-nota', 'mobile\ReturnProduksiController@getItemNota');
    Route::post('return-produksi/get-data-item', 'mobile\ReturnProduksiController@getDataItem');
    Route::post('return-produksi/tambah-return-produksi', 'mobile\ReturnProduksiController@addReturn');
});
