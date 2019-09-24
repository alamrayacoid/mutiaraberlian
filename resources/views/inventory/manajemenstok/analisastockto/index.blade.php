@extends('main')

@section('content')

    <article class="content animated fadeInLeft">
        <div class="title-block text-primary">
            <h1 class="title"> Analisis Inventory Turn Over </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> /
                <span>Aktivitas Inventory</span> /
                <span class="text-primary" style="font-weight: bold;">Analisis Inventory Turn Over</span>
            </p>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="tab-content">
                        <div class="tab-pane animated fadeIn active show" id="adjustmentstock">
                            <div class="card">
                                <div class="card-header bordered p-2">
                                    <div class="header-block">
                                        <h3 class="title">Analisis Stock Turn Over</h3>
                                    </div>
                                    <div class="header-block pull-right">
                                        {{--<a class="btn btn-primary" href="#"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>--}}
                                    </div>
                                    <div class=""></div>
                                </div>
                                <div class="card-block">
                                    <section>
                                        <div class="row">
                                            <div class="col-md-1 col-sm-6 col-xs-12">
                                                <label>Periode</label>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="input-group input-group-sm input-daterange">
                                                    <input type="text" class="form-control" id="date_from" autocomplete="off">
                                                    <span class="input-group-addon">-</span>
                                                    <input type="text" class="form-control" id="date_to" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-md-1 col-sm-6 col-xs-12">
                                                <label>Barang</label>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-control-sm" id="barang" name="barang">
                                                    <input type="hidden" class="form-control form-control-sm" id="id_barang" name="id_barang">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <button type="button" class="button btn btn-sm btn-primary" onclick="getListAnalisa()">Lakukan</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped w-100" cellspacing="0"
                                                   id="table_turnover">
                                                <thead class="bg-primary">
                                                <tr>
                                                    <th>Kode Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th>Total Produksi</th>
                                                    <th>Persediaan Awal</th>
                                                    <th>Persediaan Akhir</th>
                                                    <th>Turn Over</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </section>
                                </div>
                        		<div class="card-footer text-right">
                        			<a href="{{ route('manajemenstok.index') }}" class="btn btn-secondary">Kembali</a>
                        		</div>
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
        var tb_analisa;
        $(document).ready(function () {
            cur_date = new Date();
            first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
            last_day =   new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
            $('#date_from').datepicker('setDate', first_day);
            $('#date_to').datepicker('setDate', last_day);

            $('.input-daterange').datepicker({
                format: 'dd-mm-yyyy',
                enableOnReadonly: false,
                autoclose: true
            });

            tb_analisa = $('#table_turnover').DataTable({
                responsive: true
            });

            $("#barang").autocomplete({
                source: baseUrl + '/masterdatautama/harga/cari-barang',
                minLength: 1,
                select: function (event, data) {
                    $("#id_barang").val(data.item.data.i_id);
                }
            });

        });

        function getListAnalisa() {
            let id_barang = $('#id_barang').val();
            let startdate = $('#date_from').val();
            let enddate = $('#date_to').val();
            if (id_barang == null || id_barang == '') {
                messageWarning("Perhatian", "Isi nama barang terlebih dahulu");
                return false;
            }
            if (startdate == null || startdate == '') {
                messageWarning("Perhatian", "Isi tanggal awal terlebih dahulu");
                return false;
            }
            if (enddate == null || enddate == '') {
                messageWarning("Perhatian", "Isi tanggal akhir terlebih dahulu");
                return false;
            }

            axios.get("{{ route('TurnOverController.getDataPeriode') }}", {
                params:{
                    "id_item": id_barang,
                    "start": $('#date_from').val(),
                    "end": $('#date_to').val(),
                }
            }).then(function(response){
                let data = response.data;
                tb_analisa.clear();
                tb_analisa.row.add([
                    data.i_code,
                    data.i_name,
                    data.totalhpp,
                    data.persediaanawal,
                    data.persediaanakhir,
                    data.hasil
                    ]).draw(false);
            }).catch(function(error){
                messageWarning("Error", 'Terjadi kesalahan : ' + error);
            })
        }

    </script>
@endsection
