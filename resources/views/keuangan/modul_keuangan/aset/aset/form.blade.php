@extends('main')

@section('title', 'Tambah Data Aset')

@section(modulSetting()['extraStyles'])

    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/wait_me_v_1_1/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/toast/dist/jquery.toast.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/select2/dist/css/select2.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/datepicker/dist/datepicker.min.css') }}">

@endsection


@section('content')
    <div class="content-wrapper" id="vue-component">
      <div class="row">
        <div class="col-lg-12">
          <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb bg-info">
              <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="#">Home</a></li>
              <li class="breadcrumb-item">Manajemen Aset</li>
              <li class="breadcrumb-item active" aria-current="page">Input Data Aset</li>
            </ol>
          </nav>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Input Data Aset</h4>

            <form id="data-form" v-cloak>

                <div class="text-center" style="background: #ff4444; position: absolute; z-index: 1000; right: 2em; padding: 15px 5px 5px 5px; border-bottom-left-radius: 5px;" v-if="onUpdate">
                    <i class="fa fa-eraser" style="color: white; cursor: pointer;" @click="confirmDelete" title="! Hapus Aset yang Dipilih"></i>
                </div>

                <div class="table-responsive">
                    <input type="hidden" readonly name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" readonly name="at_id" v-model="singleData.at_id">

                    <div class="col-md-12" style="border-top: 1px solid #eee; padding-top: 20px;">
                        <div class="row">
                            <div class="col-md-6" style="background: none;">

                                <div class="row mt-form">
                                    <div class="col-md-4">
                                        <label class="modul-keuangan">Nomor Aset</label>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" name="at_nomor" class="form-control modul-keuangan" placeholder="Di Isi Oleh Sistem" readonly v-model="singleData.at_nomor">
                                    </div>

                                    <div class="col-md-1 form-info-icon link" @click="search" v-if="!onUpdate">
                                        <i class="fa fa-search" title="Cari Penerimaan Berdasarkan Nomor, Jenis, dan Bulan"></i>
                                    </div>

                                    <div class="col-md-1 form-info-icon link" @click="formReset" v-if="onUpdate">
                                        <i class="fa fa-times" title="Bersihkan Pencarian" style="color: #CC0000;"></i>
                                    </div>
                                </div>


                                <div class="row mt-form">
                                    <div class="col-md-4">
                                        <label class="modul-keuangan">Nama Aset *</label>
                                    </div>

                                    <div class="col-md-7">
                                        <input type="text" name="at_nama" class="form-control modul-keuangan" placeholder="contoh: Komputer Admin" v-model="singleData.at_nama" title="Tidak Boleh Kosong" :disabled="onUpdate">
                                    </div>
                                </div>

                                <div class="row mt-form">
                                    <div class="col-md-4">
                                        <label class="modul-keuangan">Tanggal Pembelian *</label>
                                    </div>

                                    <div class="col-md-7">
                                        <vue-datepicker :name="'at_tanggal_beli'" :id="'at_tanggal_beli'" :title="'Tidak Boleh Kosong'" :readonly="true" :placeholder="'Pilih Tanggal'" @input="tanggalChange" v-if="!onUpdate"></vue-datepicker>

                                        <input type="text" name="qwe" class="form-control modul-keuangan" placeholder="contoh: Komputer Admin" v-model="singleData.at_tanggal_beli" title="Tidak Boleh Kosong" :disabled="onUpdate" v-if="onUpdate">
                                    </div>
                                </div>

                                <div class="row mt-form">
                                    <div class="col-md-4">
                                        <label class="modul-keuangan">Kelompok Aset</label>
                                    </div>

                                    <div class="col-md-7">
                                        <vue-select :name="'at_golongan'" :id="'at_golongan'" :options="kelompokAset" @input="kelompokChange" :disabled="onUpdate"></vue-select>
                                    </div>
                                </div>

                                <div class="row mt-form">
                                    <div class="col-md-4">
                                        <label class="modul-keuangan">Metode Penyusutan</label>
                                    </div>

                                    <div class="col-md-7">
                                        <vue-select :name="'at_metode'" :id="'at_metode'" :options="metodePenyusutan" @input="metodeChange" :disabled="onUpdate"></vue-select>
                                    </div>
                                </div>

                                <div class="row mt-form" style="border-top: 1px solid #eee; padding-top: 20px;">
                                    <div class="col-md-4">
                                        <label class="modul-keuangan">Harga Pembelian *</label>
                                    </div>

                                    <div class="col-md-7">
                                        <vue-inputmask :name="'at_harga_beli'" :id="'at_harga_beli'" v-if="!onUpdate"></vue-inputmask>

                                        <input type="text" id="at_harga_beli" name="asd" class="form-control modul-keuangan" placeholder="contoh: Komputer Admin" v-model="singleData.at_harga_beli" title="Tidak Boleh Kosong" :disabled="onUpdate" v-if="onUpdate" style="text-align: right;">
                                    </div>
                                </div>

                                {{-- <div class="row mt-form" v-if="!onUpdate">
                                    <div class="col-md-3">
                                        <label class="modul-keuangan">Pilih Akun Keluaran</label>
                                    </div>

                                    <div class="col-md-6">
                                        <vue-select :name="'akun_keluaran'" :id="'akun_keluaran'" :options="akunKas"></vue-select>
                                    </div>

                                </div> --}}

                                {{-- <div class="row mt-form" v-show="onUpdate">
                                    <div class="col-md-3">
                                        <label class="modul-keuangan">Metode Penyusutan</label>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="checkbox" name="dana_titipan" title="Centang Untuk Menambahkan Nilai Lebih Bayar Ke Akun Dana Titipan" v-model="dijual">

                                        <span style="font-size: 8pt; margin-left: 5px;">Tandai Sebagai Aset Yang Telah Terjual</span>
                                    </div>
                                </div> --}}

                                {{-- <div class="row mt-form" style="border-top: 1px solid #eee; padding-top: 20px;" v-if="onUpdate && dijual">
                                    <div class="col-md-3">
                                        <label class="modul-keuangan">Harga Penjualan</label>
                                    </div>

                                    <div class="col-md-6">
                                       <vue-inputmask :name="'harga_jual'" :id="'harga_jual'" @input="hargaJualChange"></vue-inputmask>
                                    </div>
                                </div>

                                <div class="row mt-form" v-if="onUpdate && dijual">
                                    <div class="col-md-3">
                                        <label class="modul-keuangan">Pilih Akun Masukkan</label>
                                    </div>

                                    <div class="col-md-6">
                                        <vue-select :name="'akun_masukkan'" :id="'akun_masukkan'" :options="akunKas" @input="akunMasukkanChange"></vue-select>
                                    </div>

                                </div> --}}

                            </div>

                            <div class="col-md-6" style="background: none; padding: 5px; border:1px solid #eee;">
                                <div class="col-md-12" style="padding: 0px; min-height: 130px; background: #f7f7f7; border: 0px solid #eee;">
                                    <table class="table table-stripped table-mini">
                                        <thead>
                                            <td colspan="3" style="font-weight: 600; border-top: 0px; padding-top: 5px;">Detail Terkait Kelompok Dan Metode Penyusutan Yang Dipilih</td>
                                        </thead>
                                        <tbody id="wrap">
                                            <tr>
                                                <td width="30%" class="text-center" style="border: 1px solid #eee; font-weight: 600; border-left: 0px;">Metode</td>
                                                <td width="30%" class="text-center" style="border: 1px solid #eee; font-weight: 600;">Masa Manfaat</td>
                                                <td width="30%" class="text-center" style="border: 1px solid #eee; font-weight: 600; border-right: 0px;">Persentase Penyusutan</td>
                                            </tr>

                                            <tr>
                                                <td width="30%" class="text-center" style="border: 1px solid #eee; border-left: 0px;">@{{ singleData.at_metode }}</td>
                                                <td width="30%" class="text-center" style="border: 1px solid #eee;">@{{ singleData.at_masa_manfaat }} Tahun</td>
                                                <td width="30%" class="text-center" style="border: 1px solid #eee; border-right: 0px;">@{{ singleData.at_persentase }} %</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12" style="padding: 0px; min-height: 170px; background: #f7f7f7; border: 0px solid #eee;">
                                    <table class="table table-stripped table-mini">
                                        <thead>
                                            <td colspan="4" style="font-weight: 600; border-top: 0px; padding-top: 5px;">Detail Akun Keuangan Terkait Kelompok Yang Dipilih</td>
                                        </thead>
                                        <tbody id="wrap">
                                            <tr>
                                                <td class="text-right" width="5%">-</td>
                                                <td width="40%" style="border-right: 1px dotted #eee;">Akun Harta</td>
                                                <td width="55%" style="font-weight: 600; font-size: 8pt; color: #0099CC;">@{{ singleData.at_akun_harta }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-right">-</td>
                                                <td style="border-right: 1px dotted #eee;">Akun Akumulasi Penyusutan</td>
                                                <td style="font-weight: 600; font-size: 8pt; color: #0099CC;">@{{ singleData.at_akun_akumulasi }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-right">-</td>
                                                <td style="border-right: 1px dotted #eee;">Akun Beban</td>
                                                <td style="font-weight: 600; font-size: 8pt; color: #0099CC;">@{{ singleData.at_akun_beban }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                {{-- <div class="col-md-12" style="padding: 0px;">
                                    <table class="table table-stripped table-mini" border="0">
                                        <thead>
                                            <tr>
                                                <td width="2%" style="border: 0px; padding: 2px 10px;">-</td>
                                                <td style="border: 0px; padding: 2px 10px;">
                                                    <small><b>Dua Metode Diatas Akan Anda Pilih Saat Membuat Aset Baru.</b></small> 
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="2%" style="border: 0px; padding: 2px 10px;">-</td>
                                                <td style="border: 0px; padding: 2px 10px;">
                                                    <small><b>Penentuan Nilai Masa Manfaat Dan Persentase Penyusutan, Sesuai Dengan <a href="https://www.google.co.id/search?q=psak+no+17&oq=&sourceid=chrome&ie=UTF-8" target="_blank">PSAK Nomor 17.</a></b></small> 
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" style="border-top: 1px solid #eee; margin-top: 20px; padding-top: 20px;">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('aset.index') }}">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-arrow-left" :disabled="btnDisabled"></i> &nbsp;Kembali Ke Halaman Data Aset</button>
                                </a>
                            </div>

                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-info btn-sm" @click="saveData" :disabled="btnDisabled" v-if="onUpdate"><i class="fa fa-floppy-o"></i> &nbsp;Lihat Detail Penyusutan</button>
                                
                                <button type="button" class="btn btn-warning btn-sm" @click="terjual" :disabled="btnDisabled" v-if="onUpdate"><i class="fa fa-bell-slash-o"></i> &nbsp;Aset Ini Sudah Terjual</button>

                                <button type="button" class="btn btn-primary btn-sm" @click="saveData" :disabled="btnDisabled" v-if="!onUpdate"><i class="fa fa-floppy-o"></i> &nbsp;Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ez-popup" id="data-popup">
                    <div class="layout" style="width: 70%">
                        <div class="top-popup" style="background: none;">
                            <span class="title">
                                Data Grup Aset Yang Sudah Masuk
                            </span>

                            <span class="close"><i class="fa fa-times" style="font-size: 12pt; color: #CC0000"></i></span>
                        </div>
                        
                        <div class="content-popup">
                            <vue-datatable :data_resource="list_data_table" :columns="data_table_columns" :selectable="true" :ajax_on_loading="onAjaxLoading" :index_column="'at_id'" @selected="dataSelected"></vue-datatable>
                        </div>
                    </div>
                </div>

                <div class="ez-popup" id="penyusutan-popup">
                    <div class="layout" style="width: 70%">
                        <div class="top-popup" style="background: none;">
                            <span class="title">
                                Detail Penyusutan Sesuai Dengan Inputan
                            </span>

                            <span class="close"><i class="fa fa-times" style="font-size: 12pt; color: #CC0000"></i></span>
                        </div>
                        
                        <div class="content-popup">
                            <div class="row">
                                <div class="col-md-12">
                                    <table width="100%">
                                        <thead>
                                            <tr>
                                                <td class="text-left" width="30%" style="background: #eee; padding: 8px; font-size: 8pt; padding-left: 15px; font-weight: 600;">
                                                    <i class="fa fa-arrow-right"></i> &nbsp; Tanggal Pembelian : @{{ singleData.at_tanggal_beli }}
                                                </td>

                                                <td class="text-left" width="35%" style="background: #eee; padding: 8px; font-size: 8pt; font-weight: 600;">
                                                    <i class="fa fa-arrow-right"></i> &nbsp; Metode Penyusutan : @{{ singleData.at_metode }}
                                                </td>

                                                <td class="text-left" width="30%" style="background: #eee; padding: 8px; font-size: 8pt; font-weight: 600; padding-right: 15px;">
                                                    <i class="fa fa-arrow-right"></i> &nbsp; Masa Manfaat : @{{ singleData.at_masa_manfaat }} Tahun
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                                <div class="col-md-12" style="margin-top: 20px; max-height: 232px; overflow-y: scroll;">
                                    <table class="table table-stripped table-mini" style="margin-bottom: 0px;">
                                        <thead>
                                            <tr>
                                                <th width="10%" style="border: 1px solid #eee; position: sticky; top: 0; background: #f9f9f9;">Tahun</th>
                                                <th width="14%" style="border: 1px solid #eee; position: sticky; top: 0; background: #f9f9f9;">Jumlah Bulan</th>
                                                <th width="19%" style="border: 1px solid #eee; position: sticky; top: 0; background: #f9f9f9;">Harga Perolehan</th>
                                                <th width="19%" style="border: 1px solid #eee; position: sticky; top: 0; background: #f9f9f9;">Biaya penyusutan</th>
                                                <th width="19%" style="border: 1px solid #eee; position: sticky; top: 0; background: #f9f9f9;">Akumulasi Penyusutan</th>
                                                <th width="19%" style="border: 1px solid #eee; position: sticky; top: 0; background: #f9f9f9;">Nilai Sisa (residu)</th>
                                            </tr>
                                        </thead>

                                        <tbody id="wrap">
                                            <tr v-for="dataPenyusutan in penyusutan">
                                                <td class="text-center" :style="(dataPenyusutan.tahunIni) ? 'border: 1px solid #eee; background: #90caf9; color: white;' : 'border: 1px solid #eee;'">
                                                    @{{ dataPenyusutan.tahun }}
                                                    <input type="hidden" name="ad_tahun[]" :value="dataPenyusutan.tahun" readonly>
                                                </td>

                                                <td class="text-center" :style="(dataPenyusutan.tahunIni) ? 'border: 1px solid #eee; background: #90caf9; color: white;' : 'border: 1px solid #eee;'">
                                                    @{{ dataPenyusutan.bulan }}
                                                    <input type="hidden" name="ad_jumlah_bulan[]" :value="dataPenyusutan.bulan" readonly>
                                                </td>

                                                <td class="text-right" :style="(dataPenyusutan.tahunIni) ? 'border: 1px solid #eee; background: #90caf9; color: white;' : 'border: 1px solid #eee;'">
                                                    @{{ dataPenyusutan.hargaPerolehan }}
                                                </td>

                                                <td class="text-right" :style="(dataPenyusutan.tahunIni) ? 'border: 1px solid #eee; background: #90caf9; color: white;' : 'border: 1px solid #eee;'">
                                                    @{{ dataPenyusutan.nilaiPenyusutan }}
                                                    <input type="hidden" name="ad_penyusutan[]" :value="dataPenyusutan.nilaiPenyusutan" readonly>
                                                </td>

                                                <td class="text-right" :style="(dataPenyusutan.tahunIni) ? 'border: 1px solid #eee; background: #90caf9; color: white;' : 'border: 1px solid #eee;'">
                                                    @{{ dataPenyusutan.nilaiAkumulasi }}
                                                </td>

                                                <td class="text-right" :style="(dataPenyusutan.tahunIni) ? 'border: 1px solid #eee; background: #90caf9; color: white;' : 'border: 1px solid #eee;'">
                                                    @{{ dataPenyusutan.nilaiSisa }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12" style="margin-top: 5px;">
                                    <table class="table table-stripped table-mini" border="0" style="margin-bottom: 0px; margin-top: 5px;">
                                        <thead>
                                            <tr>
                                                <td width="2%" style="border: 0px; padding: 2px 10px;">-</td>
                                                <td style="border: 0px; padding: 2px 10px;">
                                                    <small><b>Pastikan Data Perhitungan Penyusutan Ini Benar (nilai residu akhir 0)</b></small> 
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="2%" style="border: 0px; padding: 2px 10px;">-</td>
                                                <td style="border: 0px; padding: 2px 10px;">
                                                    <small><b>Penyusutan Pertama Akan Dilakukan Sesuai Dengan Data Baris Berwarna Biru</b></small> 
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="2%" style="border: 0px; padding: 2px 10px;">-</td>
                                                <td style="border: 0px; padding: 2px 10px;">
                                                    <small><b>Nantinya, Penyusutan Juga Akan Dilakukan Setiap Pergantian Periode (per bulan)</b></small> 
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                                <div class="col-md-12 text-right" style="border-top: 1px solid #eee; margin-top: 10px; padding-top: 20px;">
                                    <button type="button" class="btn btn-info btn-sm" @click="saving" :disabled="btnDisabled" v-if="!onUpdate">
                                        <i class="fa fa-floppy-o"></i> &nbsp;Konfirmasi Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ez-popup" id="delete-confirmation-popup">
                    <div class="layout" style="width: 40%; min-height: 200px;">
                        <div class="top-popup" style="background: none;">
                            <span class="title">
                                Konfirmasi Hapus
                            </span>

                            {{-- <span class="close"><i class="fa fa-times" style="font-size: 12pt; color: #CC0000"></i></span> --}}
                        </div>
                        
                        <div class="content-popup">
                            <div class="row" style="padding: 0px 15px;">
                                <div class="col-md-2 text-center" style="padding: 10px 0px; background: #0099CC; ">
                                    <i class="fa fa-exclamation-triangle" style="font-size: 32pt; color: #fff;"></i>
                                </div>

                                <div class="col-md-10" style="font-size: 9pt; color: #0099CC; background: white; border: 1px solid #0099CC; padding: 5px 10px;">
                                    Apakah Anda Yakin, Data Aset Yang Dihapus Tidak Akan Bisa Dikembalikan. Menghapus Aset Juga Akan Menghapus Semua Jurnal Penyusutan Yang Telah Dibukukan !
                                </div>
                            </div>

                            <div class="row" style="border-top: 1px solid #eee; margin-top: 20px; padding-top: 20px;">
                                <div class="col-md-5">
                                    <div class="loader" v-if="stat == 'loading'" v-cloak>
                                       <div class="loading"></div> &nbsp; <span>@{{ statMessage }}</span>
                                    </div>
                                </div>

                                <div class="col-md-7 text-right">
                                    <button class="btn btn-info btn-sm" @click="close" :disabled="btnDisabled">Batal</button>
                                    &nbsp;<button class="btn btn-danger btn-sm" @click="hapus" :disabled="btnDisabled">Saya Mengerti !</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="ez-popup" id="jual-confirmation-popup">
                    <div class="layout" style="width: 40%;">
                        <div class="top-popup" style="background: none;">
                            <span class="title">
                                Konfirmasi Penjualan Aset
                            </span>

                            {{-- <span class="close"><i class="fa fa-times" style="font-size: 12pt; color: #CC0000"></i></span> --}}
                        </div>
                        
                        <div class="content-popup">

                            <div class="row">
                                <div class="col-md-12">
                                    <table width="100%">
                                        <thead>
                                            <tr>
                                                <td class="text-left"style="background: #eee; padding: 8px; font-size: 8pt; padding-left: 15px; font-weight: 600;">
                                                    <i class="fa fa-arrow-right"></i> &nbsp; Detail Perhitungan Nilai Aset Yang Dipilih 
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 20px;">
                                <div class="col-md-12">
                                    <table class="table table-stripped table-mini" style="margin-bottom: 0px;">
                                        <thead>
                                            <tr>
                                                <th width="50%" style="border: 1px solid #eee;">Keterangan</th>
                                                <th width="25%" style="border: 1px solid #eee;">Nominal</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <tr>
                                                <td width="50%" style="border: 1px solid #eee;">-&nbsp; Nilai Perolehan Aset</td>
                                                <td width="25%" class="text-right jurnalDebet" style="border: 1px solid #eee;">@{{ singleData.at_harga_beli }}</td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="border: 1px solid #eee;">-&nbsp; Nilai Akumulasi Penyusutan</td>
                                                <td width="25%" class="text-right jurnalDebet" style="border: 1px solid #eee;">@{{ nilai_akumulasi }}</td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="border: 1px solid #eee;">-&nbsp; Nilai Buku</td>
                                                <td width="25%" class="text-right jurnalDebet" style="border: 1px solid #eee;">@{{ singleData.at_nilai_sisa }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 5px;">
                                <div class="col-md-12">
                                    <table class="table table-stripped table-mini" border="0" style="margin-bottom: 0px; margin-top: 5px;">
                                        <thead>
                                            <tr>
                                                <td width="2%" style="border: 0px; padding: 2px 10px;">-</td>
                                                <td style="border: 0px; padding: 2px 10px;">
                                                    <small><b>Segera Lakukan Transaksi Jurnal Apabila Aset Yang Dipilih Telah Resmi Di Jual.</b></small> 
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="row" style="border-top: 1px solid #eee; margin-top: 20px; padding-top: 20px;">
                                <div class="col-md-5">
                                    <div class="loader" v-if="stat == 'loading'" v-cloak>
                                       <div class="loading"></div> &nbsp; <span>@{{ statMessage }}</span>
                                    </div>
                                </div>

                                <div class="col-md-7 text-right">
                                    <button class="btn btn-info btn-sm" @click="closeJual" :disabled="btnDisabled">Batal</button>
                                    &nbsp;<button class="btn btn-warning btn-sm" @click="jualConfirm" :disabled="btnDisabled">Konfirmasi Jual !</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

              </div>
            </div>
        </div>
      </div>
    </div>
@endsection


@section(modulSetting()['extraScripts'])
    
    <script src="{{ asset('modul_keuangan/js/options.js') }}"></script>

    <script src="{{ asset('modul_keuangan/js/vendor/vue_2_x/vue_2_x.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/vue_2_x/components/datatable.component.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/vue_2_x/components/select.component.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/vue_2_x/components/inputmask.component.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/vue_2_x/components/datepicker.component.js') }}"></script>

    <script src="{{ asset('modul_keuangan/js/vendor/wait_me_v_1_1/wait.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/toast/dist/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/validator/bootstrapValidator.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/axios_0_18_0/axios.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/inputmask/inputmask.jquery.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/datepicker/dist/datepicker.min.js') }}"></script>

    <script type="text/javascript">

        function register_validator(){
            $('#data-form').bootstrapValidator({
                feedbackIcons : {
                  valid : 'glyphicon glyphicon-ok',
                  invalid : 'glyphicon glyphicon-remove',
                  validating : 'glyphicon glyphicon-refresh'
                },
                fields : {
                  at_nama : {
                    validators : {
                      notEmpty : {
                        message : 'Nama Aset Tidak Boleh Kosong',
                      }
                    }
                  },

                  at_tanggal_beli : {
                    validators : {
                      notEmpty : {
                        message : 'Tanggal Beli Tidak Boleh Kosong',
                      }
                    }
                  },

                  at_harga_beli : {
                    validators : {
                      notEmpty : {
                        message : 'Harga Beli Harus Di isi',
                      }
                    }
                  },

                }
            });
        }

        var app = new Vue({
            el: '#vue-component',
            data: {
                stat: 'standby',
                statMessage: '',
                btnDisabled: false,
                onAjaxLoading: false,
                onUpdate: false,
                dijual: false,

                data_table_columns : [],
                list_data_table : [],

                metodePenyusutan : [
                    {
                        id            : "GL",
                        text          : 'GL - Garis Lurus'
                    },

                    {
                        id            : "SM",
                        text          : 'SM - Saldo Menurun'
                    },
                ],

                kelompokAset : [],
                penyusutan : [],
                akunKas: [],
                akunPendapatan: '',
                akunKerugian: '',
                totDebet: 0,
                totKredit: 0,

                singleData: {
                    at_id: '',
                    at_nomor: '',
                    at_nama: '',
                    at_tanggal_beli: '',
                    at_metode: '',
                    at_masa_manfaat: '',
                    at_persentase: '',
                    at_akun_harta: '',
                    at_akun_akumulasi: '',
                    at_akun_beban: '',
                    at_harga_beli: '',
                    at_harga_jual: '0.00',
                    at_nilai_sisa: '',
                    at_akun_masukkan: '',
                }
            },

            created: function(){
                console.log('Initializing Vue');
            },

            mounted: function(){
                console.log('Vue Ready');
                register_validator();
                $('#at_tanggal_beli').val('{{ date('d/m/Y') }}');
                this.singleData.at_tanggal_beli = '{{ date('d/m/Y') }}';
                this.singleData.at_metode = this.metodePenyusutan[0].text;

                axios.get('{{route('aset.form_resource')}}')
                          .then((response) => {
                            console.log(response.data);

                            if(response.data.golongan.length > 0){
                                this.kelompokAset = response.data.golongan;
    
                                this.singleData.at_akun_harta = response.data.golongan[0].ga_akun_harta+' - '+response.data.golongan[0].nama_akun_harta;
                                this.singleData.at_akun_akumulasi = response.data.golongan[0].ga_akun_akumulasi+' - '+response.data.golongan[0].nama_akun_akumulasi;
                                this.singleData.at_akun_beban = response.data.golongan[0].ga_akun_beban+' - '+response.data.golongan[0].nama_akun_beban;

                                this.singleData.at_masa_manfaat = response.data.golongan[0].ga_masa_manfaat;
                                if($('#at_metode').val() == 'GL')
                                    this.singleData.at_persentase = response.data.golongan[0].ga_garis_lurus;
                                else
                                    this.singleData.at_persentase = response.data.golongan[0].ga_saldo_menurun;
                            }

                            if(response.data.akunKas.length > 0){
                                this.akunKas = response.data.akunKas;
                                this.singleData.at_akun_masukkan = this.akunKas[0].text;
                            }

                            this.akunPendapatan = response.data.akunPendapatan;
                            this.akunKerugian = response.data.akunKerugian

                          })
                          .catch((e) => {
                            alert('error '+e);
                          })
            },

            computed: {
                nilai_harta: function(){
                    if(this.onUpdate){
                        return this.singleData.at_harga_beli;
                    }

                    return '0.00';
                },

                nilai_akumulasi: function(){
                    if(this.onUpdate){
                        var at_harga_beli = parseFloat(this.singleData.at_harga_beli.replace(/\,/g, ''));
                        var at_nilai_sisa = parseFloat(this.singleData.at_nilai_sisa.replace(/\,/g, ''));

                        return this.humanizePrice(Number((at_harga_beli - at_nilai_sisa).toFixed(2)));
                    }

                    return '0.00';
                },

                laba_rugi: function(){
                    if(this.onUpdate){
                        var at_harga_jual = parseFloat(this.singleData.at_harga_jual.replace(/\,/g, ''));
                        var at_nilai_sisa = parseFloat(this.singleData.at_nilai_sisa.replace(/\,/g, ''));

                        return this.humanizePrice(Number((at_harga_jual - at_nilai_sisa).toFixed(2)));
                    }

                    return '0.00';
                },

                akunLabaRugi: function(){
                    if(parseFloat(this.laba_rugi.replace(/\,/g, '')) < 0)
                        return this.akunKerugian;

                    return this.akunPendapatan;
                }
            },

            watch: {
                // 
            },

            methods: {
                saveData: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    if($('#at_harga_beli').val() == '0.00' || $('#at_harga_beli').val() == '' || parseFloat($('#at_harga_beli').val().replace(/\,/g, '')) < 1000){

                        $.toast({
                            text: "Harga Beli Tidak Boleh Nol (0) Dan Harus Lebih Dari Atau Sama Dengan 1000",
                            showHideTransition: 'slide',
                            position: 'top-right',
                            icon: 'error',
                            hideAfter: false
                        });

                        return;
                    }

                    if($('#data-form').data('bootstrapValidator').validate().isValid()){
                        this.calculated();
                        $('#penyusutan-popup').ezPopup('show');
                    }else{
                        $.toast({
                            text: "Harap Lengkapi Data Inputan",
                            showHideTransition: 'slide',
                            position: 'top-right',
                            icon: 'error',
                            hideAfter: false
                        });

                        return;
                    }
                },

                saving: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    this.stat = 'loading';
                    this.statMessage = 'Sedang Memperbarui Data ..'
                    this.btnDisabled = true;

                    axios.post('{{ route('aset.store') }}', $('#data-form').serialize())
                                .then((response) => {
                                    console.log(response.data);
                                    
                                    if(response.data.status == 'berhasil'){
                                        $.toast({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'top-right',
                                            icon: 'success',
                                            hideAfter: 5000
                                        });

                                        this.formReset();
                                        $('#penyusutan-popup').ezPopup('close');
                                    }else{
                                        $.toast({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'top-right',
                                            icon: 'error',
                                            hideAfter: false
                                        });

                                        this.stat = 'standby';
                                    }

                                })
                                .catch((err) => {
                                    alert('Ups. Sistem Mengalami kesalahan. Message: '+err);
                                })
                                .then(() => {
                                    this.btnDisabled = false;
                                })
                },

                hapus: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    var cfrm = confirm('Aset Anda Akan Dihapus. Lanjutkan ?');

                    if(cfrm){
                        this.stat = 'loading';
                        this.statMessage = 'Sedang Menghapus Data ..'
                        this.btnDisabled = true;

                        axios.post('{{ route('aset.delete') }}', { at_id: this.singleData.at_id, _token: '{{ csrf_token() }}' })
                                .then((response) => {
                                    console.log(response.data);
                                    
                                    if(response.data.status == 'berhasil'){
                                        $.toast({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'top-right',
                                            icon: 'success',
                                            hideAfter: 5000
                                        });

                                        this.formReset();
                                        $('#delete-confirmation-popup').ezPopup('close');
                                    }else{
                                        $.toast({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'top-right',
                                            icon: 'error',
                                            hideAfter: false
                                        });

                                        this.stat = 'standby';
                                    }

                                })
                                .catch((err) => {
                                    alert('Ups. Sistem Mengalami kesalahan. Message: '+err);
                                })
                                .then(() => {
                                    this.btnDisabled = false;
                                })
                    }else{
                       $('#delete-confirmation-popup').ezPopup('close');
                    }
                },

                terjual: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    this.calculatingJurnal();
                    $('#jual-confirmation-popup').ezPopup('show');
                },

                jualConfirm: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    var cfrm = confirm('Aset Anda Akan Ditandai Sebagai Aset Yang Terjual. Lanjutkan ?');

                    if(cfrm){
                        this.stat = 'loading';
                        this.statMessage = 'Sedang Memproses Penjualan Data ..'
                        this.btnDisabled = true;

                        axios.post('{{ route('aset.update') }}', { at_id: this.singleData.at_id, _token: '{{ csrf_token() }}' })
                                .then((response) => {
                                    console.log(response.data);
                                    
                                    if(response.data.status == 'berhasil'){
                                        $.toast({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'top-right',
                                            icon: 'success',
                                            hideAfter: 5000
                                        });

                                        this.formReset();
                                        $('#jual-confirmation-popup').ezPopup('close');
                                    }else{
                                        $.toast({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'top-right',
                                            icon: 'error',
                                            hideAfter: false
                                        });

                                        this.stat = 'standby';
                                    }

                                })
                                .catch((err) => {
                                    alert('Ups. Sistem Mengalami kesalahan. Message: '+err);
                                })
                                .then(() => {
                                    this.btnDisabled = false;
                                })
                    }
                },

                confirmDelete: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    $('#delete-confirmation-popup').ezPopup('show');
                },

                close: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    $('#delete-confirmation-popup').ezPopup('close');
                },

                closeJual: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    $('#jual-confirmation-popup').ezPopup('close');
                },

                metodeChange: function(e){
                    var idx = this.kelompokAset.findIndex(alpha => alpha.id == $('#at_golongan').val());
                    var ids = this.metodePenyusutan.findIndex(beta => beta.id == e);
                    var conteks = this.kelompokAset[idx];

                    this.singleData.at_metode = this.metodePenyusutan[ids].text;

                    switch(e){
                        case 'SM':
                            this.singleData.at_persentase = this.kelompokAset[idx].ga_saldo_menurun;
                            break;

                        case 'GL':
                            this.singleData.at_persentase = this.kelompokAset[idx].ga_garis_lurus;
                            break;

                    }
                },

                kelompokChange: function(e){
                    var idx = this.kelompokAset.findIndex(alpha => alpha.id == e);

                    this.singleData.at_masa_manfaat = this.kelompokAset[idx].ga_masa_manfaat;

                    this.singleData.at_akun_harta = this.kelompokAset[idx].ga_akun_harta+' - '+this.kelompokAset[idx].nama_akun_harta;
                    this.singleData.at_akun_akumulasi = this.kelompokAset[idx].ga_akun_akumulasi+' - '+this.kelompokAset[idx].nama_akun_akumulasi;
                    this.singleData.at_akun_beban = this.kelompokAset[idx].ga_akun_beban+' - '+this.kelompokAset[idx].nama_akun_beban;

                    this.metodeChange($('#at_metode').val());
                },

                tanggalChange: function(e){
                    this.singleData.at_tanggal_beli = e;
                },

                hargaJualChange: function(e){
                    this.singleData.at_harga_jual = e.val;
                },

                akunMasukkanChange: function(e){
                    var idx = this.akunKas.findIndex(alpha => alpha.id === e);
                    this.singleData.at_akun_masukkan = this.akunKas[idx].text;
                },

                search: function(e){
                    e.preventDefault();
                    this.list_data_table = [];
                    this.onAjaxLoading = true;

                    that = this;

                    this.data_table_columns = [
                        {name: 'Nomor Aset', context: 'at_nomor', width: '20%', childStyle: 'text-align: center; font-size:9pt;'},
                        {name: 'Nama Aset', context: 'at_nama', width: '20%', childStyle: 'text-align: center; font-size:9pt;'},
                        {name: 'Metode Penyusutan', context: 'at_metode', width: '20%', childStyle: 'text-align: center; font-size:9pt;', override(e){

                            if(e == 'GL')
                                return 'Garis Lurus';

                            return "Saldo menurun";
                        }},

                        {name: 'Harga Beli', context: 'at_harga_beli', width: '20%', childStyle: 'text-align: right; font-size:9pt;', override(e){
                            return that.humanizePrice(e);
                        }},

                        {name: 'Nilai Sisa', context: 'at_nilai_sisa', width: '20%', childStyle: 'text-align: right; font-size:9pt;', override(e){
                            return that.humanizePrice(e);
                        }},
                    ];

                    axios.get('{{ route('aset.datatable') }}')
                            .then((response) => {
                                console.log(response.data);
                                
                                if(response.data.length > 0)
                                    this.list_data_table = response.data;
                                
                            })
                            .then(() => {
                                this.onAjaxLoading = false;
                            })
                            .catch((err) => {
                                alert('Ups. Sistem Mengalami kesalahan. Message: '+err);
                            })

                    $('#data-popup').ezPopup('show');
                },

                dataSelected: function(e){
                    var idx = this.list_data_table.findIndex(alpha => alpha.at_id === e);
                    var conteks = this.list_data_table[idx];
                    var tanggal = conteks.at_tanggal_beli.split('-')[2]+'/'+conteks.at_tanggal_beli.split('-')[1]+'/'+conteks.at_tanggal_beli.split('-')[0];

                    this.singleData.at_id = conteks.at_id;
                    this.singleData.at_nomor = conteks.at_nomor;
                    this.singleData.at_nama = conteks.at_nama;
                    this.singleData.at_harga_beli = this.humanizePrice(conteks.at_harga_beli);
                    this.singleData.at_nilai_sisa = this.humanizePrice(conteks.at_nilai_sisa);
                    this.singleData.at_tanggal_beli = tanggal;

                    if(this.akunKas.length > 0){
                        $('#akun_masukkan').val(this.akunKas[0].id).trigger('change.select2');
                    }

                    $('#at_harga_beli').val(conteks.at_harga_beli);
                    $('#at_tanggal_beli').val(tanggal);
                    $('#at_metode').val(conteks.at_metode).trigger('change.select2');
                    $('#at_golongan').val(conteks.at_golongan).trigger('change.select2');

                    this.kelompokChange(conteks.at_golongan);

                    this.onUpdate = true;
                    $('#data-popup').ezPopup('close');
                },

                calculated: function(){
                    var np = parseFloat($('#at_harga_beli').val().replace(/\,/g, ''));
                    var tahun = parseInt(this.singleData.at_tanggal_beli.split('/')[2]);
                    var bulan = parseInt(this.singleData.at_tanggal_beli.split('/')[1]);
                    var masaManfaat = parseInt(this.singleData.at_masa_manfaat);
                    var loop  = (bulan > 1) ? (masaManfaat + 1) : masaManfaat;
                    var nilai_penyusutan = nilai_akumulasi = 0 ;
                    var persentase = this.singleData.at_persentase/100;
                    var nilai_sisa = np;
                    var result = [];

                    for(var stack = 0; stack < loop; stack++){

                        var bulan_jalan = 12;
                        var year_now = (tahun == '{{ date('Y') }}');

                        // console.log(tahun + stack);

                        if((tahun+stack) == tahun){
                          bulan_jalan = 12 - (bulan-1);
                        }else if((tahun+stack) == (tahun + masaManfaat)){
                          bulan_jalan = (bulan - 1);
                        }

                        if($('#at_metode').val() == "GL"){
                          nilai_penyusutan = ((np * persentase) / 12) * bulan_jalan;
                        }else{
                          if(stack != (loop-1)){
                            nilai_penyusutan = (((np - nilai_akumulasi) * persentase) / 12) * bulan_jalan;
                          }else{
                            nilai_penyusutan = nilai_sisa;
                          }
                        }

                        nilai_akumulasi += nilai_penyusutan;
                        nilai_sisa -= nilai_penyusutan;

                        result[stack] = {
                            "tahun"             : tahun++,
                            "bulan"             : bulan_jalan,
                            "hargaPerolehan"    : this.humanizePrice(np),
                            "nilaiPenyusutan"   : this.humanizePrice(Number(nilai_penyusutan.toFixed(2))),
                            "nilaiAkumulasi"    : this.humanizePrice(Number(nilai_akumulasi.toFixed(2))),
                            "nilaiSisa"         : this.humanizePrice(Number(nilai_sisa.toFixed(2))),
                            'tahunIni'          : year_now,
                        };
                    }

                    this.penyusutan = result;
                },

                calculatingJurnal: function(){
                    var debet = $('.jurnalDebet');
                    var kredit = $('.jurnalKredit');

                    var d = k = 0;
                    
                    debet.each(function(e){
                        ctx = parseFloat($(this).text().replace(/\,/g, ''));
                        d += ctx;
                    });

                    kredit.each(function(e){
                        ctx = parseFloat($(this).text().replace(/\,/g, ''));
                        k += ctx;
                    });

                    this.totDebet = d;
                    this.totKredit = k;

                    console.log(k);

                },

                humanizePrice: function(alpha){
                  var bilangan = alpha.toString();
                  var commas = '00';


                  if(bilangan.split('.').length > 1){
                    commas = bilangan.split('.')[1];
                    bilangan = bilangan.split('.')[0];
                  }
                  
                  var number_string = bilangan.toString(),
                    sisa  = number_string.length % 3,
                    rupiah  = number_string.substr(0, sisa),
                    ribuan  = number_string.substr(sisa).match(/\d{3}/g);
                      
                  if (ribuan) {
                    separator = sisa ? ',' : '';
                    rupiah += separator + ribuan.join(',');
                  }

                  // Cetak hasil
                  return rupiah+'.'+commas; // Hasil: 23.456.789
                },

                formReset: function(){

                    this.singleData.at_id = '';
                    this.singleData.at_nomor = '';
                    this.singleData.at_nama = '';
                    this.singleData.at_tanggal_beli = '{{ date('d/m/Y') }}';

                    // at_metode: '',
                    // at_masa_manfaat: '',
                    // at_persentase: '',

                    $('#at_harga_beli').val(0);
                    $('#at_metode').val(this.metodePenyusutan[0].id).trigger('change.select2');

                    if(this.kelompokAset.length > 0){
                        $('#at_golongan').val(this.kelompokAset[0].id).trigger('change.select2');
                        this.kelompokChange(this.kelompokAset[0].id);
                    }

                    if(this.akunKas.length > 0){
                        $('#akun_keluaran').val(this.akunKas[0].id).trigger('change.select2');
                    }

                    this.stat = 'standby';
                    this.onAjaxLoading = false;
                    this.onUpdate = false;

                    setTimeout(function(){
                        $('#at_tanggal_beli').val('{{ date('d/m/Y') }}');
                    }, 0);

                    $('#penyusutan-popup').ezPopup('close');
                    $('#data-form').data('bootstrapValidator').resetForm();
                }
            }
        })

    </script>

@endsection