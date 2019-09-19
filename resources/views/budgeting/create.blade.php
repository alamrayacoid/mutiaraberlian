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
                        <section>
                            <div class="row mb-5">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>periode</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <input type="text" class="form-control" id="periode" name="periode" autocomplete="off">
                                </div>
                            </div>

                            <!--Table-->
                            <div class="table-responsive">
                                <table id="tablePendapatan" class="table table-hover display table-bordered w-100">
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
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Penjualan</td>
                                            <td><input type="text" class="form-control pend-value w-100 rupiah" name="pendValue" value="0"></td>
                                            <td class="text-right"><label class="pend-persentase"></label></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Penjualan</td>
                                            <td><input type="text" class="form-control pend-value w-100 rupiah" name="pendValue" value="0"></td>
                                            <td class="text-right"><label class="pend-persentase"></label></td>
                                        </tr>
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

                        </section>
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
    var idxPendapatan = 0;
    var month_years = new Date();

    $(document).ready(function() {
        setTimeout(function () {
            month_years = new Date(month_years.getFullYear(), month_years.getMonth());

            // set month picker
            $("#periode").datepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months"
            });
// start : test get akun
            getPendapatan();
// end : test get akun
            $('#periode').datepicker('setDate', month_years);

            $('.pend-value').on('keyup', function() {
                idxPendapatan = $('.pend-value').index(this);
                totalPendapatan = calculateTotalPendapatan();
                $('.total-pend-value').val(totalPendapatan);
                calculatePersentasePendapatan();
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

    function getPendapatan() {
        $.ajax({
            url: "{{ route('budgeting.getAkunPendapatan') }}",
            type: 'GET',
            data: {
                // periode: 
            },
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                messageFailed('Error', 'Terjadi kesalahan : ' + error);
            }
        });
    }

</script>
@endsection
