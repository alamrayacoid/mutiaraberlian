@extends('main')

@section('title', 'Tambah Data Group Aset')

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
              <li class="breadcrumb-item active" aria-current="page">Data Golongan Aset</li>
            </ol>
          </nav>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Tambahkan Data Golongan Aset</h4>

                <div class="table-responsive">
                    <form id="data-form" v-cloak>
                        <input type="hidden" readonly name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" readonly name="ga_id" v-model="singleData.ga_id">

                        <input type="hidden" readonly name="ga_masa_manfaat" v-model="singleData.masaManfaat">
                        <input type="hidden" readonly name="ga_garis_lurus" v-model="singleData.persentaseGL">
                        <input type="hidden" readonly name="ga_saldo_menurun" v-model="singleData.persentaseSM">

                        <div class="col-md-12" style="border-top: 1px solid #eee; padding-top: 20px;">
                            <div class="row">
                                <div class="col-md-6" style="background: none;">

                                    <div class="row mt-form">
                                        <div class="col-md-4">
                                            <label class="modul-keuangan">Nomor Group Aset</label>
                                        </div>

                                        <div class="col-md-6">
                                            <input type="text" name="ga_nomor" class="form-control modul-keuangan" placeholder="Di Isi Oleh Sistem" readonly v-model="singleData.ga_nomor">
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
                                            <label class="modul-keuangan">Golongan Group *</label>
                                        </div>

                                        <div class="col-md-6">
                                            <vue-select :name="'ga_golongan'" :id="'ga_golongan'" :options="groupAset" :disabled="onUpdate" @input="golonganChange"></vue-select>
                                        </div>

                                        <div class="col-md-1 form-info-icon" title="Parameter Golongan Digunakan Untuk Pencarian Data">
                                            <i class="fa fa-info-circle"></i>
                                        </div>
                                    </div>


                                    <div class="row mt-form">
                                        <div class="col-md-4">
                                            <label class="modul-keuangan">Nama Group Aset *</label>
                                        </div>

                                        <div class="col-md-7">
                                            <input type="text" name="ga_nama" class="form-control modul-keuangan" placeholder="contoh: Inventaris Kantor" v-model="singleData.ga_nama" title="Tidak Boleh Kosong">
                                        </div>
                                    </div>

                                    <div class="row mt-form">
                                        <div class="col-md-4">
                                            <label class="modul-keuangan">Keterangan Group</label>
                                        </div>

                                        <div class="col-md-7">
                                            <input type="text" name="ga_keterangan" class="form-control modul-keuangan" placeholder="contoh: Kumpulan Aset Inventaris Kantor" v-model="singleData.ga_keterangan" title="Tidak Boleh Kosong">
                                        </div>
                                    </div>

                                    <div class="row mt-form" style="border-top: 1px solid #eee; padding-top: 20px;">
                                       <div class="col-md-5">
                                            <label class="modul-keuangan">Akun Harta *</label>
                                        </div>

                                        <div class="col-md-6">
                                            <vue-select :name="'ga_akun_harta'" :id="'ga_akun_harta'" :options="akunHarta" :disabled="onUpdate"></vue-select>
                                        </div>
                                    </div>

                                    <div class="row mt-form">
                                       <div class="col-md-5">
                                            <label class="modul-keuangan">Akun Akm. Penyusutan *</label>
                                        </div>

                                        <div class="col-md-6">
                                            <vue-select :name="'ga_akun_akumulasi'" :id="'ga_akun_akumulasi'" :options="akunAkumulasi" :disabled="onUpdate"></vue-select>
                                        </div>
                                    </div>

                                    <div class="row mt-form">
                                       <div class="col-md-5">
                                            <label class="modul-keuangan">Akun Beban Penyusutan *</label>
                                        </div>

                                        <div class="col-md-6">
                                            <vue-select :name="'ga_akun_beban'" :id="'ga_akun_beban'" :options="akunBeban" :disabled="onUpdate"></vue-select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" style="background: none; padding: 5px; border:1px solid #eee;">
                                    <div class="col-md-12" style="padding: 0px; min-height: 170px; background: #f7f7f7; border: 0px solid #eee;">
                                        <table class="table table-stripped table-mini">
                                            <thead>
                                                <td colspan="3" style="font-weight: 600; border-top: 0px; padding-top: 5px;">Detail Terkait Golongan Yang Dipilih</td>
                                            </thead>
                                            <tbody id="wrap">
                                                <tr>
                                                    <td width="30%" class="text-center" style="border: 1px solid #eee; font-weight: 600; border-left: 0px;">Metode</td>
                                                    <td width="30%" class="text-center" style="border: 1px solid #eee; font-weight: 600;">Masa Manfaat</td>
                                                    <td width="30%" class="text-center" style="border: 1px solid #eee; font-weight: 600; border-right: 0px;">Persentase Penyusutan</td>
                                                </tr>

                                                <tr>
                                                    <td width="30%" class="text-center" style="border: 1px solid #eee; border-left: 0px;">Garis Lurus</td>
                                                    <td width="30%" class="text-center" style="border: 1px solid #eee;">@{{ singleData.masaManfaat }} Tahun</td>
                                                    <td width="30%" class="text-center" style="border: 1px solid #eee; border-right: 0px;">@{{ singleData.persentaseGL }} %</td>
                                                </tr>

                                                <tr>
                                                    <td width="30%" class="text-center" style="border: 1px solid #eee; border-left: 0px;">Saldo Menurun</td>
                                                    <td width="30%" class="text-center" style="border: 1px solid #eee;">@{{ singleData.masaManfaat }} Tahun</td>
                                                    <td width="30%" class="text-center" style="border: 1px solid #eee; border-right: 0px;">@{{ singleData.persentaseSM }} %</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-12" style="padding: 0px;">
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" style="border-top: 1px solid #eee; margin-top: 20px; padding-top: 20px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('group.aset.index') }}">
                                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-arrow-left" :disabled="btnDisabled"></i> &nbsp;Kembali Ke Halaman Data Group Aset</button>
                                    </a>
                                </div>

                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-info btn-sm" @click="updateData" :disabled="btnDisabled" v-if="onUpdate"><i class="fa fa-floppy-o"></i> &nbsp;Simpan Perubahan</button>
                                    
                                    <button type="button" class="btn btn-danger btn-sm" @click="deleteData" :disabled="btnDisabled" v-if="onUpdate"><i class="fa fa-times"></i> &nbsp;Hapus</button>

                                    <button type="button" class="btn btn-primary btn-sm" @click="saveData" :disabled="btnDisabled" v-if="!onUpdate"><i class="fa fa-floppy-o"></i> &nbsp;Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
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
                    <vue-datatable :data_resource="list_data_table" :columns="data_table_columns" :selectable="true" :ajax_on_loading="onAjaxLoading" :index_column="'ga_id'" @selected="dataSelected"></vue-datatable>
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
                  ga_golongan : {
                    validators : {
                      notEmpty : {
                        message : 'Anda Harus Memilih Golongan',
                      }
                    }
                  },

                  ga_nama : {
                    validators : {
                      notEmpty : {
                        message : 'Nama Group Tidak Boleh Kosong',
                      }
                    }
                  },

                  ga_akun_harta : {
                    validators : {
                      notEmpty : {
                        message : 'Anda Harus Memilih Akun Harta',
                      }
                    }
                  },

                  ga_akun_akumulasi : {
                    validators : {
                      notEmpty : {
                        message : 'Anda Harus Memilih Akun Akumulasi',
                      }
                    }
                  },

                  ga_akun_beban : {
                    validators : {
                      notEmpty : {
                        message : 'Anda Harus Memilih Akun Beban',
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

                data_table_columns : [],
                list_data_table : [],

                groupAset : [
                    {
                        text            : "Non Bangunan - Kelompok 1",
                        masa_manfaat    : 4,
                        garis_lurus     : 25,
                        saldo_menurun   : 50,
                        id              : '1'
                      },
                      {
                        text            : "Non Bangunan - Kelompok 2",
                        masa_manfaat    : 8,
                        garis_lurus     : 12.50,
                        saldo_menurun   : 25,
                        id              : '2'
                      },
                      {
                        text            : "Non Bangunan - Kelompok 3",
                        masa_manfaat    : 16,
                        garis_lurus     : 6.25,
                        saldo_menurun   : 12.5,
                        id              : '3'
                      },
                      {
                        text            : "Non Bangunan - Kelompok 4",
                        masa_manfaat    : 20,
                        garis_lurus     : 5,
                        saldo_menurun   : 10,
                        id              : '4'
                      },
                      {
                        text            : "Bangunan - Permanen",
                        masa_manfaat    : 20,
                        garis_lurus     : 5,
                        saldo_menurun   : 0,
                        id              : '5'
                      },
                      {
                        text            : "Bangunan - Non Permanen",
                        masa_manfaat    : 10,
                        garis_lurus     : 10,
                        saldo_menurun   : 0,
                        id              : '6'
                      }
                ],

                akunHarta : [],
                akunAkumulasi : [],
                akunBeban : [],

                singleData: {
                    ga_id: '',
                    ga_nomor: '',
                    ga_nama: '',
                    ga_keterangan: '',
                    masaManfaat: '',
                    persentaseGL: '',
                    persentaseSM: '',
                }
            },

            created: function(){
                console.log('Initializing Vue');
            },

            mounted: function(){
                console.log('Vue Ready');
                register_validator();
                this.singleData.masaManfaat = this.groupAset[0].masa_manfaat;
                this.singleData.persentaseSM = this.groupAset[0].saldo_menurun;
                this.singleData.persentaseGL = this.groupAset[0].garis_lurus;

                axios.get('{{route('group.aset.form_resource')}}')
                          .then((response) => {
                            console.log(response.data);

                            if(response.data.acc_harta.length > 0){
                                this.akunHarta = response.data.acc_harta;
                            }

                            if(response.data.acc_akumulasi.length > 0){
                                this.akunAkumulasi = response.data.acc_akumulasi;
                            }

                            if(response.data.acc_beban.length > 0){
                                this.akunBeban = response.data.acc_beban;
                            }

                          })
                          .catch((e) => {
                            alert('error '+e);
                          })
            },

            computed: {

            },

            watch: {
                // 
            },

            methods: {
                saveData: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();
                    if($('#data-form').data('bootstrapValidator').validate().isValid()){

                        this.stat = 'loading';
                        this.statMessage = 'Sedang Menyimpan Data ..'
                        this.btnDisabled = true;

                        axios.post('{{ route('group.aset.store') }}', $('#data-form').serialize())
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

                        this.stat = 'loading';
                        this.statMessage = 'Sedang Memperbarui Data ..'
                        this.btnDisabled = true;

                        axios.post('{{ route('group.aset.update') }}', $('#data-form').serialize())
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
                        this.statMessage = 'Sedang Menghapus Group ..'
                        this.btnDisabled = true;

                        axios.post('{{ route('group.aset.delete') }}', { ga_id: this.singleData.ga_id, _token: '{{ csrf_token() }}' })
                                .then((response) => {
                                    // console.log(response.data);
                                    
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

                golonganChange: function(e){
                    var idx = this.groupAset.findIndex(alpha => alpha.id === e);
                    context = this.groupAset[idx];

                    this.singleData.masaManfaat = context.masa_manfaat;
                    this.singleData.persentaseSM = context.saldo_menurun;
                    this.singleData.persentaseGL = context.garis_lurus;
                },

                search: function(e){
                    e.preventDefault();
                    this.list_data_table = [];
                    this.onAjaxLoading = true;

                    this.data_table_columns = [
                        {name: 'Nomor Group', context: 'ga_nomor', width: '20%', childStyle: 'text-align: center; font-size:9pt;'},

                        {name: 'Nama Group', context: 'ga_nama', width: '20%', childStyle: 'text-align: center; font-size:9pt;'},

                        {name: 'Golongan Group', context: 'ga_golongan', width: '20%', childStyle: 'text-align: center; font-size:9pt;', override(e){
                            switch(e){
                                case 1:
                                    return 'Non Bangunan - Kelompok 1';
                                    break;

                                case 2:
                                    return 'Non Bangunan - Kelompok 2';
                                    break;

                                case 3:
                                    return 'Non Bangunan - Kelompok 3';
                                    break;

                                case 4:
                                    return 'Non Bangunan - Kelompok 4';
                                    break;

                                case 5:
                                    return 'Bangunan - Permanen';
                                    break;

                                case 6:
                                    return 'Bangunan - Non Permanen';
                                    break;
                            }
                        }},

                        {name: 'Masa Manfaat', context: 'ga_masa_manfaat', width: '20%', childStyle: 'text-align: center; font-size:9pt;', override(e){
                            return e+' Tahun';
                        }},
                    ];

                    axios.get('{{ route('group.aset.datatable') }}?golongan='+$('#ga_golongan').val())
                            .then((response) => {
                                // console.log(response.data);
                                
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
                    
                    var idx = this.list_data_table.findIndex(alpha => alpha.ga_id === e);
                    var conteks = this.list_data_table[idx];

                    this.singleData.ga_id = conteks.ga_id;
                    this.singleData.ga_nomor = conteks.ga_nomor;
                    this.singleData.ga_nama = conteks.ga_nama;
                    this.singleData.ga_keterangan = conteks.ga_keterangan;

                    $('#ga_akun_harta').val(conteks.ga_akun_harta).trigger('change.select2');
                    $('#ga_akun_akumulasi').val(conteks.ga_akun_akumulasi).trigger('change.select2');
                    $('#ga_akun_beban').val(conteks.ga_akun_beban).trigger('change.select2');

                    this.onUpdate = true;

                    $('#data-popup').ezPopup('close');
                },

                formReset: function(){

                    this.singleData.ga_id = '';
                    this.singleData.ga_nomor = '';
                    this.singleData.ga_nama = '';
                    this.singleData.ga_keterangan = '';

                    $('#ga_golongan').val(this.groupAset[0].id).trigger('change.select2');
                    $('#ga_akun_harta').val(this.akunHarta[0].id).trigger('change.select2');
                    $('#ga_akun_akumulasi').val(this.akunAkumulasi[0].id).trigger('change.select2');
                    $('#ga_akun_beban').val(this.akunBeban[0].id).trigger('change.select2');

                    this.golonganChange(this.groupAset[0].id);
                    this.stat = 'standby';
                    this.onAjaxLoading = false;
                    this.onUpdate = false;

                    $('#data-form').data('bootstrapValidator').resetForm();
                }
            }
        })

    </script>

@endsection