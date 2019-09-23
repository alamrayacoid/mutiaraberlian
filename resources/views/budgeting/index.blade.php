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

        function ajax_output(url,type,data)
        {
            $.ajax({
                url : url,
                type : type,
                data : data,
                success : function(response) {
                    resp = response.data;
                    let layoutPend = '';
                    let layoutBeban = '';

                    $.each(resp, function (j, data) {
                        if (data.subclass.length <= 0) { return true };
                        $.each(data.subclass, function (k, subclass) {
                            if (subclass.level2.length <= 0) { return true };
                            $.each(subclass.level2, function (l, level2) {
                                if (level2.akun.length <= 0) { return true };
                                $.each(level2.akun, function (m, akun) {
                                    if (akun.ak_nomor.substring(0,1) != '4' && akun.ak_nomor.substring(0,1) != '8') {
                                        layoutBeban += '<div class="col-md-12 p-2 border-bot mt-2">\n' +
                                        '<div class="row">\n' +
                                        '<p class="col-md-6 col-sm-6">'+ akun.ak_nomor +' - '+ akun.ak_nama +'</p>\n' +
                                        '<p class="col-md-6 rupiah data">'+ parseInt(akun.saldo_akhir) +'</p>\n' +
                                        '</div>\n' +
                                        '</div>';
                                    }
                                    else {
                                        layoutPend += '<div class="col-md-12 p-2 border-bot mt-2">\n' +
                                        '<div class="row">\n' +
                                        '<p class="col-md-6 col-sm-6">'+ akun.ak_nomor +' - '+ akun.ak_nama +'</p>\n' +
                                        '<p class="col-md-6 rupiah data">'+ parseInt(akun.saldo_akhir) +'</p>\n' +
                                        '</div>\n' +
                                        '</div>';
                                    }
                                });
                            });
                        });
                    });

                    $('#laba_rugi').html(layoutPend);
                    $('#laba_rugi_beban').html(layoutBeban);
                    ajax_output2('{{route("budgeting.data_budget")}}','post',{'_token' : '{{csrf_token()}}'});

                }
            })
        }

        function ajax_output2(url,type,data)
        {
            var breakdPend ='';
            var breakdBeban ='';
            var layoutPend = '';
            var layoutBeban= '';
            $.ajax({
                url : url,
                type : type,
                data : data,
                success : function(get) {
                    for (var i = 0; i < (get['data']).length; i++) {
                        if (get['data'][i].ak_nomor.substring(0,1) != '4' && get['data'][i].ak_nomor.substring(0,1) != '8') {
                            layoutBeban += '<div class="col-md-12 p-2 border-bot mt-2">\n' +
                                '<div class="row">\n' +
                                '<p class="col-md-6">' + get['data'][i].ak_nomor + ' - ' + get['data'][i].ak_nama + '</p>\n' +
                                '<p class="col-md-6 rupiah budget">' + parseInt(get['data'][i].b_value) + '</p>\n' +
                                '</div>\n' +
                                '</div>';
                        }
                        else {
                            layoutPend += '<div class="col-md-12 p-2 border-bot mt-2">\n' +
                                '<div class="row">\n' +
                                '<p class="col-md-6">' + get['data'][i].ak_nomor + ' - ' + get['data'][i].ak_nama + '</p>\n' +
                                '<p class="col-md-6 rupiah budget">' + parseInt(get['data'][i].b_value) + '</p>\n' +
                                '</div>\n' +
                                '</div>';
                        }
                    }

                    for (var i = 0; i < (get['count']).length; i++) {
                        if(get['count'][i].count > 0){
                            var status = 'green';
                        }else{
                            var status = 'red';
                        }
                        if (get['count'][i].ak_nomor.substring(0,1) != '4' && get['count'][i].ak_nomor.substring(0,1) != '8') {
                            breakdBeban += '<div class="col-md-12 p-2 border-bot mt-2" style="background-color:'+status+';color:#fff">\n' +
                                '<div class="row">\n' +
                                '<p class="col-md-6">'+ get['count'][i].ak_nomor +' - '+ get['count'][i].ak_nama +'</p>\n' +
                                '<p class="col-md-6 rupiah">'+ parseInt(get['count'][i].count) +'</p>\n' +
                                '</div>\n' +
                                '</div>';
                        }
                        else {
                            breakdPend += '<div class="col-md-12 p-2 border-bot mt-2" style="background-color:'+status+';color:#fff">\n' +
                                '<div class="row">\n' +
                                '<p class="col-md-6">'+ get['count'][i].ak_nomor +' - '+ get['count'][i].ak_nama +'</p>\n' +
                                '<p class="col-md-6 rupiah">'+ parseInt(get['count'][i].count) +'</p>\n' +
                                '</div>\n' +
                                '</div>';
                        }
                    }

                    $('#budget_beban').html(layoutBeban);
                    $('#budget').html(layoutPend);
                    $('#breakdown_beban').html(breakdBeban);
                    $('#breakdown').html(breakdPend);

                    $('.rupiah').inputmask("numeric", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 0,
                        autoGroup: true,
                        prefix: ' Rp ', //Space after $, this will not truncate the first character.
                        rightAlign: true,
                        autoUnmask: true,
                        nullable: false,
                        allowMinus: true,
                        // unmaskAsNumber: true,
                    });
                }
            })
        }

        $(document).ready(function() {
            ajax_output('{{route("budgeting.getAkunPendapatan")}}','get',{'_token' : '{{csrf_token()}}'});

        });

    </script>
@endsection
