@extends('main')

@section('title', 'Tambah Data Group Akun')

@section(modulSetting()['extraStyles'])

	<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/wait_me_v_1_1/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/toast/dist/jquery.toast.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/select2/dist/css/select2.min.css') }}"> --}}

@endsection


@section('content')
    <div class="content-wrapper" id="vue-component">
      <div class="row">
        <div class="col-lg-12">
          <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb bg-info">
              <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="#">Home</a></li>
              <li class="breadcrumb-item">Master</li>
              <li class="breadcrumb-item" aria-current="page">Master Data Group Akun</li>
              <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
          </nav>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Input Master Data Group Akun</h4>
                <div class="table-responsive" style="border-top: 1px solid #eee; padding-top: 20px;">
                    <form id="data-form" v-cloak>
                        <input type="hidden" readonly name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" readonly name="ag_id" v-model="singleData.ag_id">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-7" style="background: none;">

                                    <div class="row mt-form">
                                        <div class="col-md-3">
                                            <label class="modul-keuangan">Nomor Group</label>
                                        </div>

                                        <div class="col-md-7">
                                            <input type="text" name="ag_nomor" class="form-control modul-keuangan" placeholder="Di Isi Oleh Sistem" readonly v-model="singleData.ag_nomor">
                                        </div>

                                        <div class="col-md-1 form-info-icon link" @click="search" v-if="!onUpdate">
                                            <i class="fa fa-search" title="Cari Group Berdasarkan Nomor dan Type Group"></i>
                                        </div>

                                        <div class="col-md-1 form-info-icon link" @click="formReset" v-if="onUpdate">
                                            <i class="fa fa-times" title="Bersihkan Pencarian" style="color: #CC0000;"></i>
                                        </div>
                                    </div>

                                    <div class="row mt-form">
                                        <div class="col-md-3">
                                            <label class="modul-keuangan">Type Group</label>
                                        </div>

                                        <div class="col-md-4">
                                            <vue-select :name="'ag_type'" :id="'ag_type'" :options="type" :disabled="onUpdate" @input="typeChange"></vue-select>
                                        </div>

                                        <div class="col-md-3">
                                            <vue-select :name="'ag_kelompok'" :id="'ag_kelompok'" :options="jenis" @input="kelompokChange"></vue-select>
                                        </div>

                                        <div class="col-md-1 form-info-icon" title="Parameter Type Group Digunakan Untuk Pencarian Data">
                                            <i class="fa fa-info-circle"></i>
                                        </div>
                                    </div>

                                    <div class="row mt-form" v-if="!nullSubclass">
                                        <div class="col-md-3">
                                            <label class="modul-keuangan">Sub Class</label>
                                        </div>

                                        <div class="col-md-7">
                                            <vue-select :name="'ag_subclass'" :id="'ag_subclass'" :options="subClass"></vue-select>
                                        </div>
                                    </div>

                                    <div class="row mt-form">
                                        <div class="col-md-3">
                                            <label class="modul-keuangan">Nama Group *</label>
                                        </div>

                                        <div class="col-md-7">
                                            <input type="text" name="ag_nama" class="form-control modul-keuangan" placeholder="contoh: Kas dan Setara Kas" v-model="singleData.ag_nama" title="Tidak Boleh Kosong">
                                        </div>
                                    </div>

                                    <div class="row mt-form" v-if="locked">
                                        <div class="col-md-3">
                                            <label class="modul-keuangan"></label>
                                        </div>

                                        <div class="col-md-7">
                                            <div class="modul-keuangan-alert primary" role="alert">
                                              <i class="fa fa-info-circle"></i> &nbsp;&nbsp;Group Akun Dikunci. Tidak Bisa Dinonaktifkan
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-5" style="border: 1px solid #eee; box-shadow: 0px 0px 10px #eee; border-radius: 5px; padding: 10px;">
                                    <table class="table table-stripped table-mini">
                                        <thead>
                                            <tr>
                                                <th colspan="2">@{{ singleData.ag_type }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-for="view in loopView">
                                                <td style="font-weight: bold; padding-left: 20px; border-top: 0px;">
                                                    - &nbsp; @{{ view.text }} <br>

                                                    <span style="padding-left: 30px; color: #777;" v-if="subClass.length">- &nbsp; Subclass</span><br>

                                                    <span style="padding-left: 60px; color: #ccc;">-----------------------------------------</span><br>
                                                    <span style="padding-left: 60px; color: #ccc;">-----------------------------------------</span><br>
                                                    <span style="padding-left: 60px; color: #ccc;">-----------------------------------------</span><br>
                                                </td>

                                                <td class="text-right" style="font-weight: bold; padding-left: 20px; border-top: 0px;">
                                                     <br>
                                                    <span style="color: #ccc;">xxx.xxx.xxx,xx</span><br>
                                                    <span style="color: #ccc;">xxx.xxx.xxx,xx</span><br>
                                                    <span style="color: #ccc;">xxx.xxx.xxx,xx</span><br>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" style="border-top: 1px solid #eee; margin-top: 20px; padding-top: 20px;">
                            <div class="row"> 
                                <div class="col-md-6">
                                    <a href="{{ route('grup-akun.index') }}">
                                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-arrow-left" :disabled="btnDisabled"></i> &nbsp;Kembali Ke Halaman Data Group Akun</button>
                                    </a>
                                </div>

                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-info btn-sm" @click="updateData" :disabled="btnDisabled" v-if="onUpdate"><i class="fa fa-floppy-o"></i> &nbsp;Simpan Perubahan</button>
                                    
                                    <button type="button" class="btn btn-danger btn-sm" @click="deleteData" :disabled="btnDisabled" v-if="onUpdate && dataIsActive"><i class="fa fa-times"></i> &nbsp;Nonaktifkan</button>

                                    <button type="button" class="btn btn-success btn-sm" @click="deleteData" :disabled="btnDisabled" v-if="onUpdate && !dataIsActive"><i class="fa fa-check-square-o"></i> &nbsp;Aktifkan</button>

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
                        Data Group Akun Yang Sudah Dibuat
                    </span>

                    <span class="close"><i class="fa fa-times" style="font-size: 12pt; color: #CC0000"></i></span>
                </div>
                
                <div class="content-popup">
                    <vue-datatable :data_resource="list_data_table" :columns="data_table_columns" :selectable="true" :ajax_on_loading="onAjaxLoading" :index_column="'ag_id'" @selected="dataSelected"></vue-datatable>
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

    <script src="{{ asset('modul_keuangan/js/vendor/wait_me_v_1_1/wait.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/toast/dist/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/validator/bootstrapValidator.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/axios_0_18_0/axios.min.js') }}"></script>

	<script type="text/javascript">

        function register_validator(){
            $('#data-form').bootstrapValidator({
                feedbackIcons : {
                  valid : 'glyphicon glyphicon-ok',
                  invalid : 'glyphicon glyphicon-remove',
                  validating : 'glyphicon glyphicon-refresh'
                },
                fields : {
                  ag_nama : {
                    validators : {
                      notEmpty : {
                        message : 'Nama Group Tidak Boleh Kosong',
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
                locked: false,
                dataIsActive: true,
                nullSubclass: false,

                type: [
                    {
                        id      : 'N',
                        text    : 'Neraca (Balance Sheet)' 
                    },

                    {
                        id      : 'LR',
                        text    : 'Laba Rugi (Revenue)' 
                    },

                    {
                        id      : 'A',
                        text    : 'Arus Kas (Cashflow)' 
                    }
                ],

                jenis: [
                    {
                        id: 'aktiva',
                        text: 'Aktiva'
                    },

                    {
                        id: 'pasiva',
                        text: 'Pasiva'
                    }
                ],

                listSubClass: [],
                loopView: [],
                subClass:[],
                
                data_table_columns : [
                    {name: 'Nomor Group', context: 'ag_nomor', width: '20%', childStyle: 'text-align: center'},
                    {name: 'Type Group', context: 'ag_type', width: '20%', childStyle: 'text-align: center', override: function(e){
                        switch(e){
                            case "N":
                                return "Neraca (Balance Sheet)";
                                break;

                            case "LR":
                                return "Laba Rugi (Revenue)";
                                break;

                            case "A":
                                return "Arus Kas (Cashflow)";
                                break;
                        }
                    }},
                    {name: 'Nama Group', context: 'ag_nama', width: '20%', childStyle: 'text-align: center'},
                    {name: 'Kelompok', context: 'ag_kelompok', width: '20%', childStyle: 'text-align: center'},
                    {name: 'Aktif', context: 'ag_isactive', width: '20%', childStyle: 'text-align: center', override: function(e){
                        if(e === '1')
                            return '<i class="fa fa-check-square-o" style="color: #007E33;"></i>';

                        return '<i class="fa fa-square-o" style="color: #CC0000;"></i>';
                    }}
                ],

                list_data_table : [],

                singleData: {
                    ag_id: '',
                    ag_nama: '',
                    ag_nomor: '',
                    ag_type:'Neraca (Balance Sheet)',
                }
            },

            created: function(){
                console.log('Initializing Vue');
            },

            mounted: function(){
                console.log('Vue Ready');
                register_validator();

                axios.get('{{route('grup-akun.form_resource')}}')
                          .then((response) => {
                                console.log(response.data);

                                if(response.data.subclass.length > 0){
                                	this.listSubClass = response.data.subclass;

                                	this.subClass = this.filterSubclass($('#ag_type').val(), $('#ag_kelompok').val());
                                }
                          })
                          .catch((e) => {
                            alert('error '+e);
                          })
            },

            computed: {
                // ---
            },

            watch: {
                // ---
            },

            methods: {
                saveData: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    if($('#data-form').data('bootstrapValidator').validate().isValid()){
                        this.stat = 'loading';
                        this.statMessage = 'Sedang Menyimpan Data ..'
                        this.btnDisabled = true;

                        axios.post('{{ route('grup-akun.store') }}', $('#data-form').serialize())
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

                updateData: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    if($('#data-form').data('bootstrapValidator').validate().isValid()){
                        this.stat = 'loading';
                        this.statMessage = 'Sedang Memperbarui Data ..'
                        this.btnDisabled = true;

                        axios.post('{{ route('grup-akun.update') }}', $('#data-form').serialize())
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

                deleteData: function(evt){
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    if(this.locked){
                        $.toast({
                            text: "Group Ini Sedang Dikunci (Digunakan Oleh Sistem). Tidak Bisa Dinonaktifkan",
                            showHideTransition: 'slide',
                            position: 'top-right',
                            icon: 'info',
                            hideAfter: 10000
                        });
                    }else{
                        var cfrm = confirm('Apakah Anda Yakin ?');

                        if(cfrm){
                            this.stat = 'loading';
                            this.statMessage = 'Sedang Merubah Status Aktif Data ..'
                            this.btnDisabled = true;

                            axios.post('{{ route('grup-akun.delete') }}', { ag_id: this.singleData.ag_id, _token: '{{ csrf_token() }}' })
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
                    }
                },

                typeChange: function(e){

                	var idx = this.type.findIndex(alpha => alpha.id == e);
                	this.singleData.ag_type = this.type[idx].text;

                    switch(e){
                        case 'N' :
                            this.jenis = [
                                {
                                    id      : 'aktiva',
                                    text    : 'Aktiva'
                                },

                                {
                                    id      : 'pasiva',
                                    text    : 'Pasiva'
                                }
                            ];
                        break;

                        case 'LR' :
                            this.jenis = [
                                {
                                    id      : 'pendapatan',
                                    text    : 'Pendapatan'
                                },

                                {
                                    id      : 'beban',
                                    text    : 'Beban-Beban'
                                }
                            ];
                        break;

                        case 'A' :
                            this.jenis = [
                                {
                                    id      : 'OCF',
                                    text    : 'Operasional'
                                },

                                {
                                    id      : 'FCF',
                                    text    : 'Finansial'
                                },

                                {
                                    id      : 'ICF',
                                    text    : 'Investasi'
                                }
                            ];
                        break;
                    }

                    var that = this;

                    setTimeout(function(){
                    	that.kelompokChange(that.jenis[0].id);
                    }, 0);
                },

                kelompokChange: function(e){
                	this.subClass = this.filterSubclass($('#ag_type').val(), $('#ag_kelompok').val());
                },

                filterSubclass: function(type, kelompok){
                	var that = this;

                	if(that.listSubClass.length > 0){
                		var alpha = $.grep(that.listSubClass, function(n){ 
				                		return n.gs_type === type && n.gs_kelompok === kelompok; 
				                	});

                		// console.log(alpha.length);
                		// console.log(type+' / '+kelompok);

                		if(!alpha.length){
                			this.nullSubclass = true;
                			this.loopView = this.jenis;
                		}
                		else{
                			this.nullSubclass = false;
                			this.loopView = this.jenis;
                		}
                		

                		return alpha;
                	}
                },

                search: function(e){
                    e.preventDefault();
                    this.list_data_table = [];
                    this.onAjaxLoading = true;

                    axios.get('{{ Route('grup-akun.datatable') }}?type='+$('#ag_type').val()+'&kel='+$("#ag_kelompok").val())
                            .then((response) => {
                                if(response.data.length){
                                    this.list_data_table = response.data;
                                }
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
                    var idx = this.list_data_table.findIndex(a => a.ag_id === e);

                    if(this.list_data_table[idx].ag_status == 'locked'){
                        this.locked = true;
                    }

                    if(this.list_data_table[idx].ag_isactive == '0'){
                        this.dataIsActive = false;
                    }

                    this.onUpdate = true;
                    this.singleData.ag_id = this.list_data_table[idx].ag_id;
                    this.singleData.ag_nama = this.list_data_table[idx].ag_nama;
                    this.singleData.ag_nomor = this.list_data_table[idx].ag_nomor;

                    $('#ag_type').val(this.list_data_table[idx].ag_type).trigger('change.select2');
                    $('#ag_subclass').val(this.list_data_table[idx].ag_subclass).trigger('change.select2');
                    $('#data-popup').ezPopup('close');
                },

                formReset: function(){
                    this.btnDisabled = false;
                    this.singleData.ag_nomor = '';
                    this.singleData.ag_nama = '';
                    this.stat = 'standby';
                    this.onUpdate = false;
                    this.locked = false;
                    this.dataIsActive = true;

                    $('#ag_type').val('N').trigger('change.select2');
                    this.typeChange('N');

                    $('#data-form').data('bootstrapValidator').resetForm();
                }
            }
        })

    </script>

@endsection