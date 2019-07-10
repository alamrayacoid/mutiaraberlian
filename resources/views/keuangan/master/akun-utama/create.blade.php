@extends('main')

@section('extra_style')
    
     <link rel="stylesheet" type="text/css" href="{{asset('modul_keuangan/css/style.css')}}">
     <link rel="stylesheet" type="text/css" href="{{asset('modul_keuangan/js/vendors/vue/components/datatable-v2/style.css')}}">
     <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/toast/dist/jquery.toast.min.css') }}">

     <style type="text/css">
        /* select 2 custom element */
            .select2-container .select2-selection--single{
                border-radius: 0px;
                border: 1px solid #ddd;
                height: 30px;
                font-weight: normal;
                color: #666;
                outline: none;
                font-size: 9pt;
            }

            .select2-dropdown{
                border: 1px solid #ddd;
                font-size: 9pt;
            }

        .hintText{
            font-style: italic;
            color: #0099CC;
            font-weight: 600;
            font-size: 9pt;
        }

        .hint{
            cursor: pointer;
        }

        #table-form-bottom tr td.left-border{
            border-left: 1px solid #555;
        }

        [disabled], [readonly]{
            cursor: no-drop;
        }
     </style>

@endsection

@section('content')
    <article class="content" id="vue-element">
        <div class="row" v-if="downloadingResource">
            <div class="col-md-12" style="font-style: italic; font-size: 9pt;">
                <center>Harap Tunggu. Sedang Memuat Halaman...</center>
            </div>

            <div class="col-md-12 text-center" style="margin-top: -10px;">
                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
            </div>
        </div>

        <template v-if="!downloadingResource && resourceError">
            <div class="row">
                <div class="col-md-12" style="font-style: italic; font-size: 9pt;">
                    <center>Ups. Resource Bermasalah. Coba Muat Ulang Halaman</center>
                </div>

                <div class="col-md-12 text-center">
                    <i class="fa fa-crown-o"></i>
                </div>
            </div>
        </template>

        <template v-if="!downloadingResource && !resourceError">
            <section class="section">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bordered p-2">
                                <div class="header-block">
                                    <h3 class="title"> Tambah Data COA Utama </h3>
                                </div>
                                <div class="header-block pull-right">
                                    <div class="loader" style="background: none; vertical-align: top;">
                                        <table width="100%">
                                            <tbody>
                                                <template v-if="onRequest">
                                                    <tr>
                                                        <td width="10%">
                                                            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                                        </td>

                                                        <td style="padding-bottom: 5px; font-size: 7pt; font-style: italic;">
                                                            &nbsp; &nbsp;  &nbsp;@{{ requestMessage }}
                                                        </td>
                                                    </tr>
                                                </template>

                                                <template v-if="!onRequest">
                                                    <tr>
                                                        <td style="padding-bottom: 5px; font-size: 7pt; font-style: italic;">
                                                            <i class="fa fa-thumbs-up" style="font-size: 9pt;"></i>
                                                            &nbsp; Isilah data dengan benar.
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card-block">
                                <form id="data-form" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" readonly>
                                    <input type="hidden" name="ak_id" v-model="single.ak_id" readonly>
                                    <section>
                                        <div class="row keuangan-form">
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4 label">Kode COA</div>
                                                        <div class="col-md-5">
                                                            <div class="input-group mb-3">
                                                              <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1">@{{ single.nomorView }}</span>
                                                              </div>

                                                              <input type="text" :class="$v.single.ak_nomor.$error ? 'form-control form-control-sm error' : 'form-control form-control-sm'" placeholder="Contoh: 001" @keypress="idRules" name="ak_nomor" id="ak_nomor" v-model="$v.single.ak_nomor.$model" :disabled="single.akunUtama">

                                                            </div>
                                                        </div>

                                                        <div class="col-md-1" style="background: none; padding: 0px; padding: 4px 0px 0px 0px">
                                                            <button type="button" class="btn btn-primary btn-sm" style="font-size: 8pt; border-radius: 2px;" @click="buttonHelpCicked" :disabled="disabledButton">
                                                                <template v-if="stateForm == 'insert'">
                                                                    <i class="fa fa-search"></i>
                                                                </template>

                                                                <template v-if="stateForm == 'update'">
                                                                    <i class="fa fa-close"></i>
                                                                </template>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4 label">Klasifikasi COA</div>
                                                        <div class="col-md-7">
                                                            <vue-select :name="'ak_klasifikasi'" :id="'ak_klasifikasi'" :options="ak_klasifikasi" :search="false" @option-change="hierarkiChange" :disabled="single.akunUtama"></vue-select>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 15px;">
                                                        <div class="col-md-4 label">Kelompok COA</div>
                                                        <div class="col-md-7">
                                                            <vue-select :name="'ak_kelompok'" :id="'ak_kelompok'" :options="ak_kelompok" :search="false" @option-change="kelompokChange" :disabled="single.akunUtama"></vue-select>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 15px;">
                                                        <div class="col-md-4 label">Nama COA</div>
                                                        <div class="col-md-7">
                                                            <input type="text" name="ak_nama" :class="$v.single.ak_nama.$error ? 'form-control form-control-sm error' : 'form-control form-control-sm'" placeholder="contoh: Kas Kecil Usaha" v-model="$v.single.ak_nama.$model" :disabled="single.akunUtama">
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <div class="row" style="margin-top: 15px;">
                                                        <div class="col-md-4 label">Posisi Debet/Kredit</div>
                                                        <div class="col-md-7">
                                                            <vue-select :name="'ak_posisi'" :id="'ak_posisi'" :options="ak_posisi" :search="false" :disabled="single.akunUtama"></vue-select>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 15px;">
                                                        <div class="col-md-4 label">COA Setara Kas ?</div>
                                                        <div class="col-md-5">
                                                            <vue-select :name="'ak_setara_kas'" :id="'ak_setara_kas'" :options="ak_setara_kas" :search="false" :disabled="single.akunUtama"></vue-select>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <div class="row" style="margin-top: -10px;">
                                                        <div class="col-md-12 label">  
                                                            <template v-if="stateForm == 'insert'">
                                                                <span style="font-size: 8pt; font-style: italic;">
                                                                    Membuat COA utama akan secara otomatis membuat COA untuk pusat dengan informasi yang sama dengan inputan diatas dan dengan saldo pembukaan 0;
                                                                </span>
                                                            </template>

                                                            <template v-if="stateForm == 'update'">
                                                                <span style="font-size: 8pt; font-style: italic;">
                                                                    Mengubah informasi COA utama akan mempengaruhi semua COA yang berhubungan dengan COA utama tersebut.
                                                                </span>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 button-wrapper">
                                                    <div class="row">
                                                        <div class="col-md-6 text-left">
                                                            <a href="{{ Route('keuangan.akun.index') }}">
                                                            <button type="button" class="btn btn-default btn-sm" style="color: rgba(82, 188, 211, 1); font-size: 10.5pt;">Kembali</button>
                                                            </a>
                                                        </div>

                                                        <div class="col-md-6 text-right">
                                                            <template v-if="stateForm == 'insert'">
                                                                <button class="btn btn-primary btn-sm" @click="save" :disabled="disabledButton">Simpan Data</button>
                                                            </template>

                                                            <template v-if="stateForm == 'update'">
                                                                <button class="btn btn-primary btn-sm" @click="update" :disabled="disabledButton">Simpan Perubahan</button>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-6" style="border: 0px solid #eee; margin-top: -10px">
                                                <div class="row" style="padding-right: 10px;">

                                                    <div class="col-md-12" style="padding: 0px; height: 272px; background: #eee;">
                                                        <table width="100%" class="keuangan table-mini">
                                                            <thead>
                                                                <tr>
                                                                    <th width="25%">Nomor COA</th>
                                                                    <th>Nama COA</th>
                                                                    <th width="25%">Posisi COA</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <tr v-if="!serializeAkun.length">
                                                                    <td colspan="3" class="text-center"><small>Belum Ada Akun Pada Kelompok Yang Dipilih</small></td>
                                                                </tr>

                                                                <template v-for="(akun, idx) in serializeAkun">
                                                                    <tr>
                                                                        <td style="text-align: center;">@{{ akun.ak_nomor }}</td>
                                                                        <td style="text-align: center;">@{{ akun.ak_nama }}</td>
                                                                        <td style="text-align: center;">@{{ (akun.ak_posisi == 'D') ? 'Debet' : 'Kredit' }}</td>
                                                                    </tr>
                                                                </template>
                                                            </tbody>
                                                        </table>

                                                    </div>

                                                    <div class="col-md-12" style="text-align: center; padding: 10px; font-size: 8pt; border-bottom: 1px solid #eee;">
                                                        Tabel diatas akan menampilkan semua COA pusat dan cabang yang sudah tersimpan sesuai dengan kelompok yang dipilih
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </template>

         @include('keuangan.master.akun-utama._partials._modal')
    </article>

@endsection

@section('extra_script')
    
    <!-- Vue Components -->
    <script src="{{asset('modul_keuangan/js/vendors/vue/vue.js')}}"></script>
    <script src="{{asset('modul_keuangan/js/vendors/vue/components/datatable-v2/datatable-v2.component.js')}}"></script>
    <script src="{{asset('modul_keuangan/js/vendors/vue/components/select/select.component.js')}}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/vue/components/inputmask/inputmask.component.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/vue/vuelidate/dist/vuelidate.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/vue/vuelidate/dist/validators.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/vue/components/datatable-v1/datatable.component.js') }}"></script>

    <!-- Jquery Plugin For Keuangan -->
    <script src="{{ asset('modul_keuangan/js/vendors/toast/dist/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/inputmask/inputmask.jquery.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/axios/axios.min.js') }}"></script>

    <script type="text/javascript">
        Vue.use(window.vuelidate.default)
        const { required, minLength } = window.validators;

        var vue = new Vue({
            el: '#vue-element',
            data: {
                stateForm: 'insert',
                onRequest: false,
                resourceError: false,
                downloadingResource: true,
                requestMessage: '',
                disabledButton: false,

                // addition

                ak_posisi: [
                    {
                        id      : "D",
                        text    : "Debet"
                    },

                    {
                        id      : "K",
                        text    : "Kredit"
                    }
                ],

                ak_setara_kas: [
                    {
                        id: '1',
                        text: 'Ya'
                    },

                    {
                        id: '0',
                        text: 'Tidak'
                    }
                ],

                data_table_akun: {
                    data: {
                        column: [
                            {name: 'Nomor COA', context: 'ak_nomor', width: '30%', childStyle: 'text-align: center;'},
                            {name: 'Nama COA', context: 'ak_nama', width: '45%', childStyle: 'text-align: center'},
                            {name: 'Posisi COA', context: 'ak_posisi', width: '25%', childStyle: 'text-align: center', override: function(e){
                                if(e == 'D')
                                    return "Debet";

                                return "Kredit"
                            }},
                        ],

                        source: []
                    },

                    option: {
                        selectable: true,
                        index_column: 'ak_id'
                    }
                },

                ak_klasifikasi: [],
                ak_kelompok: [],
                dataAkun : [],
                akunDetail: [],

                single: {
                    ak_id: '',
                    ak_nomor: '',
                    ak_nama: '',

                    nomorView: '-',
                    klasifikasiIdx: null,
                    kelompokIdx: null,
                    akunUtama: false,
                }
            },

            validations: {
                single : {
                    ak_nomor: {
                        required,
                    },

                    ak_nama: {
                        required
                    }
                }
            },

            computed: {
                serializeAkun: function(e){
                    var kelompok = this.ak_kelompok[this.single.kelompokIdx];

                    if(kelompok)
                        return $.grep(this.akunDetail, function(e){ return e.ak_kelompok == kelompok.id });
                    else
                        return [];
                }
            },

            mounted: function(){
                console.log('vue mounted');
                axios.get("{{ Route('keuangan.akun_utama.resource') }}")
                        .then((response) => {
                            this.downloadingResource = false;

                            this.ak_klasifikasi = response.data.hierarki;
                            this.akunDetail = response.data.akunDetail;
                            this.dataAkun = response.data.akun;
                            this.data_table_akun.data.source = response.data.akun;

                            if(response.data.hierarki.length){
                                this.hierarkiChange(this.ak_klasifikasi[0].id);
                            }

                        }).catch((e) => {
                            this.downloadingResource = false;
                            this.resourceError = true;
                            console.log('System Bermasalah '+e);
                        })
            },

            methods: {

                save: function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    this.$v.$touch();

                    if(!this.$v.$invalid){
                        this.onRequest = true;
                        this.requestMessage = 'Sedang menyimpan data..';
                        this.disabledButton = true;
                        dataForm = $('#data-form').serialize();

                        axios.post('{{ Route("keuangan.akun_utama.save") }}', dataForm)
                                .then((response) => {
                                    console.log(response.data.hierarki);
                                    if(response.data.status == 'success'){
                                        $.toast({
                                            text: response.data.text,
                                            showHideTransition: 'slide',
                                            icon: response.data.status,
                                            stack: 1
                                        });

                                        this.dataAkun = response.data.akun;
                                        this.data_table_akun.data.source = response.data.akun;
                                        this.akunDetail = response.data.akunDetail;

                                        if(response.data.akun.length){
                                            this.kelompokChange($('#ak_kelompok').val());
                                        }

                                        this.resetForm();

                                    }else{
                                        $.toast({
                                            text: response.data.text,
                                            showHideTransition: 'slide',
                                            icon: response.data.status,
                                            stack: 1
                                        })
                                    }

                                }).catch((e) => {
                                    console.log(e);
                                    $.toast({
                                        text: 'System Error, '+e,
                                        showHideTransition: 'slide',
                                        icon: 'error',
                                        stack: 1
                                    })
                                }).then((e) => {
                                    this.onRequest = false;
                                    this.requestMessage = 'Sedang menyimpan data..';
                                    this.disabledButton = false;
                                })

                    }else{
                        $.toast({
                            text: 'Ada yang salah dengan inputan anda. Harap cek kembali.',
                            showHideTransition: 'slide',
                            icon: 'warning',
                            stack: 1
                        })
                    }
                },

                update: function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    this.$v.$touch();

                    if(!this.$v.$invalid){
                        this.onRequest = true;
                        this.requestMessage = 'Sedang memperbarui data..';
                        this.disabledButton = true;
                        dataForm = $('#data-form').serialize();

                        axios.post('{{ Route("keuangan.akun_utama.update") }}', dataForm)
                                .then((response) => {
                                    console.log(response.data.hierarki);
                                    if(response.data.status == 'success'){
                                        $.toast({
                                            text: response.data.text,
                                            showHideTransition: 'slide',
                                            icon: response.data.status,
                                            stack: 1
                                        });

                                        this.dataAkun = response.data.akun;
                                        this.data_table_akun.data.source = response.data.akun;
                                        this.akunDetail = response.data.akunDetail;

                                        if(response.data.akun.length){
                                            this.kelompokChange($('#ak_kelompok').val());
                                        }

                                        this.resetForm();

                                    }else{
                                        $.toast({
                                            text: response.data.text,
                                            showHideTransition: 'slide',
                                            icon: response.data.status,
                                            stack: 1
                                        })
                                    }

                                }).catch((e) => {
                                    console.log(e);
                                    $.toast({
                                        text: 'System Error, '+e,
                                        showHideTransition: 'slide',
                                        icon: 'error',
                                        stack: 1
                                    })
                                }).then((e) => {
                                    this.onRequest = false;
                                    this.requestMessage = 'Sedang menyimpan data..';
                                    this.disabledButton = false;
                                })

                    }else{
                        $.toast({
                            text: 'Ada yang salah dengan inputan anda. Harap cek kembali.',
                            showHideTransition: 'slide',
                            icon: 'warning',
                            stack: 1
                        })
                    }
                },

                idRules: function(evt){
                    evt.stopImmediatePropagation();

                    if(evt.key == '.')
                        return true;

                    if(isNaN(evt.key))
                      evt.preventDefault()
                    else
                      return true;
                },

                hierarkiChange: function(e){
                    var index = this.ak_klasifikasi.findIndex(a => a.id == e);
                    var conteks = this.ak_klasifikasi[index];

                    this.ak_kelompok = conteks.level2;
                    this.single.klasifikasiIdx = index;

                    setTimeout(function(){
                        $('#ak_klasifikasi').val(e).trigger('change.select2');
                    }, 0)

                    if(this.ak_kelompok.length)
                        this.kelompokChange(this.ak_kelompok[0].id);
                    else
                        this.single.nomorView = '-';
                },

                kelompokChange: function(e){
                    var index = this.ak_kelompok.findIndex(a => a.id == e);
                    var conteks = this.ak_kelompok[index];

                    this.single.kelompokIdx = index;

                    setTimeout(function(){
                        $('#ak_kelompok').val(e).trigger('change.select2');
                    }, 0)

                    this.single.nomorView = conteks.hd_nomor+'.';
                },

                buttonHelpCicked: function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    if(this.stateForm == 'insert')
                        this.dataModal(e);
                    else
                        this.resetForm();
                },

                dataModal: function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    $('#modal_tambah').modal('show');
                },

                dataSelected: function(e){
                    var idx = this.dataAkun.findIndex(a => a.ak_id == e);
                    var conteks = this.dataAkun[idx];

                    this.single.ak_id       = conteks.ak_id;
                    this.single.ak_nomor    = conteks.ak_sub_id;
                    this.single.ak_nama     = conteks.ak_nama;
                    this.single.akunUtama   = (conteks.ak_akun_utama) ? true : false;
                    this.stateForm = 'update';

                    $('#ak_setara_kas').val(conteks.ak_setara_kas).trigger('change.select2');
                    $('#ak_saldo').val(conteks.ak_opening);

                    var spliter = conteks.ak_nomor.split('.');

                    this.hierarkiChange(spliter[0]);
                    this.kelompokChange(conteks.ak_kelompok);

                    $('#modal_tambah').modal('toggle');
                },

                validStatus: function(validation){
                    return{
                        error: validation.$error,
                        dirty: validation.$dirty
                    }
                },

                resetForm: function(){
                    this.single.ak_nomor = '';
                    this.single.ak_nama = '';
                    this.single.ak_id = '';

                    this.single.setara_kas = false;
                    this.single.utama = false;
                    this.single.akunUtama = false;
                    this.stateForm = 'insert';

                    $('#ak_saldo').val(0);

                    $('#ak_posisi').val('D').trigger('change.select2');
                    $('#setara_kas').val('Y').trigger('change.select2');

                }
            }
        });

    </script>
@endsection
