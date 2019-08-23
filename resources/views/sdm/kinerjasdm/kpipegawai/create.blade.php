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
                / <span class="text-primary" style="font-weight: bold;"> Tambah Data KPI Pegawai</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Tambah Data KPI Pegawai </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{ route('kinerjasdm.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-block">
                            <section>
                                <form action="" id="form-add">
                                    @csrf
                                <div id="section-kpi">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-4 col-xs-12">
                                                    <label>Nama Pegawai</label>
                                                </div>

                                                <div class="col-md-4 col-sm-8 col-xs-12">
                                                    <div class="form-group">
                                                        <select class="form-control form-control-sm select2 employee" id="m_employee" name="employee" onchange="getDataIndikatorKPIPegawai()">
                                                            <option value="" selected="" disabled="">Pilih Pegawai</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="private-section">
                                                <!-- <div class="row">
                                                    <div class="col-6 col-md-6 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-4 col-md-4 col-sm-6 col-xs-12">
                                                                <label>Indikator</label>
                                                            </div>

                                                            <div class="col-6 col-md-6 col-sm-4 col-xs-12">
                                                                <div class="form-group">
                                                                    <select class="form-control form-control-sm select2 indicator" name="indicator[]" data-last="null">
                                                                        <option value="" selected="" disabled="">Pilih Indikator</option>
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
                                                                    <input type="text" name="bobot[]" class="form-control form-control-sm digits">
                                                                </div>
                                                            </div>
                                                            <div class="col-2">
                                                                <label for="">Target</label>
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="form-group">
                                                                    <input type="text" name="target[]" class="form-control form-control-sm digits">
                                                                </div>
                                                            </div>
                                                            <div class="col-2 align-items-center" style="height: 30px;display: flex; align-items: center;">
                                                                <button type="button" class="btn btn-block btn-primary btn-sm rounded btn-tambahp align-self-center idx-btn"><i class="fa fa-plus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> -->

                                                <div id="kolom"></div>
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
        axios.get('{{url('/sdm/kinerjasdm/kpi-pegawai/get-kpi-employee')}}')
        .then(function(resp) {
            var data = resp.data.data
            $.each(data, function(key, val) {
                $('#m_employee').append('<option value="'+val.e_id+'">'+val.e_name+' ('+val.d_name+'/'+val.j_name+')</option>')
            })
        })

        index = $('.indicator').index(this);
        getIndicator(index);
    });

    // $('#section-kpi').on('change', '.employee', function(){
    //     var conteks = $(this);
    // });

    $('#private-section').on('change', '.indicator', function(){
        var conteks = $(this);
        index = $('.indicator').index(this); // data indikator ke berapa setelah ditekan tombol +
        var indi = $('.indicator').eq(index).val(); // data dari select indikator setelah dipilih
        var lastValue = conteks.data('last'); // data terakhir dari select indikator sebelum pilihan idikator diganti
        var exist = keranjang.findIndex(e => e == indi); // data dari select indikator jika data belum dipilih maka bernilai -1, jika sudah dipilih maka bernilai 0
        var dataIndexlama = keranjang.findIndex(e => e == lastValue); // data dari select jika select pertama dipilih maka bernilai -1, jika sebelumnya select pertama dipilih lagi maka bernilai 0, jika select kedua dipilih dan ternyata datanya belum dipilih select pertama maka bernilai -1, jika select kedua dipilih dan ternyata datanya sudah dipilih oleh select pertama maka akan bernilai 1
        // console.log(dataIndexlama);

        if(exist < 0){ // ketika indikator belum dipilih
            if(dataIndexlama < 0){ // ketika indikator di select kedua belum dipilih oleh select pertama
               keranjang.push(indi);
                $('.btn-submit').removeAttr('disabled', '')
            }else{
                keranjang[dataIndexlama] = indi;
            }

            conteks.data('last', indi);
            $('.messageError').eq(index).addClass('d-none');
        }else{
            if (dataIndexlama >= 0 ){
                keranjang.splice(dataIndexlama, 1);
            }

            $('.messageError').eq(index).removeClass('d-none');
            $('.btn-submit').attr('disabled', '')
        }
        $('.btn-del').on('click', function(){
            $('.btn-submit').removeAttr('disabled', '')    
        });
    })

    function getDataIndikatorKPIPegawai() {
        var data = $('#m_employee').val();
        // console.log(emp_id);
        $.ajax({
            url: '{{ route("kpipegawai.getIndikatorKpiPegawai") }}',
            type: "post",
            data: {
                    "_token": "{{ csrf_token() }}",
                    "data": data
            },
            success: function (resp) {
                // console.log(resp);
                // console.log(resp.data.length);
                            
                if (resp.data.length > 0) { // ketika pegawai memiliki indikator

                    $('.section2').remove();
                    
                    $.each(resp.data, function(key, val) {
                        if (key == 0) { // untuk data pertama
                            $('#kolom')
                                .before(
                                    '<div class="row section2">'+
                                        '<div class="col-6 col-md-6 col-sm-12">'+
                                            '<div class="row">'+
                                                '<div class="col-4 col-md-4 col-sm-6 col-xs-12">'+
                                                    '<label>Indikator</label>'+
                                                '</div>'+

                                                '<div class="col-6 col-md-6 col-sm-4 col-xs-12">'+
                                                    '<div class="form-group">'+
                                                        '<input type="hidden" class="form-control form-control-sm indicator" id="" name="indicator[]" value="'+ val.k_id +'">'+
                                                        '<input type="text" class="form-control form-control-sm indicator" id="" name="indicator[]" value="'+ val.k_indicator +'" disabled>'+
                                                    '</div>'+
                                                '</div>'+

                                                '<div class="col-2 col-md-2 col-sm-2">'+
                                                    '<label for="">Bobot</label>'+
                                                '</div>'+
                                                // '<div class="offset-md-4 col-8 col-md-8 col-sm-6 col-xs-12 mb-1 messageError d-none" style="margin-top: -18px;">'+
                                                //     '<span class="text-danger" style="font-size: 12px;">Indikator sudah terpilih</span>'+
                                                // '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-6 col-md-6 col-sm-12">'+
                                            '<div class="row">'+
                                                '<div class="col-4">'+
                                                    '<div class="form-group">'+
                                                        '<input type="text" class="form-control form-control-sm" name="bobot[]" value="'+ val.ke_weight +'">'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-2">'+
                                                    '<label for="">Target</label>'+
                                                '</div>'+
                                                '<div class="col-4">'+
                                                    '<div class="form-group">'+
                                                        '<input type="text" class="form-control form-control-sm" name="target[]" value="'+ val.ke_target +'">'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-2 align-items-center" style="height: 30px;display: flex; align-items: center;">'+
                                                    '<button type="button" class="btn btn-block btn-primary btn-sm rounded btn-tambahp align-self-center idx-btn"><i class="fa fa-plus"></i></button>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'
                                );
                        }
                        else {
                            $('#kolom')
                                .before( //untuk data kedua dan selanjutnya
                                    '<div class="row section2">'+
                                        '<div class="col-6 col-md-6 col-sm-12">'+
                                            '<div class="row">'+
                                                '<div class="col-4 col-md-4 col-sm-6 col-xs-12">'+
                                                    '<label style="display: none;">~</label>'+
                                                '</div>'+
                                                '<div class="col-6 col-md-6 col-sm-4 col-xs-12">'+
                                                    '<div class="form-group">'+
                                                        '<input type="hidden" class="form-control form-control-sm indicator" id="" name="indicator[]" value="'+ val.k_id +'">'+
                                                        '<input type="text" class="form-control form-control-sm indicator" id="" name="indicator[]" value="'+ val.k_indicator +'" disabled>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-2 col-md-2 col-sm-2">'+
                                                    '<label for="">Bobot</label>'+
                                                '</div>'+
                                                // '<div class="offset-md-4 col-8 col-md-8 col-sm-6 col-xs-12 mb-1 messageError d-none" style="margin-top: -18px;">'+
                                                //     '<span class="text-danger" style="font-size: 12px;">Indikator sudah terpilih</span>'+
                                                // '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-6 col-md-6 col-sm-12">'+
                                            '<div class="row">'+
                                                '<div class="col-4">'+
                                                    '<div class="form-group">'+
                                                        '<input type="text" class="form-control form-control-sm" name="bobot[]" value="'+ val.ke_weight +'">'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-2">'+
                                                    '<label for="">Target</label>'+
                                                '</div>'+
                                                '<div class="col-4">'+
                                                    '<div class="form-group">'+
                                                        '<input type="text" class="form-control form-control-sm" name="target[]" value="'+ val.ke_target +'">'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-2" style="height: 30px;display: flex; align-items: center;">'+
                                                    '<button type="button" class="btn btn-block btn-danger btn-sm rounded btn-hapus idx-btn btn-del"><i class="fa fa-trash"></i></button>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'
                                );
                        }
                    });
                    
                    $('.btn-del').on('click', function(){
                        var idx = $('.idx-btn').index(this);
                        var isi = $('.indicator').eq(idx).val();

                        var findArray = keranjang.findIndex(e => e == isi)
                        if (findArray >= 0) {
                            keranjang.splice(findArray, 1)
                        }
                        $(this).parents('.section2').remove()
                    });
                    
                } else { // ketika pegawai tidak memiliki indikator
                    $('.section2').remove();
                    
                    index = $('.indicator').index(this);
                    getIndicator(index);
                    
                    $('#kolom')
                        .before(
                            '<div class="row section2">'+
                                '<div class="col-6 col-md-6 col-sm-12">'+
                                    '<div class="row">'+
                                        '<div class="col-4 col-md-4 col-sm-6 col-xs-12">'+
                                            '<label>Indikator</label>'+
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
                                        '<div class="col-2 align-items-center" style="height: 30px;display: flex; align-items: center;">'+
                                            '<button type="button" class="btn btn-block btn-primary btn-sm rounded btn-tambahp align-self-center idx-btn"><i class="fa fa-plus"></i></button>'+
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
                }


                $('.btn-tambahp').on('click',function(){

                    index = $('.indicator').index(this);
                    getIndicator(index);

                    $('#kolom')
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
            },
        });
    }

    // function tambahFormIndikator() {
    //     index = $('.indicator').index(this);
    //     getIndicator(index);

    //     $('#kolom')
    //         .before(
    //             '<div class="row section2">'+
    //                 '<div class="col-6 col-md-6 col-sm-12">'+
    //                     '<div class="row">'+
    //                         '<div class="col-4 col-md-4 col-sm-6 col-xs-12">'+
    //                             '<label style="display: none;">~</label>'+
    //                         '</div>'+
    //                         '<div class="col-6 col-md-6 col-sm-4 col-xs-12">'+
    //                             '<div class="form-group">'+
    //                                 '<select class="form-control form-control-sm select2 indicator" id="" name="indicator[]" data-last="null">'+
    //                                     '<option value="" selected disabled>Pilih Indikator</option>'+
    //                                 '</select>'+
    //                             '</div>'+
    //                         '</div>'+
    //                         '<div class="col-2 col-md-2 col-sm-2">'+
    //                             '<label for="">Bobot</label>'+
    //                         '</div>'+
    //                         '<div class="offset-md-4 col-8 col-md-8 col-sm-6 col-xs-12 mb-1 messageError d-none" style="margin-top: -18px;">'+
    //                             '<span class="text-danger" style="font-size: 12px;">Indikator sudah terpilih</span>'+
    //                         '</div>'+
    //                     '</div>'+
    //                 '</div>'+
    //                 '<div class="col-6 col-md-6 col-sm-12">'+
    //                     '<div class="row">'+
    //                         '<div class="col-4">'+
    //                             '<div class="form-group">'+
    //                                 '<input type="text" name="bobot[]" class="form-control form-control-sm digits">'+
    //                             '</div>'+
    //                         '</div>'+
    //                         '<div class="col-2">'+
    //                             '<label for="">Target</label>'+
    //                         '</div>'+
    //                         '<div class="col-4">'+
    //                             '<div class="form-group">'+
    //                                 '<input type="text" name="target[]" class="form-control form-control-sm digits">'+
    //                             '</div>'+
    //                         '</div>'+
    //                         '<div class="col-2" style="height: 30px;display: flex; align-items: center;">'+
    //                             '<button type="button" class="btn btn-block btn-danger btn-sm rounded btn-hapus idx-btn btn-del"><i class="fa fa-trash"></i></button>'+
    //                         '</div>'+
    //                     '</div>'+
    //                 '</div>'+
    //             '</div>'
    //         );

    //     $('.select2').select2({            
    //         theme: "bootstrap",
    //         dropdownAutoWidth: true,
    //         width: '100%'
    //     });

    //     //mask digits
    //     $('.digits').inputmask("currency", {
    //         radixPoint: ",",
    //         groupSeparator: ".",
    //         digits: 0,
    //         autoGroup: true,
    //         prefix: '', //Space after $, this will not truncate the first character.
    //         rightAlign: true,
    //         autoUnmask: true,
    //         nullable: false,
    //         // unmaskAsNumber: true,
    //     });

    //     $('.btn-del').on('click', function(){
    //         var idx = $('.idx-btn').index(this);
    //         var isi = $('.indicator').eq(idx).val();

    //         var findArray = keranjang.findIndex(e => e == isi)
    //         if (findArray >= 0) {
    //             keranjang.splice(findArray, 1)
    //         }
    //         $(this).parents('.section2').remove()
    //     })
    // }

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
        var datas = $('#form-add').serialize();
        axios.post('{{url('/sdm/kinerjasdm/kpi-pegawai/save-kpi-pegawai')}}', datas)
        .then(function(resp){
            if (resp.data.status == 'success') {
                messageSuccess('Berhasil!', 'Data berhasil disimpan!');
                setTimeout(function(){
                    window.location.href = "{{url('/sdm/kinerjasdm/index')}}"
                }, 1000)                
            }else{
                messageFailed('Gagal!', 'Data gagal disimpan!');
            }
        })
        .catch(function(error) {

        })
    })
</script>
@endsection
