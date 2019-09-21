@extends('main')
@section('extra_style')
    <style>
        #table_agen td {
            padding: 5px;
        }
        .total-pend-value {
            pointer-events: none;
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
            / <a href="{{ route('budgeting.index') }}"><span>Manajemen Perencanaan</span></a>
            / <span class="text-primary" style="font-weight: bold;">Tambah Perencanaan</span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <div class="card">
                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Tambah Perencanaan </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a href="{{route('budgeting.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
<!-- start : form -->
                    <div class="card-block">
                        <form class="formBudgeting">
                            <section>
                                <div class="row mb-5">
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>periode</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <input type="text" class="form-control" id="periode" name="periode" autocomplete="off">
                                    </div>
                                </div>

                                <!--Table Pendapatan-->
                                <div class="table-responsive">
                                    <table id="tablePendapatan" class="table table-hover table-sm table-striped display table-bordered w-100">
                                        <!--Table head-->
                                        <thead class="bg-primary">
                                            <tr>
                                                <th colspan="4">Pendapatan</th>
                                            </tr>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-center">Akun</th>
                                                <th class="text-center">Value</th>
                                                <th class="text-center">Persentase</th>
                                            </tr>
                                        </thead>
                                        <!--Table head-->
                                        <!--Table body-->
                                        <tbody>
                                        </tbody>
                                        <!--Table body-->
                                        <!--Table footer-->
                                        <tfoot>
                                            <tr>
                                                <th scope="row" colspan="2" class="text-center">Total</th>
                                                <td><input type="text" class="form-control form-control-plaintext total-pend-value w-100 rupiah" name="totalPendValue"></td>
                                                <td class="text-right"><label class="pend-total-persentase"></label></td>
                                            </tr>
                                        </tfoot>
                                        <!--Table footer-->
                                    </table>
                                    <!--Table-->
                                </div>

                                <!--Table Beban-->
                                <div class="table-responsive">
                                    <table id="tableBeban" class="table table-hover table-sm table-striped display table-bordered w-100">
                                        <!--Table head-->
                                        <thead class="bg-primary">
                                            <tr>
                                                <th colspan="4">Beban</th>
                                            </tr>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-center">Akun</th>
                                                <th class="text-center">Value</th>
                                                <th class="text-center">Persentase</th>
                                            </tr>
                                        </thead>
                                        <!--Table head-->
                                        <!--Table body-->
                                        <tbody>
                                        </tbody>
                                        <!--Table body-->
                                        <!--Table footer-->
                                        <tfoot>
                                            <tr>
                                                <th scope="row" colspan="2" class="text-center">Total</th>
                                                <td><input type="text" class="form-control form-control-plaintext total-beban-value w-100 rupiah" name="totalBebanValue"></td>
                                                <td class="text-right"><label class="beban-total-persentase"></label></td>
                                            </tr>
                                        </tfoot>
                                        <!--Table footer-->
                                    </table>
                                    <!--Table-->
                                </div>

                            </section>
                        </form>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                            <a href="{{ route('budgeting.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
    var totalPendapatan = 0;
    var idxRow = 0;
    var month_years = new Date();

    $(document).ready(function() {
        setTimeout(function () {
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
                getAkun();
            });
            getAkun();

            $('.pend-value').on('keyup', function() {
                idxRow = $('.pend-value').index(this);
                totalPendapatan = calculateTotalPendapatan();
                $('.total-pend-value').val(totalPendapatan);
                calculatePersentasePendapatan();
            });
            $('.beban-value').on('keyup', function() {
                idxRow = $('.beban-value').index(this);
                totalBeban = calculateTotalBeban();
                $('.total-beban-value').val(totalBeban);
                calculatePersentaseBeban();
            });


            $('.btn-submit').on('click', function() {
                store();
            });
        }, 100);
    });

    function calculateTotalPendapatan() {
        let total = 0;
        $.each($('.pend-value'), function (idx, value) {
            total += parseInt($('.pend-value').eq(idx).val());
        });
        return total;
    }
    function calculatePersentasePendapatan() {
        let persen = 0;
        let totalPersen = 0;
        $.each($('.pend-persentase'), function (idx, value) {
            let pendValue = $('.pend-value').eq(idx).val();
            persen = parseFloat(pendValue / totalPendapatan) * 100;
            persen = Math.floor(persen);
            $('.pend-persentase').eq(idx).text(persen + ' %');
            totalPersen += persen;
        });
        $('.pend-total-persentase').text(totalPersen + ' %');
    }

    function calculateTotalBeban() {
        let total = 0;
        $.each($('.beban-value'), function (idx, value) {
            total += parseInt($('.beban-value').eq(idx).val());
        });
        return total;
    }
    function calculatePersentaseBeban() {
        let persen = 0;
        let totalPersen = 0;
        $.each($('.beban-persentase'), function (idx, value) {
            let valueX = $('.beban-value').eq(idx).val();
            persen = parseFloat(valueX / totalBeban) * 100;
            persen = Math.floor(persen);
            $('.beban-persentase').eq(idx).text(persen + ' %');
            totalPersen += persen;
        });
        $('.beban-total-persentase').text(totalPersen + ' %');
    }

    function getAkun() {
        periode = $('#periode').val();
        $.ajax({
            url: "{{ route('budgeting.getAkunPendapatan') }}",
            type: 'GET',
            data: {
                periode: periode
            },
            beforeSend : function (){
                loadingShow();
            },
            success: function (response) {
                let resp = response.data;
                let respBudgeting = response.budgeting;
                let layoutPend = '';
                let layoutBeban = '';
                let counterPend = 0;
                let counterBeban = 0;
                $('#tablePendapatan > tbody').empty();
                $('#tableBeban > tbody').empty();
                if (respBudgeting.length > 0) {
                    $.each(resp, function (j, data) {
                        if (data.subclass.length <= 0) { return true };
                        $.each(data.subclass, function (k, subclass) {
                            if (subclass.level2.length <= 0) { return true };
                            $.each(subclass.level2, function (l, level2) {
                                if (level2.akun.length <= 0) { return true };
                                $.each(level2.akun, function (m, akun) {
                                    let value = '';
                                    // separate between 'pendapatan' and 'beban'
                                    if (akun.ak_posisi == 'D') {
                                        counterBeban++;
                                        $.each(respBudgeting, function (idxBud, valBud) {
                                            if (valBud.b_akun == akun.ak_nomor) {
                                                value = '<td><input type="text" class="form-control beban-value w-100 rupiah" name="bebanValue[]" value="'+ parseInt(valBud.b_value) +'"></td>';
                                                return false;
                                            }
                                            else {
                                                value = '<td><input type="text" class="form-control beban-value w-100 rupiah" name="bebanValue[]" value="0"></td>';
                                            }
                                        });
                                        let number = '<th scope="row">'+ counterBeban +'</th>';
                                        let name = '<td>'+ akun.ak_nama +'<input type="hidden" name="bebanAkun[]" value="'+ akun.ak_nomor +'"></td>';
                                        let persentase = '<td class="text-right"><label class="beban-persentase"></label></td>';
                                        layoutBeban += '<tr>'+ number + name + value + persentase +'</tr>'
                                    }
                                    else {
                                        counterPend++;
                                        $.each(respBudgeting, function (idxBud, valBud) {
                                            if (valBud.b_akun == akun.ak_nomor) {
                                                value = '<td><input type="text" class="form-control pend-value w-100 rupiah" name="pendValue[]" value="'+ parseInt(valBud.b_value) +'"></td>';
                                                return false;
                                            }
                                            else {
                                                value = '<td><input type="text" class="form-control pend-value w-100 rupiah" name="pendValue[]" value="0"></td>';
                                            }
                                        });
                                        let number = '<th scope="row">'+ counterPend +'</th>';
                                        let name = '<td>'+ akun.ak_nama +'<input type="hidden" name="pendAkun[]" value="'+ akun.ak_nomor +'"></td>';
                                        let persentase = '<td class="text-right"><label class="pend-persentase"></label></td>';
                                        layoutPend += '<tr>'+ number + name + value + persentase +'</tr>'
                                    }
                                });
                            });
                        });
                    });
                }
                else {
                    $.each(resp, function (j, data) {
                        if (data.subclass.length <= 0) { return true };
                        $.each(data.subclass, function (k, subclass) {
                            if (subclass.level2.length <= 0) { return true };
                            $.each(subclass.level2, function (l, level2) {
                                if (level2.akun.length <= 0) { return true };
                                $.each(level2.akun, function (m, akun) {
                                    let value = '';
                                    // separate between 'pendapatan' and 'beban'
                                    if (akun.ak_posisi == 'D') {
                                        counterBeban++;
                                        value = '<td><input type="text" class="form-control beban-value w-100 rupiah" name="bebanValue[]" value="0"></td>';
                                        let number = '<th scope="row">'+ counterBeban +'</th>';
                                        let name = '<td>'+ akun.ak_nama +'<input type="hidden" name="bebanAkun[]" value="'+ akun.ak_nomor +'"></td>';
                                        let persentase = '<td class="text-right"><label class="beban-persentase"></label></td>';
                                        layoutBeban += '<tr>'+ number + name + value + persentase +'</tr>'
                                    }
                                    else {
                                        counterPend++;
                                        value = '<td><input type="text" class="form-control pend-value w-100 rupiah" name="pendValue[]" value="0"></td>';
                                        let number = '<th scope="row">'+ counterPend +'</th>';
                                        let name = '<td>'+ akun.ak_nama +'<input type="hidden" name="pendAkun[]" value="'+ akun.ak_nomor +'"></td>';
                                        let persentase = '<td class="text-right"><label class="pend-persentase"></label></td>';
                                        layoutPend += '<tr>'+ number + name + value + persentase +'</tr>'
                                    }
                                });
                            });
                        });
                    });

                    // for(var i = 0; i<(resp).length; i++){
                    //     for(var j = 0; j < (resp[i]['subclass']).length; j++){
                    //         for(var k = 0; k < (resp[i]['subclass'][j]['level2']).length; k++){
                    //             for(var n = 0; n < (resp[i]['subclass'][j]['level2'][k]['akun']).length; n++){
                    //                 for(var o = 0; o < (resp[i]['subclass'][j]['level2'][k]['akun']).length; o++){
                    //                     counter++;
                    //                     let number = '<th scope="row">'+ counter +'</th>';
                    //                     let name = '<td>'+ resp[i]['subclass'][j]['level2'][k]['akun'][o].ak_nama +'<input type="hidden" name="pendAkun[]" value="'+ resp[i]['subclass'][j]['level2'][k]['akun'][o].ak_nomor +'"></td>';
                    //                     let value = '<td><input type="text" class="form-control pend-value w-100 rupiah" name="pendValue[]" value="0"></td>';
                    //                     let persentase = '<td class="text-right"><label class="pend-persentase"></label></td>';
                    //                     layout += '<tr>'+ number + name + value + persentase +'</tr>'
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }
                }

                $('#tablePendapatan > tbody').append(layoutPend);
                $('#tableBeban > tbody').append(layoutBeban);
                $('.pend-value').off();
                $('.pend-value').on('keyup', function() {
                    idxRow = $('.pend-value').index(this);
                    totalPendapatan = calculateTotalPendapatan();
                    $('.total-pend-value').val(totalPendapatan);
                    calculatePersentasePendapatan();
                });
                $('.pend-value').trigger('keyup');
                $('.beban-value').off();
                $('.beban-value').on('keyup', function() {
                    idxRow = $('.beban-value').index(this);
                    totalBeban = calculateTotalBeban();
                    $('.total-beban-value').val(totalBeban);
                    calculatePersentaseBeban();
                });
                $('.beban-value').trigger('keyup');
                $('.rupiah').inputmask("currency", {
                    radixPoint: ",",
                    groupSeparator: ".",
                    digits: 0,
                    autoGroup: true,
                    prefix: ' Rp ', //Space after $, this will not truncate the first character.
                    rightAlign: true,
                    autoUnmask: true,
                    nullable: false,
                    // unmaskAsNumber: true,
                });
            },
            error: function (error) {
                messageFailed('Error', 'Terjadi kesalahan : ' + error);
            },
            complete: function () {
                loadingHide();
            }
        });
    }

    function store() {
        formData = $('.formBudgeting').serialize();
        $.ajax({
            url: "{{ route('budgeting.store') }}",
            type: 'POST',
            data: formData,
            beforeSend: function (){
                loadingShow();
            },
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                messageWarning('Error', 'Terjadi kesalahan : ' + error);
            },
            complete: function (){
                loadingHide();
            }
        })
    }

</script>
@endsection
