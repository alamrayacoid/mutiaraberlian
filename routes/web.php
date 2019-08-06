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

Route::get("/", function () {
    if (Auth::check()) {
        return redirect()->route("home");
    } else {
        return redirect()->route("login");
    }
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', function () {
        return view('auth/login');
    })->name('login');

    Route::post('auth', [
        'uses' => 'AuthController@authenticate',
        'as' => 'auth.authenticate'
    ]);
});

Route::get('errors/404', function(){
    return view('errors.404');
})->name('errors.404');

Route::get('/loading', 'RecruitmentController@loading')->name('loading.index');
Route::get('/recruitment', 'RecruitmentController@index')->name('recruitment.index');
Route::get('/rekrutmen', 'RecruitmentController@index');
Route::post('/recruitment/store', 'RecruitmentController@store')->name('recruitment.store');
Route::get('/recruitment/isduplicated/{field}/{value}', 'RecruitmentController@isDuplicated')->name('recruitment.isduplicated');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::post('logout', [
        'uses' => 'AuthController@logout',
        'as' => 'auth.logout'
    ]);
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('storage/uploads/produk/original')->name('image_produk');

    // !====================================================== Master Data Utama ======================================================!

    Route::get('/masterdatautama/datapegawai/index', 'Master\EmployeeController@index')->name('pegawai.index');
    Route::get('/masterdatautama/datapegawai/getData', 'Master\EmployeeController@getData')->name('pegawai.list');
    Route::get('/masterdatautama/datapegawai/create', 'Master\EmployeeController@create')->name('pegawai.create');
    Route::post('/masterdatautama/datapegawai/store', 'Master\EmployeeController@store')->name('pegawai.store');
    Route::match(['get', 'post'], '/masterdatautama/datapegawai/edit/{id}', 'Master\EmployeeController@edit')->name('pegawai.edit');
    Route::get('/masterdatautama/datapegawai/nonactive/{id}', 'Master\EmployeeController@nonActive')->name('cabang.nonActive');
    Route::get('/masterdatautama/datapegawai/actived/{id}', 'Master\EmployeeController@actived')->name('cabang.actived');
    Route::get('/masterdatautama/datapegawai/detail/{id}', 'Master\EmployeeController@detail')->name('cabang.detail');

    Route::get('/masterdatautama/produk/index', 'Master\ItemController@index')->name('dataproduk.index');
    Route::get('/masterdatautama/produk/list', 'Master\ItemController@getList')->name('dataproduk.list');
    Route::get('/masterdatautama/produk/create', 'Master\ItemController@create')->name('dataproduk.create');
    Route::post('/masterdatautama/produk/store', 'Master\ItemController@store')->name('dataproduk.store');
    Route::get('/masterdatautama/produk/edit/{id}', 'Master\ItemController@edit')->name('dataproduk.edit');
    Route::post('/masterdatautama/produk/update/{id}', 'Master\ItemController@update')->name('dataproduk.update');
    Route::post('/masterdatautama/produk/delete/{id}', 'Master\ItemController@destroy')->name('dataproduk.delete');
    Route::post('/masterdatautama/produk/active/{id}', 'Master\ItemController@active')->name('dataproduk.active');
    Route::post('/masterdatautama/produk/simpanjenis', 'Master\ItemController@simpanjenis');
    Route::post('/masterdatautama/produk/tablejenis', 'Master\ItemController@tablejenis');
    Route::post('/masterdatautama/produk/hapusjenis', 'Master\ItemController@hapusjenis');
    Route::post('/masterdatautama/produk/updatejenis', 'Master\ItemController@updatejenis');
    Route::get('/masterdatautama/produk/detail', 'Master\ItemController@detail');
    Route::get('/masterdatautama/produk/get-data', 'Master\ItemController@tablejenis')->name('jenisitem.getdata');

    Route::get('/masterdatautama/produk/jenis/create', 'MasterController@create_datajenisproduk')->name('datajenisproduk.create');
    Route::get('/masterdatautama/produk/jenis/edit', 'MasterController@edit_datajenisproduk')->name('datajenisproduk.edit');

    Route::get('/masterdatautama/variasisatuanproduk/index', 'MasterController@variasisatuanproduk')->name('variasisatuan.index');
    Route::get('/masterdatautama/variasisatuanproduk/create', 'MasterController@create_variasisatuanproduk')->name('variasisatuan.create');
    Route::get('/masterdatautama/variasisatuanproduk/edit', 'MasterController@edit_variasisatuanproduk')->name('variasisatuan.edit');

    Route::get('/masterdatautama/harga/index', 'Master\HargaController@dataharga')->name('dataharga.index');
    Route::get('/masterdatautama/harga/get-golongan', 'Master\HargaController@getGolongan')->name('dataharga.getgolongan');
    Route::get('/masterdatautama/harga/get-golongan-hpa', 'Master\HargaController@getGolonganHPA')->name('dataharga.getgolonganhpa');
    Route::get('/masterdatautama/harga/get-golongan-ap', 'Master\HargaController@getAgenPrice')->name('dataharga.getgolonganap');
    Route::get('/masterdatautama/harga/delete-golongan/{id}', 'Master\HargaController@deleteGolongan')->name('dataharga.deletegolongan');
    Route::get('/masterdatautama/harga/delete-golongan-hpa/{id}', 'Master\HargaController@deleteGolonganHPA')->name('dataharga.deletegolonganhpa');
    Route::get('/masterdatautama/harga/delete-golongan-pa/{id}', 'Master\HargaController@deleteGolonganPA')->name('dataharga.deletegolonganpa');
    Route::post('/masterdatautama/harga/add-golongan', 'Master\HargaController@addGolongan')->name('dataharga.addgolongan');
    Route::post('/masterdatautama/harga/add-golongan-hpa', 'Master\HargaController@addGolonganHPA')->name('dataharga.addgolonganhpa');
    Route::post('/masterdatautama/harga/add-golongan-pa', 'Master\HargaController@addGolonganPA')->name('dataharga.addgolonganpa');
    Route::post('/masterdatautama/harga/edit-golongan', 'Master\HargaController@editGolongan')->name('dataharga.editgolongan');
    Route::post('/masterdatautama/harga/edit-golongan-hpa', 'Master\HargaController@editGolonganHPA')->name('dataharga.editgolonganhpa');
    Route::post('/masterdatautama/harga/edit-golongan-pa', 'Master\HargaController@editGolonganPA')->name('dataharga.editgolonganpa');
    Route::get('/masterdatautama/harga/cari-barang', 'Master\HargaController@cariBarang')->name('dataharga.caribarang');
    Route::get('/masterdatautama/harga/get-satuan/{id}', 'Master\HargaController@getSatuan')->name('dataharga.getsatuan');
    Route::post('/masterdatautama/harga/add-golongan-harga', 'Master\HargaController@addGolonganHarga')->name('dataharga.addgolonganharga');
    Route::post('/masterdatautama/harga/add-golongan-harga-hpa', 'Master\HargaController@addGolonganHargaHPA')->name('dataharga.addgolonganhargahpa');
    Route::get('/masterdatautama/harga/get-data-need-approve', 'Master\HargaController@getDataNeddApprove')->name('dataharga.getdataneedapprove');
    Route::get('/masterdatautama/harga/get-golongan-harga/{id}', 'Master\HargaController@getGolonganHarga')->name('dataharga.getgolonganharga');
    Route::get('/masterdatautama/harga/get-golongan-harga-hpa/{id}', 'Master\HargaController@getGolonganHargaHPA')->name('dataharga.getgolonganhargahpa');
    Route::get('/masterdatautama/harga/delete-golongan-harga/{id}/{detail}/{status}', 'Master\HargaController@deleteGolonganHarga')->name('dataharga.deletegolonganharga');
    Route::get('/masterdatautama/harga/delete-golongan-harga-hpa/{id}/{detail}/{status}', 'Master\HargaController@deleteGolonganHargaHPA')->name('dataharga.deletegolonganhargahpa');
    Route::post('/masterdatautama/harga/edit-golongan-harga-unit', 'Master\HargaController@editGolonganHargaUnit')->name('dataharga.editgolonganhargaunit');
    Route::post('/masterdatautama/harga/edit-golongan-harga-unit-hpa', 'Master\HargaController@editGolonganHargaUnitHPA')->name('dataharga.editgolonganhargaunithpa');
    Route::post('/masterdatautama/harga/edit-golongan-harga-range', 'Master\HargaController@editGolonganHargaRange')->name('dataharga.editgolonganhargarange');
    Route::post('/masterdatautama/harga/edit-golongan-harga-range-hpa', 'Master\HargaController@editGolonganHargaRangeHPA')->name('dataharga.editgolonganhargarangehpa');
    Route::get('/masterdatautama/harga/satuan/create/{id}', 'Master\HargaController@create_golonganharga')->name('golonganharga.create');
    Route::get('/masterdatautama/harga/satuan/edit', 'Master\HargaController@edit_golonganharga')->name('golonganharga.edit');

    Route::get('/masterdatautama/suplier/index', 'Master\SupplierController@index')->name('suplier.index');
    Route::get('/masterdatautama/suplier/list', 'Master\SupplierController@getList')->name('suplier.list');
    Route::get('/masterdatautama/suplier/datasuplier/create', 'Master\SupplierController@create')->name('suplier.create');
    Route::post('/masterdatautama/suplier/store', 'Master\SupplierController@store')->name('suplier.store');
    Route::get('/masterdatautama/suplier/edit/{id}', 'Master\SupplierController@edit')->name('suplier.edit');
    Route::post('/masterdatautama/suplier/post/{id}', 'Master\SupplierController@update')->name('suplier.update');
    Route::post('/masterdatautama/suplier/disable/{id}', 'Master\SupplierController@disable')->name('suplier.disable');
    Route::post('/masterdatautama/suplier/enable/{id}', 'Master\SupplierController@enable')->name('suplier.enable');

    Route::get('/masterdatautama/itemsuplier/autoItem', 'Master\ItemSupplierController@auto_item')->name('itemsuplier.autoitem');
    Route::get('/masterdatautama/itemsuplier/getItemDT', 'Master\ItemSupplierController@get_itemDT')->name('itemsuplier.getitemdt');
    Route::get('/masterdatautama/itemsuplier/hapus/{item}/{supp}', 'Master\ItemSupplierController@hapus')->name('itemsuplier.hapus');
    Route::post('/masterdatautama/itemsuplier/tambah', 'Master\ItemSupplierController@tambah')->name('itemsuplier.tambah');

//    ==========Master Outlet==========
    Route::get('/masterdatautama/cabang/index', 'Master\CabangController@index')->name('cabang.index');
    Route::get('/masterdatautama/cabang/list', 'Master\CabangController@getData')->name('cabang.list');
    Route::get('/masterdatautama/cabang/get-cities', 'Master\CabangController@getCities')->name('cabang.getCities');
    Route::get('/masterdatautama/cabang/create', 'Master\CabangController@create')->name('cabang.create');
    Route::get('/masterdatautama/cabang/store', 'Master\CabangController@store')->name('cabang.store');
    Route::match(['get', 'post'], '/masterdatautama/cabang/edit/{id}', 'Master\CabangController@edit')->name('cabang.edit');
    Route::get('/masterdatautama/cabang/nonactive/{id}', 'Master\CabangController@nonActive')->name('cabang.nonActive');
    Route::get('/masterdatautama/cabang/actived/{id}', 'Master\CabangController@actived')->name('cabang.actived');
//    ==========End Master Outlet======

    Route::get('/masterdatautama/agen/index', 'Master\AgenController@index')->name('agen.index');
    Route::post('/masterdatautama/agen/list', 'Master\AgenController@getList')->name('agen.list');
    Route::get('/masterdatautama/agen/create', 'Master\AgenController@create')->name('agen.create');
    Route::get('/masterdatautama/agen/agents', 'Master\AgenController@getAgents')->name('agen.agents');
    Route::get('/masterdatautama/agen/provinces', 'Master\AgenController@getProvinces')->name('agen.provinces');
    Route::get('/masterdatautama/agen/cities/{prov}', 'Master\AgenController@getCities')->name('agen.cities');
    Route::get('/masterdatautama/agen/districts/{prov}', 'Master\AgenController@getDistricts')->name('agen.districts');
    Route::get('/masterdatautama/agen/villages/{prov}', 'Master\AgenController@getVillages')->name('agen.villages');
    Route::post('/masterdatautama/agen/store', 'Master\AgenController@store')->name('agen.store');
    Route::get('/masterdatautama/agen/edit/{id}', 'Master\AgenController@edit')->name('agen.edit');
    Route::post('/masterdatautama/agen/update/{id}', 'Master\AgenController@update')->name('agen.update');
    Route::post('/masterdatautama/agen/disable/{id}', 'Master\AgenController@disable')->name('agen.disable');
    Route::post('/masterdatautama/agen/enable/{id}', 'Master\AgenController@enable')->name('agen.enable');
    Route::get('/masterdatautama/agen/getAgenByCity/{id}', 'Master\AgenController@getAgenByCity')->name('agen.getAgenByCity');

    Route::get('/masterdatautama/agen/kelolaagen/index', 'MasterController@kelolaagen')->name('kelolaagen.index');

    Route::get('/masterdatautama/datasatuan/index', 'Master\SatuanController@index')->name('datasatuan.index');
    Route::get('/masterdatautama/datasatuan/list', 'Master\SatuanController@list_satuan')->name('datasatuan.list');
    Route::get('/masterdatautama/datasatuan/store', 'Master\SatuanController@tambahSatuan')->name('tambah_satuan');
    Route::get('/masterdatautama/datasatuan/update', 'Master\SatuanController@updateSatuan')->name('update_satuan');
    Route::post('/masterdatautama/datasatuan/delete/{id}', 'Master\SatuanController@deleteSatuan')->name('delete_satuan');

    // Master Member
    Route::get('/masterdatautama/member/index', 'Master\MemberController@index')->name('member.index');
    Route::get('/masterdatautama/member/list-member', 'Master\MemberController@listDataMember')->name('member.list');
    Route::get('/masterdatautama/member/create', 'Master\MemberController@create')->name('member.create');
    Route::get('/masterdatautama/member/store', 'Master\MemberController@memberStore')->name('member.store');
    Route::get('/masterdatautama/member/get-agen', 'Master\MemberController@getDataAgen')->name('member.getDataAgen');
    Route::get('/masterdatautama/member/cari-agen', 'Master\MemberController@cariDataAgen')->name('member.cariDataAgen');
    Route::get('/masterdatautama/member/edit/{id}', 'Master\MemberController@editMember')->name('member.edit');
    Route::get('/masterdatautama/member/update/{id}', 'Master\MemberController@updateMember')->name('member.update');
    Route::post('/masterdatautama/member/nonactivate/{id}', 'Master\MemberController@nonActivateMember')->name('member.nonActivate');
    Route::post('/masterdatautama/member/activate/{id}', 'Master\MemberController@activateMember')->name('member.activate');

    //Master ekspedisi
    Route::get('/masterdatautama/ekspedisi', 'Master\EkspedisiController@index')->name('ekspedisi.index');
    Route::get('/masterdatautama/ekspedisi/get-data-ekspedisi', 'Master\EkspedisiController@getData')->name('ekspedisi.data');
    Route::get('/masterdatautama/ekspedisi/get-data-product-ekspedisi', 'Master\EkspedisiController@getDataProduct')->name('ekspedisi.dataProduct');
    Route::post('/masterdatautama/ekspedisi/save-data-ekspedisi', 'Master\EkspedisiController@save')->name('ekspedisi.save');
    Route::post('/masterdatautama/ekspedisi/save-data-produk', 'Master\EkspedisiController@saveProduk')->name('ekspedisi.saveProduk');
    Route::post('/masterdatautama/ekspedisi/disable-ekspedisi', 'Master\EkspedisiController@disableEkspedisi')->name('ekspedisi.disableEkspedisi');
    Route::post('/masterdatautama/ekspedisi/disable-produk', 'Master\EkspedisiController@disableProduk')->name('ekspedisi.disableProduk');
    Route::post('/masterdatautama/ekspedisi/enable-ekspedisi', 'Master\EkspedisiController@enableEkspedisi')->name('ekspedisi.enableEkspedisi');
    Route::post('/masterdatautama/ekspedisi/enable-produk', 'Master\EkspedisiController@enableProduk')->name('ekspedisi.enableProduk');

    //Master Pembayaran
    Route::get('/masterdatautama/masterpembayaran', 'Master\PembayaranController@index')->name('masterdatautama.masterpembayaran');
    Route::post('/masterdatautama/masterpembayaran/simpan', 'Master\PembayaranController@save')->name('masterdatautama.save');
    Route::post('/masterdatautama/masterpembayaran/delete', 'Master\PembayaranController@delete')->name('masterdatautama.delete');
    Route::post('/masterdatautama/masterpembayaran/enable', 'Master\PembayaranController@enable')->name('masterdatautama.enable');
    Route::post('/masterdatautama/masterpembayaran/disable', 'Master\PembayaranController@disable')->name('masterdatautama.disable');
    Route::post('/masterdatautama/masterpembayaran/detail', 'Master\PembayaranController@detail')->name('masterdatautama.detail');
    Route::post('/masterdatautama/masterpembayaran/update', 'Master\PembayaranController@update')->name('masterdatautama.update');
    Route::post('/masterdatautama/masterpembayaran/get-data', 'Master\PembayaranController@getDataPembayaran')->name('masterdatautama.getData');

    // Master Cashflow
    Route::get('/masterdatautama/mastercashflow', 'Keuangan\master\cashflow\CashflowController@index')->name('masterdatautama.mastercashflow');
    Route::get('/masterdatautama/mastercashflow/get-data', 'Keuangan\master\cashflow\CashflowController@get_data_cashflow')->name('masterdatautama.mastercashflow_getData');
    Route::post('/masterdatautama/mastercashflow/save', 'Keuangan\master\cashflow\CashflowController@save')->name('masterdatautama.mastercashflow_save');
    Route::get('/masterdatautama/mastercashflow/edit/{id}', 'Keuangan\master\cashflow\CashflowController@edit')->name('masterdatautama.mastercashflow_edit');
    Route::post('/masterdatautama/mastercashflow/update/', 'Keuangan\master\cashflow\CashflowController@update')->name('masterdatautama.mastercashflow_update');
    Route::get('/masterdatautama/mastercashflow/delete/{id}', 'Keuangan\master\cashflow\CashflowController@delete')->name('masterdatautama.mastercashflow_delete');
    // !===================================================== End Master Data Utama =====================================================!

    // !===================================================== PRODUKSI =====================================================!
    // Order Produksic
    Route::get('/produksi/orderproduksi/index', 'ProduksiController@order_produksi')->name('order.index');
    Route::match(['get', 'post'], '/produksi/orderproduksi/create', 'ProduksiController@create_produksi')->name('order.create');
    Route::get('/produksi/orderproduksi/cari-barang', 'ProduksiController@cariBarang')->name('order.caribarang');
    Route::get('/produksi/orderproduksi/get-satuan/{id}', 'ProduksiController@getSatuan')->name('order.getsatuan');
    Route::get('/produksi/orderproduksi/edit', 'ProduksiController@edit_produksi')->name('order.edit');
    Route::post('/produksi/orderproduksi/edit-order-produksi', 'ProduksiController@editOrderProduksi');
    Route::post('/produksi/orderproduksi/get-order-produksi', 'ProduksiController@get_order')->name('order.getOrderProd');
    Route::get('/produksi/orderproduksi/detailitem', 'ProduksiController@getProduksiDetailItem')->name('order.detailitem');
    Route::get('/produksi/orderproduksi/detailtermin', 'ProduksiController@getProduksiDetailTermin')->name('order.detailtermin');
    Route::get('/produksi/orderproduksi/hapus/{id}', 'ProduksiController@delete_produksi')->name('order.delete');
    Route::get('/produksi/orderproduksi/hapus-item/{order}/{detail}/{item}', 'ProduksiController@deleteItemProduksi')->name('order.delete.item');
    Route::get('/produksi/orderproduksi/hapus-termin/{order}/{termin}', 'ProduksiController@deleteTerminProduksi')->name('order.delete.termin');
    Route::get('/produksi/orderproduksi/paksa-hapus/{id}', 'ProduksiController@forceDeleteProduksi')->name('order.forceDeleteProduksi');
    Route::get('/produksi/orderproduksi/nota/{id}', 'ProduksiController@printNota')->name('order.nota');

    // Penerimaan Barang
    Route::get('/produksi/penerimaanbarang/index', 'PenerimaanProduksiController@penerimaan_barang')->name('penerimaan.index');
    Route::get('/produksi/penerimaanbarang/getnotapo', 'PenerimaanProduksiController@getNotaPO');
    Route::get('/produksi/penerimaanbarang/detailitem', 'PenerimaanProduksiController@getProduksiDetailItem')->name('penerimaan.detailitem');
    Route::get('/produksi/penerimaanbarang/detailtermin', 'PenerimaanProduksiController@getProduksiDetailTermin')->name('penerimaan.detailtermin');
    Route::get('/produksi/penerimaanbarang/terima-barang/{id}', 'PenerimaanProduksiController@terimaBarang')->name('penerimaan.terimabarang');
    Route::get('/produksi/penerimaanbarang/getlistitem/{order}', 'PenerimaanProduksiController@listTerimaBarang');
    Route::get('/produksi/penerimaanbarang/terimalistitem/{id}/{item}', 'PenerimaanProduksiController@detailTerimaBarang');
    Route::post('/produksi/penerimaanbarang/checkqty', 'PenerimaanProduksiController@checkTerima')->name('penerimaan.checkqty');
    Route::post('/produksi/penerimaanbarang/terima-item', 'PenerimaanProduksiController@receiptItem')->name('penerimaan.terimaitem');
    Route::get('/produksi/penerimaanbarang/cari-histori', 'PenerimaanProduksiController@searchHistory')->name('penerimaan.histori');
    Route::get('/produksi/penerimaanbarang/create', 'PenerimaanProduksiController@create_penerimaan_barang')->name('penerimaan.create');
    // Pembayaran
    Route::get('/produksi/pembayaran/index', 'Produksi\PembayaranController@index')->name('pembayaran.index');
    Route::get('/produksi/pembayaran/list', 'Produksi\PembayaranController@getList')->name('pembayaran.list');
    Route::get('/produksi/pembayaran/show/{id}/{termin}', 'Produksi\PembayaranController@show')->name('pembayaran.show');
    Route::get('/produksi/pembayaran/bayar-list', 'Produksi\PembayaranController@listBayar')->name('pembayaran.listbayar');
    Route::post('/produksi/pembayaran/bayar', 'Produksi\PembayaranController@bayar')->name('pembayaran.bayar');
    Route::get('/produksi/pembayaran/nota', 'Produksi\PembayaranController@printNota' )->name('pembayaran.print');
    Route::get('/produksi/pembayaran/find-nota-history', 'Produksi\PembayaranController@findNotaHistory')->name('pembayaran.findNotaHistory');
    Route::get('/produksi/pembayaran/find-supplier', 'Produksi\PembayaranController@findSupplier')->name('pembayaran.findSupplier');
    Route::get('/produksi/pembayaran/get-list-history', 'Produksi\PembayaranController@getListHistory')->name('pembayaran.getListHistory');
    Route::get('/produksi/pembayaran/show-detail-history/{id}', 'Produksi\PembayaranController@showDetailHistory')->name('pembayaran.showDetailHistory');
    Route::get('/produksi/pembayaran/get-list-nota', 'Produksi\PembayaranController@getListNota')->name('pembayaran.getListNota');
    Route::get('/produksi/pembayaran/get-termin-date', 'Produksi\PembayaranController@getTerminByDate')->name('pembayaran.getTerminByDate');
    // Return Produksi
    Route::get('/produksi/returnproduksi/index', 'ProduksiController@return_produksi')->name('return.index');
    Route::post('/produksi/returnproduksi/list', 'ProduksiController@listReturn')->name('return.list');
    Route::get('/produksi/returnproduksi/detail-return/{id}/{detail}', 'ProduksiController@detailReturn')->name('return.detailreturn');
    Route::get('/produksi/returnproduksi/get-editreturn/{id}/{detail}', 'ProduksiController@getEditReturn')->name('return.geteditreturn');
    Route::get('/produksi/returnproduksi/create', 'ProduksiController@create_return_produksi')->name('return.create');
    Route::get('/produksi/returnproduksi/get-nota', 'ProduksiController@getNotaProductionOrder')->name('return.getnota');
    Route::get('/produksi/returnproduksi/detail-nota/{id}', 'ProduksiController@detailNota')->name('return.detailnota');
    Route::get('/produksi/returnproduksi/cari-supplier', 'ProduksiController@searchSupplier')->name('return.carisupplier');
    Route::get('/produksi/returnproduksi/cari-prodkode', 'ProduksiController@cariProdKode')->name('return.cariprodkode');
    Route::get('/produksi/returnproduksi/cari-nota', 'ProduksiController@cariNota')->name('return.carinota');
    Route::get('/produksi/returnproduksi/cari-barang-po', 'ProduksiController@cariBarangPO')->name('return.caribarangpo');
    Route::get('/produksi/returnproduksi/set-satuan/{id}', 'ProduksiController@setSatuan')->name('return.setunit');
    Route::get('/produksi/returnproduksi/hapus-return/{id}/{detail}/{qty}', 'ProduksiController@deleteReturn')->name('return.delete');
    Route::post('/produksi/returnproduksi/tambah-return', 'ProduksiController@addReturn')->name('return.add');
    Route::get('/produksi/returnproduksi/tambah-return', 'ProduksiController@addReturn')->name('return.add');
    Route::post('/produksi/returnproduksi/edit-return', 'ProduksiController@editReturn')->name('return.edit');
    Route::get('/produksi/returnproduksi/nota-return/{id}/{detail}', 'ProduksiController@notaReturn')->name('return.nota');
    Route::get('/produksi/returnproduksi/create/next', 'ProduksiController@next_create_return_produksi')->name('return.nextcreate');
    // !===================================================== END PRODUKSI =====================================================!

    // !===================================================== INVENTORY =====================================================!
    // Barang Masuk
    Route::get('/inventory/barangmasuk/index', 'Inventory\BarangMasukController@index')->name('barangmasuk.index');
    Route::get('/inventory/barangmasuk/list', 'Inventory\BarangMasukController@getData')->name('barangmasuk.list');
    Route::get('/inventory/barangmasuk/create', 'Inventory\BarangMasukController@create')->name('barangmasuk.create');
    Route::get('/inventory/barangmasuk/store', 'Inventory\BarangMasukController@store')->name('barangmasuk.store');
    Route::get('/inventory/barangmasuk/edit', 'Inventory\BarangMasukController@edit')->name('barangmasuk.edit');
    Route::get('/inventory/barangmasuk/autoItem', 'Inventory\BarangMasukController@auto_item')->name('barangmasuk.autoitem');
    Route::get('/inventory/barangmasuk/getUnit', 'Inventory\BarangMasukController@getUnit')->name('barangmasuk.getUnit');
    Route::get('/inventory/barangmasuk/getDetail', 'Inventory\BarangMasukController@getDetail')->name('barangmasuk.getDetail');

    // Barang Keluar
    Route::get('/inventory/barangkeluar/index', 'Inventory\BarangKeluarController@index')->name('barangkeluar.index');
    Route::get('/inventory/barangkeluar/list', 'Inventory\BarangKeluarController@getList')->name('barangkeluar.list');
    Route::get('/inventory/barangkeluar/detail/{id}/{detail}', 'Inventory\BarangKeluarController@getDetail')->name('barangkeluar.detail');
    Route::get('/inventory/barangkeluar/getItems', 'Inventory\BarangKeluarController@getItems')->name('barangkeluar.getItems');
    Route::get('/inventory/barangkeluar/create', 'Inventory\BarangKeluarController@create')->name('barangkeluar.create');
    Route::post('/inventory/barangkeluar/store', 'Inventory\BarangKeluarController@store')->name('barangkeluar.store');
    Route::get('/inventory/barangkeluar/edit/{id}', 'Inventory\BarangKeluarController@edit')->name('barangkeluar.edit');
    Route::post('/inventory/barangkeluar/update', 'Inventory\BarangKeluarController@update')->name('barangkeluar.update');

    // Distribusi Barang
    Route::get('/inventory/distribusibarang/index', 'Inventory\DistribusiController@index')->name('distribusibarang.index');
    Route::get('/inventory/distribusibarang/create', 'Inventory\DistribusiController@create')->name('distribusibarang.create');
    Route::get('/inventory/distribusibarang/get-item', 'Inventory\DistribusiController@getItem')->name('distribusibarang.getItem');
    Route::get('/inventory/distribusibarang/get-stock/{id}', 'Inventory\DistribusiController@getStock')->name('distribusibarang.getStock');
    Route::get('/inventory/distribusibarang/get-list-unit', 'Inventory\DistribusiController@getListUnit')->name('distribusibarang.getListUnit');
    Route::get('/inventory/distribusibarang/get-item-price', 'Inventory\DistribusiController@getItemPrice')->name('distribusibarang.getItemPrice');
    Route::post('/inventory/distribusibarang/store', 'Inventory\DistribusiController@store')->name('distribusibarang.store');
    Route::get('/inventory/distribusibarang/get-areas', 'Inventory\DistribusiController@getAreas')->name('distribusibarang.getAreas');
    Route::get('/inventory/distribusibarang/get-branch', 'Inventory\DistribusiController@getBranch')->name('distribusibarang.getBranch');
    Route::get('/inventory/distribusibarang/get-expedition-type', 'Inventory\DistribusiController@getExpeditionType')->name('distribusibarang.getExpeditionType');
    Route::get('/inventory/distribusibarang/edit/{id}', 'Inventory\DistribusiController@edit')->name('distribusibarang.edit');
    Route::post('/inventory/distribusibarang/update/{id}', 'Inventory\DistribusiController@update')->name('ditribusibarang.update');
    Route::get('/inventory/distribusibarang/table', 'Inventory\DistribusiController@table');
    Route::get('/inventory/distribusibarang/table-history', 'Inventory\DistribusiController@tableHistory');
    Route::get('/inventory/distribusibarang/detail-ht/{id}', 'Inventory\DistribusiController@showDetailHt')->name('distribusibarang.showDetailHt');
    Route::get('/inventory/distribusibarang/show-pc/{id}/{detailid}', 'Inventory\DistribusiController@showPC')->name('distribusibarang.showPC');
    Route::get('/inventory/distribusibarang/table-acceptance', 'Inventory\DistribusiController@tableAcceptance');
    Route::get('/inventory/distribusibarang/detail-ac/{id}', 'Inventory\DistribusiController@showDetailAc')->name('distribusibarang.showDetailAc');
    Route::post('/inventory/distribusibarang/set-acceptance/{id}', 'Inventory\DistribusiController@setAcceptance')->name('distribusibarang.setAcceptance');
    Route::get('/inventory/distribusibarang/hapus', 'Inventory\DistribusiController@hapus');
    Route::get('/inventory/distribusibarang/nota', 'Inventory\DistribusiController@printNota')->name('ditribusibarang.nota');
    // Distribusi -> Receive order from branch
    Route::get('/inventory/distribusibarang/get-list-order', 'Inventory\Distribusi\ProsesOrderController@getListOrder')->name('distribusibarangorder.getListOrder');
    Route::get('/inventory/distribusibarang/approve-order/{id}', 'Inventory\Distribusi\ProsesOrderController@approveOrder')->name('distribusibarangorder.approveOrder');
    Route::post('/inventory/distribusibarang/store-approval/{id}', 'Inventory\Distribusi\ProsesOrderController@storeApproval')->name('distribusibarangorder.storeApproval');
    Route::get('/inventory/distribusibarang/reject-order/{id}', 'Inventory\Distribusi\ProsesOrderController@rejectOrder')->name('distribusibarangorder.rejectOrder');

    // Manajemen Stok
    Route::get('/inventory/manajemenstok/index', 'InventoryController@manajemenstok_index')->name('manajemenstok.index');
    Route::get('/inventory/manajemenstok/create', 'InventoryController@manajemenstok_create')->name('manajemenstok.create');
    Route::get('/inventory/manajemenstok/edit', 'InventoryController@manajemenstok_edit')->name('manajemenstok.edit');
    // Analisa Stock Turn Over
    Route::get('/inventory/analisaturnover/index', 'InventoryController@analisaTO')->name('analisaTO.index');
    Route::get('/inventory/analisaturnover/get-list', 'InventoryController@analisaTO_list')->name('analisaTO.list');
    // Opname
    Route::get('/inventory/manajemenstok/opnamestock/index', 'Inventory\OpnameController@index')->name('opname.index');
    Route::get('/inventory/manajemenstok/opnamestock/list', 'Inventory\OpnameController@getList')->name('opname.list');
    Route::get('/inventory/manajemenstok/opnamestock/getItemAutocomplete', 'Inventory\OpnameController@getItemAutocomplete')->name('opname.getItemAutocomplete');
    Route::get('/inventory/manajemenstok/opnamestock/getItem', 'Inventory\OpnameController@getItem')->name('opname.getItem');
    Route::get('/inventory/manajemenstok/opnamestock/getQty', 'Inventory\OpnameController@getQty')->name('opname.getQty');
    Route::get('/inventory/manajemenstok/opnamestock/show/{id}', 'Inventory\OpnameController@show')->name('opname.show');
    Route::get('/inventory/manajemenstok/opnamestock/create', 'Inventory\OpnameController@create')->name('opname.create');
    Route::get('/inventory/manajemenstok/opnamestock/list-code-produksi', 'Inventory\OpnameController@list_codeProduksi')->name('codeProduksi.list');
    Route::get('/inventory/manajemenstok/opnamestock/list-code-opname', 'Inventory\OpnameController@list_codeOpname')->name('codeOpname.list');
    Route::post('/inventory/manajemenstok/opnamestock/store', 'Inventory\OpnameController@store')->name('opname.store');
    Route::get('/inventory/manajemenstok/opnamestock/edit/{id}', 'Inventory\OpnameController@edit')->name('opname.edit');
    Route::post('/inventory/manajemenstok/opnamestock/update/{id}', 'Inventory\OpnameController@update')->name('opname.update');
    Route::post('/inventory/manajemenstok/opnamestock/delete/{id}', 'Inventory\OpnameController@destroy')->name('opname.delete');
    Route::get('/inventory/manajemenstok/opnamestock/print', 'Inventory\OpnameController@print_opname')->name('opname.print');
    Route::get('/inventory/manajemenstok/historyopname/list', 'Inventory\HistoryOpnameController@getList')->name('history.list');
    // Adjustment
    Route::get('/inventory/manajemenstok/adjustmentstock/index', 'Inventory\AdjusmentController@index')->name('adjustment.index');
    Route::POST('/inventory/manajemenstok/adjustmentstock/list', 'Inventory\AdjusmentController@list')->name('adjustment.list');
    Route::get('/inventory/manajemenstok/adjustmentstock/list', 'Inventory\AdjusmentController@list')->name('adjustment.list');
    Route::get('/inventory/manajemenstok/adjustmentstock/create', 'Inventory\AdjusmentController@create')->name('adjustment.create');
    Route::get('/inventory/manajemenstok/adjustmentstock/print', 'Inventory\AdjusmentController@nota')->name('adjustment.nota');
    Route::get('/inventory/manajemenstok/adjustmentstock/getopname', 'Inventory\AdjusmentController@getopname')->name('adjustment.getopname');
    Route::get('/inventory/manajemenstok/adjustmentstock/list-code-produksi', 'Inventory\AdjusmentController@list_codeProduksi')->name('codeProduksiAdjustment.list');
    Route::get('/inventory/manajemenstok/adjustmentstock/simpan', 'Inventory\AdjusmentController@simpan')->name('adjustment.simpan');
    // Pengelolaan Data Max/Min, Safety stok
    Route::get('/inventory/manajemenstok/pengelolaanmms/index', 'InventoryController@pengelolaanmms_index')->name('pengelolaanmms.index');
    Route::get('/inventory/manajemenstok/pengelolaanmms/list-stock', 'InventoryController@dataStock')->name('pengelolaanmms.liststock');
    Route::get('/inventory/manajemenstok/pengelolaanmms/detail-stock/{id}', 'InventoryController@detailStock')->name('pengelolaanmms.detailstock');
    Route::get('/inventory/manajemenstok/pengelolaanmms/create', 'InventoryController@pengelolaanmms_create')->name('pengelolaanmms.create');
    Route::get('/inventory/manajemenstok/pengelolaanmms/cari-barang', 'InventoryController@cariBarang')->name('pengelolaanmms.caribarang');
    Route::post('/inventory/manajemenstok/pengelolaanmms/add-pengelolaanms', 'InventoryController@addPengelolaanms')->name('pengelolaanmms.addpengelolaanms');
    Route::match(['get', 'post'], '/inventory/manajemenstok/pengelolaanmms/edit/{id}', 'InventoryController@pengelolaanmms_edit')->name('pengelolaanmms.edit');
    Route::get('/inventory/manajemenstok/pengelolaanmms/cari-stock', 'InventoryController@searchStock')->name('pengelolaanmms.caristock');

    // !===================================================== END INVENTORY =====================================================!

    // !===================================================== SDM =====================================================!
    // Rekruitmen
    Route::get('/sdm/prosesrekruitmen/index', 'SDM\RecruitmentController@index')->name('rekruitmen.index');
    Route::get('/sdm/prosesrekruitmen/listRecruitment', 'SDM\RecruitmentController@getList')->name('rekruitmen.list');
    Route::get('/sdm/prosesrekruitmen/simpanLoker', 'SDM\RecruitmentController@simpanLoker')->name('rekruitmen.simpanLoker');
    Route::get('/sdm/prosesrekruitmen/detail/{id}', 'SDM\RecruitmentController@detail')->name('rekruitmen.detail');
    Route::get('/sdm/prosesrekruitmen/proses/{id}', 'SDM\RecruitmentController@proses')->name('rekruitmen.proses');
    Route::get('/sdm/prosesrekruitmen/addProses/{id}', 'SDM\RecruitmentController@addProses')->name('rekruitmen.addProses');
    Route::get('/sdm/prosesrekruitmen/delete-pelamar/{id}', 'SDM\RecruitmentController@deletePelamar')->name('rekruitmen.deletePelamar');

    Route::get('sdm/prosesrekruitmen/simpanPengajuan','SDM\RecruitmentController@simpanPengajuan')->name('pengajuan.simpanPengajuan');
    Route::get('/sdm/prosesrekruitmen/listPengajuan', 'SDM\RecruitmentController@getListPengajuan')->name('pengajuan.listPengajuan');
    Route::post('/sdm/prosesrekruitment/activatePengajuan/{id}', 'SDM\RecruitmentController@activatePengajuan')->name('pengajuan.activatePengajuan');
    Route::post('/sdm/prosesrekruitment/nonPengajuan/{id}', 'SDM\RecruitmentController@nonPengajuan')->name('pengajuan.nonPengajuan');
    Route::get('/sdm/prosesrekruitment/deletePengajuan/{id}', 'SDM\RecruitmentController@deletePengajuan')->name('pengajuan.deletePengajuan');
    Route::get('/sdm/prosesrekruitment/editPengajuan/{id}', 'SDM\RecruitmentController@editPengajuan')->name('pengajuan.editPengajuan');
    Route::get('/sdm/prosesrekruitment/updatePengajuan', 'SDM\RecruitmentController@updatePengajuan')->name('pengajuan.updatePengajuan');

    Route::get('/sdm/prosesrekruitmen/listTerima', 'SDM\RecruitmentController@getListTerima')->name('rekruitmen.listTerima');

    Route::get('/sdm/prosesrekruitmen/listPublish', 'SDM\RecruitmentController@getListPublish')->name('rekruitmen.listPublish');
    Route::get('/sdm/prosesrekruitment/kelolarekruitment', 'SDM\RecruitmentController@kelola_rekruitment')->name('rekruitment.kelola');
    Route::post('/sdm/prosesrekruitment/approvePublish/{id}', 'SDM\RecruitmentController@approvePublish')->name('rekruitment.approvePublish');
    Route::post('/sdm/prosesrekruitment/rejectPublish/{id}', 'SDM\RecruitmentController@rejectPublish')->name('rekruitment.rejectPublish');
    Route::get('/sdm/prosesrekruitment/deleteLoker/{id}', 'SDM\RecruitmentController@deleteLoker')->name('rekruitment.deleteLoker');
    Route::get('/sdm/prosesrekruitment/editLoker/{id}', 'SDM\RecruitmentController@editLoker')->name('rekruitment.editLoker');
    Route::get('/sdm/prosesrekruitment/updateLoker', 'SDM\RecruitmentController@updateLoker')->name('rekruitment.updateLoker');

    // Aktivitas SDM -> Proses Rekruitmen -> Kelola Posisi SDM
    Route::get('/sdm/prosesrekruitment/get-list-kps', 'SDM\KpsController@getTableKPS')->name('kps.getTableKPS');
    Route::post('/sdm/prosesrekruitment/store', 'SDM\KpsController@store')->name('kps.store');
    Route::get('/sdm/prosesrekruitment/edit/{id}', 'SDM\KpsController@edit')->name('kps.edit');
    Route::post('/sdm/prosesrekruitment/update/{id}', 'SDM\KpsController@update')->name('kps.update');
    Route::post('/sdm/prosesrekruitment/delete/{id}', 'SDM\KpsController@delete')->name('kps.delete');

    // Kinerja
    Route::get('/sdm/kinerjasdm/index', 'SDMController@kinerja')->name('kinerjasdm.index');

    //Master KPI
    Route::post('/sdm/kinerjasdm/master-kpi/create', 'SDM\MasterKPIController@create')->name('masterkpi.create');
    Route::post('/sdm/kinerjasdm/master-kpi/get-data', 'SDM\MasterKPIController@getData')->name('masterkpi.getData');
    Route::post('/sdm/kinerjasdm/master-kpi/nonKpi/{id}', 'SDM\MasterKPIController@nonKpi')->name('masterkpi.nonKpi');
    Route::post('/sdm/kinerjasdm/master-kpi/activeKpi/{id}', 'SDM\MasterKPIController@activeKpi')->name('masterkpi.activeKpi');
    Route::get('/sdm/kinerjasdm/master-kpi/deleteKpi/{id}', 'SDM\MasterKPIController@deleteKpi')->name('masterkpi.deleteKpi');

    // KPI Pegawi
    Route::get('/sdm/kinerjasdm/kpi-pegawai/create', 'SDM\MasterKPIController@kpi_create_p')->name('kpipegawai.create');
    Route::get('/sdm/kinerjasdm/kpi-pegawai/get-kpi-pegawai', 'SDM\MasterKPIController@get_kpi_pegawai')->name('kpipegawai.get_kpi_pegawai');
    Route::get('/sdm/kinerjasdm/kpi-pegawai/get-kpi-employee', 'SDM\MasterKPIController@get_kpi_employee')->name('kpipegawai.get_employee');
    Route::get('/sdm/kinerjasdm/kpi-pegawai/get-kpi-indikator', 'SDM\MasterKPIController@get_kpi_indikator')->name('kpipegawai.get_indikator');
    Route::post('/sdm/kinerjasdm/kpi-pegawai/save-kpi-pegawai', 'SDM\MasterKPIController@save_kpi_pegawai')->name('kpipegawai.save_kpi_pegawai');
    Route::get('/sdm/kinerjasdm/kpi-pegawai/get-detail-kpi-pegawai', 'SDM\MasterKPIController@get_detail_kpi_pegawai')->name('kpipegawai.get_detail_kpi_pegawai');
    Route::get('/sdm/kinerjasdm/kpi-pegawai/edit-kpi-pegawai', 'SDM\MasterKPIController@edit_kpi_pegawai')->name('kpipegawai.edit_kpi_pegawai');
    Route::post('/sdm/kinerjasdm/kpi-pegawai/update-kpi-pegawai', 'SDM\MasterKPIController@update_kpi_pegawai')->name('kpipegawai.update_kpi_pegawai');
    Route::post('/sdm/kinerjasdm/kpi-pegawai/delete-kpi-pegawai/{emp}', 'SDM\MasterKPIController@delete_kpi_pegawai')->name('kpipegawai.delete_kpi_pegawai');

    // KPI Divisi
    Route::get('/sdm/kinerjasdm/kpi-divisi/create', 'SDM\MasterKPIController@kpi_create_d')->name('kpidivisi.create');

    // Absensi -> presensi
    Route::get('/sdm/absensisdm/index', 'SDMController@absensi')->name('absensisdm.index');
    Route::get('/sdm/absensisdm/presensi/get-summary', 'SDM\Absensi\PresensiController@getPresenceSummary')->name('presensi.getPresenceSummary');
    Route::get('/sdm/absensisdm/presensi/get-detail-presence', 'SDM\Absensi\PresensiController@getDetailPresence')->name('presensi.getDetailPresence');
    Route::get('/sdm/absensisdm/presensi/get-branch', 'SDM\Absensi\PresensiController@getBranch')->name('presensi.getBranch');
    Route::get('/sdm/absensisdm/presensi/get-division', 'SDM\Absensi\PresensiController@getDivision')->name('presensi.getDivision');
    Route::get('/sdm/absensisdm/presensi/get-presence', 'SDM\Absensi\PresensiController@getPresence')->name('presensi.getPresence');
    Route::get('/sdm/absensisdm/presensi/get-employee', 'SDM\Absensi\PresensiController@getEmployee')->name('presensi.getEmployee');
    Route::post('/sdm/absensisdm/presensi/store', 'SDM\Absensi\PresensiController@store')->name('presensi.store');
    // Absensi -> Dashboard
    Route::get('/sdm/absensisdm/dashboard/get-presence', 'SDM\Absensi\DashboardController@getPresence')->name('presensiDash.getPresence');
    // Penggajian
    Route::get('/sdm/penggajian/index', 'SDMController@penggajian')->name('penggajian.index');
    // TAB MANAJEMEN
    Route::get('/sdm/penggajian/manajemen/create', 'SDMController@create_manajemen')->name('manajemen.create');
    Route::get('/sdm/penggajian/manajemen/edit', 'SDMController@edit_manajemen')->name('manajemen.edit');
    // END
    // TAB TUNJANGAN
    Route::get('/sdm/penggajian/tunjangan/create', 'SDMController@create_tunjangan')->name('tunjangan.create');
    Route::get('/sdm/penggajian/tunjangan/edit', 'SDMController@edit_tunjangan')->name('tunjangan.edit');
    Route::get('/sdm/penggajian/tunjangan/setting', 'SDMController@set_tunjangan')->name('tunjangan.setting');
    Route::get('/sdm/penggajian/tunjangan/edit_setting_tunjangan', 'SDMController@edit_set_tunjangan')->name('tunjangan.setting.edit');
    // END
    // TAB PRODUKSI
    Route::get('/sdm/penggajian/produksi/create', 'SDMController@create_produksi')->name('produksi.create');
    Route::get('/sdm/penggajian/produksi/edit', 'SDMController@edit_produksi')->name('produksi.edit');
    // END
    // !===================================================== END SDM =====================================================!

    // !===================================================== Marketing =====================================================!
    // Manajemen Marketing
    Route::get('/marketing/manajemenmarketing/index', 'MarketingController@marketing')->name('mngmarketing.index');
    Route::get('/marketing/manajemenmarketing/create-year-promotion', 'MarketingController@year_promotion_create')->name('yearpromotion.create');
    Route::get('/marketing/manajemenmarketing/data-year-promotion', 'MarketingController@getPromosiTahunan')->name('yearpromotion.data');
    Route::get('/marketing/manajemenmarketing/edit-year-promotion', 'MarketingController@year_promotion_edit')->name('yearpromotion.edit');
    Route::post('/marketing/manajemenmarketing/save-year-promotion', 'MarketingController@year_promotion_save')->name('yearpromotion.save');
    Route::post('/marketing/manajemenmarketing/update-year-promotion', 'MarketingController@year_promotion_update')->name('yearpromotion.update');
    Route::get('/marketing/manajemenmarketing/create-month-promotion', 'MarketingController@month_promotion_create')->name('monthpromotion.create');
    Route::get('/marketing/manajemenmarketing/edit-month-promotion', 'MarketingController@month_promotion_edit')->name('monthpromotion.edit');
    Route::get('/marketing/manajemenmarketing/delete-month-promotion', 'MarketingController@month_promotion_delete')->name('monthpromotion.delete');
    Route::post('/marketing/manajemenmarketing/save-month-promotion', 'MarketingController@month_promotion_save')->name('monthpromotion.save');
    Route::post('/marketing/manajemenmarketing/update-month-promotion', 'MarketingController@month_promotion_update')->name('monthpromotion.update');
    Route::get('/marketing/manajemenmarketing/data-month-promotion', 'MarketingController@getPromosiBulanan')->name('monthpromotion.data');
    Route::get('/marketing/manajemenmarketing/detail-promotion', 'MarketingController@detailPromotion')->name('monthpromotion.detailpromosi');
    Route::post('/marketing/manajemenmarketing/done-promotion', 'MarketingController@donePromotion')->name('donepromotion.done');
    Route::get('/marketing/manajemenmarketing/data-history-promotion', 'MarketingController@getHistoryPromotion')->name('historypromotion.data');
    // Penjualan Pusat
    Route::get('/marketing/penjualanpusat/index', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@index')->name('penjualanpusat.index');
    Route::get('/marketing/penjualanpusat/create', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@createTOP')->name('penjualanpusat.createTOP');
    Route::get('/marketing/penjualanpusat/get-table-top', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getTableTOP')->name('penjualanpusat.getTableTOP');
    Route::get('/marketing/penjualanpusat/get-detail-top', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getDetailTOP')->name('penjualanpusat.getDetailTOP');
    Route::post('/marketing/penjualanpusat/delete-top', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@deleteTOP')->name('penjualanpusat.deleteTOP');
    Route::get('/marketing/penjualanpusat/get-detail-send', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getDetailSend')->name('penjualanpusat.getDetailSend');
    Route::get('/marketing/penjualanpusat/get-proses-top', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getProsesTOP')->name('penjualanpusat.getProsesTOP');
    Route::post('/marketing/penjualanpusat/confirm-process-top/{id}', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@confirmProcessTOP')->name('penjualanpusat.confirmProcessTOP');
    Route::get('/marketing/penjualanpusat/orderpenjualan/proses', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@orderpenjualan_proses')->name('orderpenjualan.proses');
    Route::get('/marketing/penjualanpusat/ganti-status', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@changeStatus')->name('penjualanpusat.gantistatus');
    Route::get('/marketing/penjualanpusat/get-harga-satuan', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getPrice')->name('penjualanpusat.gantisatuan');
    Route::get('/marketing/penjualanpusat/get-table-distribusi', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getTableDistribusi')->name('penjualanpusat.getTableDistribusi');
    Route::get('/marketing/penjualanpusat/get-payment-method', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getPaymentMethod')->name('penjualanpusat.getPaymentMethod');
    Route::get('/marketing/penjualanpusat/get-produk-ekspedisi', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getProdukEkspedisi')->name('penjualanpusat.getProdukEkspedisi');
    Route::post('/marketing/penjualanpusat/send-order-penjualan', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@sendOrder')->name('penjualanpusat.sendOrder');
    // Target Realisasi
    Route::get('/marketing/penjualanpusat/targetrealisasi/targetList', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@targetList')->name('targetReal.list');
    Route::get('/marketing/penjualanpusat/targetrealisasi', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@createTargetReal')->name('targetReal.create');
    Route::get('/marketing/penjualanpusat/targetrealisasi/get-periode', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getPeriode')->name('targetReal.getPeriode');
    Route::get('/marketing/penjualanpusat/targetrealisasi/cari-barang', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@cariBarang')->name('targetReal.caribarang');
    Route::get('/marketing/penjualanpusat/targetrealisasi/get-satuan/{id}', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getSatuan')->name('targetReal.getsatuan');
    Route::get('/marketing/penjualanpusat/targetrealisasi/get-company', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getComp')->name('targetReal.getcomp');
    Route::get('/marketing/penjualanpusat/targetrealisasi/store', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@targetRealStore')->name('targetReal.store');
    Route::get('/marketing/penjualanpusat/targetrealisasi/editTarget/{st_id}/{dt_id}', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@editTarget')->name('targetReal.edit');
    Route::get('/marketing/penjualanpusat/targetrealisasi/updateTarget/{st_id}/{dt_id}', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@updateTarget')->name('targetReal.update');
    // Penerimaan Piutang ---------------------------
    Route::get('/marketing/penjualanpusat/penerimaanpiutang/get-list', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@listPiutang')->name('piutang.list');
    Route::get('/marketing/penjualanpusat/penerimaanpiutang/cari-nota', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@cariNota')->name('piutang.cariNota');
    Route::get('/marketing/penjualanpusat/penerimaanpiutang/get-list/{nota}', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@listPiutang')->name('piutang.list');
    Route::get('/marketing/penjualanpusat/penerimaanpiutang/save-payment', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@savePayment')->name('piutang.savePayment');
    Route::get('/get-provinsi', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getProvinsi')->name('get.provinsi');
    Route::get('/get-city/{id}', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getCity')->name('get.city');
    Route::get('/marketing/penjualanpusat/penerimaanpiutang/get-agen/{id}', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getAgen')->name('piutang.getAgen');
    Route::get('/marketing/penjualanpusat/penerimaanpiutang/get-nota-agen/{id}', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getNotaAgen')->name('piutang.getNotaAgen');
    // End ---
    // Return Penjualan
    Route::post('/marketing/penjualanpusat/returnpenjualan/index', 'Aktivitasmarketing\Penjualanpusat\ReturnPenjualanController@index')->name('returnpenjualanagen.index');
    Route::get('/marketing/penjualanpusat/returnpenjualan/create', 'Aktivitasmarketing\Penjualanpusat\ReturnPenjualanController@create')->name('returnpenjualanagen.create');
    Route::get('/marketing/penjualanpusat/returnpenjualan/getcity', 'Aktivitasmarketing\Penjualanpusat\ReturnPenjualanController@getCity')->name('returnpenjualanagen.getCity');
    Route::get('/marketing/penjualanpusat/returnpenjualan/getagent', 'Aktivitasmarketing\Penjualanpusat\ReturnPenjualanController@getAgent')->name('returnpenjualanagen.getAgent');
    Route::get('/marketing/penjualanpusat/returnpenjualan/getprodcode', 'Aktivitasmarketing\Penjualanpusat\ReturnPenjualanController@getProdCode')->name('returnpenjualanagen.getProdCode');
    Route::get('/marketing/penjualanpusat/returnpenjualan/getnota', 'Aktivitasmarketing\Penjualanpusat\ReturnPenjualanController@getNota')->name('returnpenjualanagen.getNota');
    Route::get('/marketing/penjualanpusat/returnpenjualan/getdata', 'Aktivitasmarketing\Penjualanpusat\ReturnPenjualanController@getData')->name('returnpenjualanagen.getData');
    Route::get('/marketing/penjualanpusat/returnpenjualan/getprodcodesubstitute', 'Aktivitasmarketing\Penjualanpusat\ReturnPenjualanController@getProdCodeSubstitute')->name('returnpenjualanagen.getProdCodeSubstitute');
    // Route::get('/marketing/penjualanpusat/returnpenjualan/simpan', 'Aktivitasmarketing\Penjualanpusat\ReturnPenjualanController@returnpenjualanagen_simpan');
    Route::post('/marketing/penjualanpusat/returnpenjualan/simpan', 'Aktivitasmarketing\Penjualanpusat\ReturnPenjualanController@store')->name('returnpenjualanagen.store');
    Route::post('/marketing/penjualanpusat/returnpenjualan/hapus/{id}', 'Aktivitasmarketing\Penjualanpusat\ReturnPenjualanController@delete')->name('returnpenjualanagen.delete');
    // End ---
    // Konsinyasi Pusat
    Route::get('/marketing/konsinyasipusat/index', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@konsinyasipusat')->name('konsinyasipusat.index');
    Route::get('/marketing/konsinyasipusat/get-konsinyasi', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@getKonsinyasi')->name('konsinyasipusat.getData');
    Route::get('/marketing/konsinyasipusat/detail-konsinyasi/{id}/{action}', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@detailKonsinyasi')->name('konsinyasipusat.detail');
    Route::get('/marketing/konsinyasipusat/get-provinsi', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@getProv')->name('konsinyasipusat.getProv');
    Route::get('/marketing/konsinyasipusat/get-kota/{idprov}', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@getKota')->name('konsinyasipusat.getKota');
    Route::get('/marketing/konsinyasipusat/cari-konsigner-select2/{idprov}/{idkota}', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@carikonsignerselect2')->name('konsinyasipusat.carikonsignerselect2');
    Route::get('/marketing/konsinyasipusat/cari-konsigner/{idprov}/{idkota}', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@cariKonsigner')->name('konsinyasipusat.carikonsigner');
    Route::get('/marketing/konsinyasipusat/cari-barang', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@cariBarangKonsinyasi')->name('konsinyasipusat.caribarang');
    Route::get('/marketing/konsinyasipusat/get-satuan/{id}', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@getSatuan')->name('konsinyasipusat.getsatuan');
    Route::get('/marketing/konsinyasipusat/cek-stok/{stock}/{item}/{satuan}/{qty}', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@checkStock')->name('konsinyasipusat.checkstock');
    Route::get('/marketing/konsinyasipusat/cek-stok-old/{stock}/{item}/{oldSatuan}/{satuan}/{qtyOld}/{qty}', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@checkStockOld')->name('konsinyasipusat.checkstockold');
    Route::get('/marketing/konsinyasipusat/cek-harga/{konsigner}/{item}/{unit}/{qty}', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@checkHarga')->name('konsinyasipusat.checkharga');
    Route::get('/marketing/konsinyasipusat/penempatanproduk/create', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@create_penempatanproduk')->name('penempatanproduk.create');
    Route::post('/marketing/konsinyasipusat/penempatanproduk/add', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@add_penempatanproduk')->name('penempatanproduk.add');
    Route::match(['get', 'post'],'/marketing/konsinyasipusat/penempatanproduk/edit/{id}', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@edit_penempatanproduk')->name('penempatanproduk.edit');
    Route::get('/marketing/konsinyasipusat/penempatanproduk/hapus', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@deletePenempatanproduk')->name('penempatanproduk.delete');
    // --- Konsinyasi pusat: Monitoring penjualan
    Route::get('/marketing/konsinyasipusat/monitoringpenjualan/get-list', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@getListMP')->name('monitoringpenjualan.getListMP');
    Route::get('/marketing/konsinyasipusat/monitoringpenjualan/get-cities', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@getCitiesMP')->name('monitoringpenjualan.getCitiesMP');
    Route::get('/marketing/konsinyasipusat/monitoringpenjualan/get-agents', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@getAgentsMP')->name('monitoringpenjualan.getAgentsMP');
    Route::get('/marketing/konsinyasipusat/monitoringpenjualan/find-agents-au', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@findAgentsByAu')->name('monitoringpenjualan.findAgentsByAu');
    Route::get('/marketing/konsinyasipusat/monitoringpenjualan/get-sales-detail/{id}', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@getSalesCompDetail')->name('monitoringpenjualan.getSalesCompDetail');
    // --- Konsinyasi pusat: Penerimaan Uang Pembayaran
    Route::get('/marketing/konsinyasipusat/penerimaanpembayaran/get-nota', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@getListNotaPP')->name('penerimaanpembayaran.getListNotaPP');
    Route::get('/marketing/konsinyasipusat/penerimaanpembayaran/get-payment', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@getPaymentPP')->name('penerimaanpembayaran.getPaymentPP');
    Route::post('/marketing/konsinyasipusat/penerimaanpembayaran/store', 'Aktivitasmarketing\Konsinyasipusat\KonsinyasiPusatController@storePP')->name('penerimaanpembayaran.storePP');

    // Marketing Area
    Route::get('/marketing/marketingarea/index', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@index')->name('marketingarea.index');
    Route::get('/marketing/marketingarea/get-expedition', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getExpedition')->name('marketingarea.getExpedition');
    Route::get('/marketing/marketingarea/get-payment-method', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getPaymentMethod')->name('marketingarea.getPaymentMethod');
    Route::get('/marketing/marketingarea/get-expeditionType/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getExpeditionType')->name('marketingarea.getExpeditionType');
    // Order Produk Ke Cabang
    Route::get('/marketing/marketingarea/orderproduk/create', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@createOrderProduk')->name('orderProduk.create');
    Route::get('/marketing/marketingarea/orderproduk/list', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@orderList')->name('orderProduk.list');
    Route::get('/marketing/marketingarea/orderproduk/store', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@orderProdukStore')->name('orderProduk.store');
    Route::get('/marketing/marketingarea/orderproduk/edit/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@editOrderProduk')->name('orderProduk.edit');
    Route::get('/marketing/marketingarea/orderproduk/update/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@updateOrderProduk')->name('orderProduk.update');
    Route::get('/marketing/marketingarea/orderproduk/get-city', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getCity')->name('orderProduk.getCity');
    Route::get('/marketing/marketingarea/orderproduk/get-agen', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getAgen')->name('orderProduk.getAgen');
    Route::get('/marketing/marketingarea/orderproduk/cari-barang', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@cariBarang')->name('orderProduk.cariBarang');
    Route::get('/marketing/marketingarea/orderproduk/get-satuan/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getSatuan')->name('orderProduk.getSatuan');
    Route::get('/marketing/marketingarea/orderproduk/get-price', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@cekHarga')->name('orderProduk.getPrice');
    Route::get('/marketing/marketingarea/orderproduk/delete-order/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@deleteOrder')->name('orderProduk.delete');
    Route::get('/marketing/marketingarea/orderproduk/nota/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@printNota')->name('orderProduk.nota');
    Route::get('/marketing/marketingarea/orderproduk/show-detail-ac/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@showDetailAc')->name('orderProduk.showDetailAc');
    Route::get('/marketing/marketingarea/terima-barang/get-kode-produksi', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getKodeProduksi')->name('orderProduk.getKodeProduksi');
    Route::post('/marketing/marketingarea/orderproduk/set-acceptance/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@setAcceptance')->name('orderProduk.setAcceptance');
    Route::get('/marketing/marketingarea/orderproduk/detail/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@detailOrder')->name('orderProduk.detail');
    // End Order Ke Cabang ==================

    // Kelola Data Order Agen ========================================================================================
    Route::get('/marketing/marketingarea/keloladataorder/list-agen/{status}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@listAgen')->name('keloladataorder.listAgen');
    Route::get('/marketing/marketingarea/keloladataorder/get-agen', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getDataAgen')->name('keloladataorder.getDataAgen');
    Route::get('/marketing/marketingarea/keloladataorder/cari-agen', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@cariDataAgen')->name('keloladataorder.cariDataAgen');
    Route::get('/marketing/marketingarea/keloladataorder/filter-agen', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@filterDataAgen')->name('keloladataorder.filterDataAgen');
    Route::get('/marketing/marketingarea/keloladataorder/get-detail-order', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getDetailOrder')->name('keloladataorder.getdetailorder');
    Route::get('/marketing/marketingarea/keloladataorder/get-detail-order-agen', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getDetailOrderAgen')->name('keloladataorder.getdetailorderagen');
    Route::get('/marketing/marketingarea/keloladataorder/get-detail-code-order', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getCodeOrder')->name('keloladataorder.getdetailcodeorder');
    Route::get('/marketing/marketingarea/keloladataorder/detail-agen/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@detailAgen')->name('keloladataorder.detailAgen');
    Route::get('/marketing/marketingarea/keloladataorder/get-harga', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getPrice')->name('keloladataorder.getPrice');
    Route::get('/marketing/marketingarea/keloladataorder/set-kode', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@setKode')->name('keloladataorder.setKode');
    Route::get('/marketing/marketingarea/keloladataorder/hapus-kode', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@removeKode')->name('keloladataorder.removeKode');
    Route::post('/marketing/marketingarea/keloladataorder/reject-agen/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@rejectAgen')->name('keloladataorder.rejectAgen');
    Route::post('/marketing/marketingarea/keloladataorder/activate-agen/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@activateAgen')->name('keloladataorder.activateAgen');
    Route::post('/marketing/marketingarea/keloladataorder/approve-agen/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@approveAgen')->name('keloladataorder.approveAgen');
    Route::post('/marketing/marketingarea/keloladataorder/reject-approve-agen/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@rejectApproveAgen')->name('keloladataorder.rejectApproveAgen');
    Route::get('/marketing/marketingarea/keloladataorder/show-detail-ac/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@showDetailAcOrderAgen')->name('keloladataorder.showDetailAcOrderAgen');
    Route::get('/marketing/marketingarea/keloladataorder/get-kode-produksi', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getKodeProduksiOrderAgen')->name('keloladataorder.getKodeProduksiOrderAgen');
    Route::post('/marketing/marketingarea/keloladataorder/receive-item-order/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@receiveItemOrder')->name('keloladataorder.receiveItemOrder');
    // End Order Agen ================================================================================================

    // Start Kelola Data Canvassing ================================================================================================
    Route::get('/marketing/marketingarea/datacanvassing/get-list', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getListDC')->name('datacanvassing.getListDC');
    Route::get('/marketing/marketingarea/datacanvassing/get-cities', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getCitiesDC')->name('datacanvassing.getCitiesDC');
    Route::get('/marketing/marketingarea/datacanvassing/get-agents', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getAgentsDC')->name('datacanvassing.getAgentsDC');
    Route::get('/marketing/marketingarea/datacanvassing/find-agents-by-au', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@findAgentsByAu')->name('datacanvassing.findAgentsByAu');
    Route::get('/marketing/marketingarea/datacanvassing/create', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@createDC')->name('datacanvassing.create');
    Route::post('/marketing/marketingarea/datacanvassing/store', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@storeDC')->name('datacanvassing.store');
    Route::get('/marketing/marketingarea/datacanvassing/edit/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@editDC')->name('datacanvassing.edit');
    Route::post('/marketing/marketingarea/datacanvassing/update/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@updateDC')->name('datacanvassing.update');
    Route::post('/marketing/marketingarea/datacanvassing/delete/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@deleteDC')->name('datacanvassing.delete');
    // End Kelola Data Canvassing ================================================================================================

    // Start Manajemen Data Penjualan Agen ================================================================================================
    Route::get('/marketing/marketingarea/manajemenpenjualanagen/get-list', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getListMPA')->name('manajemenpenjualanagen.getListMPA');
    Route::get('/marketing/marketingarea/manajemenpenjualanagen/get-detail/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getDetailMPA')->name('manajemenpenjualanagen.getDetailMPA');
    // End Manajemen Data Penjualan Agen ================================================================================================

    // Start: MMA kelola data konsinyasi ================================================================================================
    Route::get('/marketing/marketingarea/datakonsinyasi/get-list', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getListDK')->name('datakonsinyasi.getListDK');
    Route::get('/marketing/marketingarea/datakonsinyasi/create', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@create_datakonsinyasi')->name('datakonsinyasi.create');
    Route::get('/marketing/marketingarea/datakonsinyasi/get-branch', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getBranchDK')->name('datakonsinyasi.getBranchDK');
    Route::get('/marketing/marketingarea/datakonsinyasi/get-agents', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getAgentsDK')->name('datakonsinyasi.getAgentsDK');
    Route::get('/marketing/marketingarea/datakonsinyasi/get-items', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getItemsDK')->name('datakonsinyasi.getItemsDK');
    Route::get('/marketing/marketingarea/datakonsinyasi/get-satuan/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getSatuanDK')->name('datakonsinyasi.getSatuanDK');
    Route::get('/marketing/marketingarea/datakonsinyasi/check-items-stock', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@checkItemStockDK')->name('datakonsinyasi.checkItemStockDK');
    Route::get('/marketing/marketingarea/datakonsinyasi/check-items-stock-old', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@checkItemStockDKOld')->name('datakonsinyasi.checkItemStockDKOld');
    Route::get('/marketing/marketingarea/datakonsinyasi/check-harga', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@checkHargaDK')->name('datakonsinyasi.checkHargaDK');
    Route::post('/marketing/marketingarea/datakonsinyasi/store', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@storeDK')->name('datakonsinyasi.storeDK');
    Route::get('/marketing/marketingarea/datakonsinyasi/edit/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@edit_datakonsinyasi')->name('datakonsinyasi.edit');
    Route::post('/marketing/marketingarea/datakonsinyasi/update/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@updateDK')->name('datakonsinyasi.updateDK');
    Route::post('/marketing/marketingarea/datakonsinyasi/delete', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@deleteDK')->name('datakonsinyasi.deleteDK');
    // End: MMA kelola data konsinyasi ================================================================================================

    // Start: MMA Pembayaran Piutang ===================================================================================
    Route::get('/marketing/marketingarea/penerimaanpiutang/getdata', 'Aktivitasmarketing\Marketingarea\MMAPenerimaanPiutangController@getData')->name('mmapenerimaanpiutang.getdata');
    Route::get('/marketing/marketingarea/penerimaanpiutang/get-data-agen', 'Aktivitasmarketing\Marketingarea\MMAPenerimaanPiutangController@getDataAgen')->name('mmapenerimaanpiutang.getDataAgen');
    Route::get('/marketing/marketingarea/penerimaanpiutang/get-detail-transaksi', 'Aktivitasmarketing\Marketingarea\MMAPenerimaanPiutangController@getDetailTransaksi')->name('mmapenerimaanpiutang.getDetailTransaksi');
    Route::get('/marketing/marketingarea/penerimaanpiutang/bayar-piutang', 'Aktivitasmarketing\Marketingarea\MMAPenerimaanPiutangController@bayarPiutang')->name('mmapenerimaanpiutang.bayarPiutang');
    // End: MMA Pembayaran Piutang =====================================================================================

    // Start: MMA Return Penjualan ===================================================================================
    Route::post('/marketing/marketingarea/returnpenjualan/index', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@index')->name('mmareturn.index');
    Route::get('/marketing/marketingarea/returnpenjualan/create', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@create')->name('mmareturn.create');
    Route::get('/marketing/marketingarea/returnpenjualan/get-agent', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@getAgent')->name('mmareturn.getAgent');
    Route::get('/marketing/marketingarea/returnpenjualan/get-prod-code', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@getProdCode')->name('mmareturn.getProdCode');
    Route::get('/marketing/marketingarea/returnpenjualan/get-prod-code-subs', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@getProdCodeSubstitute')->name('mmareturn.getProdCodeSubstitute');
    Route::get('/marketing/marketingarea/returnpenjualan/get-nota', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@getNota')->name('mmareturn.getNota');
    Route::get('/marketing/marketingarea/returnpenjualan/find-item', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@findItem')->name('mmareturn.findItem');
    Route::get('/marketing/marketingarea/returnpenjualan/find-all-item', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@findAllItem')->name('mmareturn.findAllItem');
    Route::get('/marketing/marketingarea/returnpenjualan/get-unit/{id}', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@getUnit')->name('mmareturn.getUnit');
    Route::get('/marketing/marketingarea/returnpenjualan/cek-stok/{stock}/{item}/{satuan}/{qty}', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@checkStock')->name('mmareturn.checkstock');
    Route::get('/marketing/marketingarea/returnpenjualan/get-price', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@getPrice')->name('mmareturn.getPrice');
    Route::get('/marketing/marketingarea/returnpenjualan/get-data', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@getData')->name('mmareturn.getData');
    Route::post('/marketing/marketingarea/returnpenjualan/store', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@store')->name('mmareturn.store');
    Route::get('/marketing/marketingarea/returnpenjualan/detail/{id}', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@detail')->name('mmareturn.detail');
    Route::post('/marketing/marketingarea/returnpenjualan/delete/{id}', 'Aktivitasmarketing\Marketingarea\ReturnPenjualanController@delete')->name('mmareturn.delete');

    // End: MMA Return Penjualan ===================================================================================

    // Manajemen Agen ===============================================================================================
    // tambahan dirga
        Route::get('/marketing/agen/laporan', 'Aktivitasmarketing\Agen\ManajemenAgenController@getLaporan')->name('agen.laporan');

    Route::get('/marketing/agen/index', 'Aktivitasmarketing\Agen\ManajemenAgenController@index')->name('manajemenagen.index');
    Route::get('/marketing/agen/get-agen/{city}', 'Aktivitasmarketing\Agen\ManajemenAgenController@getAgen')->name('manajemenagen.getAgen');
    Route::post('/marketing/agen/filter-data/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@filterData')->name('manajemenagen.filterData');
    Route::get('/marketing/agen/get-detail-inventory/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@getDetail_inventory')->name('inventoryAgen.getDetail');

    Route::get('/marketing/agen/kelolapenjualanlangsung/get-list-kpl', 'Aktivitasmarketing\Agen\ManajemenAgenController@getListKPL')->name('kelolapenjualan.getListKPL');
    Route::get('/marketing/agen/kelolapenjualanlangsung/get-cities', 'Aktivitasmarketing\Agen\ManajemenAgenController@getCitiesKPL')->name('kelolapenjualan.getCitiesKPL');
    Route::get('/marketing/agen/kelolapenjualanlangsung/get-agents-kpl', 'Aktivitasmarketing\Agen\ManajemenAgenController@getAgentsKPL')->name('kelolapenjualan.getAgentsKPL');
    Route::get('/marketing/agen/kelolapenjualanlangsung/get-detail-penjualan', 'Aktivitasmarketing\Agen\ManajemenAgenController@getDetailPenjualan')->name('kelolapenjualan.getDetailPenjualan');
    Route::post('/marketing/agen/kelolapenjualanlangsung/delete-detail-penjualan', 'Aktivitasmarketing\Agen\ManajemenAgenController@deleteDetailPenjualan')->name('kelolapenjualan.deleteDetailPenjualan');
    Route::get('/marketing/agen/kelolapenjualanlangsung/create', 'Aktivitasmarketing\Agen\ManajemenAgenController@createKPL')->name('kelolapenjualan.create');
    Route::get('/marketing/agen/kelolapenjualanlangsung/get-member-kpl', 'Aktivitasmarketing\Agen\ManajemenAgenController@getMemberKPL')->name('kelolapenjualan.getMemberKPL');
    Route::get('/marketing/agen/kelolapenjualanlangsung/find-item', 'Aktivitasmarketing\Agen\ManajemenAgenController@findItem')->name('kelolapenjualan.findItem');
    Route::post('/marketing/agen/kelolapenjualanlangsung/find-agen', 'Aktivitasmarketing\Agen\ManajemenAgenController@getAgenKPL')->name('kelolapenjualan.getAgenKPL');
    // Route::get('/marketing/agen/kelolapenjualanlangsung/get-item-stock', 'Aktivitasmarketing\Agen\ManajemenAgenController@getItemStock')->name('kelolapenjualan.getItemStock');
    Route::get('/marketing/agen/kelolapenjualanlangsung/get-price', 'Aktivitasmarketing\Agen\ManajemenAgenController@getPrice')->name('kelolapenjualan.getPrice');
    Route::post('/marketing/agen/kelolapenjualanlangsung/store', 'Aktivitasmarketing\Agen\ManajemenAgenController@storeKPL')->name('kelolapenjualan.storeKPL');
    Route::get('/marketing/agen/kelolapenjualanlangsung/get-unit/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@getUnit')->name('kelolapenjualan.getUnit');
    Route::get('/marketing/agen/kelolapenjualanlangsung/edit/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@editKPL')->name('kelolapenjualan.edit');
    Route::post('/marketing/agen/kelolapenjualanlangsung/update/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@updateKPL')->name('kelolapenjualan.update');

    Route::get('/marketing/agen/kelolapenjualanviawebsite/create', 'Aktivitasmarketing\Agen\ManajemenAgenController@createKPW')->name('kelolapenjualanviawebsite.create');
    Route::get('/marketing/agen/kelolapenjualanviawebsite/cari-produk', 'Aktivitasmarketing\Agen\ManajemenAgenController@cariProduk')->name('kelolapenjualanviawebsite.cariProduk');
    Route::get('/marketing/agen/kelolapenjualanviawebsite/get-unit', 'Aktivitasmarketing\Agen\ManajemenAgenController@getUnit')->name('kelolapenjualanviawebsite.getUnit');
    Route::get('/marketing/agen/kelolapenjualanviawebsite/get-stock-kpw', 'Aktivitasmarketing\Agen\ManajemenAgenController@getStockKPW')->name('kelolapenjualanviawebsite.getStockKPW');
    Route::post('/marketing/agen/kelolapenjualanviawebsite/save-kpw', 'Aktivitasmarketing\Agen\ManajemenAgenController@storeKPW')->name('kelolapenjualanviawebsite.storeKPW');
    Route::get('/marketing/agen/kelolapenjualanviawebsite/cek-code', 'Aktivitasmarketing\Agen\ManajemenAgenController@cekProductionCode')->name('kelolapenjualanviawebsite.cekProductionCode');
    Route::get('/marketing/agen/kelolapenjualanviawebsite/edit-kpw/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@editKPW')->name('kelolapenjualanviawebsite.editKPW');
    Route::post('/marketing/agen/kelolapenjualanviawebsite/update-kpw/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@updateKPW')->name('kelolapenjualanviawebsite.updateKPW');
    Route::get('/marketing/agen/kelolapenjualanviawebsite/get-list-kpw', 'Aktivitasmarketing\Agen\ManajemenAgenController@getListKPW')->name('kelolapenjualanviawebsite.getListKPW');
    Route::get('/marketing/agen/kelolapenjualanviawebsite/get-detail-kpw', 'Aktivitasmarketing\Agen\ManajemenAgenController@getDetailKPW')->name('kelolapenjualanviawebsite.getDetailKPW');
    Route::get('/marketing/agen/kelolapenjualanviawebsite/delete-kpw', 'Aktivitasmarketing\Agen\ManajemenAgenController@deleteKPW')->name('kelolapenjualanviawebsite.deleteKPW');

    // End Manajemen Agen ======================================================================================================================================================================================

    Route::get('/marketing/agen/orderproduk/create', 'Aktivitasmarketing\Agen\ManajemenAgenController@create_orderprodukagencabang')->name('orderagenpusat.create');
    Route::get('/marketing/agen/orderproduk/get-provinsi', 'Aktivitasmarketing\Agen\ManajemenAgenController@getProv')->name('orderagenpusat.getprovinsi');
    Route::get('/marketing/agen/orderproduk/get-kota/{idprov}', 'Aktivitasmarketing\Agen\ManajemenAgenController@getKota')->name('orderagenpusat.getkota');
    Route::get('/marketing/agen/orderproduk/cari-penjual/{prov}/{kota}', 'Aktivitasmarketing\Agen\ManajemenAgenController@cariPenjual')->name('orderagenpusat.caripenjual');
    Route::get('/marketing/agen/orderproduk/get-penjual/{kota}', 'Aktivitasmarketing\Agen\ManajemenAgenController@getPenjual')->name('orderagenpusat.getpenjual');
    Route::get('/marketing/agen/orderproduk/cari-pembeli/{kode}', 'Aktivitasmarketing\Agen\ManajemenAgenController@cariPembeli')->name('orderagenpusat.caripembeli');
    Route::get('/marketing/agen/orderproduk/get-pembeli/{kode}', 'Aktivitasmarketing\Agen\ManajemenAgenController@getPembeli')->name('orderagenpusat.getpembeli');
    Route::get('/marketing/agen/orderproduk/cari-barang', 'Aktivitasmarketing\Agen\ManajemenAgenController@cariBarang')->name('orderagenpusat.caribarang');
    Route::get('/marketing/agen/orderproduk/get-satuan/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@getSatuan')->name('orderagenpusat.getunit');
    Route::get('/marketing/agen/orderproduk/cek-stok/{stock}/{item}/{satuan}/{qty}', 'Aktivitasmarketing\Agen\ManajemenAgenController@checkStock')->name('orderagenpusat.checkstock');
    Route::get('/marketing/agen/orderproduk/cek-harga/{konsigner}/{item}/{unit}/{qty}', 'Aktivitasmarketing\Agen\ManajemenAgenController@checkHarga')->name('orderagenpusat.checkharga');
    Route::post('/marketing/agen/orderproduk/add', 'Aktivitasmarketing\Agen\ManajemenAgenController@simpanOrderProduk')->name('orderagenpusat.add');
    Route::get('/marketing/agen/orderproduk/add', 'Aktivitasmarketing\Agen\ManajemenAgenController@simpanOrderProduk')->name('orderagenpusat.add');
    Route::get('/marketing/agen/orderproduk/delivery-order', 'Aktivitasmarketing\Agen\ManajemenAgenController@getOrder')->name('orderagenpusat.getDO');
    Route::get('/marketing/agen/orderproduk/hapus-delivery-order/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@deleteDO')->name('orderagenpusat.deleteDO');
    Route::get('/marketing/agen/orderproduk/detail-delivery-order/{id}/{action}', 'Aktivitasmarketing\Agen\ManajemenAgenController@detailDO')->name('orderagenpusat.detailDO');
    Route::get('/marketing/agen/orderproduk/get-detail-do-accept/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@getDetailDOAccept')->name('orderagenpusat.getDetailDOAccept');
    Route::get('/marketing/agen/orderproduk/get-detail-do-code', 'Aktivitasmarketing\Agen\ManajemenAgenController@getDetailDOCode')->name('orderagenpusat.getDetailDOCode');
    Route::post('/marketing/agen/orderproduk/terima-delivery-order/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@terimaDO')->name('orderagenpusat.terimaDO');
    Route::get('/marketing/agen/orderproduk/get-cabang', 'Aktivitasmarketing\Agen\ManajemenAgenController@getCabang')->name('orderagenpusat.getcabang');
    Route::get('/marketing/agen/orderproduk/get-pembeli-cabang/{prov}/{kota}', 'Aktivitasmarketing\Agen\ManajemenAgenController@getPembeliCabang')->name('orderagenpusat.getpembelicabang');
    Route::get('/marketing/agen/orderproduk/edit', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@edit_orderprodukagenpusat')->name('orderagenpusat.edit');

    Route::get('/marketing/agen/konsinyasi/get-provinsi', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@getProv')->name('konsinyasiAgen.getProv');
    Route::get('/marketing/agen/konsinyasi/get-kota/{idprov}', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@getkota')->name('konsinyasiAgen.getKota');
    Route::get('/marketing/agen/konsinyasi/get-agents', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@getAgentsDK')->name('konsinyasiAgen.getAgentsDK');
    Route::get('/marketing/agen/konsinyasi/get-konsigner', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@getKonsignerDK')->name('konsinyasiAgen.getKonsignerDK');
    Route::get('/marketing/agen/konsinyasi/get-items', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@getItemsDK')->name('konsinyasiAgen.getItemsDK');
    Route::get('/marketing/agen/konsinyasi/get-satuan/{id}', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@getSatuanDK')->name('konsinyasiAgen.getSatuanDK');
    Route::get('/marketing/agen/konsinyasi/check-items-stock', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@checkItemStockDK')->name('konsinyasiAgen.checkItemStockDK');
    Route::get('/marketing/agen/konsinyasi/check-items-stock-old', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@checkItemStockDKOld')->name('konsinyasiAgen.checkItemStockDKOld');
    Route::get('/marketing/agen/konsinyasi/check-harga', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@checkHargaDK')->name('konsinyasiAgen.checkHargaDK');
    Route::post('/marketing/agen/konsinyasi/store', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@storeDK')->name('konsinyasiAgen.storeDK');
    Route::get('/marketing/agen/konsinyasi/index', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@index')->name('konsinyasiAgen.index');
    Route::get('/marketing/agen/konsinyasi/get-list', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@getListDK')->name('konsinyasiAgen.getListDK');
    Route::get('/marketing/agen/konsinyasi/edit/{id}', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@editDK')->name('konsinyasiAgen.editDK');
    Route::post('/marketing/agen/konsinyasi/update/{id}', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@updateDK')->name('konsinyasiAgen.updateDK');
    Route::post('/marketing/agen/konsinyasi/delete/{id}', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@deleteDK')->name('konsinyasiAgen.deleteDK');

    Route::get('/marketing/agen/konsinyasi/pembayaran', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@bayar')->name('konsinyasiAgen.bayar');
    Route::get('/marketing/agen/konsinyasi/get-data-pembayaran', 'Aktivitasmarketing\Agen\AgenKonsinyasiController@getData')->name('konsinyasiAgen.getData');
    // !===================================================== END Marketing =====================================================!

    // !===================================================== KEUANGAN =====================================================!
    // Input Transaksi
    Route::get('/keuangan/inputtransaksi/index', 'KeuanganController@inputtransaksi_index')->name('inputtransaksi.index');
    Route::get('/keuangan/inputtransaksi/transaksi/kas', 'KeuanganController@create_transaksikas')->name('transaksikas.create');
    Route::get('/keuangan/inputtransaksi/transaksi/bank', 'KeuanganController@create_transaksibank')->name('transaksibank.create');
    Route::get('/keuangan/inputtransaksi/transaksi/memorial', 'KeuanganController@create_transaksimemorial')->name('transaksimemorial.create');
    // Laporan Keuangan
    Route::get('/keuangan/laporankeuangan/index', 'KeuanganController@laporankeuangan_index')->name('laporankeuangan.index');
    Route::get('/keuangan/laporankeuangan/jurnal/index', 'KeuanganController@laporankeuangan_jurnal')->name('laporankeuangan.jurnal');
    Route::get('/keuangan/laporankeuangan/bukubesar/index', 'KeuanganController@laporankeuangan_bukubesar')->name('laporankeuangan.bukubesar');
    Route::get('/keuangan/laporankeuangan/neraca/index', 'KeuanganController@laporankeuangan_neraca')->name('laporankeuangan.neraca');
    Route::get('/keuangan/laporankeuangan/labarugi/index', 'KeuanganController@laporankeuangan_labarugi')->name('laporankeuangan.labarugi');
    Route::get('/keuangan/laporankeuangan/aruskas/index', 'KeuanganController@laporankeuangan_aruskas')->name('laporankeuangan.aruskas');
    // Penerimaan Piutang
    Route::get('/keuangan/penerimaanpiutang/index', 'Keuangan\penerimaanpiutang\PenerimaanPiutangController@index')->name('penerimaanpiutang.index');
    Route::get('/keuangan/penerimaanpiutang/pembayarancabang/get-data-list-cabang', 'Keuangan\penerimaanpiutang\PenerimaanPiutangController@getDataListCabang')->name('pembayarancabang.getdatalistcabang');
    // !===================================================== END KEUANGAN =====================================================!

    // !===================================================== PENGATURAN =====================================================!
    // Perubahan Harga Jual
    Route::get('/pengaturan/otoritas/perubahanhargajual/index', 'SettingController@perubahanhargajual_index')->name('perubahanhargajual.index');
    Route::get('/pengaturan/pengaturanpengguna/index', 'SettingController@pengaturanpengguna_index')->name('pengaturanpengguna.index');
    Route::post('/pengaturan/pengaturanpengguna/datatable', 'SettingController@datatable')->name('pengaturanpengguna.datatable');
    Route::get('/pengaturan/pengaturanpengguna/datatable', 'SettingController@datatable')->name('pengaturanpengguna.datatable');
    Route::get('/pengaturan/pengaturanpengguna/akses', 'SettingController@pengaturanpengguna_akses')->name('pengaturanpengguna.akses');
    Route::get('/pengaturan/pengaturanpengguna/create', 'SettingController@pengaturanpengguna_create')->name('pengaturanpengguna.create');
    Route::get('/pengaturan/pengaturanpengguna/simpan', 'SettingController@pengaturanpengguna_simpan')->name('pengaturanpengguna.simpan');
    Route::get('/pengaturan/pengaturanpengguna/hapus', 'SettingController@pengaturanpengguna_hapus')->name('pengaturanpengguna.hapus');
    Route::get('/pengaturan/pengaturanpengguna/updatepassword', 'SettingController@pengaturanpengguna_updatepassword')->name('pengaturanpengguna.updatepassword');
    Route::get('/pengaturan/pengaturanpengguna/edit', 'SettingController@pengaturanpengguna_edit')->name('pengaturanpengguna.edit');
    Route::post('/pengaturan/pengaturanpengguna/simpanakses', 'SettingController@pengaturanpengguna_simpanakses')->name('pengaturanpengguna.simpanakses');
    Route::get('/pengaturan/pengaturanpengguna/updatelevel', 'SettingController@pengaturanpengguna_updatelevel')->name('pengaturanpengguna.updatelevel');
    // !===================================================== END PENGATURAN =====================================================!

    // !================================================== OTORISASI NOTIFIKASI ==============================================!
    Route::get('/notifikasiotorisasi/otorisasi/index', 'OtorisasiController@otorisasi')->name('otorisasi');

    // Sub Otorisasi
    //== otorisasi perubahan harga jual
    Route::get('/notifikasiotorisasi/otorisasi/perubahanhargajual/index', 'OtorisasiController@perubahanhargajual')->name('perubahanhargajual');
    Route::get('notifikasiotorisasi/otorisasi/perubahanhargajual/getdataperubahan', 'OtorisasiController@getDataPerubahanHarga');
    Route::get('notifikasiotorisasi/otorisasi/perubahanhargajual/getdataperubahanhpa', 'OtorisasiController@getDataPerubahanHargaHPA');
    Route::get('notifikasiotorisasi/otorisasi/perubahanhargajual/approve/{id}/{detail}', 'OtorisasiController@approvePerubahanHarga');
    Route::get('notifikasiotorisasi/otorisasi/perubahanhargajual/approve-hpa/{id}/{detail}', 'OtorisasiController@approvePerubahanHargaHPA');
    //== end otorisasi perubahan harga jual
    Route::get('/notifikasiotorisasi/otorisasi/pengeluaranlebih/index', 'OtorisasiController@pengeluaranlebih')->name('pengeluaranlebih');
    Route::get('/notifikasiotorisasi/otorisasi/opname/index', 'OtorisasiController@opname_otorisasi')->name('opname_otorisasi');
    Route::get('/notifikasiotorisasi/otorisasi/adjustment/index', 'OtorisasiController@adjustment')->name('adjustment');
    Route::get('/notifikasiotorisasi/otorisasi/adjustment/getadjusment', 'OtorisasiController@getadjusment')->name('getadjusment');
    Route::get('/notifikasiotorisasi/otorisasi/adjustment/getList', 'Inventory\HistoryAdjusmentController@getList');
    Route::get('/notifikasiotorisasi/otorisasi/adjustment/show-detail-approve/{id}', 'OtorisasiController@detailApprove')->name('detailApprove.show');
    Route::get('/notifikasiotorisasi/otorisasi/adjustment/agreeadjusment/{id}', 'OtorisasiController@agreeadjusment')->name('agreeadjusment');
    Route::get('/notifikasiotorisasi/otorisasi/adjustment/rejectadjusment/{id}', 'OtorisasiController@rejectadjusment')->name('rejectadjusment');
    Route::get('/notifikasiotorisasi/otorisasi/revisi/index', 'OtorisasiController@revisi')->name('revisi');
    //Oerder Produksi
    Route::get('/notifikasiotorisasi/otorisasi/revisi/get-order-produksi', 'OtorisasiController@getProduksi')->name('getproduksi');
    Route::get('/notifikasiotorisasi/otorisasi/revisi/get-order-produksi-detail-item', 'OtorisasiController@getProduksiDetailItem')->name('getproduksidetailitem');
    Route::get('/notifikasiotorisasi/otorisasi/revisi/get-order-produksi-detail-termin', 'OtorisasiController@getProduksiDetailTermin')->name('getproduksidetailtermin');
    Route::get('/notifikasiotorisasi/otorisasi/revisi/order-produksi-agree/{id}', 'OtorisasiController@agree')->name('orderpoduksi.agree');
    Route::get('/notifikasiotorisasi/otorisasi/revisi/order-produksi-rejected/{id}', 'OtorisasiController@rejected')->name('orderpoduksi.rejected');

    // Revisi Data Produk
    Route::get('/notifikasiotorisasi/otorisasi/revisi/get-list-dataproduk', 'OtorisasiController@getListRevDataProduk')->name('revproduk.getListRevDataProduk');
    Route::get('/notifikasiotorisasi/otorisasi/revisi/get-detail-dataproduk/{id}', 'OtorisasiController@getDetailRevDataProduk')->name('revproduk.getDetailRevDataProduk');
    Route::post('/notifikasiotorisasi/otorisasi/revisi/approve-dataproduk/{id}', 'OtorisasiController@approveRevisiProduk')->name('revproduk.approveRevisiProduk');
    Route::post('/notifikasiotorisasi/otorisasi/revisi/reject-dataproduk/{id}', 'OtorisasiController@rejectRevisiProduk')->name('revproduk.rejectRevisiProduk');

    // End Sub Otorisasi

    Route::get('/notifikasiotorisasi/notifikasi/index', 'NotifikasiController@notifikasi')->name('notifikasi');

    //Aproval Promosi
    Route::get('/notifikasiotorisasi/otorisasi/promotion/index', 'OtorisasiController@promotion')->name('promotion');
    Route::get('/notifikasiotorisasi/otorisasi/promotion/data-promotion', 'OtorisasiController@getDataPromotion')->name('promotion.data');
    Route::post('/notifikasiotorisasi/otorisasi/promotion/approve-promotion', 'OtorisasiController@approvePromotion')->name('promotion.approve');
    Route::post('/notifikasiotorisasi/otorisasi/promotion/reject-promotion', 'OtorisasiController@rejectPromotion')->name('promotion.reject');

    Route::get('/notifikasiotorisasi/otorisasi/sdm/index', 'OtorisasiController@sdm')->name('sdm');
    Route::get('/notifikasiotorisasi/otorisasi/sdm/getListPengajuanInOtorisasi', 'OtorisasiController@getListPengajuanInOtorisasi')->name('otorisasi.ListPengajuanInOtorisasi');
    Route::get('/notifikasiotorisasi/otorisasi/sdm/simpanPublikasi', 'OtorisasiController@simpanPublikasi')->name('otorisasi.simpanPublikasi');
    Route::post('/notifikasiotorisasi/otorisasi/sdm/ApprovePengajuan/{id}', 'OtorisasiController@ApprovePengajuan')->name('otorisasi.ApprovePengajuan');
    Route::post('/notifikasiotorisasi/otorisasi/sdm/DeclinePengajuan/{id}', 'OtorisasiController@DeclinePengajuan')->name('otorisasi.DeclinePengajuan');
    // !================================================ END OTORISASI NOTIFIKASI ============================================!

    // Profile
    Route::get('/profile', 'ProfileController@profile')->name('profile');
    Route::post('/profile/update-photo', 'ProfileController@updatePhoto')->name('profile.updatePhoto');
    Route::post('/profile/update-password', 'ProfileController@updatePassword')->name('profile.updatePassword');
    Route::post('/profile/reset-password', 'ProfileController@resetPassword')->name('profile.resetPassword');

    //Get ototitasi
    Route::get('/getoto', 'getotorisasiController@get');
    Route::get('/gettmpoto', 'getotorisasiController@gettmpoto');

    Route::get('/getnotif', 'getnotifikasiController@get');
    Route::get('/gettmpnotif', 'getnotifikasiController@gettmpnotif');
    // Route::get('/testoto', 'pushotorisasiController@otorisasiup');

    //Otorisasi Stock Opname
    Route::GET('/notifikasiotorisasi/otorisasi/opname/getdataopname', 'OtorisasiController@getopname');
    Route::get('/notifikasiotorisasi/otorisasi/opname/approveopname/{id}', 'OtorisasiController@approveopname');
    Route::get('/notifikasiotorisasi/otorisasi/opname/show-detail-approve/{id}', 'OtorisasiController@detailApproveOpname')->name('detailApproveOpname.show');
    Route::get('/notifikasiotorisasi/otorisasi/opname/rejectedopname/{id}', 'OtorisasiController@rejectedopname');


    // Tambahan dirga

        // periode keuangan
            Route::post('keuangan/periode_keuangan/proses', [
                'uses'  => 'Keuangan\periode\periode_controller@proses'
            ])->name('keuangan.periode.proses');

        // master akun
            Route::get('keuangan/masterdatautama/akun-keuangan', [
                'uses'  => 'Keuangan\master\akun\akun_controller@index'
            ])->name('keuangan.akun.index');

            Route::get('keuangan/masterdatautama/akun-keuangan/grap', [
                'uses'  => 'Keuangan\master\akun\akun_controller@grap'
            ])->name('keuangan.akun.grap');

            Route::get('keuangan/masterdatautama/akun-keuangan/create', [
                'uses'  => 'Keuangan\master\akun\akun_controller@create'
            ])->name('keuangan.akun.create');

            Route::get('keuangan/masterdatautama/akun-keuangan/resource', [
                'uses'  => 'Keuangan\master\akun\akun_controller@resource'
            ])->name('keuangan.akun.resource');

            Route::post('keuangan/masterdatautama/akun-keuangan/save', [
                'uses'  => 'Keuangan\master\akun\akun_controller@save'
            ])->name('keuangan.akun.save');

            Route::post('keuangan/masterdatautama/akun-keuangan/save/akun-utama', [
                'uses'  => 'Keuangan\master\akun\akun_controller@saveAkunUtama'
            ])->name('keuangan.akun.save.utama');

            Route::post('keuangan/masterdatautama/akun-keuangan/update', [
                'uses'  => 'Keuangan\master\akun\akun_controller@update'
            ])->name('keuangan.akun.update');


        // master akun utama
            Route::get('keuangan/masterdatautama/akun-utama', [
                'uses'  => 'Keuangan\master\akun_utama\akun_utama_controller@index'
            ])->name('keuangan.akun-utama.index');

            Route::get('keuangan/masterdatautama/akun-utama/grap', [
                'uses'  => 'Keuangan\master\akun_utama\akun_utama_controller@grap'
            ])->name('keuangan.akun_utama.grap');

            Route::get('keuangan/masterdatautama/akun-utama/create', [
                'uses'  => 'Keuangan\master\akun_utama\akun_utama_controller@create'
            ])->name('keuangan.akun_utama.create');

            Route::get('keuangan/masterdatautama/akun-utama/resource', [
                'uses'  => 'Keuangan\master\akun_utama\akun_utama_controller@resource'
            ])->name('keuangan.akun_utama.resource');

            Route::post('keuangan/masterdatautama/akun-utama/save', [
                'uses'  => 'Keuangan\master\akun_utama\akun_utama_controller@save'
            ])->name('keuangan.akun_utama.save');

            Route::post('keuangan/masterdatautama/akun-utama/update', [
                'uses'  => 'Keuangan\master\akun_utama\akun_utama_controller@update'
            ])->name('keuangan.akun_utama.update');


        // Setting hierarki
            Route::get('keuangan/pengaturan/hierarki-akun', [
                'uses'  => 'Keuangan\pengaturan\hierarki_akun\hierarki_akun_controller@index'
            ])->name('keuangan.hierarki_akun.index');

            Route::get('keuangan/pengaturan/hierarki-akun/resource', [
                'uses'  => 'Keuangan\pengaturan\hierarki_akun\hierarki_akun_controller@resource'
            ])->name('keuangan.hierarki_akun.resource');

            Route::post('keuangan/pengaturan/hierarki-akun/save/level_1', [
                'uses'  => 'Keuangan\pengaturan\hierarki_akun\hierarki_akun_controller@save_level_1'
            ])->name('keuangan.hierarki_akun.save.level_1');

            Route::post('keuangan/pengaturan/hierarki-akun/save/subclass', [
                'uses'  => 'Keuangan\pengaturan\hierarki_akun\hierarki_akun_controller@save_subclass'
            ])->name('keuangan.hierarki_akun.save.subclass');

            Route::post('keuangan/pengaturan/hierarki-akun/save/level_2', [
                'uses'  => 'Keuangan\pengaturan\hierarki_akun\hierarki_akun_controller@save_level_2'
            ])->name('keuangan.hierarki_akun.save.level_2');

        // Setting coa pembukuan
            Route::get('keuangan/pengaturan/pembukuan', [
                'uses'  => 'Keuangan\pengaturan\pembukuan\pembukuan_controller@index'
            ])->name('keuangan.pembukuan.index');

            Route::get('keuangan/pengaturan/pembukuan/resource', [
                'uses'  => 'Keuangan\pengaturan\pembukuan\pembukuan_controller@resource'
            ])->name('keuangan.pembukuan.resource');

            Route::post('keuangan/pengaturan/pembukuan/store', [
                'uses'  => 'Keuangan\pengaturan\pembukuan\pembukuan_controller@store'
            ])->name('keuangan.pembukuan.store');

        // Mutasi antar Kas
            Route::get('keuangan/manajemen-input-transaksi/mutasi_kas/create', [
                'uses'  => 'Keuangan\transaksi\mutasi_kas\mutasi_kas_controller@create'
            ])->name('keuangan.mutasi_kas.create');

            Route::get('keuangan/manajemen-input-transaksi/mutasi_kas/resource', [
                'uses'  => 'Keuangan\transaksi\mutasi_kas\mutasi_kas_controller@resource'
            ])->name('keuangan.mutasi_kas.resource');

            Route::post('keuangan/manajemen-input-transaksi/mutasi_kas/save', [
                'uses'  => 'Keuangan\transaksi\mutasi_kas\mutasi_kas_controller@save'
            ])->name('keuangan.mutasi_kas.save');

            Route::post('keuangan/manajemen-input-transaksi/mutasi_kas/update', [
                'uses'  => 'Keuangan\transaksi\mutasi_kas\mutasi_kas_controller@update'
            ])->name('keuangan.mutasi_kas.update');

            Route::post('keuangan/manajemen-input-transaksi/mutasi_kas/delete', [
                'uses'  => 'Keuangan\transaksi\mutasi_kas\mutasi_kas_controller@delete'
            ])->name('keuangan.mutasi_kas.delete');


        // Transaksi Kas
            Route::get('keuangan/manajemen-input-transaksi/transaksi_kas/create', [
                'uses'  => 'Keuangan\transaksi\transaksi_kas\transaksi_kas_controller@create'
            ])->name('keuangan.transaksi_kas.create');

            Route::get('keuangan/manajemen-input-transaksi/transaksi_kas/resource', [
                'uses'  => 'Keuangan\transaksi\transaksi_kas\transaksi_kas_controller@resource'
            ])->name('keuangan.transaksi_kas.resource');

            Route::post('keuangan/manajemen-input-transaksi/transaksi_kas/save', [
                'uses'  => 'Keuangan\transaksi\transaksi_kas\transaksi_kas_controller@save'
            ])->name('keuangan.transaksi_kas.save');

            Route::post('keuangan/manajemen-input-transaksi/transaksi_kas/update', [
                'uses'  => 'Keuangan\transaksi\transaksi_kas\transaksi_kas_controller@update'
            ])->name('keuangan.transaksi_kas.update');

            Route::post('keuangan/manajemen-input-transaksi/transaksi_kas/delete', [
                'uses'  => 'Keuangan\transaksi\transaksi_kas\transaksi_kas_controller@delete'
            ])->name('keuangan.transaksi_kas.delete');


        // Transaksi Memorial
            Route::get('keuangan/manajemen-input-transaksi/transaksi_memorial/create', [
                'uses'  => 'Keuangan\transaksi\transaksi_memorial\transaksi_memorial_controller@create'
            ])->name('keuangan.transaksi_memorial.create');

            Route::get('keuangan/manajemen-input-transaksi/transaksi_memorial/resource', [
                'uses'  => 'Keuangan\transaksi\transaksi_memorial\transaksi_memorial_controller@resource'
            ])->name('keuangan.transaksi_memorial.resource');

            Route::post('keuangan/manajemen-input-transaksi/transaksi_memorial/save', [
                'uses'  => 'Keuangan\transaksi\transaksi_memorial\transaksi_memorial_controller@save'
            ])->name('keuangan.transaksi_memorial.save');

            Route::post('keuangan/manajemen-input-transaksi/transaksi_memorial/update', [
                'uses'  => 'Keuangan\transaksi\transaksi_memorial\transaksi_memorial_controller@update'
            ])->name('keuangan.transaksi_memorial.update');

            Route::post('keuangan/manajemen-input-transaksi/transaksi_memorial/delete', [
                'uses'  => 'Keuangan\transaksi\transaksi_memorial\transaksi_memorial_controller@delete'
            ])->name('keuangan.transaksi_memorial.delete');


        // laporan keuangan
            // laporan Jurnal Umum
                Route::get('modul/keuangan/laporan/jurnal_umum', [
                    'uses'  => 'Keuangan\laporan\jurnal\laporan_jurnal_controller@index'
                ])->name('laporan.keuangan.jurnal_umum');

                Route::get('modul/keuangan/laporan/jurnal_umum/resource', [
                    'uses'  => 'Keuangan\laporan\jurnal\laporan_jurnal_controller@resource'
                ])->name('laporan.keuangan.jurnal_umum.resource');

            // laporan Neraca
                Route::get('modul/keuangan/laporan/neraca', [
                    'uses'  => 'Keuangan\laporan\neraca\laporan_neraca_controller@index'
                ])->name('laporan.keuangan.neraca');

                Route::get('modul/keuangan/laporan/neraca/resource', [
                    'uses'  => 'Keuangan\laporan\neraca\laporan_neraca_controller@resource'
                ])->name('laporan.keuangan.neraca.resource');

                // laporan Laba Rugi
                Route::get('modul/keuangan/laporan/laba_rugi', [
                    'uses'  => 'Keuangan\laporan\lr\laporan_lr_controller@index'
                ])->name('laporan.keuangan.lr');

                Route::get('modul/keuangan/laporan/laba_rugi/resource', [
                    'uses'  => 'Keuangan\laporan\lr\laporan_lr_controller@resource'
                ])->name('laporan.keuangan.lr.resource');


    // Selesai Dirga
});

// End Route Group

/*


hyssshyyhdhsyysssss+++oooooooooooyy+/+//+///+:.````````./s++++++o/://:/s/+/////::::::///:/::::///++o
-:oyyyydhsyyysssso+++ooooooooooshs////++//++-``````````.:o+++++++:::/:os///////:::::::+:::/::::////+
hyyyyhdysyysssss++++ooosoooo+oyho////o+/++:.```````````.:o++++++/:///:ys////////:::::-+:::::::::////
yyyyhhsyyysssso+++osoosooooooyd+///+o//+/-`````.```````.:o+++++o/:////ho//++////:::::-+::::::::-:+::
yyyhysyyssssso+++ooossoooooshho///+o+++:.``````````````.:oo+++++:///:sh+//+s////::::::o/::::::::-/+:
shhssyyssssso+++sooyssooooydho///oo+o/:.```````````````.:oo+++o///+//hh+//+y/////:::::o/::::::::::+/
dyssyysssyoo+++sssysssooshddo///so+o:-`````````````````.:oo++o+//+//o+y///oy/////:::::s+/::::::::::o
soyyysssyoo+++ssyhsssssshhss//+soo+:-``````````````````-:so++o/+++/++/s+//oh//////::::s++:::::::-::+
oyyysssyso+++ssyhsssssyhs+s+/+soo/:.```````````````````-:so+o+++o/++::o+++oy+/////::::y/o/:::::::::-
yyyyssyoo+++yshhssssyys+/y+/oyso/:.````````````````````-/soo+oos/++:--+o+++s+////+:::/h+++::::::/:--
yhyyyyos++oyshhysssys+//+s/oyso/:.`````````````````````-+oo+oos+++:.``:o++++o/+/++:::+h++o///::::/--
hyyyyos+++yyhhyssyyo++++yooyys/:.``````````````````````-oo+oss+++:.```.+++//s+++++:::sh++s////:://:-
yyyysy+++yyhdyyyys++:-/+s+yyo//-.`````````````````````./yosyyoso/:-...`:o+::oo++++://yy++s//////://-
yyysyo++syhdhyys+/:.`.:ooyy+::.`-.````````````````````./osys+o/-.`..---:++-//s++++//+ss++s+////////-
yysys++shhdhyyo/:.```./ssy+::.````````````````````````-/yyyo+/.`````````./-/+so+++//o+o++so///////+-
yysyooohhdhys/:.``````/yh/::.````````````````````````.:yhyo+:.`````````````./+soo+/+o/o++os///////+-
yshooshhhhyo:.``````..+h/-:.`````````````````````````.ohy+/-````````````````:/o+o++o/:o++oy///////+-
shsooyhhsy/.``````````o:.-.``````````````````````````:ys/-.`````````````````.:+s+/+::/o++oy/////+++-
yhooyhhoo-````````````-`-.``````````````````````````.s+:.`````````.``````````-/s+/+../oo+oy+///++++:
dsoshho:.``````````````..```````````````````````````:-.``````````````````````.:+o+-``-/soos+///o/o/:
hoshho:.``````````````````````````````````````````````````````````````````````.:s+```.:s+ss++/+++o/+
soyds:`````````````````````````````````````````````````````````````````````````-/:````-ooso+++o++o+y
osdy:``````````````````````````````````````````````````````````````````````````.:``````:yso+++o++oyo
shy/.```````````````````````````````````````````````````````````````````````````.``````.ssoo+s++sho:
yh+:```````..-::://::----.``````````````````````````````````````````````````````````````:yo++s+sho:+
d+:-``-/shmNNMMMMMMMNNmdyo+/-````````````````````````````......``````````````````````````+ooosyho/oo
o:::odNMMNNmmmNmmdmmmmddNmy+.``````````````````````````-:/++++++++/:-.```````````````````-+oohho+soy
::/oddsy+++ssmdhydhhdhydN/--```````````````````````````.+hdmNNNNNNNNNmhyo/-``````````````.oooh+ossyy
-:/+:-./ys:::ydssosoo+/dd```````````````````````````````:o/--oddhddmNNmNNNNmy+-``````````.//sossyyss
-::.-.`oNd++++so+//::--..`````````````````````````````````...:o/-/shmddmmmdmNNNds:``````.:/oyyyhysss
-:-`..-:/:-.....`````````````````````````````````````````````:Nm///ohshyhddddhdmNNdo-``.:/sysoyysss+
-:..................``````````````..`````````````````````````:ddo:::soooooshyyhdNdhNms-:+yy+:ssssso/
:-....``....-.........```````````..```````````````````````````..--:/+ssssss+::+dm:-/ydsoo/::+sssso//
:-......---..........````````````````````````````````````````````````.--/+++++ym/....oo-`-:/sosso//+
:..--.--........```````````````````````````````````````````````.......````..-:+o-...-.``.:/oosso/+oo
-..........```````````````````````````````````````````````.........`.`.````````````````.:/ooss+/+oss
.....```````````````````````````````````````````````````````...```..````...```````````.-/ooso++ssso+
.`.``````````````````````````````````````````````````````````````````````````....`````-/sssoossso+++
.````````````````````````````````````````````````````````````````````````````````````-/syysyysooo+/:
-.``````````````````````.``````````````````````````````````````````````````````````.-+yyyysoooo+/://
s/-.````````````````````:.```````````````````````````````````````````````````````..:oyysssoo+/::/++-
:--.`````````````````````:......-------.``````````````````````````````````````.-:/+ssssso+/://+/:--:
:-.```````````````````````.....```````..---...`..-````````````````````````.-:/ossssso++/////+/-```.-
/:.`````````````````````````````````````````..--.````````````````````.-:/+osssso++////++o+::/:-.----
d/:.`````````````````````````````````````````````````````````````.-://++++++++//::::/oo/-:::.````.-:
md/:.`````````````````````````````````````````````````````````````````````...``````./:.:///:-..-----
mmdo:.`````````````````````````````````````````````````````````````````````````````-../-..........:+
mNmmh/-`````````````````````````````````````````````````````````````````````````````:/-``````.:/+oo+
hdddmmo:.`````````````````````````````````````````````````````````````````````````.+ysoo++osyys+oo+/
dhhhhhdh+-.``````````````````````````````````````````````````````````````````````/syysooyhhyooo++//+
yyhdddhyhy+.``````````````````````````````````````````````````````````````````.//:oyssyhysoo++///+os


*/
// Cuk kui sopo seng gambar nang duwur
// Guduk Aku ~David
