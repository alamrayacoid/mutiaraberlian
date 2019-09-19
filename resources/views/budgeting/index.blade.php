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
                                <div class="row justify-content-center">
                                {{--  box widget 1--}}
                                    <div class="col-md-4" style="background-color: #fff;">
                                        <div class="col-md-12 p-0 shadow-box-fancy" >
                                            <div class="col-md-12 p-3 py-4 text-center" style="color:#000;background-color: #d1d1d1;clip-path: polygon(0 0 , 100% 0 ,100% 70% , 0 100% ); ">
                                                <p class="h3">Laba Rugi</p>
                                            </div>
                                            <div class="fluid-container" id="laba_rugi">

                                            </div>
                                        </div>
                                    </div>
                                {{--   end box widget 1--}}

                                    {{--  box widget 1--}}
                                    <div class="col-md-7 " style="background-color: #fff;">
                                        <div class="row">
                                            <div class="col-md-6 p-0 shadow-box-fancy">
                                                <div class="col-md-12 p-3 py-4 text-center" style="color:#fff;background-color: #2b3f87;clip-path: polygon(0 0 , 100% 0 ,100% 70% , 0 100% ); ">
                                                    <p class="h3">Budgeting</p>
                                                </div>
                                                <div class="fluid-container" id="budget">

                                                </div>
                                            </div>
                                            {{--  start new widget breakdown--}}
                                            <div class="col-md-6 p-0 shadow-box-fancy">
                                                <div class="col-md-12 p-3 py-4 text-center">
                                                    <p class="h3">Breakdown</p>
                                                </div>
                                                <div class="fluid-container" id="breakdown">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{--   end box widget 1--}}
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

    function ajax_output(url,type,data)
    {
        $.ajax({
            url : url,
            type : type,
            data : data,
            success : function(get){
                var par = JSON.parse(get);
                var layout ='';
                var breakd ='';
                for(var i =0;i<(par['data']).length;i++){
                    for(var j =0;j<(par['data'][i]['subclass']).length;j++){
                        for(var k =0;k<(par['data'][i]['subclass'][j]['level2']).length;k++){
                            for(var  n=0;n<(par['data'][i]['subclass'][j]['level2'][k]['akun']).length;n++){
                                for(var o = 0;o<(par['data'][i]['subclass'][j]['level2'][k]['akun']).length;o++){
                                    if(parseFloat(par['data'][i]['subclass'][j]['level2'][k]['akun'][o].saldo_akhir) > 0){
                                        var status = 'green';
                                    }else{
                                        var status = 'red';
                                    }
                                    layout += '<div class="col-md-12 p-2 border-bot mt-2">\n' +
                                        '<div class="row">\n' +
                                        '<p class="col-md-6">'+ par['data'][i]['subclass'][j]['level2'][k]['akun'][o].ak_nomor+' - '+ par['data'][i]['subclass'][j]['level2'][k]['akun'][o].ak_nama +'</p>\n' +
                                        '<p class="col-md-6 text-right"> Rp. '+ par['data'][i]['subclass'][j]['level2'][k]['akun'][o].saldo_akhir +'</p>\n' +
                                        '</div>\n' +
                                        '</div>';

                                    breakd += '<div class="col-md-12 p-2 border-bot mt-2" style="background-color:'+status+';color:#fff">\n' +
                                        '<div class="row">\n' +
                                        '<p class="col-md-6">'+ par['data'][i]['subclass'][j]['level2'][k]['akun'][o].ak_nomor+' - '+ par['data'][i]['subclass'][j]['level2'][k]['akun'][o].ak_nama +'</p>\n' +
                                        '<p class="col-md-6 text-right"> Rp. '+ par['data'][i]['subclass'][j]['level2'][k]['akun'][o].saldo_akhir +'</p>\n' +
                                        '</div>\n' +
                                        '</div>';
                                }
                            }
                        }
                    }
                }
                $('#laba_rugi').html(layout);
                $('#budget').html(layout);
                $('#breakdown').html(breakd);
            }
        })
    }

    $(document).ready(function() {
        ajax_output('{{route("budgeting.data_lr")}}','post',{'_token' : '{{csrf_token()}}'});
    });

</script>
@endsection
