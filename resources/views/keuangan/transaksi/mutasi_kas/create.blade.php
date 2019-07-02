@extends('main')

@section('extra_style')
    
     <link rel="stylesheet" type="text/css" href="{{asset('modul_keuangan/css/style.css')}}">
     <link rel="stylesheet" type="text/css" href="{{asset('modul_keuangan/js/vendors/vue/components/datatable-v2/style.css')}}">
     <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/toast/dist/jquery.toast.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/datepicker/dist/datepicker.min.css') }}">

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
                                    <h3 class="title"> Tambah Data COA Keuangan </h3>
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
                                        <div class="row keuangan-form" style="border-bottom: 1px solid #ddd; padding-bottom: 30px;">
                                            <div class="col-md-6" style="border-right: 1px solid #ddd;">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4 label">Nomor Mutasi Kas</div>
                                                        <div class="col-md-5">
                                                            <div class="input-group mb-3">
                                                              <input type="text" :class="$v.single.tr_nomor.$error ? 'form-control form-control-sm error' : 'form-control form-control-sm'" placeholder="Diisi oleh system." name="tr_nomor" id="tr_nomor" v-model="$v.single.tr_nomor.$model" readonly>

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
                                                        <div class="col-md-4 label">Jenis Mutasi Kas</div>
                                                        <div class="col-md-7">
                                                            <vue-select :name="'tr_jenis'" :id="'tr_jenis'" :options="tr_jenis" :search="false" @option-change="jenisChange"></vue-select>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 15px;">
                                                        <div class="col-md-4 label">Tanggal Mutasi</div>
                                                        <div class="col-md-7">
                                                            <vue-datepicker :name="'tr_tanggal'" :id="'tr_tanggal'" :class="'form-control'" :placeholder="'Pilih Tanggal Mutasi'" :title="'Tidak Boleh Kosong'"></vue-datepicker>
                                                        </div>
                                                    </div>

                                                    <!-- <hr> -->

                                                    <div class="row" style="margin-top: -10px;">
                                                        <div class="col-md-12 label">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6" style="border: 0px solid #eee; margin-top: -10px;">
                                                <div class="row" style="margin-top: 15px;">
                                                    <div class="col-md-4 label">Keterangan Mutasi</div>
                                                    <div class="col-md-7">
                                                        <input type="text" name="tr_keterangan" :class="$v.single.tr_keterangan.$error ? 'form-control form-control-sm error' : 'form-control form-control-sm'" placeholder="contoh: Transfer bank ke akun kas" v-model="$v.single.tr_keterangan.$model" :disabled="single.akunUtama">
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top: 15px;">
                                                    <div class="col-md-4 label">Pilih COA Kas</div>
                                                    <div class="col-md-7">
                                                        <vue-select :name="'tr_akun_kas'" :id="'tr_akun_kas'" :options="tr_akun_kas" :search="false" @option-change="coaChange"></vue-select>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top: 15px;">
                                                    <div class="col-md-4 label">Nominal Mutasi</div>
                                                    <div class="col-md-7">
                                                        <vue-inputmask :name="'tr_nominal'" :id="'tr_nominal'" :style="'background: white;'" :minus="false" @input="nominalChange"></vue-inputmask>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row keuangan-form" style="padding-top: 10px;">
                                            <div class="col-md-12" style="padding: 0px 20px;">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <i class="fa fa-arrow-right"></i>
                                                        <span class="hintText">&nbsp; Detail COA keuangan dalam jurnal</span>
                                                        <small> &nbsp;- anda bisa menambahkan COA lebih dari satu. (pastikan debet kredit sama)</small>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-sm btn-success" style="color: white; font-size: 8.5pt; padding-top: 5px;">
                                                            <i class="fa fa-plus"></i> &nbsp;
                                                            Tambah Detail COA
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12" style="margin-top: 20px; padding: 0px 40px;">
                                                <table class="keuangan table-mini" width="100%" style="font-size: 9pt;">
                                                    <thead>
                                                        <tr>
                                                            <th style="background: #eee;" width="5%">***</th>
                                                            <th style="background: #eee;" width="30%">Nama COA</th>
                                                            <th style="background: #eee;" width="35%">Keterangan</th>
                                                            <th width="15%" style="background: #eee;">Debet</th>
                                                            <th width="15%" style="background: #eee;">Kredit</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr v-for="(detail, idx) in tr_akun_detail">
                                                            <td class="text-center">
                                                                <template v-if="detail.dt_status == 'locked'">
                                                                    <i class="fa fa-lock"></i>
                                                                </template>

                                                                <template v-if="detail.dt_status != 'locked'">
                                                                    <i class="fa fa-trash hintText" style="font-style: normal;"></i>
                                                                </template>
                                                            </td>

                                                            <td style="padding-left: 10px;">
                                                                <template v-if="idx == 0">
                                                                    @{{ detail.dt_text }}
                                                                </template>

                                                                <template v-if="idx > 0">
                                                                    <vue-select :id="'dt_akun'" :options="tr_akun_lawan" :search="false" :styles="'border: 0px;'"></vue-select>
                                                                </template>
                                                            </td>

                                                            <td>
                                                                <input type="text" v-model="detail.dt_keterangan" style="font-size: 9pt; height: 20px; border: 0px; width: 100%; text-align: left; color: #666; padding-left: 5px;">
                                                            </td>

                                                            <td>
                                                                <vue-inputmask :name="'debet'" :id="'debet'" :style="'background: white;'" :minus="false" :readonly="true" :css="'border: 0px; height: 20px; font-size: 9pt; width: 100%;'" v-model="detail.dt_debet"></vue-inputmask>
                                                            </td>

                                                            <td>
                                                                <vue-inputmask :name="'debet'" :id="'debet'" :style="'background: white;'" :minus="false" :readonly="true" :css="'border: 0px; height: 20px; font-size: 9pt; width: 100%;'" v-model="detail.dt_kredit"></vue-inputmask>
                                                            </td>
                                                        </tr>
                                                    </tbody>

                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3" style="padding-top: 10px;">Total Debet/Kredit</th>
                                                            <th style="padding-top: 10px;">
                                                                <vue-inputmask :name="'debet'" :id="'debet'" :style="'background: white;'" :minus="false" :readonly="true" :css="'border: 0px; height: 20px; font-size: 9pt; width: 100%; font-weight:600'" v-model="single.totDebet"></vue-inputmask>
                                                            </th>
                                                            <th style="padding-top: 10px;">
                                                                <vue-inputmask :name="'debet'" :id="'debet'" :style="'background: white;'" :minus="false" :readonly="true" :css="'border: 0px; height: 20px; font-size: 9pt; width: 100%; font-weight:600'" v-model="single.totKredit"></vue-inputmask>
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 30px; border-top: 1px solid #ccc; padding: 20px 10px;">
                                            <div class="col-md-12 button-wrapper">
                                                <div class="row">
                                                    <div class="col-md-6 text-left">
                                                        <!-- <a href="{{ Route('keuangan.akun.index') }}">
                                                        <button type="button" class="btn btn-default btn-sm" style="color: rgba(82, 188, 211, 1); font-size: 10.5pt;">Kembali Ke Halaman COA</button>
                                                        </a> -->
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
                                    </section>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </template>

         @include('keuangan.transaksi.mutasi_kas._partials._modal')
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
    <script src="{{ asset('modul_keuangan/js/vendors/vue/components/datepicker/datepicker.component.js') }}"></script>

    <!-- Jquery Plugin For Keuangan -->
    <script src="{{ asset('modul_keuangan/js/vendors/toast/dist/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/inputmask/inputmask.jquery.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/axios/axios.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/datepicker/dist/datepicker.min.js') }}"></script>

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
                tr_akun_kas: [
                    {
                        id: '1',
                        text: '1.000.001 - Kas Kecil'
                    },

                    {
                        id: '2',
                        text: '1.000.002 - Kas Besar'
                    }
                ],

                tr_jenis: [
                    {
                        id: 'D',
                        text: 'Mutasi Masuk Antar Kas'
                    },

                    {
                        id: 'K',
                        text: 'Mutasi Keluar Antar Kas'
                    },
                ],

                tr_akun_detail: [
                    {
                        dt_text: '1.000.001 - Kas Kecil Ku',
                        dt_keterangan: 'Kas Kecil Keluar',
                        dt_posisi: 'D',
                        dt_debet: 0,
                        dt_kredit: 0,
                        dt_status: 'locked'
                    },

                    {
                        dt_text: '1.0001.001 - Bank Mandiri KCP Lombok',
                        dt_keterangan: 'Kas Bank Masuk',
                        dt_posisi: 'K',
                        dt_kredit: 0,
                        dt_debet: 0,
                        dt_status: 'locked',
                    }
                ],

                tr_akun_lawan: [],

                single: {
                    totDebet: 0,
                    totKredit: 0,

                    coaFirst: '',
                    jenisMutasi: '',
                }
            },

            validations: {
                single : {
                    tr_nomor: {
                        required,
                    },

                    tr_keterangan: {
                        required,
                    },
                }
            },

            watch: {
                tr_akun_detail: {
                    handler: function(e) {
                        var debet = kredit = 0;

                        $.each(e, function(index, alpha){
                            debet += alpha.dt_debet;
                            kredit += alpha.dt_kredit;
                        });

                        this.single.totDebet = debet;
                        this.single.totKredit = kredit;
                    },
                    deep: true
                }
            },

            mounted: function(){
                console.log('vue mounted');
                axios.get("{{ Route('keuangan.akun.resource') }}")
                        .then((response) => {
                            this.downloadingResource = false;

                            this.coaChange(this.tr_akun_kas[0].id);
                            this.single.jenisMutasi = 'D';
                            this.jenisChange(this.tr_jenis[0].id);

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

                        axios.post('{{ Route("keuangan.akun.save") }}', dataForm)
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

                        axios.post('{{ Route("keuangan.akun.update") }}', dataForm)
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

                coaChange: function(e){
                    var idx = this.tr_akun_kas.findIndex(a => a.id == e);
                    var conteks = this.tr_akun_kas[idx];

                    this.tr_akun_detail[0].dt_text = conteks.text;
                    this.tr_akun_lawan = $.grep(this.tr_akun_kas, function(a){ return a.id != e });
                },

                nominalChange: function(e){
                    var nominal = (e.val) ? parseFloat(e.val.replace(/\,/g, '')) : 0;

                    if(this.single.jenisMutasi == "D"){
                        this.tr_akun_detail[0].dt_debet = nominal;
                        this.tr_akun_detail[1].dt_kredit = nominal;
                    }else{
                        this.tr_akun_detail[0].dt_kredit = nominal;
                        this.tr_akun_detail[1].dt_debet = nominal;
                    }
                },

                jenisChange: function(e){
                    if(e == "D"){
                        this.tr_akun_detail[0].dt_debet = this.tr_akun_detail[0].dt_kredit;
                        this.tr_akun_detail[0].dt_kredit = 0;
                        this.tr_akun_detail[0].dt_posisi = 'D';

                        this.tr_akun_detail[1].dt_kredit = this.tr_akun_detail[0].dt_debet;
                        this.tr_akun_detail[1].dt_debet = 0;
                        this.tr_akun_detail[1].dt_posisi = 'K';

                        this.single.jenisMutasi = 'D';
                    }else{
                        this.tr_akun_detail[0].dt_kredit = this.tr_akun_detail[0].dt_debet;
                        this.tr_akun_detail[0].dt_debet = 0;
                        this.tr_akun_detail[0].dt_posisi = 'K';

                        this.tr_akun_detail[1].dt_debet = this.tr_akun_detail[0].dt_kredit;
                        this.tr_akun_detail[1].dt_kredit = 0;
                        this.tr_akun_detail[1].dt_posisi = 'D';

                        this.single.jenisMutasi = 'K';
                    }
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
                    
                },

                validStatus: function(validation){
                    return{
                        error: validation.$error,
                        dirty: validation.$dirty
                    }
                },

                resetForm: function(){

                }
            }
        });

    </script>
@endsection
