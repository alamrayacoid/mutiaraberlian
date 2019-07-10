@extends('main')

@section('content')


    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Pengelolaan Manajemen Stok </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Inventory</span>
                / <a href="{{route('dataharga.index')}}"><span>Penglolaan Data Max/Min Stok, Safety Stok</span></a>
                / <span class="text-primary" style="font-weight: bold;">Tambah Data Max/Min Stok, Safety Stok</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title">Tambah Data Max/Min Stok, Safety Stok</h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('pengelolaanmms.index')}}" class="btn btn-secondary"><i
                                        class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <form id="formSafetyStock">
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
                                                        <option
                                                            value="{{ $company->c_id }}">{{ strtoupper($company->c_name) }}</option>
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
                                                            value="{{ $company->c_id }}">{{ strtoupper($company->c_name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Nama Barang</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" id="barang" name="barang"
                                                       class="form-control form-control-sm"
                                                       autocomplete="off">
                                                <input type="hidden" name="idBarang" id="idBarang">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Min Stok</label>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="number" min="0" name="minStock" id="minStock"
                                                       class="form-control form-control-sm" value="0">
                                            </div>
                                        </div>

                                        <div class="col-md-1 col-sm-6 col-xs-12">
                                            <label>Max Stok</label>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="number" min="0" name="maxStock" id="maxStock"
                                                       class="form-control form-control-sm" value="0">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Safety Stok</label>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="number" min="0" class="form-control-sm form-control"
                                                       name="firstRange"
                                                       id="firstRange"
                                                       value="0">
                                            </div>
                                        </div>

                                        <div class="col-md-1 col-sm-6 col-xs-12">
                                            <label class="col-md-12 m-auto text-center">&mdash;</label>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="number" min="0"
                                                       class="form-control-sm form-control"
                                                       name="secondRange" id="secondRange" value="0">
                                            </div>
                                        </div>

                                    </div>

                                </section>
                            </div>

                            <div class="card-footer text-right">
                                <button class="btn btn-primary btn-submit" type="submit">Simpan</button>
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
            $("#barang").on("keyup", function(){
                $("#idBarang").val('');
            })

            $( "#barang" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: '{{ route('pengelolaanmms.caribarang') }}',
                        data: {
                            term: $("#barang").val()
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                minLength: 1,
                select: function(event, data) {
                    $("#idBarang").val(data.item.id);
                }
            });

            $("#formSafetyStock").on("submit", function(evt){
                evt.preventDefault();
                var pemilik = $("#pemilik").val(), posisi = $("#posisi").val(), barang = $("#idBarang").val();
                if (pemilik == "") {
                    $("#pemilik").focus();
                    messageWarning("Peringatan", "Pilih pemilik barang");
                } else if (posisi == "") {
                    $("#posisi").focus();
                    messageWarning("Peringatan", "Pilih posisi barang");
                } else if (barang == "") {
                    $("#barang").focus();
                    messageWarning("Peringatan", "Pilih nama barang dengan benar");
                } else {
                    loadingShow();
                    axios.post('{{ route('pengelolaanmms.addpengelolaanms') }}', $("#formSafetyStock").serialize())
                        .then(function (response) {
                            if (response.data.status == "Success") {
                                loadingHide();
                                $("#formSafetyStock")[0].reset();
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
