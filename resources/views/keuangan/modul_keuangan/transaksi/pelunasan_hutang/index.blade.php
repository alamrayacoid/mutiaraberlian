@extends('main')

@section('title', 'Pelunasan Piutang')

@section(modulSetting()['extraStyles'])

	<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/wait_me_v_1_1/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/toast/dist/jquery.toast.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/datepicker/dist/datepicker.min.css') }}">

@endsection


@section('content')
    <div class="col-md-12" style="background: none;" id="vue-component">
    	<div class="col-md-12">
    		<div class="row">
    			<div class="col-md-6 content-title">
    				Input Pelunasan Hutang
    			</div>

    			<div class="col-md-6 text-right form-status">
    				<span v-if="stat == 'standby'" v-cloak>
                        <i class="fa fa-exclamation"></i> &nbsp; Pastikan Data Terisi Dengan Benar            
                    </span>

                    <div class="loader" v-if="stat == 'loading'" v-cloak>
                       <div class="loading"></div> &nbsp; <span>@{{ statMessage }}</span>
                    </div>
    			</div>
    		</div>	
    	</div>

    	<div class="col-md-12 table-content">
            <form id="data-form" v-cloak>
                <input type="hidden" readonly name="_token" value="{{ csrf_token() }}">
                <input type="hidden" readonly name="rc_id" v-model="singleData.rc_id">
                <div class="row">
                    <div class="col-md-6" style="background: none;">

                        <div class="row mt-form">
                            <div class="col-md-3">
                                <label class="modul-keuangan">Nomor Pelunasan</label>
                            </div>

                            <div class="col-md-5">
                                <input type="text" name="rc_nomor" class="form-control modul-keuangan" placeholder="Di Isi Oleh Sistem" readonly v-model="singleData.rc_nomor">
                            </div>

                            <div class="col-md-1 form-info-icon link" @click="search" v-if="!onUpdate">
                                <i class="fa fa-search" title="Cari Penerimaan Berdasarkan Nomor, Jenis, dan Bulan"></i>
                            </div>

                            <div class="col-md-1 form-info-icon link" @click="formReset" v-if="onUpdate">
                                <i class="fa fa-times" title="Bersihkan Pencarian" style="color: #CC0000;"></i>
                            </div>
                        </div>

                        <div class="row mt-form">
                            <div class="col-md-3">
                                <label class="modul-keuangan">Jenis Hutang *</label>
                            </div>

                            <div class="col-md-5">
                                <vue-select :name="'rc_chanel'" :id="'rc_chanel'" :options="listChanel" @input="chanelChange"></vue-select>
                            </div>

                            <div class="col-md-1 form-info-icon" title="Parameter Jenis Piutang Digunakan Untuk Pencarian Data">
                                <i class="fa fa-info-circle"></i>
                            </div>
                        </div>

                        <div class="row mt-form">
                            <div class="col-md-3">
                                <label class="modul-keuangan">Tgl Pelunasan *</label>
                            </div>

                            <div class="col-md-5">
                                <vue-datepicker :name="'rc_tanggal_trans'" :id="'rc_tanggal_trans'" :title="'Tidak Boleh Kosong'" :readonly="true" :placeholder="'Pilih Tanggal'" @input="dateChange"></vue-datepicker>
                            </div>

                            <div class="col-md-1 form-info-icon" title="Parameter Bulan Pada Tanggal Juga Digunakan Untuk Pencarian Data">
                                <i class="fa fa-info-circle"></i>
                            </div>
                        </div>

                        <div class="row mt-form" style="border-top: 1px solid #eee; padding-top: 20px;">
                            <div class="col-md-3">
                                <label class="modul-keuangan">Nomor Hutang *</label>
                            </div>

                            <div class="col-md-6">
                                <input type="text" name="rc_sales" class="form-control modul-keuangan" placeholder="Pilih Nomor Hutang" v-model="singleData.rc_sales" title="Tidak Boleh Kosong" readonly style="cursor: copy; background: white;" @click="notaShow">
                            </div>
                        </div>

                        <div class="row mt-form">
                            <div class="col-md-3">
                                <label class="modul-keuangan">Ket. Transaksi *</label>
                            </div>

                            <div class="col-md-6">
                                <input type="text" name="rc_keterangan" class="form-control modul-keuangan" placeholder="contoh: Penerimaan Piutang Atas Nota xxx" v-model="singleData.rc_keterangan" title="Tidak Boleh Kosong">
                            </div>
                        </div>

                        <div class="row mt-form">
                            <div class="col-md-3">
                                <label class="modul-keuangan">Jenis Pelunasan</label>
                            </div>

                            <div class="col-md-6">
                                <vue-select :name="'jenis'" :id="'jenis'" :options="jenisPenerimaan" @input="jenisChange"></vue-select>
                            </div>
                        </div>

                        <div class="row mt-form" v-if="jenis == 'C'">
                            <div class="col-md-3">
                                <label class="modul-keuangan">Pilih Akun Kas</label>
                            </div>

                            <div class="col-md-6">
                                <vue-select :name="'akun'" :id="'akunKas'" :options="akunKas" @input="akunChange"></vue-select>
                            </div>
                        </div>

                        <div class="row mt-form" v-if="jenis == 'T'">
                            <div class="col-md-3">
                                <label class="modul-keuangan">Pilih Akun Bank</label>
                            </div>

                            <div class="col-md-6">
                                <vue-select :name="'akun'" :id="'bank'" :options="akunBank" @input="akunChange"></vue-select>
                            </div>
                        </div>

                        <div class="row mt-form">
                            <div class="col-md-3">
                                <label class="modul-keuangan">Nominal Pelunasan</label>
                            </div>

                            <div class="col-md-6">
                                <vue-inputmask :name="'rc_value'" :id="'rc_value'" @input="nominalChange"></vue-inputmask>
                            </div>
                        </div>

                        <div class="row mt-form">
                            <div class="col-md-3">
                                <label class="modul-keuangan"></label>
                            </div>

                            <div class="col-md-7" v-if="afterNote && lebih_bayar != '0.00' && this.singleData.akunTitipan != '???'">
                                <input type="checkbox" name="dana_titipan" title="Centang Untuk Menambahkan Nilai Lebih Bayar Ke Akun Dana Titipan" v-model="titipan">

                                <span style="font-size: 8pt; margin-left: 5px;">Masukkan Nilai Lebih Bayar Sebagai Akun Titipan</span>
                            </div>

                            <div class="col-md-7" v-if="afterNote && lebih_bayar != '0.00' && this.singleData.akunTitipan == '???'">    
                                <i class="fa fa-exclamation-triangle" style="color: #0099CC;"></i>
                                <span style="font-size: 8pt; margin-left: 5px;">Transaksi Jenis Ini Tidak Memiliki Akun Titipan.</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" style="background: none; padding: 5px; border:1px solid #eee;">
                        <div class="col-md-12" style="padding: 0px; min-height: 170px; background: #f7f7f7; border: 0px solid #eee;">
                            <table class="table table-stripped table-mini">
                                <thead>
                                    <td colspan="4" style="font-weight: 600; border-top: 0px; padding-top: 5px;">Detail Terkait Nota Yang Dipilih <small v-if="onUpdate">(sebelum dilakukan penerimaan)</small></td>
                                </thead>
                                <tbody id="wrap">
                                    <tr>
                                        <td class="text-center" width="10%">-</td>
                                        <td width="58%">Total Tagihan</td>
                                        <td width="6%">:</td>
                                        <td width="26%" class="text-right" style="color: #ff4444; font-weight: bold">@{{ humanizePrice(singleData.total_tagihan) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-center" style="border-top: 0px;">-</td>
                                        <td style="border-top: 0px;">Nominal Yang Sudah Dibayar</td>
                                        <td width="6%" style="border-top: 0px">:</td>
                                        <td class="text-right" style="color: #00C851; font-weight: bold; border-top: 0px;">@{{ humanizePrice(singleData.sudah_dibayar) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-center" style="font-size: 8pt;"><i class="fa fa-arrow-right"></i></td>
                                        <td style="font-weight: 600;">Sisa Tagihan</td>
                                        <td width="6%">:</td>
                                        <td class="text-right" style="font-weight: bold;">@{{ humanizePrice(sisa_bayar) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12" style="padding: 0px; min-height: 170px; background: #f7f7f7; margin-top: 50px;">
                            <table class="table table-stripped table-mini">
                                <thead>
                                    <td colspan="2" style="font-weight: 600; border: 1px solid #eee; border-left: 0px;">Detail Akun Keuangan Pada Jurnal</td>
                                    <td class="text-center" style="font-weight: 600; border: 1px solid #eee;">Debet</td>
                                    <td class="text-center" style="font-weight: 600; border: 1px solid #eee; border-right: 0px;">Kredit</td>
                                </thead>
                                <tbody id="wrap">
                                    <tr>
                                        <td class="text-center" width="10%" style="font-size: 8pt; border: 1px solid #eee; border-left: 0px;"><i class="fa fa-arrow-right"></i></td>
                                        <td width="44%" style="border: 1px solid #eee;">@{{ singleData.akun }}</td>
                                        <td width="22%" class="text-right" style="font-size: 8pt; border: 1px solid #eee;">0.00</td>
                                        <td width="22%" class="text-right" style="font-size: 8pt; border: 1px solid #eee; border-right: 0px;">@{{ singleData.ak_1 }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-center" style="font-size: 8pt; border: 1px solid #eee; border-left: 0px;"><i class="fa fa-arrow-right"></i></td>
                                        <td style="border: 1px solid #eee;">@{{ this.singleData.akunPiutang }}</td>
                                        <td class="text-right" style="font-size: 8pt; border: 1px solid #eee;">@{{ singleData.ak_2 }}</td>
                                        <td class="text-right" style="font-size: 8pt; border: 1px solid #eee; border-right: 0px;">
                                            0.00
                                        </td>
                                    </tr>

                                    <tr v-if="titipan">
                                        <td class="text-center" style="font-size: 8pt; border: 1px solid #eee; border-left: 0px;"><i class="fa fa-arrow-right"></i></td>
                                        <td style="border: 1px solid #eee;">@{{ this.singleData.akunTitipan }}</td>
                                        <td class="text-right" style="font-size: 8pt; border: 1px solid #eee;">@{{ lebih_bayar }}</td>
                                        <td class="text-right" style="font-size: 8pt; border: 1px solid #eee; border-right: 0px;">
                                            0.00
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row content-button">
                    <div class="col-md-6">
                        {{-- <a href="{{ route('grup-akun.index') }}">
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-arrow-left" :disabled="btnDisabled"></i> &nbsp;Kembali Ke Halaman Data Group Akun</button>
                        </a> --}}
                    </div>

                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-info btn-sm" @click="updateData" :disabled="btnDisabled" v-if="onUpdate"><i class="fa fa-floppy-o"></i> &nbsp;Simpan Perubahan</button>
                        
                        <button type="button" class="btn btn-danger btn-sm" @click="deleteData" :disabled="btnDisabled" v-if="onUpdate"><i class="fa fa-times"></i> &nbsp;Hapus</button>

                        <button type="button" class="btn btn-primary btn-sm" @click="saveData" :disabled="btnDisabled" v-if="!onUpdate"><i class="fa fa-floppy-o"></i> &nbsp;Simpan</button>
                    </div>
                </div>
            </form>
    	</div>

        <div class="ez-popup" id="data-popup">
            <div class="layout" style="width: 70%">
                <div class="top-popup" style="background: none;">
                    <span class="title">
                        Data Pelunasan Hutang Yang Sudah Masuk <small>(@{{ singleData.chanel }})</small>
                    </span>

                    <span class="close"><i class="fa fa-times" style="font-size: 12pt; color: #CC0000"></i></span>
                </div>
                
                <div class="content-popup">
                    <vue-datatable :data_resource="list_data_table" :columns="data_table_columns" :selectable="true" :ajax_on_loading="onAjaxLoading" :index_column="'pydt_id'" @selected="dataSelected"></vue-datatable>
                </div>
            </div>
        </div>

        <div class="ez-popup" id="nota-popup">
            <div class="layout" style="width: 70%">
                <div class="top-popup" style="background: none;">
                    <span class="title">
                        Data Nomor Hutang
                    </span>

                    <span class="close"><i class="fa fa-times" style="font-size: 12pt; color: #CC0000"></i></span>
                </div>
                
                <div class="content-popup">
                    <vue-datatable :data_resource="list_data_table" :columns="data_table_columns" :selectable="true" :ajax_on_loading="onAjaxLoading" :index_column="'py_id'" @selected="notaSelected"></vue-datatable>
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
                  rc_chanel : {
                    validators : {
                      notEmpty : {
                        message : 'Anda Harus Memilih Jenis Hutang',
                      }
                    }
                  },

                  rc_keterangan : {
                    validators : {
                      notEmpty : {
                        message : 'Keterangan Tidak Boleh Kosong',
                      }
                    }
                  },

                  rc_sales : {
                    validators : {
                      notEmpty : {
                        message : 'Anda Harus Memilih Nomor Hutang',
                      }
                    }
                  },

                  rc_tanggal_trans : {
                    validators : {
                      notEmpty : {
                        message : 'Tanggal Tidak Boleh Kosong',
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
                jenis: 'C',
                afterNote: false,
                titipan: false,

                data_table_columns : [],
                
                list_data_table : [],

                listChanel: [],

                jenisPenerimaan: [
                    { 
                        id: 'C',
                        text: 'Pembayaran Secara Tunai'
                    },

                    {
                        id: 'T',
                        text: 'Pembayaran Lewat Bank Transfer'
                    }
                ],

                akunKas: [],
                akunBank: [],

                singleData: {
                    rc_nomor: '',
                    rc_id: '',
                    rc_sales: '',
                    rc_keterangan: '',
                    ak_1: '0.00',
                    ak_2: '0.00',
                    ak_3: '0.00',
                    total_tagihan: 0,
                    sudah_dibayar: 0,
                    akun: '???',
                    akunPiutang: '???',
                    akunTitipan: '???',
                    chanel: ''
                }
            },

            created: function(){
                console.log('Initializing Vue');
            },

            mounted: function(){
                console.log('Vue Ready');
                $('#rc_tanggal_trans').val('{{ date('d/m/Y') }}');
                register_validator();

                var that = this;

                axios.get('{{route('transaksi.pelunasan_hutang.form_resource')}}')
                          .then((response) => {
                            // console.log(response.data);

                            if(response.data.chanel.length > 0){
                                this.listChanel = response.data.chanel;
                                this.singleData.chanel = this.listChanel[0].id
                            }

                            if(response.data.akunKas.length > 0){
                                this.akunKas = response.data.akunKas;
                                this.singleData.akun = this.akunKas[0].text;
                            }

                            if(response.data.akunBank.length > 0){
                                this.akunBank = response.data.akunBank;
                            }                         
                          })
                          .catch((e) => {
                            alert('error '+e);
                          })
            },

            computed: {
                sisa_bayar: function(){
                    return (parseFloat(this.singleData.total_tagihan) - parseFloat(this.singleData.sudah_dibayar));
                },

                lebih_bayar: function(){
                    if(this.afterNote){
                        var total_tagihan = parseFloat(this.sisa_bayar);
                        var dibayarkan = parseFloat(this.singleData.ak_1.replace(/\,/g, ''));
                        
                        if((dibayarkan - total_tagihan) > 0){
                            return (this.humanizePrice(dibayarkan - total_tagihan));
                        }
                        else{
                            this.titipan = false;
                            return this.humanizePrice(0);
                        }
                    }else{
                        this.titipan = false;
                        return this.humanizePrice(0);
                    }
                }
            },

            watch: {
                // 
            },

            methods: {
                saveData: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();
                    if($('#data-form').data('bootstrapValidator').validate().isValid()){

                        if(this.lebih_bayar != this.humanizePrice(0) && !this.titipan){
                            $.toast({
                                text: 'Jumlah Yang Dibayarkan Tidak Boleh Melebihi Jumlah Sisa Bayar, Kecuali Nilai Lebih Pembayaran Dimasukkan Sebagai Dana Titipan',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                icon: 'info',
                                hideAfter: 8000
                            });

                            return false;
                        }

                        this.stat = 'loading';
                        this.statMessage = 'Sedang Menyimpan Data ..'
                        this.btnDisabled = true;

                        axios.post('{{ route('transaksi.pelunasan_hutang.store') }}', $('#data-form').serialize())
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

                updateData: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    if($('#data-form').data('bootstrapValidator').validate().isValid()){

                        if(this.lebih_bayar != this.humanizePrice(0) && !this.titipan){
                            $.toast({
                                text: 'Jumlah Yang Dibayarkan Tidak Boleh Melebihi Jumlah Sisa Bayar, Kecuali Nilai Lebih Pembayaran Dimasukkan Sebagai Dana Titipan',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                icon: 'info',
                                hideAfter: 8000
                            });

                            return false;
                        }

                        this.stat = 'loading';
                        this.statMessage = 'Sedang Memperbarui Data ..'
                        this.btnDisabled = true;

                        axios.post('{{ route('transaksi.pelunasan_hutang.update') }}', $('#data-form').serialize())
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

                deleteData: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();
                    var cfrm = confirm('Apakah Anda Yakin, Data Yang Dihapus Tidak Bisa Dikembalikan Lagi ?');

                    if(cfrm){
                        this.stat = 'loading';
                        this.statMessage = 'Sedang Menghapus Transaksi ..'
                        this.btnDisabled = true;

                        axios.post('{{ route('transaksi.pelunasan_hutang.delete') }}', { rc_id: this.singleData.rc_id, _token: '{{ csrf_token() }}' })
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

                jenisChange: function(e){
                    this.jenis = e;

                    switch(e){
                        case 'C':
                            if(this.akunKas.length > 0)
                                this.akunChange(this.akunKas[0].id);
                            else
                                this.singleData.akun = '???';
                            break;


                        case 'T':
                            if(this.akunBank.length > 0)
                                this.akunChange(this.akunBank[0].id);
                            else
                                this.singleData.akun = '???';
                            break;
                    }
                },

                chanelChange: function(e){
                    this.singleData.chanel = e;
                    this.singleData.rc_keterangan = '';
                    this.singleData.rc_sales = '';

                    this.singleData.total_tagihan = 0;
                    this.singleData.sudah_dibayar = 0;

                    this.titipan = false;
                    this.afterNote = false;

                    this.singleData.akunPiutang = '???';
                    this.singleData.akunTitipan = '???';

                    $('#rc_value').val(0);
                    $('#rc_tanggal_trans').val('{{ date('d/m/Y') }}');
                    
                    this.nominalChange({val: '0.00', id: null});
                },

                akunChange: function(e){
                    switch(this.jenis){
                        case 'C':
                            var idx = this.akunKas.findIndex(beta => beta.id === e);
                            this.singleData.akun = this.akunKas[idx].text;
                            break;

                        case 'T':
                            var idx = this.akunBank.findIndex(beta => beta.id === e);
                            this.singleData.akun = this.akunBank[idx].text;
                            break;
                    }
                },

                dateChange: function(e){
                    // $('#data-form').data('bootstrapValidator').resetForm();
                },

                nominalChange: function(e){
                    if(this.singleData.rc_sales == ''){
                        $.toast({
                            text: 'Pilih Nota terlebih Dahulu',
                            showHideTransition: 'slide',
                            position: 'top-right',
                            icon: 'info',
                            hideAfter: 4000
                        });

                       $('#rc_value').val(0);
                       return false;
                    };

                    var nominal = (e.val) ? parseFloat(e.val.replace(/\,/g, '')) : 0;
                    var value = (e.val) ? (e.val) : '0.00';

                    this.singleData.ak_1 = value;
                    this.singleData.ak_2 = (nominal <= (this.singleData.total_tagihan - this.singleData.sudah_dibayar)) ? value : this.humanizePrice((this.singleData.total_tagihan - this.singleData.sudah_dibayar));
                },

                notaShow: function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    if(this.onUpdate){
                        $.toast({
                            text: 'Nota Tidak Bisa Diubah',
                            showHideTransition: 'slide',
                            position: 'top-right',
                            icon: 'info',
                            hideAfter: 4000
                        });

                        return false;
                    }

                    that = this;
                    this.list_data_table = [];

                    this.data_table_columns = [
                        {name: 'Nomor Piutang', context: 'py_nomor', width: '10%', childStyle: 'text-align: center; font-size:9pt;'},

                        {name: 'Jenis Piutang', context: 'py_chanel', width: '10%', childStyle: 'text-align: center; font-size:9pt;'},

                        {name: 'Tanggal Transaksi', context: 'py_tanggal', width: '10%', childStyle: 'text-align: center; font-size:9pt;', override(e){
                            return e.split('-')[2]+'/'+e.split('-')[1]+'/'+e.split('-')[0];
                        }},

                        {name: 'Jumlah Tagihan', context: 'py_total_tagihan', width: '10%', childStyle: 'text-align: right; font-size:9pt;', override(e){
                            return that.humanizePrice(e);
                        }},

                        {name: 'Sudah Dibayar', context: 'py_sudah_dibayar', width: '10%', childStyle: 'text-align: right; font-size:9pt;', override(e){
                            return that.humanizePrice(e);
                        }},

                        {name: 'Sisa Tagihan', context: 'py_sisa_tagihan', width: '10%', childStyle: 'text-align: right; font-size:9pt;', override(e){
                            return that.humanizePrice(e);
                        }},
                    ];

                    axios.get('{{ route('transaksi.pelunasan_hutang.get_nota') }}?chanel='+$('#rc_chanel').val())
                            .then((response) => {
                                console.log(response.data);
                                this.list_data_table = response.data;
                            })
                            .catch((e) => {
                                alert("error "+e)
                            })

                    $('#nota-popup').ezPopup('show');
                },

                search: function(e){
                    e.preventDefault();
                    this.list_data_table = [];
                    this.onAjaxLoading = true;
                    var that = this;

                    this.data_table_columns = [
                        {name: 'Nomor Pelunasan', context: 'pydt_nomor', width: '20%', childStyle: 'text-align: center; font-size:9pt;'},

                        {name: 'Keterangan Pelunasan', context: 'pydt_keterangan', width: '20%', childStyle: 'text-align: center; font-size:9pt;'},

                        {name: 'Tanggal Pelunasan', context: 'pydt_tanggal', width: '20%', childStyle: 'text-align: center; font-size:9pt;', override: function(e){
                            return e.split('-')[2]+'/'+e.split('-')[1]+'/'+e.split('-')[0];
                        }},

                        {name: 'Nominal Pelunasan', context: 'pydt_value', width: '20%', childStyle: 'text-align: right; font-size:9pt;', override: function(e){
                            return that.humanizePrice(e);
                        }},

                        {name: 'Type Pelunasan', context: 'pydt_type', width: '20%', childStyle: 'text-align: center; font-size:9pt;', override: function(e){
                            
                            switch(e){
                                case 'C':
                                    return 'Cash';
                                    break;

                                case 'T':
                                    return 'Transfer';
                                    break;
                            };
                        }}
                    ];

                    axios.get('{{ route('transaksi.pelunasan_hutang.datatable') }}?jenis='+$('#rc_chanel').val()+'&tgl='+$('#rc_tanggal_trans').val())
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
                    this.afterNote = true;
                    var idx = this.list_data_table.findIndex(alpha => alpha.pydt_id === e);
                    var ctx = this.list_data_table[idx];
                    var tgl = ctx.pydt_tanggal.split('-')[2]+'/'+ctx.pydt_tanggal.split('-')[1]+'/'+ctx.pydt_tanggal.split('-')[0];

                    console.log(this.list_data_table[idx]);

                    this.singleData.rc_nomor = this.list_data_table[idx].pydt_nomor;
                    this.singleData.rc_id = this.list_data_table[idx].pydt_id;
                    this.singleData.rc_keterangan = ctx.pydt_keterangan;
                    this.singleData.rc_sales = ctx.payable.py_nomor;
                    this.singleData.akunPiutang = ctx.payable.py_akun_hutang;
                    this.singleData.total_tagihan = ctx.payable.py_total_tagihan;
                    this.singleData.sudah_dibayar = ctx.payable.py_sudah_dibayar - ctx.jurnal.detail[1].jrdt_value;

                    $('#rc_tanggal_trans').val(tgl);
                    $('#rc_value').val(ctx.jurnal.detail[0].jrdt_value);
                    $('#jenis').val(ctx.pydt_type).trigger('change.select2');
                    this.jenisChange(ctx.pydt_type);
                    this.nominalChange({val: $('#rc_value').val(), id: null});

                    if(this.jenis == 'C')
                        $('#akunKas').val(ctx.jurnal.detail[0].jrdt_akun).trigger('change.select2');
                    else
                        $('#bank').val(ctx.jurnal.detail[0].jrdt_akun).trigger('change.select2');

                    if(ctx.jurnal.detail.length == 3){
                        this.titipan = true;
                        this.singleData.akunTitipan = ctx.payable.py_akun_titipan;
                    }else if(ctx.payable.py_akun_titipan){
                        this.singleData.akunTitipan = ctx.payable.py_akun_titipan;
                    }

                    this.onUpdate = true;

                    $('#data-popup').ezPopup('close');
                },

                notaSelected: function(e){
                    var idx = this.list_data_table.findIndex(alpha => alpha.py_id === e);

                    this.singleData.rc_sales = this.list_data_table[idx].py_nomor;
                    this.singleData.total_tagihan = this.list_data_table[idx].py_total_tagihan;
                    this.singleData.sudah_dibayar = this.list_data_table[idx].py_sudah_dibayar;
                    this.singleData.akunPiutang = this.list_data_table[idx].py_akun_hutang;
                    this.afterNote = true;

                    if(this.list_data_table[idx].py_akun_titipan)
                        this.singleData.akunTitipan = this.list_data_table[idx].py_akun_titipan;

                    if(this.singleData.ak_2 != '0.00')
                        this.nominalChange({val: this.singleData.ak_1, id: null})

                    $('#nota-popup').ezPopup('close');
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
                    $('#rc_tanggal_trans').val('{{ date('d/m/Y') }}');

                    this.singleData.rc_nomor = '';
                    this.singleData.rc_id = '';
                    this.singleData.rc_keterangan = '';
                    this.singleData.total_tagihan = 0;
                    this.singleData.sudah_dibayar = 0;
                    this.singleData.ak_1 = '0.00';
                    this.singleData.ak_2 = '0.00';
                    this.singleData.ak_3 = '0.00';
                    this.singleData.akunPiutang = '???';
                    this.singleData.akunTitipan = '???';

                    $('#jenis').val(this.jenisPenerimaan[0].id).trigger('change.select2');
                    $('#rc_value').val(0);
                    this.nominalChange('0.00');
                    this.jenisChange(this.jenisPenerimaan[0].id);

                    this.singleData.rc_sales = '';

                    this.onUpdate = false;
                    this.afterNote = false;
                    this.titipan = false;
                    this.stat = 'standby';

                    $('#data-form').data('bootstrapValidator').resetForm();
                }
            }
        })

    </script>

@endsection