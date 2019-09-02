@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Aktivitas SDM </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Master Data Utama</span> /
                <span class="text-primary" style="font-weight: bold;">Aktivitas SDM</span>
                / <span>Kelola Kinerja SDM</span>
                / <span>KPI Pegawai</span>
                / <span class="text-primary" style="font-weight: bold;"> Edit Data KPI Pegawai</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Edit Data KPI Pegawai </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{ route('kinerjasdm.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-block">
                            <section>
                                <form action="" id="form-edit">
                                    @csrf

                                    <input type="hidden" name="employee" value="{{$kpiemp_first->ke_employee}}">
                                <div id="section-kpi">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-6 col-xs-12">
                                                    <label>Nama Pegawai</label>
                                                </div>

                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <select class="form-control form-control-sm select2" id="m_employee" name="ke_employee">
                                                            <option value="" disabled="">Pilih Pegawai</option>
                                                            @foreach($employee as $key => $employee)
                                                            <option value="{{$employee->e_id}}" @if($employee->e_id == $kpiemp[0]->ke_employee) selected="" @endif>{{$employee->e_name}} ({{$employee->d_name}} / {{$employee->j_name}})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div id="private-section">
                                                {{--
                                                <div class="row">
                                                    <div class="col-6 col-md-6 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-4 col-md-4 col-sm-6 col-xs-12">
                                                                <label>Indikator</label>
                                                            </div>

                                                            <div class="col-6 col-md-6 col-sm-4 col-xs-12">
                                                                <div class="form-group">
                                                                    <select class="form-control form-control-sm select2 indicator" name="indicator[]" data-last="null">
                                                                        <option value="" disabled="">Pilih Indikator</option>
                                                                        @foreach($kpi as $key => $kpi1)
                                                                        <option value="{{$kpi1->k_id}}" @if($kpi1->k_id == $kpiemp_first->ke_kpi) selected="" @endif>{{$kpi1->k_indicator}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-2 col-md-2 align-items-center">
                                                                <label for="">Bobot</label>
                                                            </div>
                                                            <div class="offset-md-4 col-8 col-md-8 col-sm-6 col-xs-12 mb-1 messageError d-none" style="margin-top: -18px;">
                                                                <span class="text-danger" style="font-size: 12px;">Indikator sudah terpilih</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-6 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <div class="form-group">
                                                                    <input type="text" name="bobot[]" class="form-control form-control-sm text-right" value="{{number_format($kpiemp_first->ke_weight, 2)}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-2">
                                                                <label for="">Target</label>
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="form-group">
                                                                    <input type="text" name="target[]" class="form-control form-control-sm text-right" value="{{number_format($kpiemp_first->ke_target, 2)}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-2" style="height: 30px;display: flex; align-items: center;">
                                                                <button type="button" class="btn btn-block btn-primary btn-sm rounded btn-tambahp align-self-center idx-btn"><i class="fa fa-plus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                --}}
                                                @foreach($kpiemp as $idx => $kpiemp)
                                                <div class="row section2">
                                                    <div class="col-6 col-md-6 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-4 col-md-4 col-sm-6 col-xs-12">
                                                                <label style="display: none;">~</label>
                                                            </div>
                                                            <div class="col-6 col-md-6 col-sm-4 col-xs-12">
                                                                <div class="form-group">
                                                                    <select class="form-control form-control-sm select2 indicator" id="" name="indicator[]" data-last="{{$kpiemp->ke_kpi}}">
                                                                        <option value="" disabled>Pilih Indikator</option>
                                                                        @foreach($kpi as $key => $kpi2)
                                                                        <option value="{{$kpi2->k_id}}" @if($kpi2->k_id == $kpiemp->ke_kpi) selected="" @endif>{{$kpi2->k_indicator}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-2 col-md-2 col-sm-2">
                                                                <label for="">Bobot</label>
                                                            </div>
                                                            <div class="offset-md-4 col-8 col-md-8 col-sm-6 col-xs-12 mb-1 messageError d-none" style="margin-top: -18px;">
                                                                <span class="text-danger" style="font-size: 12px;">Indikator sudah terpilih</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-6 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <div class="form-group">
                                                                    <input type="text" name="bobot[]" class="form-control form-control-sm text-right" value="{{number_format($kpiemp->ke_weight, 2)}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-2">
                                                                <label for="">Target</label>
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="form-group">
                                                                    <input type="text" name="target[]" class="form-control form-control-sm text-right" value="{{number_format($kpiemp->ke_target, 2)}}">
                                                                </div>
                                                            </div>
                                                            @if($idx == 0)
                                                                <div class="col-2 align-items-center" style="height: 30px;display: flex; align-items: center;">
                                                                    <button type="button" class="btn btn-block btn-primary btn-sm rounded btn-tambahp align-self-center idx-btn"><i class="fa fa-plus"></i></button>
                                                                </div>
                                                            @else
                                                                <div class="col-2" style="height: 30px;display: flex; align-items: center;">
                                                                    <button type="button" class="btn btn-block btn-danger btn-sm rounded btn-hapus idx-btn btn-del"><i class="fa fa-trash"></i></button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </section>
                        </div>
                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary btn-submit">Simpan</button>
                        </div>
                    </div>

                </div>

            </div>

        </section>

    </article>

@endsection

@section('extra_script')
<script type="text/javascript">
    var index = null;
    var keranjang = [];
    $(document).ready(function(){
        for (var i = 0; i < $('.indicator').length; i++) {
            var indicat = $('.indicator')[i].value
            keranjang.push(indicat);
        }
    });

    $('#private-section').on('change', '.indicator', function(){
        var conteks = $(this);
        index = $('.indicator').index(this);
        var indi = $('.indicator').eq(index).val();
        var lastValue = conteks.data('last');
        var exist = keranjang.findIndex(e => e == indi);
        var dataIndexlama = keranjang.findIndex(e => e == lastValue);

        if(exist < 0){
            if(dataIndexlama < 0)
                keranjang.push(indi);
            else
                keranjang[dataIndexlama] = indi;

            conteks.data('last', indi);
            $('.messageError').eq(index).addClass('d-none');
            $('.btn-submit').removeAttr('disabled')
        }else{
            if (dataIndexlama >= 0 )
                keranjang.splice(dataIndexlama, 1);

            $('.messageError').eq(index).removeClass('d-none');
            $('.btn-submit').attr('disabled', '')
        }
    })

    $('.btn-tambahp').on('click',function(){

        $('#private-section')
            .append(
                '<div class="row section2">'+
                    '<div class="col-6 col-md-6 col-sm-12">'+
                        '<div class="row">'+
                            '<div class="col-4 col-md-4 col-sm-6 col-xs-12">'+
                                '<label style="display: none;">~</label>'+
                            '</div>'+
                            '<div class="col-6 col-md-6 col-sm-4 col-xs-12">'+
                                '<div class="form-group">'+
                                    '<select class="form-control form-control-sm select2 indicator" id="" name="indicator[]" data-last="null">'+
                                        '<option value="" selected disabled>Pilih Indikator</option>'+
                                    '</select>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-2 col-md-2 col-sm-2">'+
                                '<label for="">Bobot</label>'+
                            '</div>'+
                            '<div class="offset-md-4 col-8 col-md-8 col-sm-6 col-xs-12 mb-1 messageError d-none" style="margin-top: -18px;">'+
                                '<span class="text-danger" style="font-size: 12px;">Indikator sudah terpilih</span>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-6 col-md-6 col-sm-12">'+
                        '<div class="row">'+
                            '<div class="col-4">'+
                                '<div class="form-group">'+
                                    '<input type="text" name="bobot[]" class="form-control form-control-sm digits">'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-2">'+
                                '<label for="">Target</label>'+
                            '</div>'+
                            '<div class="col-4">'+
                                '<div class="form-group">'+
                                    '<input type="text" name="target[]" class="form-control form-control-sm digits">'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-2" style="height: 30px;display: flex; align-items: center;">'+
                                '<button type="button" class="btn btn-block btn-danger btn-sm rounded btn-hapus idx-btn btn-del"><i class="fa fa-trash"></i></button>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'
            );

        $('.select2').select2({            
            theme: "bootstrap",
            dropdownAutoWidth: true,
            width: '100%'
        });

        //mask digits
        $('.digits').inputmask("currency", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            prefix: '', //Space after $, this will not truncate the first character.
            rightAlign: true,
            autoUnmask: true,
            nullable: false,
            // unmaskAsNumber: true,
        });

        index = $('.indicator').index(this);
        getIndicator(index);

        $('.btn-del').on('click', function(){
            var idx = $('.idx-btn').index(this);
            var isi = $('.indicator').eq(idx).val();

            var findArray = keranjang.findIndex(e => e == isi)
            if (findArray >= 0) {
                keranjang.splice(findArray, 1)
            }
            $(this).parents('.section2').remove()
        })
    });

    $('.btn-del').on('click', function(){
        var idx = $('.idx-btn').index(this);
        var isi = $('.indicator').eq(idx).val();

        var findArray = keranjang.findIndex(e => e == isi)
        if (findArray >= 0) {
            keranjang.splice(findArray, 1)
        }
        $(this).parents('.section2').remove()
    })

    function getIndicator(index) {
        axios.get('{{url('/sdm/kinerjasdm/kpi-pegawai/get-kpi-indikator')}}')
        .then(function(resp) {
            var data = resp.data.data
            $.each(data, function(key, val) {
                $('.indicator').eq(index).append('<option value="'+val.k_id+'">'+val.k_indicator+'</option>');
            })
        })
    }

    $('.btn-submit').on('click', function() {
        var datas = $('#form-edit').serialize();

        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Pesan!',
            content: 'Apakah anda yakin ingin mengubah data ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function() {
                        loadingShow();
                        axios.post('{{url('/sdm/kinerjasdm/kpi-pegawai/update-kpi-pegawai')}}', datas)
                        .then(function(resp){
                            if (resp.data.status == 'success') {
                                loadingHide();
                                messageSuccess('Berhasil!', 'Data berhasil diperbarui!');
                                setTimeout(function(){
                                    window.location.href = "{{url('/sdm/kinerjasdm/index')}}"
                                }, 1000)                
                            }else{
                                loadingHide();
                                messageFailed('Gagal!', 'Data gagal diperbarui!');
                            }
                        })
                        .catch(function(error) {

                        })
                    }
                },
                cancel: {
                    text: 'Tidak',
                    action: function(response) {
                        loadingHide();
                        messageWarning('Peringatan', 'Anda telah membatalkan!');
                    }
                }
            }
        })        
    })
</script>
@endsection
