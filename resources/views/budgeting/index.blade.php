@extends('main')
@section('extra_style')
    <style>
        #table_agen td {
            padding: 5px;
        }

        .shadow-box-fancy{
            box-shadow: 4px 10px 16px 0px rgba(0,0,0,0.1);
        }

        .border-bot{
            border-bottom: 1px solid rgba(0,0,0,0.2);
        }
    </style>
@stop
@section('content')

<article class="content animated fadeInLeft">

    <div class="title-block text-primary">
        <h1 class="title"> Budgeting</h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Budgeting</span>
            / <span class="text-primary" style="font-weight: bold;">Manajemen Perencanaan</span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <div class="card">
                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Budgeting </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a class="btn btn-primary" href="{{ route('budgeting.create') }}" id="create"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>

                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                            <div class="col-md-12 mb-5">
                                <div class="row col-md-12">
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>periode</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <input type="text" class="form-control" id="periode" name="periode" autocomplete="off">
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <div class="row col-md-12">
                                    <h3>Pendapatan</h3>
                                    <hr>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-4" style="background-color: #fff;">
                                        <div class="col-md-12 p-0 shadow-box-fancy" >
                                            <div class="col-md-12 p-3 py-4 text-center" style="color:#000;background-color: #d1d1d1;clip-path: polygon(0 0 , 100% 0 ,100% 70% , 0 100% ); ">
                                                <p class="h5">Laba Rugi</p>
                                            </div>
                                            <div class="fluid-container" id="laba_rugi">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="background-color: #fff;">
                                        <div class="col-md-12 p-0 shadow-box-fancy">
                                            <div class="col-md-12 p-3 py-4 text-center" style="color:#fff;background-color: #2b3f87;clip-path: polygon(0 0 , 100% 0 ,100% 70% , 0 100% ); ">
                                                <p class="h5">Budgeting</p>
                                            </div>
                                            <div class="fluid-container" id="budget">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="background-color: #fff;">
                                        <div class="col-md-12 p-0 shadow-box-fancy">
                                            <div class="col-md-12 p-3 py-4 text-center">
                                                <p class="h5">Breakdown</p>
                                            </div>
                                            <div class="fluid-container" id="breakdown">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <br>
                                </div>
                                    <div class="row col-md-12">
                                        <h3>Beban</h3>
                                        <hr>
                                    </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-4" style="background-color: #fff;">
                                        <div class="col-md-12 p-0 shadow-box-fancy" >
                                            <div class="col-md-12 p-3 py-4 text-center" style="color:#000;background-color: #d1d1d1;clip-path: polygon(0 0 , 100% 0 ,100% 70% , 0 100% ); ">
                                                <p class="h5">Laba Rugi</p>
                                            </div>
                                            <div class="fluid-container" id="laba_rugi_beban">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 " style="background-color: #fff;">
                                        <div class="col-md-12 p-0 shadow-box-fancy">
                                            <div class="col-md-12 p-3 py-4 text-center" style="color:#fff;background-color: #2b3f87;clip-path: polygon(0 0 , 100% 0 ,100% 70% , 0 100% ); ">
                                                <p class="h5">Budgeting</p>
                                            </div>
                                            <div class="fluid-container" id="budget_beban">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="background-color: #fff;">
                                        <div class="col-md-12 p-0 shadow-box-fancy">
                                            <div class="col-md-12 p-3 py-4 text-center">
                                                <p class="h5">Breakdown</p>
                                            </div>
                                            <div class="fluid-container" id="breakdown_beban">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

            </div>

        </div>

    </section>

</article>

@endsection
@section('extra_script')
    <script type="text/javascript">
        var layout ='';
        var saldo = '';
        var breakd ='';
        var compare = 0;
        var last = '';
        var month_years = new Date();

        $(document).ready(function() {
            month_years = new Date(month_years.getFullYear(), month_years.getMonth());
            // set month picker
            $("#periode").datepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true,
            });
            $('#periode').datepicker('setDate', month_years);
            $('#periode').datepicker().on('changeDate', function (ev) {
                ajax_output();
            });
            ajax_output();

        });

        function ajax_output()
        {
            periode = $('#periode').val();
            $.ajax({
                url : '{{ route("budgeting.getAkunPendapatan") }}',
                type : 'get',
                data: {
                    periode: periode
                },
                beforeSend : function (){
                    loadingShow();
                },
                success : function(response) {
                    let resp = response.data;
                    let layoutPend = '';
                    let layoutBeban = '';
                    let layoutPendBudget = '';
                    let layoutBebanBudget = '';
                    let layoutPendBreak = '';
                    let layoutBebanBreak = '';
                    // display budgeting and akun
                    if (resp.length > 0) {
                        $.each(resp, function (j, data) {
                            if (data.subclass.length <= 0) { return true };
                            $.each(data.subclass, function (k, subclass) {
                                if (subclass.level2.length <= 0) { return true };
                                $.each(subclass.level2, function (l, level2) {
                                    if (level2.akun.length <= 0) { return true };
                                    $.each(level2.akun, function (m, akun) {
                                        let status;
                                        if (akun.ak_nomor.substring(0,1) != '4' && akun.ak_nomor.substring(0,1) != '8') {
                                            // set color for breakdown
                                            if (akun.diff_value <= 0) {
                                                status = 'green';
                                            }
                                            else {
                                                status = 'red';
                                            }
                                            // akun
                                            layoutBeban += '<div class="col-md-12 p-2 border-bot mt-2">\n' +
                                                '<div class="row">\n' +
                                                '<p class="col-md-6 col-sm-6">'+ akun.ak_nomor +' - '+ akun.ak_nama +'</p>\n' +
                                                '<p class="col-md-6 rupiah data">'+ parseInt(akun.saldo_akhir) +'</p>\n' +
                                                '</div>\n' +
                                                '</div>';
                                            // budgeting
                                            let name = '<p class="col-md-6 col-sm-6">'+ akun.ak_nomor +' - '+ akun.ak_nama +'</p>\n';
                                            let value = '<p class="col-md-6 rupiah data">'+ parseInt(akun.budgeting_value) +'</p>\n';
                                            layoutBebanBudget += '<div class="col-md-12 p-2 border-bot mt-2">\n<div class="row">\n'+ name + value +'</div>\n</div>';
                                            layoutBebanBreak += '<div class="col-md-12 p-2 border-bot mt-2" style="background-color:'+ status +';color:#fff">\n' +
                                                '<div class="row">\n' +
                                                '<p class="col-md-6">'+ akun.ak_nomor +' - '+ akun.ak_nama +'</p>\n' +
                                                '<p class="col-md-6 rupiah">'+ akun.diff_value +'</p>\n' +
                                                '</div>\n' +
                                                '</div>';
                                        }
                                        else {
                                            // set color for breakdown
                                            if (akun.diff_value >= 0) {
                                                status = 'green';
                                            }
                                            else {
                                                status = 'red';
                                            }
                                            // akun
                                            layoutPend += '<div class="col-md-12 p-2 border-bot mt-2">\n' +
                                                '<div class="row">\n' +
                                                '<p class="col-md-6 col-sm-6">'+ akun.ak_nomor +' - '+ akun.ak_nama +'</p>\n' +
                                                '<p class="col-md-6 rupiah data">'+ parseInt(akun.saldo_akhir) +'</p>\n' +
                                                '</div>\n' +
                                                '</div>';
                                            // budgeting
                                            let name = '<p class="col-md-6 col-sm-6">'+ akun.ak_nomor +' - '+ akun.ak_nama +'</p>\n';
                                            let value = '<p class="col-md-6 rupiah data">'+ parseInt(akun.budgeting_value) +'</p>\n';
                                            layoutPendBudget += '<div class="col-md-12 p-2 border-bot mt-2">\n<div class="row">\n'+ name + value +'</div>\n</div>';
                                            layoutPendBreak += '<div class="col-md-12 p-2 border-bot mt-2" style="background-color:'+status+';color:#fff">\n' +
                                                '<div class="row">\n' +
                                                '<p class="col-md-6">'+ akun.ak_nomor +' - '+ akun.ak_nama +'</p>\n' +
                                                '<p class="col-md-6 rupiah">'+ akun.diff_value +'</p>\n' +
                                                '</div>\n' +
                                                '</div>';
                                        }
                                    });
                                });
                            });
                        });
                    }

                    $('#laba_rugi_beban').html(layoutBeban);
                    $('#laba_rugi').html(layoutPend);
                    $('#budget_beban').html(layoutBebanBudget);
                    $('#budget').html(layoutPendBudget);
                    $('#breakdown_beban').html(layoutBebanBreak);
                    $('#breakdown').html(layoutPendBreak);

                    $('.rupiah').inputmask("numeric", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 0,
                        autoGroup: true,
                        prefix: ' Rp ', //Space after $, this will not truncate the first character.
                        rightAlign: true,
                        autoUnmask: true,
                        nullable: false,
                        allowMinus: true
                        // unmaskAsNumber: true,
                    });
                },
                error: function (error) {
                    messageFailed('Error', 'Terjadi kesalahan : ' + error);
                },
                complete: function () {
                    loadingHide();
                }
            })
        }

    </script>
@endsection
