@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Tambah Data Penempatan Produk </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Marketing</span>
                / <a href="{{route('konsinyasipusat.index')}}"><span>Manajemen Konsinyasi Pusat </span></a>
                / <span class="text-primary" style="font-weight: bold;"> Tambah Data Penempatan Produk </span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Tambah Data Penempatan Produk </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('konsinyasipusat.index')}}" class="btn btn-secondary"><i
                                        class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <form id="formKonsinyasi">
                            <div class="card-block">
                                <section>

                                    <div id="sectionsuplier" class="row">

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Area</label>
                                        </div>

                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="provinsi" id="provinsi" class="form-control form-control-sm select2" disabled>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="kota" id="kota" class="form-control form-control-sm select2" disabled>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Konsigner</label>
                                        </div>

                                        <div class="col-md-10 col-sm-12">
                                            <div class="form-group">
                                                <input type="hidden" name="idKonsigner" id="idKonsigner">
                                                <input type="text" name="konsigner" id="konsigner" class="form-control form-control-sm" oninput="handleInput(event)" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Total</label>
                                        </div>

                                        <div class="col-md-10 col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="">
                                            </div>
                                        </div>

                                        <div class="container">
                                            <div class="table-responsive mt-3">
                                                <table class="table table-hover table-striped" id="table_rencana"
                                                       cellspacing="0">
                                                    <thead class="bg-primary">
                                                    <tr>
                                                        <th>Kode/Nama Barang</th>
                                                        <th width="10%">Satuan</th>
                                                        <th>Jumlah</th>
                                                        <th>Harga Satuan</th>
                                                        <th>Sub Total</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td><input type="text" class="form-control form-control-sm"></td>
                                                        <td><select name="" id=""
                                                                    class="form-control form-control-sm select2"></select>
                                                        </td>
                                                        <td><input type="number" class="form-control form-control-sm"></td>
                                                        <td><input type="text"
                                                                   class="form-control form-control-sm input-rupiah"
                                                                   value="Rp. 0"></td>
                                                        <td><input type="text" class="form-control form-control-sm"
                                                                   readonly=""></td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-success rounded-circle btn-tambahp"><i
                                                                    class="fa fa-plus"></i></button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                                <a href="{{route('konsinyasipusat.index')}}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </section>

    </article>

@endsection

@section('extra_script')
    <script type="text/javascript">
        $(document).ready(function () {
            getProv();
            getKota();

            $("#kota").on("change", function (evt) {
                evt.preventDefault();
                if ($("#kota").val() == "") {
                    $("#idKonsigner").val('');
                    $("#konsigner").val('');
                    $("#konsigner").attr("disabled", true);
                } else {
                    $("#konsigner").attr("disabled", false);
                    $("#idKonsigner").val('');
                    $("#konsigner").val('');
                }
            })

            $( "#konsigner" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: baseUrl+'/marketing/konsinyasipusat/cari-konsigner/'+$("#provinsi").val()+'/'+$("#kota").val(),
                        data: {
                            term: $( "#konsigner" ).val()
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                minLength: 1,
                select: function(event, data) {
                    $( "#idKonsigner" ).val(data.item.id);
                }
            });

            $(document).on('click', '.btn-hapus', function () {
                $(this).parents('tr').remove();
            });

            $('.btn-tambahp').on('click', function () {
                $('#table_rencana tbody')
                    .append(
                        '<tr>' +
                        '<td><input type="text" class="form-control form-control-sm"></td>' +
                        '<td><select name="" id="" class="form-control form-control-sm select2"></select></td>' +
                        '<td><input type="number" class="form-control form-control-sm"></td>' +
                        '<td><input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 0"></td>' +
                        '<td><input type="text" class="form-control form-control-sm" readonly=""></td>' +
                        '<td align="center"><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>' +
                        '</tr>'
                    );
            });

            $(document).on('click', '.btn-submit', function () {
                $.toast({
                    heading: 'Success',
                    text: 'Data Berhasil di Simpan',
                    bgColor: '#00b894',
                    textColor: 'white',
                    loaderBg: '#55efc4',
                    icon: 'success'
                })
            })
        });

        function getProv() {
            loadingShow();
            $("#provinsi").find('option').remove();
            $("#provinsi").attr("disabled", true);
            axios.get('{{ route('konsinyasipusat.getProv') }}')
                .then(function (resp) {
                    $("#provinsi").attr("disabled", false);
                    var option = '<option value="">Pilih Provinsi</option>';
                    var prov = resp.data;
                    prov.forEach(function (data) {
                        option += '<option value="'+data.wp_id+'">'+data.wp_name+'</option>';
                    })
                    $("#provinsi").append(option);
                    loadingHide();
                })
                .catch(function (error) {
                    loadingHide();
                    messageWarning("Error", error)
                })
        }

        function getKota() {
            $("#provinsi").on("change", function (evt) {
                evt.preventDefault();
                $("#idKonsigner").val('');
                $("#konsigner").val('');
                $("#kota").find('option').remove();
                $("#kota").attr("disabled", true);
                $("#konsigner").attr("disabled", true);
                if ($("#provinsi").val() != "") {
                    loadingShow();
                    axios.get(baseUrl+'/marketing/konsinyasipusat/get-kota/'+$("#provinsi").val())
                        .then(function (resp) {
                            $("#kota").attr("disabled", false);
                            var option = '<option value="">Pilih Kota</option>';
                            var kota = resp.data;
                            kota.forEach(function (data) {
                                option += '<option value="'+data.wc_id+'">'+data.wc_name+'</option>';
                            })
                            $("#kota").append(option);
                            loadingHide();
                        })
                        .catch(function (error) {
                            loadingHide();
                            messageWarning("Error", error)
                        })
                }
            })
        }
    </script>
@endsection
