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
                                    <h3 class="title"> Pengaturan Hierarki COA Keuangan </h3>
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
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" readonly>
                                <section>
                                    <div class="row keuangan-form">
                                        <template v-if="state=='level_1'">
                                            <div class="col-md-12">
                                                <i class="fa fa-arrow-right"></i>
                                                <span class="hintText">&nbsp; Pengaturan Hierarki Level 1</span>
                                                <small> &nbsp;- Hanya bisa mengubah nama hierarki</small>
                                            </div>

                                            <div class="col-md-12" style="padding: 0px;">
                                                <div class="col-md-6" style="margin-top: 20px; padding: 0px;">
                                                    <form id="level_1">
                                                    <div class="col-md-12">
                                                        <table width="100%" class="keuangan table-mini">
                                                            <thead>
                                                                <tr>
                                                                    <th width="20%">ID Hierarki</th>
                                                                    <th>Nama Hierarki</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <tr v-for="(data, idx) in level_1">
                                                                    <td class="text-center">
                                                                        <input type="text" name="hs_id[]" v-model="data.hs_id" readonly style="font-size: 9pt; height: 20px; border: 0px; width: 100%; text-align: center;">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="hs_nama[]" v-model="data.hs_nama" style="font-size: 9pt; height: 20px; border: 0px; color: #666; padding-left: 15px; width: 100%">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    </form>

                                                    <div class="col-md-12 button-wrapper">
                                                        <div class="row">
                                                            <div class="col-md-12 text-right">
                                                                <template v-if="stateForm == 'insert'">
                                                                    <button class="btn btn-primary btn-sm" :disabled="disabledButton" @click="save">Simpan Data</button>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <template v-if="state=='subclass'">
                                            <div class="col-md-12">
                                                <i class="fa fa-arrow-right"></i>
                                                <span class="hintText">&nbsp; Pengaturan Hierarki subclass</span>
                                                <small> &nbsp;- Hanya bisa mengubah nama hierarki</small>
                                            </div>

                                            <div class="col-md-12" style="padding: 0px;">
                                                <div class="col-md-6" style="margin-top: 20px; padding: 0px;">
                                                    <form id="level_subclass">
                                                    <div class="col-md-12">
                                                        <div class="row" style="margin-top: 15px;">
                                                            <div class="col-md-4 label text-center">Pilih hierarki level 1</div>
                                                            <div class="col-md-8">
                                                                <vue-select :name="'level_1'" :id="'level_1'" :options="level_1" :search="false" @option-change="level_1_change"></vue-select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12" style="margin-top: 15px; height: 250px; overflow-y: scroll;">
                                                        <table width="100%" class="keuangan table-mini">
                                                            <thead>
                                                                <tr>
                                                                    <th width="20%" style="background: #eee; position: sticky; top: 0;">***</th>
                                                                    <th style="background: #eee; position: sticky; top: 0;">Nama Hierarki</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <tr v-for="(data, idx) in data">
                                                                    <td class="text-center">
                                                                        <template v-if="data.hs_status != 'locked'">
                                                                            <i class="fa fa-trash hintText hint" style="font-style: normal;" @click="deleteAddition($event, idx, false)"></i>
                                                                        </template>

                                                                        <template v-if="data.hs_status == 'locked'">
                                                                            <i class="fa fa-lock"></i>
                                                                        </template>

                                                                        <input type="hidden" name="hs_id[]" readonly v-model="data.hs_id">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="hs_nama[]" v-model="data.hs_nama" style="font-size: 9pt; height: 20px; border: 0px; color: #666; padding-left: 15px; width: 100%" placeholder="Masukkan nama (Jika kosong tidak akan disimpan)">
                                                                    </td>
                                                                </tr>

                                                                <tr v-for="(data, idx) in addition">
                                                                    <td class="text-center">
                                                                        <i class="fa fa-trash hintText hint" style="font-style: normal;" @click="deleteAddition($event, idx)"></i>

                                                                        <input type="hidden" name="hs_id[]" readonly v-model="data.hs_id">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="hs_nama[]" v-model="data.hs_nama" style="font-size: 9pt; height: 20px; border: 0px; color: #666; padding-left: 15px; width: 100%" placeholder="Masukkan nama (Jika kosong tidak akan disimpan)" :id="'addition-'+data.id">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    </form>

                                                    <div class="col-md-12 button-wrapper">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <button class="btn btn-primary btn-sm" :disabled="disabledButton" @click="addAddition">Tambah Hierarki</button>
                                                            </div>
                                                            <div class="col-md-6 text-right">
                                                                <template v-if="stateForm == 'insert'">
                                                                    <button class="btn btn-primary btn-sm" :disabled="disabledButton" @click="save">Simpan Data</button>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <template v-if="state=='level_2'">
                                            <div class="col-md-12">
                                                <i class="fa fa-arrow-right"></i>
                                                <span class="hintText">&nbsp; Pengaturan Hierarki Level 2</span>
                                                <!-- <small> &nbsp;- Hanya bisa mengubah nama hierarki</small> -->
                                            </div>

                                            <div class="col-md-12" style="padding: 0px;">
                                                <div class="col-md-7" style="margin-top: 20px; padding: 0px;">
                                                    <form id="level_2">
                                                    <div class="col-md-12">
                                                        <div class="row" style="margin-top: 15px;">
                                                            <div class="col-md-4 label text-center">Pilih hierarki level 1</div>
                                                            <div class="col-md-7">
                                                                <vue-select :name="'level_1'" :id="'level_1'" :options="level_1" :search="false" @option-change="level_1_change"></vue-select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12" style="margin-top: 15px; height: 250px; overflow-y: scroll;">
                                                        <table width="100%" class="keuangan table-mini">
                                                            <thead>
                                                                <tr>
                                                                    <th width="10%" style="background: #eee; position: sticky; top: 0;">***</th>
                                                                    <th width="20%" style="background: #eee; position: sticky; top: 0;">Nomor Hierarki</th>
                                                                    <th width="45%" style="background: #eee; position: sticky; top: 0;">Nama Hierarki</th>
                                                                    <th style="background: #eee; position: sticky; top: 0;">Subclass</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <tr v-for="(data, idx) in data">
                                                                    <td class="text-center">
                                                                        <template v-if="data.hd_status != 'locked'">
                                                                            <i class="fa fa-trash hintText hint" style="font-style: normal;" @click="deleteAddition($event, idx, false)"></i>
                                                                        </template>

                                                                        <template v-if="data.hd_status == 'locked'">
                                                                            <i class="fa fa-lock"></i>
                                                                        </template>

                                                                        <input type="hidden" name="hd_id[]" readonly v-model="data.hd_id">
                                                                    </td>
                                                                    
                                                                    <td style="padding-left: 10px;">
                                                                        <span style="display: inline;">
                                                                            @{{ single.level_1 }}.
                                                                        </span>

                                                                        <input type="text" name="hd_nomor[]" v-model="data.hd_nomor" style="font-size: 9pt; height: 20px; color: #666; padding-left: 0px; width: 70%; display: inline; border: 0px;" placeholder="Input nomor" @keyPress="idRules" @blur="cekId($event, false)" :data-id="data.id">
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" name="hd_nama[]" v-model="data.hd_nama" style="font-size: 9pt; height: 20px; border: 0px; color: #666; padding-left: 15px; width: 100%" placeholder="Nama yang kosong tidak akan disimpan">
                                                                    </td>

                                                                    <td>
                                                                        <select name="hd_subclass[]" style="width: 100%; border: 0px; color: #666" class="hint" v-model="data.hd_subclass">
                                                                            <option v-for="(data, idx) in serializeSubclass" :value="data.hs_id">@{{ data.hs_nama }}</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>

                                                                <tr v-for="(data, idx) in addition">
                                                                    <td class="text-center">
                                                                        <i class="fa fa-trash hintText hint" style="font-style: normal;" @click="deleteAddition($event, idx)"></i>

                                                                        <input type="hidden" name="hd_id[]" readonly v-model="data.hd_id">
                                                                    </td>
                                                                    <td style="padding-left: 10px;">
                                                                        <span style="display: inline;">
                                                                            @{{ single.level_1 }}.
                                                                        </span>

                                                                        <input type="text" name="hd_nomor[]" v-model="data.hd_nomor" style="font-size: 9pt; height: 20px; color: #666; padding-left: 0px; width: 70%; display: inline; border: 0px;" placeholder="Input nomor" @keyPress="idRules" @blur="cekId($event)" :data-id="data.id">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="hd_nama[]" v-model="data.hd_nama" style="font-size: 9pt; height: 20px; border: 0px; color: #666; padding-left: 15px; width: 100%" placeholder="Nama yang kosong tidak akan disimpan" :id="'addition-'+data.id">
                                                                    </td>
                                                                    <td>
                                                                        <select name="hd_subclass[]" style="width: 100%; border: 0px; color: #666" class="hint" v-model="data.hd_subclass">
                                                                            <option v-for="(data, idx) in serializeSubclass" :value="data.hs_id">@{{ data.hs_nama }}</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    </form>

                                                    <div class="col-md-12 button-wrapper">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <button class="btn btn-primary btn-sm" :disabled="disabledButton" @click="addAddition">Tambah Hierarki</button>
                                                            </div>
                                                            <div class="col-md-6 text-right">
                                                                <template v-if="stateForm == 'insert'">
                                                                    <button class="btn btn-primary btn-sm" :disabled="disabledButton" @click="save">Simpan Data</button>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </section>
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
                level_1: [],
                level_subclass: [],
                level_2: [],
                addition: [],
                data: [],

                single: {
                    level_1: '',
                }
            },

            mounted: function(){
                console.log('vue mounted');
                axios.get("{{ Route('keuangan.hierarki_akun.resource') }}")
                        .then((response) => {
                            this.downloadingResource = false;
                            this.level_1 = response.data.level_1;
                            this.level_subclass = response.data.subclass;
                            this.level_2 = response.data.level_2;

                            if(this.level_1.length){
                                this.level_1_change(this.level_1[0].id);
                            }

                        }).catch((e) => {
                            this.downloadingResource = false;
                            this.resourceError = true;
                            console.log('System Bermasalah '+e);
                        })
            },

            computed: {
                serializeSubclass: function(){
                    var that = this;
                    return $.grep(this.level_subclass, function(e){ return e.hs_level_1 == that.single.level_1 });
                }
            },

            methods: {
                idRules: function(evt){
                    evt.stopImmediatePropagation();
                    var conteks = $(evt.target);

                    if(conteks.val().length > 2)
                        evt.preventDefault()

                    if(isNaN(evt.key))
                      evt.preventDefault()
                    else
                      return true;
                },

                save: function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    this.onRequest = true;
                    this.disabledButton = true;
                    var dataForm; var route;

                    if(this.state == 'level_1'){

                        this.requestMessage = 'Memperbarui hierarki level 1..';
                        dataForm = $('#level_1').serialize();
                        route = '{{ Route("keuangan.hierarki_akun.save.level_1") }}';

                    }else if(this.state == 'subclass'){

                        this.requestMessage = 'Memperbarui hierarki subclass ..';
                        dataForm = $('#level_subclass').serialize();
                        route = '{{ Route("keuangan.hierarki_akun.save.subclass") }}';

                    }else if(this.state == 'level_2'){

                        this.requestMessage = 'Memperbarui hierarki level 2 ..';
                        dataForm = $('#level_2').serialize();
                        route = '{{ Route("keuangan.hierarki_akun.save.level_2") }}';

                    }

                    axios.post(route, dataForm)
                            .then((response) => {
                                console.log(response.data);
                                if(response.data.status == 'success'){
                                    $.toast({
                                        text: response.data.text,
                                        showHideTransition: 'slide',
                                        icon: response.data.status,
                                        stack: 1
                                    });

                                    if(this.state == 'level_1'){
                                        this.level_1 = response.data.level_1;
                                    }else if(this.state == 'subclass'){
                                        this.level_subclass = response.data.subclass;
                                    }else if(this.state == 'level_2'){
                                        this.level_2 = response.data.level_2;
                                    }

                                    this.filteringData();
                                    this.addition = [];

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

                level_1_change: function(e){
                    this.single.level_1 = e;
                    this.addition = [];
                    this.filteringData();
                },

                addAddition: function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    var that = this;

                    if(this.state == 'subclass'){
                        this.addition.push({
                            hs_id: 'addition',
                            hs_nama: '',
                            id: this.addition.length
                        })
                    }else if(this.state == 'level_2'){
                        this.addition.push({
                            hd_id: 'addition',
                            hd_nama: '',
                            hd_subclass: this.serializeSubclass[0].id,
                            id: this.addition.length
                        })
                    }

                    setTimeout(function(){
                        $('#addition-'+(that.addition.length-1)).focus();
                    }, 0)
                },

                deleteAddition: function(e, idx, fromAddition = true){
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    if(fromAddition){
                        this.addition.splice(idx, 1);
                    }else{
                        this.data.splice(idx, 1);
                    }
                },

                filteringData: function(){
                    var that = this;
                    if(this.state == 'subclass')
                        this.data = $.grep(this.level_subclass, function(e){ return e.hs_level_1 == that.single.level_1 });
                    else if(this.state == 'level_2')
                        this.data = $.grep(this.level_2, function(e){ return e.hd_level_1 == that.single.level_1 });
                },

                cekId: function(e, fromAddition = true){
                    var conteks = $(e.target);
                    var cek1 = cek2 = [];

                    if(fromAddition){
                        cek1 = $.grep(this.data, function(a){ return a.hd_nomor == conteks.val() });
                        cek2 = $.grep(this.addition, function(a){ 
                            return a.hd_nomor == conteks.val() && a.id != conteks.data('id');
                        });
                    }else{
                        cek1 = $.grep(this.data, function(a){ 
                            return a.hd_nomor == conteks.val() && a.id != conteks.data('id');
                        });
                        cek2 = $.grep(this.addition, function(a){ return a.hd_nomor == conteks.val() });
                    }

                    if(cek1.length || cek2.length){
                        $.toast({
                            text: 'Nomor hierarki sudah digunakan oleh hierarki lain. Harap memasukkan nomor hierarki yang lain.',
                            showHideTransition: 'slide',
                            icon: 'info',
                            stack: 1
                        });

                        conteks.val('');
                    }
                    
                },

                resetForm: function(){
                    
                }
            }
        });

    </script>
@endsection
