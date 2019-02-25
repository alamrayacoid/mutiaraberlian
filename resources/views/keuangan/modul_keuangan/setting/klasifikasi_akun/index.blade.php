@extends('main')

@section('title', 'Setting Klasifikasi Akun')

@section(modulSetting()['extraStyles'])

    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/wait_me_v_1_1/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/toast/dist/jquery.toast.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/select2/dist/css/select2.min.css') }}">

    <style type="text/css">

        #button-wrap{
            background-color: none;
            padding:0px;
        }

        #button-wrap .button-ctn{
            background-color: white;
            border: 1px solid #eee;
            border-right: 0px;
            padding:10px 0px 10px 0px;
            cursor: pointer;
            font-weight: bold;
        }

        #button-wrap .button-ctn.active{
            background-color: #0099CC;
            color: white;
        }

        #main-content{
            background: #fff;
            box-shadow: 0px 0px 5px #ccc;
            padding: 15px 25px;
            /*color: white;*/
        }

        #main-content .title{
            border-bottom: 1px solid #eee;
            font-weight: 600;
            font-size: 11pt;
            padding-bottom: 8px;
            font-style: italic;
        }

        #main-content .text-intro{
            font-size: 9pt;
            margin-top: 10px;
        }

        input:focus{
            box-shadow: none;
            border: 0px;
            outline: none;
        }

        select{
            border: 0px;
            cursor: pointer;
            /*outline: none;*/
        }

    </style>

@endsection


@section('content')
    <div class="content-wrapper" id="vue-component">
      <div class="row">
        {{-- <div class="col-lg-12">
          <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb bg-info">
              <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="#">Home</a></li>
              <li class="breadcrumb-item">Master</li>
              <li class="breadcrumb-item active" aria-current="page">Master Data Group Akun</li>
            </ol>
          </nav>
        </div> --}}

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                {{-- <h4 class="card-title">Master Data Group Akun</h4> --}}

                <div class="table-responsive" style="border-top: 0px solid #eee; padding-top: 0px;">
                    <div class="col-md-12" style="padding-bottom: 20px;" v-cloak>
                        <div class="row">
                            <div class="col-md-1" id="button-wrap">
                                <div class="col-md-12" style="padding: 0px;">
                                    <div class="col-md-6 offset-6 text-center button-ctn active" id="inv-0">
                                        <i class="fa fa-info"></i>
                                    </div>
                                </div>

                                <div class="col-md-12" style="padding: 0px;">
                                    <div class="col-md-6 offset-6 text-center button-ctn" id="inv-1">
                                        <strong><small>1</small></strong>
                                    </div>
                                </div>

                                <div class="col-md-12" style="padding: 0px;">
                                    <div class="col-md-6 offset-6 text-center button-ctn" id="inv-2">
                                        <strong><small>2</small></strong>
                                    </div>
                                </div>

                                <div class="col-md-12" style="padding: 0px;">
                                    <div class="col-md-6 offset-6 text-center button-ctn" id="inv-3">
                                        <strong><small>3</small></strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-10" id="main-content" v-if="stateNumber == 0">
                                <div class="row">
                                    <div class="col-md-12 title">
                                        <span>Hai . Tahukah Anda Apa Klasifikasi Akun Itu ?</span> 
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-intro">
                                        Klasifikasi Akun Adalah Fitur Yang Memudahkan Anda Dalam Menyusun Hierarki Akun Keuangan Yang Nantinya Akan Ditampilkan Dalam Laporan Keuangan. Ilustrasinya Bisa Anda Lihat Pada Gambar Dibawah Ini. 
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-detail" style="background-color: none; margin-top: 15px; padding-left: 25px; max-height: 254px; min-height: 254px;">
                                        <div class="row">
                                            <div class="col-md-7" style="box-shadow: 5px 0px 10px #ccc;">
                                                <img src="{{ asset('modul_keuangan/klasifikasi.jpg') }}">
                                            </div><br>
                                            <div class="col-md-12 text-intro">
                                                Hierarki Level 1 dan Level 2 Adalah Hierarki Yang Wajib Dibuat Sedangkan Untuk Sub Hierarki Bersifat Optional (Tidak Wajib).
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12" style="border-top: 1px solid #eee; padding: 21px 10px 8px 10px; margin-top: 25px;">
                                        <div class="row">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-7 text-right">
                                                <button class="btn btn-info btn-sm" @click="nextState">
                                                    <i class="fa fa-map-marker"></i> &nbsp;Mulai Atur Klasifikasi Akun
                                                </button>
                                                &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form id="data-form-lvl1" class="col-md-10" v-if="stateNumber == 1" style="padding: 0px;">
                                <div class="col-md-12" id="main-content">
                                    <div class="row">
                                        <div class="col-md-12 title">
                                            <span>1. Setting Klasifikasi Hierarki Akun <small>(Level 1)</small></span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 text-intro">
                                           Hierarki Akun Level 1 Telah Di Set Secara Default, Sehingga Data Tidak Bisa Ditambah Atau Dihapus. Namun, Anda Tetap Bisa Mengganti Nama Dari Hierarki Ini. 
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 text-detail" style="background-color: none; margin-top: 15px; padding-left: 15px; max-height: 254px; min-height: 254px; overflow-y: scroll;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form id="form-data-level-1">
                                                        <table class="table table-stripped table-bordered table-mini">
                                                            <tbody>
                                                                <tr v-for="lvl1 in level_1">
                                                                    <td class="text-center" width="8%">@{{ lvl1.id }}</td>
                                                                    <td>
                                                                        <input type="text" name="lvl1[]" style="border: 0px; width: 70%" :value="lvl1.nama" :id="'input-'+lvl1.id" :data-value="lvl1.nama" @blur="checkValue">

                                                                        <input type="hidden" name="nama_lama[]" :value="lvl1.nama" readonly>

                                                                        <input type="hidden" name="id_lama[]" :value="lvl1.id" readonly>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-intro">
                                            <i class="fa fa-circle-o" style="font-size: 7pt;"></i> &nbsp;Simpan Terlebih Dahulu Data Anda Saat Akan Berpindah Ke Jendela Selanjutnya Atau Sebelumnya. <br>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12" style="border-top: 1px solid #eee; padding: 21px 10px 8px 10px; margin-top: 25px;">
                                            <div class="row">
                                                <div class="col-md-2 form-status" style="padding-top: 10px;">
                                                    
                                                </div>

                                                <div class="col-md-10 text-right">
                                                    <button class="btn btn-info btn-sm" type="button" @click="simpanLevel1" :disabled="btnDisabled">
                                                        Simpan Perubahan
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-default btn-sm" type="button" @click="previousState" :disabled="btnDisabled">
                                                        <i class="fa fa-arrow-left"></i> &nbsp;Sebelumnya
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-default btn-sm" type="button" @click="nextState" :disabled="btnDisabled">
                                                        Selanjutnya &nbsp;<i class="fa fa-arrow-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <form id="data-form-lvlsubclass" class="col-md-10" v-if="stateNumber == 2" style="padding: 0px;">
                                <div class="col-md-12" id="main-content">
                                    <div class="row">
                                        <div class="col-md-12 title">
                                            <span>2. Setting Klasifikasi Hierarki <small>(Subclass)</small></span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 text-intro">
                                           Untuk Menentukan Pos di Hierarki Subclass. Pilih Terlebih Dahulu Hierarki Level 1 nya.
                                        </div>

                                        <div class="col-md-12" style="margin-top: 15px; font-style: italic; padding-left: 0px; margin-bottom: 15px;">
                                            <div class="row">
                                                <div class="col-md-2 text-right">
                                                    <label class="modul-keuangan">Pilih Level 1</label>
                                                </div>

                                                <div class="col-md-4">
                                                    <select class="form-control modul-keuangan" name="level1" @change="level1SubclassChange" v-model="level1Subclass">
                                                        <option v-for="lvl1 in level_1" :value="lvl1.id">
                                                            @{{ lvl1.id +' - '+ lvl1.nama }}
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-1" style="padding-top: 5px; cursor: help;" title="Simpan terlebih Dahulu Data Anda Saat Akan Berpindah Ke Data Level 1 Yang Lain">
                                                    <i class="fa fa-exclamation"></i>
                                                </div>
                                            </div>    
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 text-detail" style="background-color: none; margin-top: 15px; padding-left: 15px; padding-right: 15px; max-height: 210px; min-height: 210px; overflow-y: scroll;" id="content-lvl-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form id="form-data-level-1">
                                                        <table class="table table-stripped table-bordered table-mini">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="5%" class="text-center">*</td>
                                                                    <td class="text-center" style="position: sticky; top: 0px; background: white;"> Nama </td>
                                                                </tr>

                                                                <tr v-for="data in dataSubclassPrint" v-if="data.nama != 'Tidak Memiliki'">
                                                                    <td class="text-center">

                                                                         <i class="fa fa-lock" v-if="data.status == 'locked'" title="Subclass Dikunci Tidak Bisa Dihapus"></i>

                                                                        <i class="fa fa-eraser" v-if="data.status != 'locked'" style="cursor: pointer; color: #ff4444;" title="Hapus Subclass Ini." @click="hapusHierarkiSubclass(data.id)"></i>
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" name="data[]" style="border: 0px; width: 70%" :value="data.nama" :id="'input-'+data.id" :data-value="data.nama" @blur="checkValue">

                                                                        <input type="hidden" name="nama_lama[]" :value="data.nama" readonly>

                                                                        <input type="hidden" name="id_lama[]" :value="data.id" readonly>
                                                                    </td>
                                                                </tr>

                                                                <tr v-for="lvl2New in lvl2New">
                                                                    <td class="text-center">-</td>

                                                                    <td>
                                                                        <input type="text" :id="'lvlSubclassNewNama'+lvl2New" name="lvl2NewNama[]" style="border: 0px; width: 70%" @blur="checkValue">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-intro">
                                            <i class="fa fa-circle-o" style="font-size: 7pt;"></i> &nbsp;Simpan Terlebih Dahulu Data Anda Saat Akan Berpindah Ke Jendela Selanjutnya Atau Sebelumnya. <br>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12" style="border-top: 1px solid #eee; padding: 21px 10px 8px 10px; margin-top: 25px;">
                                            <div class="row">
                                                <div class="col-md-2 form-status" style="padding-top: 10px;">
                                                    
                                                </div>

                                                <div class="col-md-10 text-right">
                                                    <button class="btn btn-primary btn-sm" type="button" @click="tambahLevel2" :disabled="btnDisabled">
                                                        <i class="fa fa-plus"></i> &nbsp;Tambah
                                                    </button>
                                                    <button class="btn btn-info btn-sm" type="button" @click="simpanLevelSubclass" :disabled="btnDisabled">
                                                        Simpan Perubahan
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-default btn-sm" type="button" @click="previousState" :disabled="btnDisabled">
                                                        <i class="fa fa-arrow-left"></i> &nbsp;Sebelumnya
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-default btn-sm" type="button" @click="nextState">
                                                        Selanjutnya &nbsp;<i class="fa fa-arrow-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <form id="data-form-lvl2" class="col-md-10" v-if="stateNumber == 3" style="padding: 0px;">
                                <div class="col-md-12" id="main-content">
                                    <div class="row">
                                        <div class="col-md-12 title">
                                            <span>3. Setting Klasifikasi Hierarki Akun <small>(Level 2)</small></span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 text-intro">
                                           Untuk Menentukan Pos di Hierarki Level 2. Pilih Terlebih Dahulu Hierarki Level 1 nya. <small>(Simpan Terlebih Dahulu Data Anda Saat Berpindah Ke Data Level 1 Yang Lain)</small>
                                        </div>

                                        <div class="col-md-12" style="margin-top: 15px; font-style: italic; padding-left: 0px; margin-bottom: 15px;">
                                            <div class="row">
                                                <div class="col-md-2 text-right">
                                                    <label class="modul-keuangan">Pilih Level 1</label>
                                                </div>

                                                <div class="col-md-4">
                                                    <select class="form-control modul-keuangan" name="level1" @change="level1Change" v-model='level1'>
                                                        <option v-for="lvl1 in level_1" :value="lvl1.id">
                                                            @{{ lvl1.id +' - '+ lvl1.nama }}
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-1" style="padding-top: 5px; cursor: help;" title="Simpan terlebih Dahulu Data Anda Saat Akan Berpindah Ke Data Level 1 Yang Lain">
                                                    <i class="fa fa-exclamation"></i>
                                                </div>
                                            </div>    
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-11 text-detail" style="background-color: none; margin-top: 15px; padding-left: 15px; padding-right: 15px; max-height: 210px; min-height: 210px; overflow-y: scroll;" id="content-lvl-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form id="form-data-level-1">
                                                        <table class="table table-stripped table-bordered table-mini">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="5%" class="text-center">*</td>
                                                                    <td width="15%" class="text-center" style="position: sticky; top: 0px; background: white;">ID</td>
                                                                    <td width="45%" class="text-center" style="position: sticky; top: 0px; background: white;"> Nama </td>
                                                                    <td width="25%" class="text-center" style="position: sticky; top: 0px; background: white;"> Subclass </td>
                                                                    <td class="text-center" style="position: sticky; top: 0px; background: white;"> Cashflow </td>
                                                                </tr>

                                                                <tr v-for="data in data2Print">
                                                                    <td class="text-center">
                                                                        <i class="fa fa-lock" v-if="data.status == 'locked'" title="Hierarki Dikunci Tidak Bisa Dihapus"></i>

                                                                        <i class="fa fa-eraser" v-if="data.status != 'locked'" style="cursor: pointer; color: #ff4444;" title="Hapus Hierarki Ini." @click="hapusHierarki2(data.id)"></i>
                                                                    </td>
                                                                    <td class="text-center" width="8%">
                                                                        @{{ level1 }}.
                                                                        <input type="text" name="dataId[]" style="border: 0px; width: 70%" :value="data.id.split('.')[1]" :id="'inputID-'+data.id.replace(/\./g, '-')" :data-value="data.id.split('.')[1]" @blur="checkIdlvl2" @keypress="onlyNumber" data-max="3">
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" name="data[]" style="border: 0px; width: 70%" :value="data.nama" :id="'input-'+data.id.replace(/\./g, '-')" :data-value="data.nama" @blur="checkValue">

                                                                        <input type="hidden" name="nama_lama[]" :value="data.nama" readonly>

                                                                        <input type="hidden" name="id_lama[]" :value="data.id" readonly>
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <select name="hld_subclass[]">
                                                                            <option v-for="det in dataSubclassPrintLvl2" :value="det.id" :selected="det.id == data.subclass">@{{ det.nama }}</option>
                                                                        </select>
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <select name="cashflow[]">
                                                                            <option value="">Tidak Ada Cashflow</option>
                                                                            <option value="OCF" :selected="data.cashflow == 'OCF'">Operating Cashflow</option>
                                                                            <option value="ICF" :selected="data.cashflow == 'ICF'">Investing Cashflow</option>
                                                                            <option value="FCF" :selected="data.cashflow == 'FCF'">Financial Cashflow</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>

                                                                <tr v-for="lvl2New in lvl2New">
                                                                    <td class="text-center">-</td>
                                                                    <td class="text-center" width="8%">
                                                                        @{{ level1 }}.
                                                                        <input type="text" name="lvl2NewId[]" style="border: 0px; width: 70%" :id="'lvl2NewId'+lvl2New" @blur="checkIdlvl2" @keypress="onlyNumber" data-max="3">
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" :id="'lvl2NewNama'+lvl2New" name="lvl2NewNama[]" style="border: 0px; width: 70%" @blur="checkValue">
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <select name="hld_subclassNew[]">
                                                                            <option v-for="det in dataSubclassPrintLvl2" :value="det.id">@{{ det.nama }}</option>
                                                                        </select>
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <select name="lvl2NewCashflow[]">
                                                                            <option value="">Tidak Ada Cashflow</option>
                                                                            <option value="OCF">Operating Cashflow</option>
                                                                            <option value="ICF">Investing Cashflow</option>
                                                                            <option value="FCF">Financial Cashflow</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-intro">
                                            <i class="fa fa-circle-o" style="font-size: 7pt;"></i> &nbsp;Simpan Terlebih Dahulu Data Anda Saat Akan Berpindah Ke Jendela Selanjutnya Atau Sebelumnya. <br>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12" style="border-top: 1px solid #eee; padding: 21px 10px 8px 10px; margin-top: 25px;">
                                            <div class="row">
                                                <div class="col-md-2 form-status" style="padding-top: 10px;">
                                                    
                                                </div>

                                                <div class="col-md-10 text-right">
                                                    <button class="btn btn-primary btn-sm" type="button" @click="tambahLevel2" :disabled="btnDisabled">
                                                        <i class="fa fa-plus"></i> &nbsp;Tambah
                                                    </button>
                                                    <button class="btn btn-info btn-sm" type="button" @click="simpanLevel2" :disabled="btnDisabled">
                                                        Simpan Perubahan
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-default btn-sm" type="button" @click="previousState" :disabled="btnDisabled">
                                                        <i class="fa fa-arrow-left"></i> &nbsp;Sebelumnya
                                                    </button>
                                                    &nbsp;
                                                    {{-- <button class="btn btn-default btn-sm" type="button" @click="nextState">
                                                        Selanjutnya &nbsp;<i class="fa fa-arrow-right"></i>
                                                    </button> --}}
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
        </div>
      </div>
    </div>
@endsection


@section(modulSetting()['extraScripts'])
    
    <script src="{{ asset('modul_keuangan/js/options.js') }}"></script>

    <script src="{{ asset('modul_keuangan/js/vendors/vue_2_x/vue_2_x.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/vue_2_x/components/datatable.component.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/vue_2_x/components/select.component.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/vue_2_x/components/inputmask.component.js') }}"></script>

    <script src="{{ asset('modul_keuangan/js/vendors/wait_me_v_1_1/wait.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/toast/dist/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/inputmask/inputmask.jquery.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/validator/bootstrapValidator.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendors/axios_0_18_0/axios.min.js') }}"></script>

    <script type="text/javascript">

        function register_validator(){
            $('#data-form').bootstrapValidator({
                feedbackIcons : {
                  valid : 'glyphicon glyphicon-ok',
                  invalid : 'glyphicon glyphicon-remove',
                  validating : 'glyphicon glyphicon-refresh'
                },
                fields : {
                  ak_nama : {
                    validators : {
                      notEmpty : {
                        message : 'Nama Akun Tidak Boleh Kosong',
                      }
                    }
                  },

                  ak_nomor : {
                    validators : {
                      notEmpty : {
                        message : 'Nomor Akun Tidak Boleh Kosong',
                      }
                    }
                  },

                  ak_kelompok : {
                    validators : {
                      notEmpty : {
                        message : 'Kelompok Akun Harus Dipilih',
                      }
                    }
                  },

                }
            });
        }

        var app = new Vue({
            el: '#vue-component',
            data: {
                btnDisabled: false,
                onAjaxLoading: false,
                onUpdate: false,

                stateNumber: 0,

                // resource
                    lvl2New : 0,
                    lvlSubclassNew : 0,

                    level1 : 0,
                    level1Subclass : 0,

                    level_1 : [],
                    level_2 : [],
                    levelSubclass : [],

                    data2Print: [],
                    dataSubclassPrint: [],
                    dataSubclassPrintLvl2: [],

                    printLvl2 : [],

            },

            created: function(){
                console.log('Initializing Vue');
            },

            mounted: function(){
                console.log('Vue Ready');
                this.kelompok = this.kelompokParrent;
                register_validator();

                axios.get('{{route('setting.klasifikasi_akun.form_resource')}}')
                          .then((response) => {
                            console.log(response.data);
                            if(response.data.level_2.length > 0){
                                this.level_2 = response.data.level_2;
                            }

                            if(response.data.subclass.length > 0){
                                this.levelSubclass = response.data.subclass;
                                // alert(response.data.subclass[0].id);
                                this.level1SubclassChange(parseInt(response.data.subclass[0].id));
                                
                                
                            }

                            if(response.data.level_1.length > 0){
                                this.level_1 = response.data.level_1;
                                // alert(response.data.subclass[0].id);
                                this.level1Change(parseInt(response.data.level_1[0].id));
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
                nextState: function(){
                    this.stateNumber++;
                    $('.button-ctn').removeClass('active');
                    $('#inv-'+this.stateNumber).addClass('active');
                    this.lvl2New = 0;
                },

                previousState: function(){
                    this.stateNumber--;
                    $('.button-ctn').removeClass('active');
                    $('#inv-'+this.stateNumber).addClass('active');
                    this.lvl2New = 0;
                },

                level1Change: function(e){
                    var alpha = (typeof(e) === 'number') ? e : e.srcElement.value;
                    var dataLevel2 = $.grep(this.level_2, function(n){ return n.lvl1 == alpha });
                    var dataLevelSubclass = $.grep(this.levelSubclass, function(n){ return n.level1 == alpha });
                    
                    this.level1 = alpha;
                    this.data2Print = dataLevel2;
                    this.dataSubclassPrintLvl2 = dataLevelSubclass;
                    this.lvl2New = 0;
                },

                level1SubclassChange: function(e){
                    var alpha = (typeof(e) === 'number') ? e : e.srcElement.value;
                    var dataLevel2 = $.grep(this.levelSubclass, function(n){ return n.level1 == alpha });

                    this.level1Subclass = alpha;
                    this.dataSubclassPrint = dataLevel2;
                    this.lvl2New = 0;
                },

                simpanLevel1: function(e){
                    this.btnDisabled = true;

                    var toast = $.toast({
                        text: "Menyimpan Hierarki Level 1",
                        showHideTransition: 'slide',
                        position: 'bottom-right',
                        icon: 'info',
                        hideAfter: false,
                        showHideTransition: 'slide',
                        allowToastClose: false,
                        stack: false
                    });

                    axios.post('{{ route('setting.klasifikasi_akun.simpan.level_1') }}', $('#data-form-lvl1').serialize())
                                .then((response) => {
                                    // console.log(response.data);
                                    
                                    if(response.data.status == 'berhasil'){
                                        toast.update({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'bottom-right',
                                            icon: 'success',
                                            hideAfter: 5000,
                                            allowToastClose: true,
                                        });

                                        if(response.data.level_1.length > 0){
                                            this.level_1 = response.data.level_1;
                                            this.level1Change(response.data.level_1[0].id);
                                        }

                                        this.status = 'standby';
                                    }else{
                                        toast.update({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'top-right',
                                            icon: 'error',
                                            hideAfter: false,
                                            allowToastClose: true,
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

                simpanLevel2: function(e){
                    this.btnDisabled = true;

                    var toast = $.toast({
                        text: "Menyimpan Hierarki Level 2",
                        showHideTransition: 'slide',
                        position: 'bottom-right',
                        icon: 'info',
                        hideAfter: false,
                        showHideTransition: 'slide',
                        allowToastClose: false,
                        stack: false
                    });

                    axios.post('{{ route('setting.klasifikasi_akun.simpan.level_2') }}', $('#data-form-lvl2').serialize())
                                .then((response) => {
                                    console.log(response.data);
                                    
                                    if(response.data.status == 'berhasil'){
                                        toast.update({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'bottom-right',
                                            icon: 'success',
                                            hideAfter: 5000,
                                            allowToastClose: true,
                                        });

                                        if(response.data.level_2.length > 0){
                                            this.level_2 = response.data.level_2;
                                        }

                                        this.level1Change(parseInt(this.level1));
                                        this.status = 'standby';
                                        this.lvl2New = 0;

                                    }else{
                                        toast.update({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'top-right',
                                            icon: 'error',
                                            hideAfter: false,
                                            allowToastClose: true,
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

                simpanLevelSubclass: function(e){
                    this.btnDisabled = true;

                    var toast = $.toast({
                        text: "Menyimpan Hierarki Subclass",
                        showHideTransition: 'slide',
                        position: 'bottom-right',
                        icon: 'info',
                        hideAfter: false,
                        showHideTransition: 'slide',
                        allowToastClose: false,
                        stack: false
                    });

                    axios.post('{{ route('setting.klasifikasi_akun.simpan.subclass') }}', $('#data-form-lvlsubclass').serialize())
                                .then((response) => {
                                    console.log(response.data);
                                    
                                    if(response.data.status == 'berhasil'){
                                        toast.update({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'bottom-right',
                                            icon: 'success',
                                            hideAfter: 5000,
                                            allowToastClose: true,
                                        });

                                        if(response.data.subclass.length > 0){
                                            this.levelSubclass = response.data.subclass;
                                        }

                                        this.level1SubclassChange(parseInt(this.level1Subclass));
                                        this.status = 'standby';
                                        this.lvl2New = 0;
                                    }else{
                                        toast.update({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'top-right',
                                            icon: 'error',
                                            hideAfter: false,
                                            allowToastClose: true,
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

                hapusHierarki2: function(e){
                    var cfrm = confirm('Apakah Anda Yakin ?')

                    if(cfrm){
                        this.btnDisabled = true;

                        var toast = $.toast({
                            text: "Menghapus Hierarki Level 2",
                            showHideTransition: 'slide',
                            position: 'bottom-right',
                            icon: 'info',
                            hideAfter: false,
                            showHideTransition: 'slide',
                            allowToastClose: false,
                            stack: false
                        });

                        axios.post('{{ route('setting.klasifikasi_akun.hapus.level_2') }}', {id: e, _token: '{{ csrf_token() }}' })
                                .then((response) => {
                                    // console.log(response.data);
                                    
                                    if(response.data.status == 'berhasil'){
                                        toast.update({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'bottom-right',
                                            icon: 'success',
                                            hideAfter: 5000,
                                            allowToastClose: true,
                                        });

                                        if(response.data.level_2.length > 0){
                                            this.level_2 = response.data.level_2;
                                        }

                                        this.level1Change(this.level1);
                                        this.status = 'standby';
                                        this.lvl2New = 0;

                                        console.log(this.level_2);
                                    }else{
                                        toast.update({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'top-right',
                                            icon: 'error',
                                            hideAfter: false,
                                            allowToastClose: true,
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

                hapusHierarkiSubclass: function(e){
                    var cfrm = confirm('Apakah Anda Yakin ?')

                    if(cfrm){
                        this.btnDisabled = true;

                        var toast = $.toast({
                            text: "Menghapus Hierarki Level Subclass",
                            showHideTransition: 'slide',
                            position: 'bottom-right',
                            icon: 'info',
                            hideAfter: false,
                            showHideTransition: 'slide',
                            allowToastClose: false,
                            stack: false
                        });

                        axios.post('{{ route('setting.klasifikasi_akun.hapus.subclass') }}', {id: e, _token: '{{ csrf_token() }}' })
                                .then((response) => {
                                    console.log(response.data);
                                    
                                    if(response.data.status == 'berhasil'){
                                        toast.update({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'bottom-right',
                                            icon: 'success',
                                            hideAfter: 5000,
                                            allowToastClose: true,
                                        });

                                        if(response.data.subclass.length > 0){
                                            this.levelSubclass = response.data.subclass;
                                        }

                                        this.level1SubclassChange(this.level1Subclass);
                                        this.status = 'standby';
                                        this.lvl2New = 0;

                                    }else{
                                        toast.update({
                                            text: response.data.message,
                                            showHideTransition: 'slide',
                                            position: 'top-right',
                                            icon: 'error',
                                            hideAfter: false,
                                            allowToastClose: true,
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

                tambahLevel2: function(e){
                    this.lvl2New++;
                    setTimeout(function() {
                      var elem = document.getElementById('content-lvl-2');
                      elem.scrollTop = elem.scrollHeight;
                    }, 0);
                },

                checkIdlvl2: function(e){
                    var conteks = $('#'+e.srcElement.id);
                    var recentVal = conteks.data('value');
                    var id = this.level1+'.'+conteks.val();
                    
                    var greping = $.grep(this.level_2, function(n){ return n.id == id && n.id.split('.')[1] != recentVal });

                    if(greping.length > 0){
                        $.toast({
                            text: 'ID Yang Anda Inputkan Sudah Digunakan Oleh Data Yang Lain',
                            showHideTransition: 'slide',
                            position: 'bottom-right',
                            icon: 'error',
                            hideAfter: 5000,
                            allowToastClose: true,
                        });

                        conteks.val(recentVal);
                        conteks.focus();
                        return;
                    }

                    if(conteks.val() == ''){
                        conteks.val(recentVal);
                    }
                },

                checkValue: function(e){
                    var conteks = $('#'+e.srcElement.id);
                    var recentVal = conteks.data('value');
                    if(conteks.val() == ''){
                        conteks.val(recentVal);
                    }
                },

                onlyNumber: function(e){
                    var conteks = $('#'+e.srcElement.id);
                    var max = conteks.data('max');
                    var now = parseInt(conteks.val().length) + 1;

                    if(now > max){
                        $.toast({
                            text: 'Panjang ID Yang Dibolehkan Hanya 3 Digit',
                            showHideTransition: 'slide',
                            position: 'bottom-right',
                            icon: 'error',
                            hideAfter: 5000,
                            allowToastClose: true,
                        });

                        e.preventDefault();
                        return false;
                    }

                    if(isNaN(e.key))
                      e.preventDefault()
                    else
                      return true;
                }
            }
        })

    </script>

@endsection