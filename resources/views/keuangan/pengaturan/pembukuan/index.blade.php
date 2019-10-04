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
                                    <h3 class="title"> Pengaturan COA Untuk Pembukuan  </h3>
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
                                <form id="form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" readonly>
                                <section>
                                    <div class="row keuangan-form">
                                        <template v-for="(ctx, idx) in pembukuan">
                                            <input type="hidden" name="pe_id[]" v-model="ctx.pe_id" readonly>
                                            <div class="col-md-12" :style="(idx > 0) ? 'margin-top: 30px' : ''">
                                                <i class="fa fa-arrow-right"></i>
                                                <span class="hintText">&nbsp; Setting COA Untuk Transaksi <u>@{{ ctx.pe_nama }}</u></span>
                                                <!-- <small> &nbsp;- Hanya bisa mengubah nama hierarki</small> -->
                                            </div>

                                            <div class="col-md-12" style="padding: 0px;">
                                                <div class="col-md-12" style="margin-top: 15px;">
                                                    <table width="100%" class="keuangan table-mini">
                                                        <thead>
                                                            <tr>
                                                                <th width="22%" style="background: #eee; position: sticky; top: 0;">Nama COA Pembukuan</th>
                                                                <th width="27%" style="background: #eee; position: sticky; top: 0;">COA Yang Digunakan</th>
                                                                <th width="24%" style="background: #eee; position: sticky; top: 0;">COA Cashflow</th>
                                                                <th width="27%" style="background: #eee; position: sticky; top: 0;">Keterangan COA</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <tr v-for="(detail, emp) in ctx.detail">
                                                                <td>
                                                                    @{{ detail.pd_nama }}
                                                                </td>
                                                                
                                                                <td style="padding-left: 10px;">
                                                                    <template v-if="detail.pd_status != 'locked'">
                                                                        <input type="hidden" :name="'akun['+ctx.pe_id+'][]'" v-model="detail.pd_acc" readonly>

                                                                        <select style="width: 100%; border: 0px; color: #666" class="hint" v-model="detail.pd_acc">
                                                                            <option value="">- Pilih Akun</option>
                                                                            <option v-for="(data, idx) in akun" :value="data.id">@{{ data.text }}</option>
                                                                        </select>
                                                                    </template>

                                                                    <template v-if="detail.pd_status == 'locked'">
                                                                        <input type="hidden" :name="'akun['+ctx.pe_id+'][]'" value="null" readonly>
                                                                        <span style="cursor: no-drop; color: #ccc;">@{{ detail.pd_placeholder }}</span>
                                                                    </template>
                                                                </td>

                                                                <td>
                                                                    <template v-if="detail.pd_cf_status == '1'">
                                                                        <input type="hidden" :name="'cashflow['+ctx.pe_id+'][]'" v-model="detail.pd_cashflow" readonly>

                                                                        <select style="width: 100%; border: 0px; color: #666" class="hint" v-model="detail.pd_cashflow">
                                                                            <optgroup :label="'> '+renameCF(data.label)" v-for="(data, idx) in cashflow">
                                                                                <option v-for="(cf, idx) in data.detail" :value="cf.id">@{{ cf.text }}</option>
                                                                            </optgroup>
                                                                        </select>
                                                                    </template>

                                                                    <template v-if="detail.pd_cf_status != '1'">
                                                                        <input type="hidden" :name="'cashflow['+ctx.pe_id+'][]'" value="null" readonly>
                                                                        <i style="cursor: no-drop; color: #ccc;">Tidak Termasuk Cashflow</i>
                                                                    </template>
                                                                </td>

                                                                <td>
                                                                    <input type="text" :name="'pd_keterangan['+ctx.pe_id+'][]'" style="font-size: 9pt; height: 20px; border: 0px; color: #666; padding-left: 0px; width: 100%" placeholder="Berikan Keterangan Untuk COA Ini" v-model="detail.pd_keterangan">
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </template>

                                        <div class="col-md-12 button-wrapper">
                                            <div class="row">
                                                <div class="col-md-12 text-right" style="padding-right: 40px;">
                                                    <template v-if="stateForm == 'insert'">
                                                        <button class="btn btn-primary btn-sm" :disabled="disabledButton" @click="save">Simpan Data</button>
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
                state: 'level_2',

                akun: [],
                pembukuan: [],
                cashflow: [],

                single: {
                    level_1: '',
                }
            },

            mounted: function(){
                console.log('vue mounted');
                axios.get("{{ Route('keuangan.pembukuan.resource') }}")
                        .then((response) => {

                            this.downloadingResource = false;
                            this.cashflow = response.data.cashflow;
                            this.akun = response.data.akun;
                            this.pembukuan = response.data.pembukuan;

                            console.log(this.cashflow);

                        }).catch((e) => {
                            this.downloadingResource = false;
                            this.resourceError = true;
                            console.log('System Bermasalah '+e);
                        })
            },

            computed: {

            },

            methods: {

                save: function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    this.onRequest = true;
                    this.disabledButton = true;
                    var dataForm = $('#form-data').serialize();

                    axios.post('{{ Route("keuangan.pembukuan.store") }}', dataForm)
                            .then((response) => {
                                console.log(response.data);
                                if(response.data.status == 'success'){
                                    $.toast({
                                        text: response.data.text,
                                        showHideTransition: 'slide',
                                        icon: response.data.status,
                                        stack: 1
                                    });

                                    this.pembukuan = response.data.pembukuan

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
                },

                renameCF: function(conteks){
                    if(conteks == 'OCF')
                        return 'Operating Cashflow';
                    else if(conteks == 'ICF')
                        return 'Investing Cashflow';
                    else
                        return 'Financial Cashflow';
                }
            }
        });

    </script>
@endsection
