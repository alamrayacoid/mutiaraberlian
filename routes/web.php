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

Route::get('/loading', 'RecruitmentController@loading')->name('loading.index');
Route::get('/recruitment', 'RecruitmentController@index')->name('recruitment.index');
Route::post('/recruitment/store', 'RecruitmentController@store')->name('recruitment.store');
Route::get('/recruitment/isduplicated/{field}/{value}', 'RecruitmentController@isDuplicated')->name('recruitment.isduplicated');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', [
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

    Route::get('/masterdatautama/produk/jenis/create', 'MasterController@create_datajenisproduk')->name('datajenisproduk.create');
    Route::get('/masterdatautama/produk/jenis/edit', 'MasterController@edit_datajenisproduk')->name('datajenisproduk.edit');

    Route::get('/masterdatautama/variasisatuanproduk/index', 'MasterController@variasisatuanproduk')->name('variasisatuan.index');
    Route::get('/masterdatautama/variasisatuanproduk/create', 'MasterController@create_variasisatuanproduk')->name('variasisatuan.create');
    Route::get('/masterdatautama/variasisatuanproduk/edit', 'MasterController@edit_variasisatuanproduk')->name('variasisatuan.edit');

    Route::get('/masterdatautama/harga/index', 'Master\HargaController@dataharga')->name('dataharga.index');
    Route::get('/masterdatautama/harga/get-golongan', 'Master\HargaController@getGolongan')->name('dataharga.getgolongan');
    Route::get('/masterdatautama/harga/get-golongan-hpa', 'Master\HargaController@getGolonganHPA')->name('dataharga.getgolonganhpa');
    Route::get('/masterdatautama/harga/delete-golongan/{id}', 'Master\HargaController@deleteGolongan')->name('dataharga.deletegolongan');
    Route::get('/masterdatautama/harga/delete-golongan-hpa/{id}', 'Master\HargaController@deleteGolonganHPA')->name('dataharga.deletegolonganhpa');
    Route::post('/masterdatautama/harga/add-golongan', 'Master\HargaController@addGolongan')->name('dataharga.addgolongan');
    Route::post('/masterdatautama/harga/add-golongan-hpa', 'Master\HargaController@addGolonganHPA')->name('dataharga.addgolonganhpa');
    Route::post('/masterdatautama/harga/edit-golongan', 'Master\HargaController@editGolongan')->name('dataharga.editgolongan');
    Route::post('/masterdatautama/harga/edit-golongan-hpa', 'Master\HargaController@editGolonganHPA')->name('dataharga.editgolonganhpa');
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
    Route::get('/masterdatautama/cabang/create', 'Master\CabangController@create')->name('cabang.create');
    Route::get('/masterdatautama/cabang/store', 'Master\CabangController@store')->name('cabang.store');
    Route::match(['get', 'post'], '/masterdatautama/cabang/edit/{id}', 'Master\CabangController@edit')->name('cabang.edit');
    Route::get('/masterdatautama/cabang/nonactive/{id}', 'Master\CabangController@nonActive')->name('cabang.nonActive');
    Route::get('/masterdatautama/cabang/actived/{id}', 'Master\CabangController@actived')->name('cabang.actived');
//    ==========End Master Outlet======

    Route::get('/masterdatautama/agen/index', 'Master\AgenController@index')->name('agen.index');
    Route::get('/masterdatautama/agen/list', 'Master\AgenController@getList')->name('agen.list');
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
    // !===================================================== End Master Data Utama =====================================================!

    // !===================================================== PRODUKSI =====================================================!
    // Order Produksi
    Route::get('/produksi/orderproduksi/index', 'ProduksiController@order_produksi')->name('order.index');
    Route::match(['get', 'post'], '/produksi/orderproduksi/create', 'ProduksiController@create_produksi')->name('order.create');
    Route::get('/produksi/orderproduksi/cari-barang', 'ProduksiController@cariBarang')->name('order.caribarang');
    Route::get('/produksi/orderproduksi/get-satuan/{id}', 'ProduksiController@getSatuan')->name('order.getsatuan');
    Route::get('/produksi/orderproduksi/edit', 'ProduksiController@edit_produksi')->name('order.edit');
    Route::post('/produksi/orderproduksi/edit-order-produksi', 'ProduksiController@editOrderProduksi');
    Route::get('/produksi/orderproduksi/get-order-produksi', 'ProduksiController@get_order')->name('order.getOrderProd');
    Route::get('/produksi/orderproduksi/detailitem', 'ProduksiController@getProduksiDetailItem')->name('order.detailitem');
    Route::get('/produksi/orderproduksi/detailtermin', 'ProduksiController@getProduksiDetailTermin')->name('order.detailtermin');
    Route::get('/produksi/orderproduksi/hapus/{id}', 'ProduksiController@delete_produksi')->name('order.delete');
    Route::get('/produksi/orderproduksi/hapus-item/{order}/{detail}/{item}', 'ProduksiController@deleteItemProduksi')->name('order.delete.item');
    Route::get('/produksi/orderproduksi/hapus-termin/{order}/{termin}', 'ProduksiController@deleteTerminProduksi')->name('order.delete.termin');
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
    // Return Produksi
    Route::get('/produksi/returnproduksi/index', 'ProduksiController@return_produksi')->name('return.index');
    Route::get('/produksi/returnproduksi/list', 'ProduksiController@listReturn')->name('return.list');
    Route::get('/produksi/returnproduksi/detail-return/{id}/{detail}', 'ProduksiController@detailReturn')->name('return.detailreturn');
    Route::get('/produksi/returnproduksi/get-editreturn/{id}/{detail}', 'ProduksiController@getEditReturn')->name('return.geteditreturn');
    Route::get('/produksi/returnproduksi/create', 'ProduksiController@create_return_produksi')->name('return.create');
    Route::get('/produksi/returnproduksi/get-nota', 'ProduksiController@getNotaProductionOrder')->name('return.getnota');
    Route::get('/produksi/returnproduksi/detail-nota/{id}', 'ProduksiController@detailNota')->name('return.detailnota');
    Route::get('/produksi/returnproduksi/cari-supplier', 'ProduksiController@searchSupplier')->name('return.carisupplier');
    Route::get('/produksi/returnproduksi/cari-nota', 'ProduksiController@cariNota')->name('return.carinota');
    Route::get('/produksi/returnproduksi/cari-barang-po/{id}', 'ProduksiController@cariBarangPO')->name('return.caribarangpo');
    Route::get('/produksi/returnproduksi/set-satuan/{id}', 'ProduksiController@setSatuan')->name('return.setunit');
    Route::get('/produksi/returnproduksi/hapus-return/{id}/{detail}/{qty}', 'ProduksiController@deleteReturn')->name('return.delete');
    Route::post('/produksi/returnproduksi/tambah-return', 'ProduksiController@addReturn')->name('return.add');
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
    Route::get('/inventory/barangkeluar/detail/{nota}', 'Inventory\BarangKeluarController@getDetail')->name('barangkeluar.detail');
    Route::get('/inventory/barangkeluar/getItems', 'Inventory\BarangKeluarController@getItems')->name('barangkeluar.getItems');
    Route::get('/inventory/barangkeluar/create', 'Inventory\BarangKeluarController@create')->name('barangkeluar.create');
    Route::post('/inventory/barangkeluar/store', 'Inventory\BarangKeluarController@store')->name('barangkeluar.store');
    Route::get('/inventory/barangkeluar/edit/{id}', 'Inventory\BarangKeluarController@edit')->name('barangkeluar.edit');
    Route::post('/inventory/barangkeluar/update', 'Inventory\BarangKeluarController@update')->name('barangkeluar.update');

    // Distribusi Barang
    Route::get('/inventory/distribusibarang/index', 'Inventory\DistribusiController@distribusibarang_index')->name('distribusibarang.index');
    Route::get('/inventory/distribusibarang/create', 'Inventory\DistribusiController@distribusibarang_create')->name('distribusibarang.create');
    Route::get('/inventory/distribusibarang/edit/{id}', 'Inventory\DistribusiController@distribusibarang_edit')->name('distribusibarang.edit');
    Route::get('/inventory/distribusibarang/getitem', 'Inventory\DistribusiController@getitem')->name('distribusibarang.getitem');
    Route::get('/inventory/distribusibarang/getsatuan', 'Inventory\DistribusiController@getsatuan');
    Route::get('/inventory/distribusibarang/simpancabang', 'Inventory\DistribusiController@simpancabang');
    Route::get('/inventory/distribusibarang/table', 'Inventory\DistribusiController@table');
    Route::get('/inventory/distribusibarang/hapus', 'Inventory\DistribusiController@hapus');
    Route::get('/inventory/distribusibarang/updatecabang', 'Inventory\DistribusiController@updatecabang');
    Route::get('/inventory/distribusibarang/nota', 'Inventory\DistribusiController@printNota')->name('ditribusibarang.nota');
    // Manajemen Stok
    Route::get('/inventory/manajemenstok/index', 'InventoryController@manajemenstok_index')->name('manajemenstok.index');
    Route::get('/inventory/manajemenstok/create', 'InventoryController@manajemenstok_create')->name('manajemenstok.create');
    Route::get('/inventory/manajemenstok/edit', 'InventoryController@manajemenstok_edit')->name('manajemenstok.edit');
    // Opname
    Route::get('/inventory/manajemenstok/opnamestock/index', 'Inventory\OpnameController@index')->name('opname.index');
    Route::get('/inventory/manajemenstok/opnamestock/list', 'Inventory\OpnameController@getList')->name('opname.list');
    Route::get('/inventory/manajemenstok/opnamestock/getItemAutocomplete', 'Inventory\OpnameController@getItemAutocomplete')->name('opname.getItemAutocomplete');
    Route::get('/inventory/manajemenstok/opnamestock/getItem', 'Inventory\OpnameController@getItem')->name('opname.getItem');
    Route::get('/inventory/manajemenstok/opnamestock/getQty', 'Inventory\OpnameController@getQty')->name('opname.getQty');
    Route::get('/inventory/manajemenstok/opnamestock/show/{id}', 'Inventory\OpnameController@show')->name('opname.show');
    Route::get('/inventory/manajemenstok/opnamestock/create', 'Inventory\OpnameController@create')->name('opname.create');
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

    Route::get('/sdm/prosesrekruitmen/listTerima', 'SDM\RecruitmentController@getListTerima')->name('rekruitmen.listTerima');

    Route::get('/sdm/prosesrekruitmen/listLoker', 'SDM\RecruitmentController@getListLoker')->name('rekruitmen.listLoker');
    Route::get('/sdm/prosesrekruitment/kelolarekruitment', 'SDM\RecruitmentController@kelola_rekruitment')->name('rekruitment.kelola');
    Route::post('/sdm/prosesrekruitment/activateLoker/{id}', 'SDM\RecruitmentController@activateLoker')->name('rekruitment.activateLoker');
    Route::post('/sdm/prosesrekruitment/nonLoker/{id}', 'SDM\RecruitmentController@nonLoker')->name('rekruitment.nonLoker');
    Route::get('/sdm/prosesrekruitment/deleteLoker/{id}', 'SDM\RecruitmentController@deleteLoker')->name('rekruitment.deleteLoker');
    Route::get('/sdm/prosesrekruitment/editLoker/{id}', 'SDM\RecruitmentController@editLoker')->name('rekruitment.editLoker');
    Route::get('/sdm/prosesrekruitment/updateLoker', 'SDM\RecruitmentController@updateLoker')->name('rekruitment.updateLoker');

    // Kinerja
    Route::get('/sdm/kinerjasdm/index', 'SDMController@kinerja')->name('kinerjasdm.index');
    // Absensi
    Route::get('/sdm/absensisdm/index', 'SDMController@absensi')->name('absensisdm.index');
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
    Route::get('/marketing/manajemenmarketing/edit-year-promotion', 'MarketingController@year_promotion_edit')->name('yearpromotion.edit');
    Route::get('/marketing/manajemenmarketing/create-month-promotion', 'MarketingController@month_promotion_create')->name('monthpromotion.create');
    Route::get('/marketing/manajemenmarketing/edit-month-promotion', 'MarketingController@month_promotion_edit')->name('monthpromotion.edit');
    // Penjualan Pusat
    Route::get('/marketing/penjualanpusat/index', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@index')->name('penjualanpusat.index');
    Route::get('/marketing/penjualanpusat/tableterima', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@tableterima')->name('penjualanpusat.tableterima');
    Route::get('/marketing/penjualanpusat/getdetail', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getdetail')->name('penjualanpusat.getdetail');
    Route::get('/marketing/penjualanpusat/orderpenjualan/proses', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@orderpenjualan_proses')->name('orderpenjualan.proses');
    // Target Realisasi
    Route::get('/marketing/penjualanpusat/targetrealisasi/targetList', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@targetList')->name('targetReal.list');
    Route::get('/marketing/penjualanpusat/targetrealisasi', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@createTargetReal')->name('targetReal.create');
    Route::get('/marketing/penjualanpusat/targetrealisasi/cari-barang', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@cariBarang')->name('targetReal.caribarang');
    Route::get('/marketing/penjualanpusat/targetrealisasi/get-satuan/{id}', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getSatuan')->name('targetReal.getsatuan');
    Route::get('/marketing/penjualanpusat/targetrealisasi/get-company', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@getComp')->name('targetReal.getcomp');
    Route::get('/marketing/penjualanpusat/targetrealisasi/store', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@targetRealStore')->name('targetReal.store');
    Route::get('/marketing/penjualanpusat/targetrealisasi/editTarget/{st_id}/{dt_id}', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@editTarget')->name('targetReal.edit');
    Route::get('/marketing/penjualanpusat/targetrealisasi/updateTarget/{st_id}/{dt_id}', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@updateTarget')->name('targetReal.update');
    // End ---
    // Return Penjualan
    Route::get('/marketing/penjualanpusat/returnpenjualan/create', 'MarketingController@returnpenjualanagen_create')->name('returnpenjualanagen.create');
    // End ---
    // End ---
    // Konsinyasi Pusat
    Route::get('/marketing/konsinyasipusat/index', 'MarketingController@konsinyasipusat')->name('konsinyasipusat.index');
    Route::get('/marketing/konsinyasipusat/get-konsinyasi', 'MarketingController@getKonsinyasi')->name('konsinyasipusat.getData');
    Route::get('/marketing/konsinyasipusat/detail-konsinyasi/{id}/{action}', 'MarketingController@detailKonsinyasi')->name('konsinyasipusat.detail');
    Route::get('/marketing/konsinyasipusat/get-provinsi', 'MarketingController@getProv')->name('konsinyasipusat.getProv');
    Route::get('/marketing/konsinyasipusat/get-kota/{idprov}', 'MarketingController@getKota')->name('konsinyasipusat.getKota');
    Route::get('/marketing/konsinyasipusat/cari-konsigner/{idprov}/{idkota}', 'MarketingController@cariKonsigner')->name('konsinyasipusat.carikonsigner');
    Route::get('/marketing/konsinyasipusat/cari-barang', 'MarketingController@cariBarangKonsinyasi')->name('konsinyasipusat.caribarang');
    Route::get('/marketing/konsinyasipusat/get-satuan/{id}', 'MarketingController@getSatuan')->name('konsinyasipusat.getsatuan');
    Route::get('/marketing/konsinyasipusat/cek-stok/{stock}/{item}/{satuan}/{qty}', 'MarketingController@checkStock')->name('konsinyasipusat.checkstock');
    Route::get('/marketing/konsinyasipusat/cek-stok-old/{stock}/{item}/{oldSatuan}/{satuan}/{qtyOld}/{qty}', 'MarketingController@checkStockOld')->name('konsinyasipusat.checkstockold');
    Route::get('/marketing/konsinyasipusat/cek-harga/{konsigner}/{item}/{unit}/{qty}', 'MarketingController@checkHarga')->name('konsinyasipusat.checkharga');
    Route::get('/marketing/konsinyasipusat/penempatanproduk/create', 'MarketingController@create_penempatanproduk')->name('penempatanproduk.create');
    Route::post('/marketing/konsinyasipusat/penempatanproduk/add', 'MarketingController@add_penempatanproduk')->name('penempatanproduk.add');
    Route::match(['get', 'post'],'/marketing/konsinyasipusat/penempatanproduk/edit/{id}', 'MarketingController@edit_penempatanproduk')->name('penempatanproduk.edit');
    Route::get('/marketing/konsinyasipusat/penempatanproduk/hapus', 'MarketingController@deletePenempatanproduk')->name('penempatanproduk.delete');
    // Marketing Area
    Route::get('/marketing/marketingarea/index', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@index')->name('marketingarea.index');
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
    Route::get('/marketing/marketingarea/orderproduk/get-price', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getPrice')->name('orderProduk.getPrice');
    Route::get('/marketing/marketingarea/orderproduk/delete-order/{id}/{dt}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@deleteOrder')->name('orderProduk.delete');
    Route::get('/marketing/marketingarea/orderproduk/nota/{id}/{dt}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@printNota')->name('orderProduk.nota');
    Route::get('/marketing/marketingarea/orderproduk/detail/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@detailOrder')->name('orderProduk.detail');
    // End Order Ke Cabang ==================

    // Kelola Data Order Agen ========================================================================================
    Route::get('/marketing/marketingarea/keloladataorder/list-agen/{status}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@listAgen')->name('keloladataorder.listAgen');
    Route::get('/marketing/marketingarea/keloladataorder/get-agen', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@getDataAgen')->name('keloladataorder.getDataAgen');
    Route::get('/marketing/marketingarea/keloladataorder/cari-agen', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@cariDataAgen')->name('keloladataorder.cariDataAgen');
    Route::get('/marketing/marketingarea/keloladataorder/filter-agen', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@filterDataAgen')->name('keloladataorder.filterDataAgen');
    Route::get('/marketing/marketingarea/keloladataorder/detail-agen/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@detailAgen')->name('keloladataorder.detailAgen');
    Route::post('/marketing/marketingarea/keloladataorder/reject-agen/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@rejectAgen')->name('keloladataorder.rejectAgen');
    Route::post('/marketing/marketingarea/keloladataorder/activate-agen/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@activateAgen')->name('keloladataorder.activateAgen');
    Route::post('/marketing/marketingarea/keloladataorder/approve-agen/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@approveAgen')->name('keloladataorder.approveAgen');
    Route::post('/marketing/marketingarea/keloladataorder/reject-approve-agen/{id}', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@rejectApproveAgen')->name('keloladataorder.rejectApproveAgen');
    // End Order Agen ================================================================================================

    Route::get('/marketing/marketingarea/datacavassing/create', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@create_datacanvassing')->name('datacanvassing.create');
    Route::get('/marketing/marketingarea/datacavassing/edit', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@edit_datacanvassing')->name('datacanvassing.edit');
    Route::get('/marketing/marketingarea/datakonsinyasi/create', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@create_datakonsinyasi')->name('datakonsinyasi.create');
    Route::get('/marketing/marketingarea/datakonsinyasi/edit', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@edit_datakonsinyasi')->name('datakonsinyasi.edit');

    // Manajemen Agen ===============================================================================================
    Route::get('/marketing/agen/index', 'Aktivitasmarketing\Agen\ManajemenAgenController@index')->name('manajemenagen.index');
    Route::get('/marketing/agen/get-agen/{city}', 'Aktivitasmarketing\Agen\ManajemenAgenController@getAgen')->name('manajemenagen.getAgen');
    Route::post('/marketing/agen/filter-data/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@filterData')->name('manajemenagen.filterData');

    Route::get('/marketing/agen/kelolapenjualanlangsung/get-list-kpl', 'Aktivitasmarketing\Agen\ManajemenAgenController@getListKPL')->name('kelolapenjulan.getListKPL');
    Route::get('/marketing/agen/kelolapenjualanlangsung/get-detail-penjualan', 'Aktivitasmarketing\Agen\ManajemenAgenController@getDetailPenjualan')->name('kelolapenjulan.getDetailPenjualan');
    Route::post('/marketing/agen/kelolapenjualanlangsung/delete-detail-penjualan', 'Aktivitasmarketing\Agen\ManajemenAgenController@deleteDetailPenjualan')->name('kelolapenjulan.deleteDetailPenjualan');
    Route::get('/marketing/agen/kelolapenjualanlangsung/create', 'Aktivitasmarketing\Agen\ManajemenAgenController@createKPL')->name('kelolapenjulan.create');
    Route::get('/marketing/agen/kelolapenjualanlangsung/find-item', 'Aktivitasmarketing\Agen\ManajemenAgenController@findItem')->name('kelolapenjulan.findItem');
    Route::get('/marketing/agen/kelolapenjualanlangsung/get-item-stock', 'Aktivitasmarketing\Agen\ManajemenAgenController@getItemStock')->name('kelolapenjulan.getItemStock');
    Route::get('/marketing/agen/kelolapenjualanlangsung/get-price', 'Aktivitasmarketing\Agen\ManajemenAgenController@getPrice')->name('kelolapenjulan.getPrice');
    Route::post('/marketing/agen/kelolapenjualanlangsung/store', 'Aktivitasmarketing\Agen\ManajemenAgenController@storeKPL')->name('kelolapenjulan.storeKPL');
    Route::get('/marketing/agen/kelolapenjualanlangsung/edit/{id}', 'Aktivitasmarketing\Agen\ManajemenAgenController@editKPL')->name('kelolapenjulan.edit');
    Route::get('/marketing/agen/kelolapenjualanlangsung/update', 'Aktivitasmarketing\Agen\ManajemenAgenController@updateKPL')->name('kelolapenjulan.update');
    // End Manajemen Agen ============================================================================================

    Route::get('/marketing/agen/orderproduk/create', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@create_orderprodukagenpusat')->name('orderagenpusat.create');
    Route::get('/marketing/agen/orderproduk/edit', 'Aktivitasmarketing\Marketingarea\MarketingAreaController@edit_orderprodukagenpusat')->name('orderagenpusat.edit');
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
    // !===================================================== END KEUANGAN =====================================================!

    // !===================================================== PENGATURAN =====================================================!
    // Perubahan Harga Jual
    Route::get('/pengaturan/otoritas/perubahanhargajual/index', 'SettingController@perubahanhargajual_index')->name('perubahanhargajual.index');
    Route::get('/pengaturan/pengaturanpengguna/index', 'SettingController@pengaturanpengguna_index')->name('pengaturanpengguna.index');
    Route::POST('/pengaturan/pengaturanpengguna/datatable', 'SettingController@datatable')->name('pengaturanpengguna.datatable');
    Route::get('/pengaturan/pengaturanpengguna/akses', 'SettingController@pengaturanpengguna_akses')->name('pengaturanpengguna.akses');
    Route::get('/pengaturan/pengaturanpengguna/create', 'SettingController@pengaturanpengguna_create')->name('pengaturanpengguna.create');
    Route::get('/pengaturan/pengaturanpengguna/simpan', 'SettingController@pengaturanpengguna_simpan')->name('pengaturanpengguna.simpan');
    Route::get('/pengaturan/pengaturanpengguna/hapus', 'SettingController@pengaturanpengguna_hapus')->name('pengaturanpengguna.hapus');
    Route::get('/pengaturan/pengaturanpengguna/updatepassword', 'SettingController@pengaturanpengguna_updatepassword')->name('pengaturanpengguna.updatepassword');
    Route::get('/pengaturan/pengaturanpengguna/edit', 'SettingController@pengaturanpengguna_edit')->name('pengaturanpengguna.edit');
    Route::get('/pengaturan/pengaturanpengguna/simpanakses', 'SettingController@pengaturanpengguna_simpanakses')->name('pengaturanpengguna.simpanakses');
    Route::get('/pengaturan/pengaturanpengguna/updatelevel', 'SettingController@pengaturanpengguna_updatelevel')->name('pengaturanpengguna.updatelevel');
    // !===================================================== END PENGATURAN =====================================================!

    // !================================================== OTORISASI NOTIFIKASI ==============================================!
    Route::get('/notifikasiotorisasi/otorisasi/index', 'OtorisasiController@otorisasi')->name('otorisasi');

    // Sub Otorisasi
    //== otorisasi perubahan harga jual
    Route::get('/notifikasiotorisasi/otorisasi/perubahanhargajual/index', 'OtorisasiController@perubahanhargajual')->name('perubahanhargajual');
    Route::get('notifikasiotorisasi/otorisasi/perubahanhargajual/getdataperubahan', 'OtorisasiController@getDataPerubahanHarga');
    Route::get('notifikasiotorisasi/otorisasi/perubahanhargajual/approve/{id}/{detail}', 'OtorisasiController@approvePerubahanHarga');
    //== end otorisasi perubahan harga jual
    Route::get('/notifikasiotorisasi/otorisasi/pengeluaranlebih/index', 'OtorisasiController@pengeluaranlebih')->name('pengeluaranlebih');
    Route::get('/notifikasiotorisasi/otorisasi/opname/index', 'OtorisasiController@opname_otorisasi')->name('opname_otorisasi');
    Route::get('/notifikasiotorisasi/otorisasi/adjustment/index', 'OtorisasiController@adjustment')->name('adjustment');
    Route::get('/notifikasiotorisasi/otorisasi/adjustment/getadjusment', 'OtorisasiController@getadjusment')->name('getadjusment');
    Route::get('/notifikasiotorisasi/otorisasi/adjustment/getList', 'Inventory\HistoryAdjusmentController@getList');
    Route::get('/notifikasiotorisasi/otorisasi/adjustment/agreeadjusment/{id}', 'OtorisasiController@agreeadjusment')->name('agreeadjusment');
    Route::get('/notifikasiotorisasi/otorisasi/adjustment/rejectadjusment/{id}', 'OtorisasiController@rejectadjusment')->name('rejectadjusment');
    Route::get('/notifikasiotorisasi/otorisasi/revisi/index', 'OtorisasiController@revisi')->name('revisi');

    //Oerder Produksi
    Route::get('/notifikasiotorisasi/otorisasi/revisi/get-order-produksi', 'OtorisasiController@getProduksi')->name('getproduksi');
    Route::get('/notifikasiotorisasi/otorisasi/revisi/get-order-produksi-detail-item', 'OtorisasiController@getProduksiDetailItem')->name('getproduksidetailitem');
    Route::get('/notifikasiotorisasi/otorisasi/revisi/get-order-produksi-detail-termin', 'OtorisasiController@getProduksiDetailTermin')->name('getproduksidetailtermin');
    Route::get('/notifikasiotorisasi/otorisasi/revisi/order-produksi-agree/{id}', 'OtorisasiController@agree')->name('orderpoduksi.agree');
    Route::get('/notifikasiotorisasi/otorisasi/revisi/order-produksi-rejected/{id}', 'OtorisasiController@rejected')->name('orderpoduksi.rejected');

    // End Sub Otorisasi

    Route::get('/notifikasiotorisasi/notifikasi/index', 'NotifikasiController@notifikasi')->name('notifikasi');
    // !================================================ END OTORISASI NOTIFIKASI ============================================!

    // Profile
    Route::get('/profile', 'ProfileController@profile')->name('profile');

    //Get ototitasi
    Route::get('/getoto', 'getotorisasiController@get');
    Route::get('/gettmpoto', 'getotorisasiController@gettmpoto');

    //Otorisasi Stock Opname
    Route::get('/notifikasiotorisasi/otorisasi/opname/getdataopname', 'OtorisasiController@getopname');
    Route::get('/notifikasiotorisasi/otorisasi/opname/approveopname/{id}', 'OtorisasiController@approveopname');
    Route::get('/notifikasiotorisasi/otorisasi/opname/rejectedopname/{id}', 'OtorisasiController@rejectedopname');

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
