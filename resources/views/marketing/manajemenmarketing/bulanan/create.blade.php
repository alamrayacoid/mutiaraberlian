@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Tambah Data Promosi Bulanan </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Marketing</span>
                / <a href="{{route('mngmarketing.index')}}"><span>Manajemen Marketing</span></a>
                / <span class="text-primary font-weight-bold">Tambah Data Promosi Bulanan</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title">Tambah Data Promosi Bulanan</h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('mngmarketing.index')}}" class="btn btn-secondary"><i
                                        class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-block">
                            <section>


                                <div class="row" id="form-tambah">

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Judul Promosi</label>
                                    </div>

                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" id="judul_promosi" name="judul">
                                        </div>
                                    </div>


                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Bulan</label>
                                    </div>

                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" id="bulan_promosi"
                                                   name="bulan">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Output Target</label>
                                    </div>

                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <textarea class="form-control output" name="output"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Outcome Target</label>
                                    </div>

                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <textarea class="form-control outcome" name="outcome"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Impact Target</label>
                                    </div>

                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <textarea class="form-control impact" name="impact"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Biaya Promosi</label>
                                    </div>

                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm input-rupiah budget"
                                                   name="budget" value="0">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Catatan</label>
                                    </div>

                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <textarea class="form-control catatan" name="note"></textarea>
                                        </div>
                                    </div>

                                </div>


                            </section>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                            <a href="{{route('mngmarketing.index')}}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>

                </div>

            </div>

        </section>

    </article>

@endsection
@section('extra_script')
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '.btn-submit', function () {
                let judul = $('#judul_promosi').val();
                let bulan = $('#bulan_promosi').val();
                let output = $('.output').val();
                let outcome = $('.outcome').val();
                let impact = $('.impact').val();
                let catatan = $('.catatan').val();
                let budget = $('.budget').val();
                let valid = 1;
                if (judul == ''){
                    valid = 0;
                    messageWarning("Perhatian", "Judul promosi tidak boleh kosong");
                    $('#judul_promosi').focus();
                    return false;
                }
                if (bulan == ''){
                    valid = 0;
                    messageWarning("Perhatian", "Bulan tidak boleh kosong");
                    $('#bulan_promosi').focus();
                    return false;
                }
                if (output == ''){
                    valid = 0;
                    messageWarning("Perhatian", "Output target tidak boleh kosong");
                    $('.output').focus();
                    return false;
                }
                if (outcome == ''){
                    valid = 0;
                    messageWarning("Perhatian", "Outcome target tidak boleh kosong");
                    $('.outcome').focus();
                    return false;
                }
                if (impact == ''){
                    valid = 0;
                    messageWarning("Perhatian", "Impact target tidak boleh kosong");
                    $('.impact').focus();
                    return false;
                }
                if (valid == 1){
                    budget = parseInt(convertToAngka(budget));
                    if (budget == '' || budget == null){
                        budget = 0;
                    }
                    axios.post('{{ route("monthpromotion.save") }}', {
                        "judul": judul,
                        "bulan": bulan,
                        "output": output,
                        "outcome": outcome,
                        "impact": impact,
                        "note": catatan,
                        "budget": budget,
                        "_token": "{{ csrf_token() }}"
                    }).then(function (response) {
                        if (response.data.status == 'success'){
                            messageSuccess("Sukses", "Data berhasil disimpan");
                            window.location.href = "{{ route('mngmarketing.index') }}";
                        } else if (response.data.status == 'gagal'){
                            messageFailed("Gagal", response.data.message);
                        }
                    })
                }
            })

            $("#bulan_promosi").datepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true
            });

        });
    </script>
@endsection

