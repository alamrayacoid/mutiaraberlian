@extends('main')

@section('content')


    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Pengelolaan Manajemen Stok </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Inventory</span>
                / <a href="{{route('dataharga.index')}}"><span>Penglolaan Data Max/Min Stok, Safety Stok</span></a>
                / <span class="text-primary" style="font-weight: bold;">Edit Data Max/Min Stok, Safety Stok</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title">Edit Data Max/Min Stok, Safety Stok</h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('pengelolaanmms.index')}}" class="btn btn-secondary"><i
                                        class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <form id="formEditSafetyStock">{{ csrf_field() }}
                            <input type="hidden" name="id" id="idx" value="{{ $idx }}">
                            <div class="card-block">
                                <section>

                                    <div class="row">

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Pemilik</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="pemilik" id="pemilik"
                                                        class="form-control form-control-sm select2">
                                                    <option value="">Pilih Pemilik</option>
                                                    @foreach($companies as $key => $company)
                                                        <option value="{{ $company->c_id }}"
                                                                @if($data->pemilik == $company->c_id) selected @endif>{{ strtoupper($company->c_name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Posisi</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="posisi" id="posisi"
                                                        class="form-control form-control-sm select2">
                                                    <option value="">Pilih Posisi</option>
                                                    @foreach($companies as $key => $company)
                                                        <option
                                                            value="{{ $company->c_id }}"
                                                            @if($data->posisi == $company->c_id) selected @endif>{{ strtoupper($company->c_name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Nama Barang</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="hidden" name="idItem" id="iditem"
                                                       value="{{ $data->idItem }}">
                                                <input type="hidden" name="idBarang" id="idBarang" value="">
                                                <input type="text" name="barang" id="barang"
                                                       class="form-control form-control-sm" value="{{ $data->item }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Min Stok</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="number"
                                                       name="qtymin"
                                                       id="qtymin"
                                                       class="form-control form-control-sm"
                                                       value="{{ $data->qtymin }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Max Stok</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="number"
                                                       name="qtymax"
                                                       id="qtymax"
                                                       class="form-control form-control-sm"
                                                       value="{{ $data->qtymax }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Safety Stok</label>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text"
                                                       name="rangemin"
                                                       id="rangemin"
                                                       class="form-control-sm form-control"
                                                       value="{{ $data->rangemin }}">
                                            </div>
                                        </div>

                                        <div class="" style="font-size:15pt;">
                                            <label for="">-</label>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text"
                                                       name="rangemax"
                                                       id="rangemax"
                                                       class="form-control-sm form-control"
                                                       value="{{ $data->rangemax }}">
                                            </div>
                                        </div>

                                    </div>

                                </section>
                            </div>

                            <div class="card-footer text-right">
                                <button class="btn btn-primary" type="submit">Simpan</button>
                                <a href="{{route('pengelolaanmms.index')}}" class="btn btn-secondary">Kembali</a>
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
            $("#barang").on("keyup", function () {
                $("#idBarang").val('');
            })

            $("#barang").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: '{{ route('pengelolaanmms.caribarang') }}',
                        data: {
                            term: $("#barang").val()
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function (event, data) {
                    $("#idBarang").val(data.item.id);
                }
            });

            $("#formEditSafetyStock").on("submit", function (evt) {
                evt.preventDefault();
                var pemilik = $("#pemilik").val(), posisi = $("#posisi").val();
                if (pemilik == "") {
                    $("#pemilik").focus();
                    messageWarning("Peringatan", "Pilih pemilik barang");
                } else if (posisi == "") {
                    $("#posisi").focus();
                    messageWarning("Peringatan", "Pilih posisi barang");
                } else {
                    loadingShow();
                    axios.post('{{ url('/inventory/manajemenstok/pengelolaanmms/edit') }}'+'/'+$("#idx").val(), $("#formEditSafetyStock").serialize())
                        .then(function (response) {
                            if (response.data.status == "Success") {
                                loadingHide();
                                messageSuccess("Berhasil", response.data.message);
                            } else if (response.data.status == "Failed") {
                                loadingHide();
                                messageFailed("Gagal", response.data.message)
                            }
                        })
                        .catch(function (error) {
                            loadingHide();
                            messageWarning("Error", error);
                        });
                }
            })
        });
    </script>
@endsection
