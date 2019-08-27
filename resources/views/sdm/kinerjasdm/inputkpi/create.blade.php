@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Aktivitas SDM </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Master Data Utama</span> /
                <span class="text-primary" style="font-weight: bold;">Aktivitas SDM</span>
                / <span>Kelola Kinerja SDM</span>
                / <span>Kelola KPI</span>
                / <span class="text-primary" style="font-weight: bold;"> Tambah Data Kelola KPI</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title">Tambah Data Kelola KPI</h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{ route('kinerjasdm.index') }}" class="btn btn-secondary"><i
                                        class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-block">
                            <form id="myform">
                                {{ csrf_field() }}
                                <section>
                                    <div class="row mb-3">
                                        <div class="col-md-5 col-sm-6">
                                            <div class="row col-md-12 col-sm-12">
                                                <div class="col-md-3 col-sm-12">
                                                    <label for="">Periode</label>
                                                </div>
                                                <div class="col-md-9 col-sm-12">
                                                    <div class="input-group input-group-sm input-daterange periode_kpi">
                                                        <input type="text" class="form-control" id="periode_kpi" name="periode_kpi" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5 col-sm-6">
                                            <div class="row col-md-12 col-sm-12">
                                                <div class="col-md-3 col-sm-12">
                                                    <label for="">Tipe</label>
                                                </div>
                                                <div class="col-md-9 col-sm-12">
                                                    <div class="form-group">
                                                        <select name="tipe" id="tipe" class="form-control form-control-sm select2 tipe" onchange="getFormDivisiOrEmployee()">
                                                            <option value="" selected="" disabled="">Pilih Tipe</option>
                                                            <option value="D">Divisi</option>
                                                            <option value="P">Pegawai</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="kolom"></div>
                                    </div>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped table-bordered display nowrap" cellspacing="0" style="width: 100%" id="table_indikator_divisi_pegawai">
                                            <thead class="bg-primary">
                                                <tr>
                                                    <th class="text-center">Indikator</th>
                                                    <th class="text-center">Unit</th>
                                                    <th class="text-center">Bobot</th>
                                                    <th class="text-center">Target</th>
                                                    <th class="text-center">Hasil</th>
                                                    <th class="text-center">Point</th>
                                                    <th class="text-center">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </form>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary btn-submit" type="button" id="btn_submit">Simpan</button>
                            <a href="{{ route('kinerjasdm.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>

                </div>

            </div>

        </section>

    </article>

@endsection

@section('extra_script')
    <script type="text/javascript">
        var month_years = new Date();
        const month_year = new Date(month_years.getFullYear(), month_years.getMonth());

        $("#periode_kpi").datepicker( {
        format: "mm-yyyy",
        viewMode: "months", 
        minViewMode: "months"
        });

        $('#periode_kpi').on('change', function(){
            $("#table_indikator_divisi_pegawai > tbody").find('tr').remove();
        });
    </script>

    <script>
        $(document).ready(function(){
            // $('.divisi').on('select2:select', function () {
            //     console.log('testes');
            //     getDivisi();
            // });
        });

        function getDivisi(index) {
            axios.get('{{url('/sdm/kinerjasdm/kpi-divisi/get-kpi-divisi')}}')
            .then(function(resp) {
                var data = resp.data.data
                $.each(data, function(key, val) {
                    $('#divisi').append('<option value="'+val.m_id+'">'+val.m_name+'</option>')
                })
            })
        }

        function getPegawai(index) {
            axios.get('{{url('/sdm/kinerjasdm/kpi-pegawai/get-kpi-employee')}}')
            .then(function(resp) {
                var data = resp.data.data
                $.each(data, function(key, val) {
                    $('#pegawai').append('<option value="'+val.e_id+'">'+val.e_name+' ('+val.d_name+'/'+val.j_name+')</option>')
                })
            })
        }

        function getFormDivisiOrEmployee() {
            var data = $('#tipe').val();
            // console.log(data);
            if ( data == 'D' ) {
                console.log('ini data divisi');
                $('.section2').remove();
                $('#kolom')
                    .before(
                        '<div class="col-md-5 col-sm-6 section2">'+
                            '<div class="row col-md-12 col-sm-12">'+
                                '<div class="col-md-3 col-sm-12">'+
                                    '<label for="">Divisi</label>'+
                                '</div>'+
                                '<div class="col-md-9 col-sm-12">'+
                                    '<div class="form-group">'+
                                        '<select name="divisi" id="divisi" class="form-control form-control-sm select2 divisi" onchange="getDataIndikatorDivisi()">'+
                                            '<option value="" disable="" selected="">Pilih Divisi</option>'+
                                        '</select>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );

                $('.divisi').on('select2:select', function () {
                    $("#table_indikator_divisi_pegawai > tbody").find('tr').remove();
                });
                    
                $('.select2').select2({            
                    theme: "bootstrap",
                    dropdownAutoWidth: true,
                    width: '100%'
                });

                getDivisi();

                $("#table_indikator_divisi_pegawai > tbody").find('tr').remove();

            } else if ( data == 'P' ){
                console.log('ini data pegawai');
                $('.section2').remove();
                $('#kolom')
                    .before(
                        '<div class="col-md-5 col-sm-6 section2">'+
                            '<div class="row col-md-12 col-sm-12">'+
                                '<div class="col-md-3 col-sm-12">'+
                                    '<label for="">Pegawai</label>'+
                                '</div>'+
                                '<div class="col-md-9 col-sm-12">'+
                                    '<div class="form-group">'+
                                        '<select name="pegawai" id="pegawai" class="form-control form-control-sm select2 pegawai" onchange="getDataIndikatorPegawai()">'+
                                            '<option value="" disable="" selected="">Pilih Pegawai</option>'+
                                        '</select>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );

                $('.pegawai').on('select2:select', function () {
                    $("#table_indikator_divisi_pegawai > tbody").find('tr').remove();
                });

                $('.select2').select2({            
                    theme: "bootstrap",
                    dropdownAutoWidth: true,
                    width: '100%'
                });

                getPegawai();

                $("#table_indikator_divisi_pegawai > tbody").find('tr').remove();
            }
        }

        function getDataIndikatorDivisi() {
            var idxHasil = null;
            var data = $('#divisi').val();
            var periode = $('#periode_kpi').val();
            // console.log(emp_id);
            $.ajax({
                url: '{{ route("inputkpi.getDataIndikatorKpiDivisi") }}',
                type: "post",
                data: {
                        "_token": "{{ csrf_token() }}",
                        "data": data,
                        "periode": periode,
                        "total": data
                },
                success: function (resp) {
                    // console.log(resp.total);
                    // console.log(resp.data.length);
                    if (resp.data.length > 0) {
                        $("#table_indikator_divisi_pegawai > tbody").find('tr').remove();
                    }
                    else {
                        // $("#table_indikator_divisi_pegawai > tbody").find('tr:gt(0)').remove();
                        $("#table_indikator_divisi_pegawai > tbody").find('tr').remove();
                    }

                    // $('#table_indikator_divisi_pegawai').dataTable().fnDestroy();
                    $.each(resp.data, function(key, val) {

                        let indikatorD = `<td class="pad-1">
                                        <input type="hidden" name="kd_indikatorD[]" class="form-control-plaintext kd_indikatorD onlyread w-100" value="`+ val.k_id +`">
                                        <input type="text" name="indikatorD[]" class="form-control-plaintext indikatorD onlyread w-100" value="`+ val.k_indicator +`" readonly>
                                        </td>`;

                        let unitD = `<td class="pad-1">
                                        <input type="text" name="unitD[]" class="form-control-plaintext unitD onlyread w-100" value="`+ val.k_unit +`" readonly>
                                        </td>`;

                        let bobotD = `<td class="pad-1">
                                        <input type="text" name="bobotD[]" class="form-control-plaintext text-center bobotD onlyread w-100" value="`+ val.ke_weight +`" readonly>
                                        </td>`;

                        let targetD = `<td class="pad-1">
                                        <input type="text" name="targetD[]" class="form-control-plaintext text-center targetD onlyread w-100" value="`+ val.ke_target +`" readonly>
                                        </td>`;


                        // var a = val.ke_weight;
                        // var b = val.ke_target;
                        // var c = (parseInt(a)+parseInt(b));
                        let hasilD = `<td class="pad-1">
                                        <input type="text" name="hasilD[]" class="form-control text-center hasilD w-100 digits" value="">
                                        </td>`;
                        if (val.kd_result == null) {
                            hasilD = `<td class="pad-1">
                                        <input type="text" name="hasilD[]" class="form-control text-center hasilD w-100 digits" value="">
                                        </td>`;
                        } else {
                            hasilD = `<td class="pad-1">
                                            <input type="text" name="hasilD[]" class="form-control text-center hasilD w-100 digits" value="`+ val.kd_result +`">
                                            </td>`;
                        }

                        let pointD = `<td class="pad-1">
                                        <input type="text" name="pointD[]" class="form-control-plaintext text-center pointD w-100" value="" readonly>
                                        </td>`;
                        if (val.kd_point == null) {
                            pointD = `<td class="pad-1">
                                        <input type="text" name="pointD[]" class="form-control-plaintext text-center pointD w-100" value="" readonly>
                                        </td>`;
                        } else {
                            pointD = `<td class="pad-1">
                                            <input type="text" name="pointD[]" class="form-control-plaintext text-center pointD w-100" value="`+ val.kd_point +`" readonly>
                                            </td>`;
                        }


                        let nilaiD = `<td class="pad-1">
                                        <input type="text" name="nilaiD[]" class="form-control-plaintext text-center nilaiD w-100" value="" readonly>
                                        </td>`;
                        if (val.kd_total == null) {
                            nilaiD = `<td class="pad-1">
                                        <input type="text" name="nilaiD[]" class="form-control-plaintext text-center nilaiD w-100" value="" readonly>
                                        </td>`;
                        } else {
                            nilaiD = `<td class="pad-1">
                                            <input type="text" name="nilaiD[]" class="form-control-plaintext text-center nilaiD w-100" value="`+ val.kd_total +`" readonly>
                                            </td>`;
                        }

                        let row = '<tr>'+ indikatorD + unitD + bobotD + targetD + hasilD + pointD + nilaiD +'</tr>' 
                        $('#table_indikator_divisi_pegawai tbody').append(row);
                    });

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

                    $('.hasilD').on('keyup', function(){
                        idxHasil = $('.hasilD').index(this);
                        
                        let target_divisi = $(".targetD").eq(idxHasil).val();
                        let hasil_divisi = $(".hasilD").eq(idxHasil).val();
                        let hitung_point_divisi = parseInt(hasil_divisi) / parseInt(target_divisi);
                        
                        $(".pointD").eq(idxHasil).val(hitung_point_divisi);

                        let bobot_divisi = $(".bobotD").eq(idxHasil).val();
                        let point_divisi = $(".pointD").eq(idxHasil).val();
                        let total_bobot_divisi = parseInt(resp.total);
                        let nilai_divisi = (bobot_divisi * point_divisi) / parseInt(total_bobot_divisi);
                        console.log(nilai_divisi);

                        $(".nilaiD").eq(idxHasil).val(nilai_divisi);
                    });
                },
            });
        }

        function getDataIndikatorPegawai() {
            var idxHasil = null;
            var data = $('#pegawai').val();
            var periode = $('#periode_kpi').val();

            $.ajax({
                url: '{{ route("inputkpi.getDataIndikatorKpiPegawai") }}',
                type: "post",
                data: {
                        "_token": "{{ csrf_token() }}",
                        "data": data,
                        "periode": periode,
                        "total": data
                },
                success: function (resp) {
                    // console.log(resp.total);
                    // console.log(resp.data.length);
                    if (resp.data.length > 0) {
                        $("#table_indikator_divisi_pegawai > tbody").find('tr').remove();
                    }
                    else {
                        $("#table_indikator_divisi_pegawai > tbody").find('tr').remove();
                    }

                    // $('#table_indikator_divisi_pegawai').dataTable().fnDestroy();
                    $.each(resp.data, function(key, val) {

                        let indikatorP = `<td class="pad-1">
                                        <input type="hidden" name="kd_indikatorP[]" class="form-control-plaintext kd_indikatorP onlyread w-100" value="`+ val.k_id +`">
                                        <input type="text" name="indikatorP[]" class="form-control-plaintext indikatorP onlyread w-100" value="`+ val.k_indicator +`" readonly>
                                        </td>`;

                        let unitP = `<td class="pad-1">
                                        <input type="text" name="unitP[]" class="form-control-plaintext unitP onlyread w-100" value="`+ val.k_unit +`" readonly>
                                        </td>`;

                        let bobotP = `<td class="pad-1">
                                        <input type="text" name="bobotP[]" class="form-control-plaintext text-center bobotP onlyread w-100" value="`+ val.ke_weight +`" readonly>
                                        </td>`;

                        let targetP = `<td class="pad-1">
                                        <input type="text" name="targetP[]" class="form-control-plaintext text-center targetP onlyread w-100" value="`+ val.ke_target +`" readonly>
                                        </td>`;

                        let hasilP = `<td class="pad-1">
                                        <input type="text" name="hasilP[]" class="form-control text-center hasilP w-100 digits" value="">
                                        </td>`;
                        if (val.kd_result == null) {
                            hasilP = `<td class="pad-1">
                                        <input type="text" name="hasilP[]" class="form-control text-center hasilP w-100 digits" value="">
                                        </td>`;
                        } else {
                            hasilP = `<td class="pad-1">
                                            <input type="text" name="hasilP[]" class="form-control text-center hasilP w-100 digits" value="`+ val.kd_result +`">
                                            </td>`;
                        }

                        let pointP = `<td class="pad-1">
                                        <input type="text" name="pointP[]" class="form-control-plaintext text-center pointP w-100" value="" readonly>
                                        </td>`;
                        if (val.kd_point == null) {
                            pointP = `<td class="pad-1">
                                        <input type="text" name="pointP[]" class="form-control-plaintext text-center pointP w-100" value="" readonly>
                                        </td>`;
                        } else {
                            pointP = `<td class="pad-1">
                                            <input type="text" name="pointP[]" class="form-control-plaintext text-center pointP w-100" value="`+ val.kd_point +`" readonly>
                                            </td>`;
                        }

                        let nilaiP = `<td class="pad-1">
                                        <input type="text" name="nilaiP[]" class="form-control-plaintext text-center nilaiP w-100" value="" readonly>
                                        </td>`;
                        if (val.kd_total == null) {
                            nilaiP = `<td class="pad-1">
                                        <input type="text" name="nilaiP[]" class="form-control-plaintext text-center nilaiP w-100" value="" readonly>
                                        </td>`;
                        } else {
                            nilaiP = `<td class="pad-1">
                                            <input type="text" name="nilaiP[]" class="form-control-plaintext text-center nilaiP w-100" value="`+ val.kd_total +`" readonly>
                                            </td>`;
                        }

                        let row = '<tr>'+ indikatorP + unitP + bobotP + targetP + hasilP + pointP + nilaiP +'</tr>' 
                        $('#table_indikator_divisi_pegawai tbody').append(row);
                    });

                    $('.hasilP').on('keyup', function(){
                        idxHasil = $('.hasilP').index(this);
                        
                        let target_pegawai = $(".targetP").eq(idxHasil).val();
                        let hasil_pegawai = $(".hasilP").eq(idxHasil).val();
                        let hitung_point_pegawai = parseInt(hasil_pegawai) / parseInt(target_pegawai);
                        
                        $(".pointP").eq(idxHasil).val(hitung_point_pegawai);

                        let bobot_pegawai = $(".bobotP").eq(idxHasil).val();
                        let point_pegawai = $(".pointP").eq(idxHasil).val();
                        let total_bobot_pegawai = parseInt(resp.total);
                        let nilai_pegawai = (bobot_pegawai * point_pegawai) / parseInt(total_bobot_pegawai);
                        console.log(nilai_pegawai);

                        $(".nilaiP").eq(idxHasil).val(nilai_pegawai);
                    });
                },
            });
        }

        // $(document).on('click', '.btn-submit', function (evt) {
        //     evt.preventDefault();

        //     loadingShow();
        //     var data = $('#myform').serialize();
        //     axios.post(baseUrl+'/sdm/kinerjasdm/input-kpi/save', data).then(function (response){
        //         console.log(response.data);
        //         if(response.data.status == 'success'){
        //             loadingHide();
        //             messageSuccess("Berhasil", "Data Kelola KPI Berhasil Disimpan");
        //             setInterval(function(){location.reload(true);}, 3500)
        //         }else{
        //             loadingHide();
        //             messageFailed("Gagal", "Data Kelola KPI Gagal Disimpan");
        //         }

        //     });
        // })
        
        $(document).on('click', '.btn-submit', function (evt) {
            evt.preventDefault();

            loadingShow();
            var data = $('#myform').serialize();
            axios.post(baseUrl+'/sdm/kinerjasdm/input-kpi/save', data).then(function (response){
                console.log(response.data);
                if(response.data.status == 'success'){
                    loadingHide();
                    messageSuccess("Berhasil", "Data Kelola KPI Berhasil Disimpan");
                    setTimeout(function(){
                        window.location.href = "{{url('/sdm/kinerjasdm/index')}}"
                    }, 1000) 
                }else{
                    loadingHide();
                    messageFailed("Gagal", "Data Kelola KPI Gagal Disimpan");
                }

            });
        }) 

    </script>
@endsection
