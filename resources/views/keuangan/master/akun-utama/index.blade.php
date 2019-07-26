@extends('main')

@section('extra_style')

     <link rel="stylesheet" type="text/css" href="{{asset('modul_keuangan/js/vendors/vue/components/datatable-v2/style.css')}}">
     <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/toast/dist/jquery.toast.min.css') }}">
@endsection

@section('content')
    <article class="content" id="vue-element">
        <div class="title-block text-primary">
            <h1 class="title"> Master COA Utama</h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{ url('/home') }}">Home</a>
                / <span>Master Data Utama</span>
                / <span class="text-primary" style="font-weight: bold;">Master COA keuangan</span>
            </p>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Data COA Utama </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a class="btn btn-primary" id="e-create" style="color: white" href="{{ Route('keuangan.akun_utama.create') }}">
                                    <i class="fa fa-plus"></i>&nbsp;Tambah Data
                                </a>
                            </div>
                        </div>
                        <div class="card-block">
                            <section>
                                <!-- <div class="table-responsive"> -->
                                    <vue-datatable-v2 :config="dataPegawai"></vue-datatable-v2>
                                <!-- </div> -->
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
@endsection

@section('extra_script')
    
    <!-- Vue Components -->
    <script src="{{asset('modul_keuangan/js/vendors/vue/vue.js')}}"></script>
    <script src="{{asset('modul_keuangan/js/vendors/vue/components/datatable-v2/datatable-v2.component.js')}}"></script>

    <!-- Jquery Plugin For Keuangan -->
    <script src="{{ asset('modul_keuangan/js/vendors/toast/dist/jquery.toast.min.js') }}"></script>

    <script type="text/javascript">
        
        var vue = new Vue({
            el: '#vue-element',
            data: {
                dataPegawai: {
                    feeder: {
                        column: [
                            {text: 'Kode COA', conteks: 'au_nomor', childStyle: 'text-align: center', style: 'width: 12%'},
                            {text: 'Nama COA', conteks: 'au_nama', childStyle: 'text-align: center', style: 'width: 25%'},
                            {text: 'Posisi', conteks: 'au_posisi', childStyle: 'text-align: center', style: 'width: 15%', overide: function(e){
                                if(e == "D")
                                    return "Debet";
                                else
                                    return "Kredit";
                            }},
                            {text: 'Saldo Pembukaan', conteks: 'ak_opening', childStyle: 'text-align: center', style: 'width: 15%'},
                        ],

                        data: []
                    },

                    addition: {
                        columnNumber: {
                            show: false,
                            width: '5%'
                        },

                        columnButton: {
                            show: false,
                            width: "15%",
                            content: [
                                {
                                    html: '<button type="button" class="btn btn-info btn-sm"><i class="fa fa-folder-open"></i> Lihat Data</button>',
                                    onClick: function(dataClicked){
                                        alert('you clicked me')
                                    }
                                },
                            ]
                        },
                    },

                    config: {
                        dataPerPage: 10,
                        buttonHelper: [
                            // {
                            //     text: '<i class="fa fa-print"></i> &nbsp; Print',
                            //     onClick: function(evt){
                            //         $.toast({
                            //             text: "Mencetak Data",
                            //             showHideTransition: 'slide',
                            //             position: 'top-right',
                            //             icon: 'info',
                            //             hideAfter: 5000,
                            //             showHideTransition: 'slide',
                            //             allowToastClose: false,
                            //             stack: false
                            //         });


                            //     }
                            // },
                        ]
                    }
                },
            },
            mounted: function(){
                axios.get("{{ Route('keuangan.akun_utama.grap') }}")
                        .then((response) => {
                            this.downloadingResource = false;

                            if(response.data.akun.length){
                                this.dataPegawai.feeder.data = response.data.akun;
                            }

                        }).catch((e) => {
                            alert('Sytem Bermasalah..')
                            console.log('System Bermasalah '+e);
                        })
            }
        });

    </script>
@endsection
