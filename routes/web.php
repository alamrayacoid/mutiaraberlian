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
    Route::get('/masterdatautama/harga/delete-golongan/{id}', 'Master\HargaController@deleteGolongan')->name('dataharga.deletegolongan');
    Route::post('/masterdatautama/harga/add-golongan', 'Master\HargaController@addGolongan')->name('dataharga.addgolongan');
    Route::post('/masterdatautama/harga/edit-golongan', 'Master\HargaController@editGolongan')->name('dataharga.editgolongan');
    Route::get('/masterdatautama/harga/cari-barang', 'Master\HargaController@cariBarang')->name('dataharga.caribarang');
    Route::get('/masterdatautama/harga/get-satuan/{id}', 'Master\HargaController@getSatuan')->name('dataharga.getsatuan');
    Route::post('/masterdatautama/harga/add-golongan-harga', 'Master\HargaController@addGolonganHarga')->name('dataharga.addgolonganharga');
    Route::get('/masterdatautama/harga/get-golongan-harga/{id}', 'Master\HargaController@getGolonganHarga')->name('dataharga.getgolonganharga');
    Route::get('/masterdatautama/harga/delete-golongan-harga/{id}/{detail}', 'Master\HargaController@deleteGolonganHarga')->name('dataharga.deletegolonganharga');
    Route::post('/masterdatautama/harga/edit-golongan-harga-unit', 'Master\HargaController@editGolonganHargaUnit')->name('dataharga.editgolonganhargaunit');
    Route::post('/masterdatautama/harga/edit-golongan-harga-range', 'Master\HargaController@editGolonganHargaRange')->name('dataharga.editgolonganhargarange');
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
    // !===================================================== End Master Data Utama =====================================================!

    // !===================================================== PRODUKSI =====================================================!
    // Order Produksi
    Route::get('/produksi/orderproduksi/index', 'ProduksiController@order_produksi')->name('order.index');
    Route::match(['get', 'post'],'/produksi/orderproduksi/create', 'ProduksiController@create_produksi')->name('order.create');
    Route::get('/produksi/orderproduksi/cari-barang', 'ProduksiController@cariBarang')->name('order.caribarang');
    Route::get('/produksi/orderproduksi/get-satuan/{id}', 'ProduksiController@getSatuan')->name('order.getsatuan');
    Route::get('/produksi/orderproduksi/edit', 'ProduksiController@edit_produksi')->name('order.edit');
    Route::get('/produksi/orderproduksi/gethistory', 'ProduksiController@get_history')->name('order.gethistory');
    Route::get('/produksi/orderproduksi/detail', 'ProduksiController@detail_produksi')->name('order.detail');
    Route::get('/produksi/orderproduksi/hapus/{id}', 'ProduksiController@delete_produksi')->name('order.delete');

    // Penerimaan Barang
    Route::get('/produksi/penerimaanbarang/index', 'PenerimaanProduksiController@penerimaan_barang')->name('penerimaan.index');
    Route::get('/produksi/penerimaanbarang/getnotapo', 'PenerimaanProduksiController@getNotaPO');
    Route::get('/produksi/penerimaanbarang/create', 'PenerimaanProduksiController@create_penerimaan_barang')->name('penerimaan.create');
    // Pembayaran
    Route::get('/produksi/pembayaran/index', 'ProduksiController@pembayaran')->name('pembayaran.index');
    // Return Produksi
    Route::get('/produksi/returnproduksi/index', 'ProduksiController@return_produksi')->name('return.index');
    Route::get('/produksi/returnproduksi/create', 'ProduksiController@create_return_produksi')->name('return.create');
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
    Route::get('/inventory/distribusibarang/index', 'InventoryController@distribusibarang_index')->name('distribusibarang.index');
    Route::get('/inventory/distribusibarang/create', 'InventoryController@distribusibarang_create')->name('distribusibarang.create');
    Route::get('/inventory/distribusibarang/edit', 'InventoryController@distribusibarang_edit')->name('distribusibarang.edit');
    // Manajemen Stok
    Route::get('/inventory/manajemenstok/index', 'InventoryController@manajemenstok_index')->name('manajemenstok.index');
    Route::get('/inventory/manajemenstok/create', 'InventoryController@manajemenstok_create')->name('manajemenstok.create');
    Route::get('/inventory/manajemenstok/edit', 'InventoryController@manajemenstok_edit')->name('manajemenstok.edit');
    Route::get('/inventory/manajemenstok/opnamestock/index', 'InventoryController@opname_stock')->name('opname.index');
    Route::get('/inventory/manajemenstok/opnamestock/create', 'InventoryController@opname_stock_create')->name('opname.create');
    Route::get('/inventory/manajemenstok/historyopname/index', 'InventoryController@history_opname')->name('history.index');
    Route::get('/inventory/manajemenstok/adjustmentstock/index', 'InventoryController@adjustment_index')->name('adjustment.index');
    Route::get('/inventory/manajemenstok/adjustmentstock/create', 'InventoryController@adjustment_create')->name('adjustment.create');
    // !===================================================== END INVENTORY =====================================================!

    // !===================================================== SDM =====================================================!
    // Rekruitmen
    Route::get('/sdm/prosesrekruitmen/index', 'SDMController@proses_rekruitmen')->name('rekruitmen.index');
    Route::get('/sdm/prosesrekruitmen/list/{status}', 'SDM\RekrutmentController@getList')->name('rekruitmen.list');
    Route::get('/sdm/prosesrekruitmen/process', 'SDMController@process')->name('rekruitmen.process');
    Route::get('/sdm/prosesrekruitmen/preview', 'SDMController@preview')->name('rekruitmen.preview');
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
    Route::get('/marketing/penjualanpusat/index', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@index')->name('pusat.index');
        // Target Realisasi
        Route::get('/marketing/penjualanpusat/targetrealisasi', 'Aktivitasmarketing\Penjualanpusat\PenjualanPusatController@createTargetReal')->name('targetReal.create');
        Route::get('/marketing/penjualanpusat/targetrealisasi/cari-barang', 'ProduksiController@cariBarang')->name('targetReal.caribarang');
        Route::get('/marketing/penjualanpusat/targetrealisasi/get-satuan/{id}', 'ProduksiController@getSatuan')->name('targetReal.getsatuan');
        // End ---
        // Return Penjualan
        Route::get('/marketing/penjualanpusat/returnpenjualan/create', 'MarketingController@returnpenjualanagen_create')->name('returnpenjualanagen.create');
        // End ---
    // End ---
    // Konsinyasi Pusat
    Route::get('/marketing/konsinyasipusat/index', 'MarketingController@konsinyasipusat')->name('konsinyasipusat.index');
    Route::get('/marketing/konsinyasipusat/penempatanproduk/create', 'MarketingController@create_penempatanproduk')->name('penempatanproduk.create');
    Route::get('/marketing/konsinyasipusat/penempatanproduk/edit', 'MarketingController@edit_penempatanproduk')->name('penempatanproduk.edit');
    // Marketing Area
    Route::get('/marketing/marketingarea/index', 'MarketingController@marketing_area')->name('marketingarea.index');
    Route::get('/marketing/marketingarea/orderproduk/create', 'MarketingController@create_orderproduk')->name('orderproduk.create');
    Route::get('/marketing/marketingarea/orderproduk/edit', 'MarketingController@edit_orderproduk')->name('orderproduk.edit');
    Route::get('/marketing/marketingarea/keloladataorder/create', 'MarketingController@create_keloladataorder')->name('keloladataorder.create');
    Route::get('/marketing/marketingarea/keloladataorder/edit', 'MarketingController@edit_keloladataorder')->name('keloladataorder.edit');
    Route::get('/marketing/marketingarea/datacavassing/create', 'MarketingController@create_datacanvassing')->name('datacanvassing.create');
    Route::get('/marketing/marketingarea/datacavassing/edit', 'MarketingController@edit_datacanvassing')->name('datacanvassing.edit');
    Route::get('/marketing/marketingarea/datakonsinyasi/create', 'MarketingController@create_datakonsinyasi')->name('datakonsinyasi.create');
    Route::get('/marketing/marketingarea/datakonsinyasi/edit', 'MarketingController@edit_datakonsinyasi')->name('datakonsinyasi.edit');
    // Manajemen Agen
    Route::get('/marketing/agen/index', 'MarketingController@agen')->name('mngagen.index');
    Route::get('/marketing/agen/orderproduk/create', 'MarketingController@create_orderprodukagenpusat')->name('orderagenpusat.create');
    Route::get('/marketing/agen/orderproduk/edit', 'MarketingController@edit_orderprodukagenpusat')->name('orderagenpusat.edit');
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
        Route::get('/notifikasiotorisasi/otorisasi/perubahanhargajual/index', 'OtorisasiController@perubahanhargajual')->name('perubahanhargajual');
        Route::get('/notifikasiotorisasi/otorisasi/pengeluaranlebih/index', 'OtorisasiController@pengeluaranlebih')->name('pengeluaranlebih');
        Route::get('/notifikasiotorisasi/otorisasi/opname/index', 'OtorisasiController@opname_otorisasi')->name('opname_otorisasi');
        Route::get('/notifikasiotorisasi/otorisasi/adjustment/index', 'OtorisasiController@adjustment')->name('adjustment');
        Route::get('/notifikasiotorisasi/otorisasi/revisi/index', 'OtorisasiController@revisi')->name('revisi');

        // End Sub Otorisasi

    Route::get('/notifikasiotorisasi/notifikasi/index', 'NotifikasiController@notifikasi')->name('notifikasi');
    // !================================================ END OTORISASI NOTIFIKASI ============================================!

    // Profile
    Route::get('/profile', 'ProfileController@profile')->name('profile');
});
// End Route Group
