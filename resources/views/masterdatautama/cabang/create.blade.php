@extends('main')

@section('content')
<article class="content animated fadeInLeft">
    <div class="title-block text-primary">
        <h1 class="title"> Tambah Data Cabang </h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Master Data Utama</span>
            / <a href="{{route('cabang.index')}}"><span>Data Cabang</span></a>
            / <span class="text-primary" style="font-weight: bold;"> Tambah Data Cabang</span>
        </p>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Tambah Data Cabang </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a href="{{route('cabang.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <form id="formAdd" autocomplete="off">
                        <div class="card-block">
                            <section>
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Nama Cabang <span style="color:red;">*</span></label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" id="cabang_name" name="cabang_name" style="text-transform: uppercase;" title="required: branch name">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Alamat Cabang <span style="color:red;">*</span></label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <textarea type="text" class="form-control form-control-sm" id="cabang_address" name="cabang_address" title="required: address"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Area (Provinsi) <span style="color:red;">*</span></label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select class="form-control select2" name="cabang_prov" id="cabang_prov">
                                                <option value="" selected disabled>Pilih Provinsi</option>
                                                @foreach($data['provinces'] as $prov)
                                                <option value="{{ $prov->wp_id }}">{{ $prov->wp_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Area (Kota) <span style="color:red;">*</span></label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select class="form-control select2" name="cabang_city" id="cabang_city">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>No Telp <span style="color:red;">*</span></label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm hp" id="cabang_telp" name="cabang_telp" placeholder="0853 xxx" title="required: no telp">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Tipe Company</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                          <input type="text" class="form-control" name="cabang_type" value="CABANG" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Pemilik Cabang</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select id="cabang_user" class="form-control form-control-sm select2" name="cabang_user">
                                                <option value="" selected disabled>Pilih Pemilik Cabang</option>
                                                @foreach($employe as $emp)
                                                <option value="{{$emp->e_id}}">{{$emp->e_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary btn-submit" type="button" id="btn_simpan">Simpan</button>
                            <a href="{{route('cabang.index')}}" class="btn btn-secondary">Kembali</a>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#btn_simpan').on('click', function() {
            $.ajax({
                url: "{{route('cabang.store')}}",
                type: "get",
                data: $('#formAdd').serialize(),
                dataType: "json",
                beforeSend: function() {
                    loadingShow();
                },
                success: function(response) {
                    if (response.status == 'sukses') {
                        loadingHide();
                        messageSuccess('Berhasil', 'Data berhasil ditambahkan !');
                        window.location.href = "{{route('cabang.index')}}";
                    } else {
                        loadingHide();
                        messageFailed('Gagal', response.message);
                    }
                },
                error: function(e) {
                    loadingHide();
                    messageWarning('Peringatan', e.message);
                }
            });
        });

        $('#cabang_prov').on('change', function() {
            getCities();
        });

        $('#cabang_city').on('select2:select', function() {
            $('#cabang_telp').focus();
        });
    });

    function getCities()
    {
        $.ajax({
            url: "{{ route('cabang.getCities') }}",
            data: {
                provId: $('#cabang_prov').val()
            },
            type: "get",
            success: function (response) {
                console.log(response);
                $('#cabang_city').empty();
                opt = '<option selected disabled>Pilih Kota</option>';
                $.each(response, function(key, val) {
                    opt += '<option value="'+ val.wc_id +'">'+ val.wc_name +'</option>';
                })
                $('#cabang_city').append(opt);
                $('#cabang_city').focus();
            },
            error: function (err) {
                console.log(err);
                messageWarning('Error', err + ', Hubungi pengembang !')
            }
        })
    }

</script>
@endsection
