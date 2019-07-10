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
                                    <h3 class="title"> Transaksi Memorial </h3>
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
                                    <input type="hidden" name="tr_id" v-model="single.tr_id" readonly>
                                    <section>
                                        <div class="row keuangan-form" style="border-bottom: 1px solid #ddd; padding-bottom: 30px;">
                                            <div class="col-md-6" style="border-right: 1px solid #ddd;">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4 label">Nomor Transaksi</div>
                                                        <div class="col-md-5">
                                                            <div class="input-group mb-3">
                                                              <input type="text" class="form-control form-control-sm" placeholder="Diisi oleh system." name="tr_nomor" id="tr_nomor" v-model="single.tr_nomor" readonly>

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
                                                        <div class="col-md-4 label">Jenis Transaksi</div>
                                                        <div class="col-md-7">
                                                            <vue-select :name="'tr_jenis'" :id="'tr_jenis'" :options="tr_jenis" :search="false" @option-change="jenisChange" v-model="single.tr_jenis"></vue-select>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 15px;">
                                                        <div class="col-md-4 label">Tanggal Transaksi</div>
                                                        <div class="col-md-7">
                                                            <vue-datepicker :name="'tr_tanggal'" :id="'tr_tanggal'" :class="'form-control'" :placeholder="'Pilih Tanggal Transaksi'" :title="'Tidak Boleh Kosong'" :readonly="true" v-model="single.tr_tanggal" :disabled="stateForm == 'update'"></vue-datepicker>
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
                                                    <div class="col-md-4 label">Keterangan Transaksi</div>
                                                    <div class="col-md-7">
                                                        <div class="input-group mb-3">
                                                          <input type="text" name="tr_keterangan" :class="$v.single.tr_keterangan.$error ? 'form-control form-control-sm error' : 'form-control form-control-sm'" placeholder="contoh: Transfer bank ke akun kas" v-model="$v.single.tr_keterangan.$model" :disabled="single.akunUtama">

                                                          <div class="input-group-append">
                                                            <span class="input-group-text hint" id="basic-addon2" style="min-width: 20px;" @click="keteranganModal">
                                                                <i class="fa fa-search"></i>
                                                            </span>
                                                          </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top: 0px;">
                                                    <div class="col-md-4 label">Pilih COA Utama</div>
                                                    <div class="col-md-7">
                                                        <vue-select :name="'tr_akun_kas'" :id="'tr_akun_kas'" :options="tr_akun_kas" :search="false" @option-change="coaChange" v-model="single.tr_akun_kas"></vue-select>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top: 15px;">
                                                    <div class="col-md-4 label">Nominal Transaksi</div>
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
                                                        <button type="button" class="btn btn-sm btn-success" style="color: white; font-size: 8.5pt; padding-top: 5px;" @click="addAddition">
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
                                                                <template v-if="idx <= 1">
                                                                    <i class="fa fa-lock"></i>
                                                                </template>

                                                                <template v-if="idx > 1">
                                                                    <i class="fa fa-trash hintText hint" style="font-style: normal;" @click="deleteAddition($event, idx)"></i>
                                                                </template>
                                                            </td>

                                                            <td style="padding-left: 10px;">
                                                                <template v-if="idx == 0">
                                                                    @{{ detail.dt_akun_nama }}
                                                                </template>

                                                                <template v-if="idx > 0">
                                                                    <vue-select :classes="'akun_detail'" :id="'dt_akun_'+idx" :name="'dt_akun[]'" :options="tr_akun_kas" :search="false" :styles="'border: 0px;'" v-model="detail.dt_akun"></vue-select>
                                                                </template>
                                                            </td>

                                                            <td>
                                                                <input type="text" name="dt_keterangan[]" v-model="detail.dt_keterangan" style="font-size: 9pt; height: 20px; border: 0px; width: 100%; text-align: left; color: #666; padding-left: 5px;" placeholder="Berikan keterangan tentang COA ini">
                                                            </td>

                                                            <td>
                                                                <vue-inputmask :name="'dt_debet[]'" :classes="'debet'" :id="'debet'" :style="'background: white;'" :minus="false" :readonly="detail.dt_status=='locked'" :css="'border: 0px; height: 20px; font-size: 9pt; width: 100%;'" v-model="detail.dt_debet" @input="cekAdditionDebet($event, idx)"></vue-inputmask>
                                                            </td>

                                                            <td>
                                                                <vue-inputmask :name="'dt_kredit[]'" :classes="'kredit'" :id="'debet'" :style="'background: white;'" :minus="false" :readonly="detail.dt_status=='locked'" :css="'border: 0px; height: 20px; font-size: 9pt; width: 100%;'" v-model="detail.dt_kredit" @input="cekAdditionKredit($event, idx)"></vue-inputmask>
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

                                                            <button type="button" class="btn btn-primary btn-sm" @click="resetForm" :disabled="disabledButton">Reset</button>

                                                        </template>

                                                        <template v-if="stateForm == 'update'">
                                                            <button class="btn btn-primary btn-sm" @click="update" :disabled="disabledButton">Simpan Perubahan</button>

                                                            <button type="button" class="btn btn-danger btn-sm" @click="deleted" :disabled="disabledButton">Hapus Transaksi</button>
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

         @include('keuangan.transaksi.transaksi_memorial._partials._modal')
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
                tr_akun_kas: [],

                tr_jenis: [
                    {
                        id: 'D',
                        text: 'Transaksi Memorial Debet'
                    },

                    {
                        id: 'K',
                        text: 'Transaksi Memorial Kredit'
                    },
                ],

                tr_akun_detail: [
                    {
                        dt_akun_nama: '',
                        dt_keterangan: '',
                        dt_kredit: 0,
                        dt_debet: 0,
                        dt_status: 'locked',
                        dt_akun: '',
                        dt_deleted: false,
                    },

                    {
                        dt_akun_nama: '',
                        dt_keterangan: '',
                        dt_kredit: 0,
                        dt_debet: 0,
                        dt_status: 'open',
                        dt_akun: '',
                        dt_deleted: false,
                    }
                ],

                data_table_transaksi: {
                    data: {
                        column: [
                            {name: 'Nomor Transaksi', context: 'tr_nomor', width: '30%', childStyle: 'text-align: center;'},
                            {name: 'Keterangan', context: 'tr_keterangan', width: '45%', childStyle: 'text-align: center'},
                            {name: 'Tanggal', context: 'tr_tanggal_trans', width: '25%', childStyle: 'text-align: center'},
                        ],

                        source: []
                    },

                    option: {
                        selectable: true,
                        index_column: 'tr_id'
                    }
                },

                data_table_keterangan: {
                    data: {
                        column: [
                            {name: 'Keterangan', context: 'tr_keterangan', width: '30%', childStyle: 'text-align: left;'}
                        ],

                        source: []
                    },

                    option: {
                        selectable: true,
                        index_column: 'tr_keterangan'
                    }
                },

                tr_akun_lawan: [],
                addition: [],

                single: {
                    tr_id: '',
                    tr_nomor: '',
                    tr_akun_kas: '',
                    tr_jenis : 'D',
                    tr_keterangan: '',
                    tr_tanggal: '',

                    totDebet: 0,
                    totKredit: 0,

                    coaFirst: '',
                    jenisMutasi: '',
                }
            },

            validations: {
                single : {
                    tr_keterangan: {
                        required,
                    },
                }
            },

            watch: {

            },

            mounted: function(){
                console.log('vue mounted');

                axios.get("{{ Route('keuangan.transaksi_memorial.resource') }}")
                        .then((response) => {
                            this.downloadingResource = false;

                            this.single.tr_tanggal = '{{ date("d/m/Y") }}';
                            this.tr_akun_kas = response.data.akun;
                            this.single.jenisMutasi = 'D';
                            this.data_table_transaksi.data.source = response.data.transaksi;
                            this.data_table_keterangan.data.source = response.data.keterangan;

                            if(response.data.akun.length){
                                this.coaChange(this.tr_akun_kas[0].id);
                                this.tr_akun_detail[1].dt_akun = this.tr_akun_kas[0].id;
                            }else{
                                $('#modal_err').modal('show');
                            }

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

                    if($('#tr_tanggal').val() == ''){
                        $.toast({
                            text: 'Tanggal mutasi tidak boleh kosong.',
                            showHideTransition: 'slide',
                            icon: 'warning',
                            stack: 1
                        })

                        return false;
                    }else if(this.totDebet <= 0 || this.totKredit <= 0){
                        $.toast({
                            text: 'Total debet/kredit harus lebih besar dari 0.',
                            showHideTransition: 'slide',
                            icon: 'warning',
                            stack: 1
                        })

                        return false;
                    }else if(this.single.totDebet != this.single.totKredit){
                        $.toast({
                            text: 'Total debet/kredit harus sama.',
                            showHideTransition: 'slide',
                            icon: 'warning',
                            stack: 1
                        })

                        return false;
                    }

                    this.$v.$touch();
                    if(!this.$v.$invalid){

                        if(this.checkSame()){
                            var cfrm = confirm("Beberapa COA detail sama dengan COA detail lain, Apakah anda tetap ingin menyimpannya ? ")

                            if(!cfrm){
                                return false;
                            }
                        }

                        this.onRequest = true;
                        this.requestMessage = 'Sedang menyimpan data..';
                        this.disabledButton = true;
                        dataForm = $('#data-form').serialize();

                        axios.post('{{ Route("keuangan.transaksi_memorial.save") }}', dataForm)
                                .then((response) => {
                                    console.log(response.data);
                                    if(response.data.status == 'success'){
                                        $.toast({
                                            text: response.data.text,
                                            showHideTransition: 'slide',
                                            icon: response.data.status,
                                            stack: 1
                                        });

                                        this.data_table_transaksi.data.source = response.data.transaksi;
                                        this.data_table_keterangan.data.source = response.data.keterangan;

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

                    if($('#tr_tanggal').val() == ''){
                        $.toast({
                            text: 'Tanggal mutasi tidak boleh kosong.',
                            showHideTransition: 'slide',
                            icon: 'warning',
                            stack: 1
                        })

                        return false;
                    }else if(this.totDebet <= 0 || this.totKredit <= 0){
                        $.toast({
                            text: 'Total debet/kredit harus lebih besar dari 0.',
                            showHideTransition: 'slide',
                            icon: 'warning',
                            stack: 1
                        })

                        return false;
                    }else if(this.single.totDebet != this.single.totKredit){
                        $.toast({
                            text: 'Total debet/kredit harus sama.',
                            showHideTransition: 'slide',
                            icon: 'warning',
                            stack: 1
                        })

                        return false;
                    }

                    this.$v.$touch();

                    if(!this.$v.$invalid){

                        if(this.checkSame()){
                            var cfrm = confirm("Beberapa COA detail sama dengan COA detail lain, Apakah anda tetap ingin menyimpannya ? ")

                            if(!cfrm){
                                return false;
                            }
                        }

                        this.onRequest = true;
                        this.requestMessage = 'Sedang memperbarui data..';
                        this.disabledButton = true;
                        dataForm = $('#data-form').serialize();

                        axios.post('{{ Route("keuangan.transaksi_memorial.update") }}', dataForm)
                                .then((response) => {
                                    console.log(response.data);
                                    if(response.data.status == 'success'){
                                        $.toast({
                                            text: response.data.text,
                                            showHideTransition: 'slide',
                                            icon: response.data.status,
                                            stack: 1
                                        });

                                        this.data_table_transaksi.data.source = response.data.transaksi;
                                        this.data_table_keterangan.data.source = response.data.keterangan;

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

                deleted: function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    var cfrm = confirm('Apa anda yakin ?')

                    if(cfrm){
                        axios.post('{{ Route("keuangan.transaksi_memorial.delete") }}', {_token: '{{ csrf_token() }}', tr_id: this.single.tr_id})
                                .then((response) => {
                                    console.log(response.data);
                                    if(response.data.status == 'success'){
                                        $.toast({
                                            text: response.data.text,
                                            showHideTransition: 'slide',
                                            icon: response.data.status,
                                            stack: 1
                                        });

                                        this.data_table_transaksi.data.source = response.data.transaksi;
                                        this.data_table_keterangan.data.source = response.data.keterangan;

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
                    }
                },

                coaChange: function(e){
                    var idx = this.tr_akun_kas.findIndex(a => a.id == e);
                    var conteks = this.tr_akun_kas[idx];
                    this.single.tr_akun_kas = conteks.id;

                    this.tr_akun_detail[0].dt_akun_nama = conteks.text;
                    this.tr_akun_detail[0].dt_akun = conteks.id;
                },

                nominalChange: function(e){
                    var nominal = (e) ? parseFloat(e.replace(/\,/g, '')) : 0;

                    if(this.single.jenisMutasi == 'D'){
                        this.tr_akun_detail[0].dt_debet = nominal;
                    }else{
                        this.tr_akun_detail[0].dt_kredit = nominal;
                    }

                    this.calculatedTotal();
                },

                jenisChange: function(e){
                    if(e == 'D'){
                        this.tr_akun_detail[0].dt_debet = this.tr_akun_detail[0].dt_kredit;
                        this.tr_akun_detail[0].dt_kredit = 0;
                        this.single.jenisMutasi = 'D';                        
                    }else{
                        this.tr_akun_detail[0].dt_kredit = this.tr_akun_detail[0].dt_debet;
                        this.tr_akun_detail[0].dt_debet = 0;
                        this.single.jenisMutasi = 'K';
                    }

                    this.calculatedTotal();
                },

                addAddition: function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    var that = this;
                    var dbFeed = [];

                    that.tr_akun_detail.push(
                        {
                            dt_akun_nama: that.tr_akun_kas[0].text,
                            dt_keterangan: '',
                            dt_kredit: 0,
                            dt_debet: 0,
                            dt_status: 'open',
                            dt_akun: that.tr_akun_kas[0].id,
                            dt_deleted: false,
                        }
                    );
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

                keteranganModal: function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    $('#modal_keterangan').modal('show');
                },

                dataSelected: function(e){
                    var idx = this.data_table_transaksi.data.source.findIndex(a => a.tr_id == e);
                    var conteks = this.data_table_transaksi.data.source[idx];
                    var that = this;

                    if(idx >= 0){
                        this.stateForm = 'update';
                        this.single.tr_id = conteks.tr_id;
                        this.single.tr_nomor = conteks.tr_nomor;
                        this.single.tr_keterangan = conteks.tr_keterangan;
                        this.single.jenisMutasi = conteks.detail[0].trdt_dk;
                        this.single.tr_tanggal = this.humanizeDate(conteks.tr_tanggal_trans);

                        $('#tr_jenis').val(conteks.detail[0].trdt_dk).trigger('change.select2');
                        $('#tr_nominal').val(conteks.detail[0].trdt_value);

                        this.tr_akun_detail = []; var feeder = [];

                        $.each(conteks.detail, function(index, data){
                            feeder.push(
                                {
                                    dt_akun_nama: data.akun_nama,
                                    dt_keterangan: data.trdt_keterangan,
                                    dt_kredit: (data.trdt_dk == 'K') ? data.trdt_value : 0,
                                    dt_debet: (data.trdt_dk == 'D') ? data.trdt_value : 0,
                                    dt_status: (index == 0) ? 'locked' : 'open',
                                    dt_akun: data.trdt_akun,
                                    dt_deleted: false,
                                }
                            );
                        });

                        setTimeout(function(){
                            that.tr_akun_detail = feeder;
                            $('#dt_akun_1').val(conteks.detail[1].trdt_akun).trigger('change.select2');
                            that.calculatedTotal();
                        }, 0);
                    }

                    $('#modal_tambah').modal('toggle');
                },

                keteranganSelected: function(e){
                    this.single.tr_keterangan = e;
                    $('#modal_keterangan').modal('toggle');
                },

                validStatus: function(validation){
                    return{
                        error: validation.$error,
                        dirty: validation.$dirty
                    }
                },

                cekAdditionDebet: function(val, idx){
                    var nominal = (val) ? parseFloat(val.replace(/\,/g, '')) : 0;
                    this.tr_akun_detail[idx].dt_debet = nominal;
                    this.tr_akun_detail[idx].dt_kredit = 0;

                    this.calculatedTotal();
                },

                cekAdditionKredit: function(val, idx){
                    var nominal = (val) ? parseFloat(val.replace(/\,/g, '')) : 0;
                    this.tr_akun_detail[idx].dt_kredit = nominal;
                    this.tr_akun_detail[idx].dt_debet = 0;

                    this.calculatedTotal();
                },

                deleteAddition: function(e, index){
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    var conteks = $(e.target).closest('tr').remove();

                    this.tr_akun_detail[index].dt_deleted = true;
                },

                calculatedTotal: function(e){
                    var debet = kredit = 0;

                    $.each(this.tr_akun_detail, function(index, conteks){
                        if(!conteks.dt_deleted){
                            if(conteks.dt_debet != 0)
                                debet += parseFloat(conteks.dt_debet);
                            else
                                kredit += parseFloat(conteks.dt_kredit);
                        }
                    })

                    this.single.totDebet = debet;
                    this.single.totKredit = kredit;
                },

                humanizeDate: function(conteks){
                    var data = conteks.split('-')[2]+'/'+conteks.split('-')[1]+'/'+conteks.split('-')[0];

                    return data;
                },

                checkSame: function(e){
                    var dictionary = [this.tr_akun_detail[0].dt_akun]; var ret = false;
                    $('.akun_detail').each(function(e){
                        var ctx = $(this);
                        var cek = $.grep(dictionary, function(e){ return e == ctx.val() }) 

                        if(cek.length){
                            ret = true;
                            return;
                        }

                        dictionary.push(ctx.val());
                    });

                    return ret;
                },

                resetForm: function(){
                    var that = this;
                    this.stateForm = 'insert';

                    this.single.tr_id = '';
                    this.single.tr_nomor = '';
                    this.single.tr_akun_kas = '';
                    this.single.tr_jenis = 'D';
                    this.single.tr_keterangan = '';
                    this.single.tr_tanggal = '{{ date("d/m/Y") }}';

                    this.single.totDebet = 0;
                    this.single.totKredit = 0;

                    this.tr_akun_detail = [
                        {
                            dt_akun_nama: this.tr_akun_kas[0].text,
                            dt_keterangan: '',
                            dt_kredit: 0,
                            dt_debet: 0,
                            dt_status: 'locked',
                            dt_akun: this.tr_akun_kas[0].id,
                            dt_deleted: false,
                        },

                        {
                            dt_akun_nama: '',
                            dt_keterangan: '',
                            dt_kredit: 0,
                            dt_debet: 0,
                            dt_status: 'open',
                            dt_akun: this.tr_akun_kas[0].id,
                            dt_deleted: false,
                        }
                    ];

                    if(that.tr_akun_kas.length){
                        that.coaChange(that.tr_akun_kas[0].id);
                        $('#dt_akun_1').val(this.tr_akun_kas[0].id).trigger('change.select2');
                    }

                    this.jenisChange(this.tr_jenis[0].id);

                    $('#tr_jenis').val('D').trigger('change.select2');
                    $('#tr_nominal').val(0);
                }
            }
        });

    </script>
@endsection
