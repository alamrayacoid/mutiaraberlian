@extends('main')

@section('content')
<article class="content animated fadeInLeft">
    <div class="title-block text-primary">
        <h1 class="title"> Edit Data Cabang </h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Master Data Utama</span>
            / <a href="{{route('cabang.index')}}"><span>Data Cabang</span></a>
            / <span class="text-primary" style="font-weight: bold;"> Edit Data Cabang</span>
        </p>
    </div>
    <section class="section">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Edit Data Cabang </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a href="{{route('cabang.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>

                    <form action="{{ route('cabang.edit', Crypt::encrypt([$data['agen']->a_id])) }}" method="post" id="myForm" autocomplete="off">
                        <div class="card-block">
                            <section>

                                <div class="row">

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Kode Agen</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" name="code" id="code" value="{{ $data['agen']->a_code }}" readonly="">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Area (Provinsi)</label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="hidden" id="area_prov_hidden" value="{{ $data['area_prov'] }}">
                                            <select id="area_prov" class="select2 form-control form-control-sm" name="area_prov" tabindex="1">
                                                <option value="">Pilih Provinsi</option>
                                                @foreach($data['provinces'] as $prov)
                                                    <option value="{{ $prov->wp_id }}">{{ $prov->wp_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Area (Kota)</label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="hidden" id="area_city_hidden" value="{{ $data['agen']->a_area }}">
                                            <select id="area_city" class="select2 form-control form-control-sm" name="area_city" tabindex="2">
                                                <option value="">Pilih Kota/Kabupaten</option>
                                                @foreach($data['area_cities'] as $city)
                                                    <option value="{{ $city->wc_id }}">{{ $city->wc_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Tempat Order (MMA/Pusat)</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select id="mma" class="select2 form-control form-control-sm" name="mma">
                                                <option value="" selected disabled>Pilih MMA</option>
                                                @foreach($data['mma'] as $mma)
                                                    @if($mma->c_id == $data['agen']->a_mma)
                                                        <option value="{{ $mma->c_id }}" selected>{{ $mma->c_name }}</option>
                                                    @else
                                                        <option value="{{ $mma->c_id }}">{{ $mma->c_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Nama Agen</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" name="name" value="{{ $data['agen']->a_name }}" tabindex="3">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Jenis Kelamin</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select name="jekel" id="jekel" class="form-control form-control-sm select2">
                                                <option value="L" @if($data['agen']->a_sex == "L") selected @endif>Laki - laki</option>
                                                <option value="P" @if($data['agen']->a_sex == "P") selected @endif>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Tipe Agen</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="hidden" name="type_hidden" id="type_hidden" value="{{ $data['agen']->a_type }}">
                                            <input type="hidden" id="has_subagent" value="{{ $data['has_subagent'] }}">
                                            <select id="type" class="select2 form-control form-control-sm" name="type">
                                                <option value="MMA">MMA/Cabang</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Harga Penjualan ke Customer</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select id="a_salesprice" class="select2 form-control form-control-sm" name="a_salesprice">
                                                <option value="" selected="" disabled>Pilih Jenis Harga Penjualan</option>
                                                @foreach($data['salesPrice'] as $class)
                                                    <option value="{{ $class->sp_id }}" @if($data['agen']->a_salesprice == $class->sp_id) selected @endif>{{ $class->sp_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Tanggal Lahir</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm datepicker" id="birthday" name="birthday" value="{{ date('d-m-Y', strtotime($data['agen']->a_birthday)) }}" tabindex="5">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Alamat (Provinsi)</label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="hidden" id="address_prov_hidden" value="{{ $data['agen']->a_provinsi }}">
                                            <select id="address_prov" class="select2 form-control form-control-sm" name="address_prov" tabindex="6">
                                                <option value="">Pilih Provinsi</option>
                                                @foreach($data['provinces'] as $prov)
                                                    <option value="{{ $prov->wp_id }}">{{ $prov->wp_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Alamat (Kota/Kabupaten)</label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="hidden" id="address_city_hidden" value="{{ $data['agen']->a_kabupaten }}">
                                            <select id="address_city" class="select2 form-control form-control-sm" name="address_city">
                                                <option value="">Pilih Kota/Kabupaten</option>
                                                @foreach($data['address_cities'] as $city)
                                                    <option value="{{ $city->wc_id }}">{{ $city->wc_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Alamat (Kecamatan)</label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="hidden" id="address_district_hidden" value="{{ $data['agen']->a_kecamatan }}">
                                            <select id="address_district" class="select2 form-control form-control-sm" name="address_district">
                                                <option value="">Pilih Kecamatan</option>
                                                @foreach($data['address_districts'] as $district)
                                                    <option value="{{ $district->wk_id }}">{{ $district->wk_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Alamat (Desa)</label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="hidden" id="address_village_hidden" value="{{ $data['agen']->a_desa }}">
                                            <select id="address_village" class="select2 form-control form-control-sm" name="address_village">
                                                <option value="">Pilih Desa</option>
                                                @foreach($data['address_villages'] as $village)
                                                    <option value="{{ $village->wd_id }}">{{ $village->wd_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Alamat Agen</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <textarea type="text" class="form-control form-control-sm" name="address" tabindex="7">{{ $data['agen']->a_address }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Email</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm email" name="email" value="{{ $data['agen']->a_email }}" placeholder="user@email.com" tabindex="8">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>No Telp</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm hp" name="telp" value="{{ $data['agen']->a_telp }}" tabindex="8">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Foto</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="file" class="form-control form-control-sm" name="photo" id="photo" accept="image/*">
                                            <input type="hidden" name="current_photo" value="{{ $data['agen']->a_img }}">
                                        </div>
                                    </div>
                                    <div class="col-12" align="center">
                                        <div class="form-group">
                                            @if($data['agen']->a_img != null)
                                                <img src="{{ asset('storage/app/'. $data['agen']->a_img) }}" id="img-preview" style="cursor: pointer; max-height: 254px;max-width: 100%;" class="img-thumbnail">
                                            @else
                                                <img src="{{ asset('assets/img/add-image-icon2.png') }}" id="img-preview" style="cursor: pointer; max-height: 254px;max-width: 100%;" class="img-thumbnail">
                                            @endif
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
            // $('#type_hidden').val($('#type').val());
            if ($('#type').val() == 'SUB AGEN' || $('#type').val() == 'APOTEK/RADIO') {
                $('.userlogin').hide();
                $('.div_parent').show();
                $('.harga-beli').show();
            } else if ($('#type').val() == 'MMA'){
                $('.harga-beli').hide();
                $('.div_parent').hide();
                $('.userlogin').show();
                $('#parent').empty();
            } else {
                $('.userlogin').show();
                $('.harga-beli').show();
                $('.div_parent').hide();
                $('#parent').empty();
            }
            if ($('#type').val() == 'SUB AGEN'){
                $('.userlogin').show();
            }

        })

        $('#type').on('change', function() {
            $('#type_hidden').val($('#type').val());
            if ($(this).val() == 'SUB AGEN' || $(this).val() == 'APOTEK/RADIO') {
                $('#parent_city').empty();
                $('#parent').empty();
                $('#parent_prov').val('').trigger('change');
                $('.userlogin').hide();
                $('.div_parent').show();
                $('.harga-beli').show();
            } else if ($(this).val() == 'MMA'){
                $('.harga-beli').hide();
                $('.div_parent').hide();
                $('.userlogin').show();
                $('#parent').empty();
            } else {
                $('.userlogin').show();
                $('.harga-beli').show();
                $('.div_parent').hide();
                $('#parent').empty();
            }
            if ($(this).val() == 'SUB AGEN'){
                $('.userlogin').show();
            }
        });

        $('#parent_prov').on('change', function() {
            $.ajax({
                type: 'get',
                url: baseUrl + '/masterdatautama/agen/cities/' + $('#parent_prov').val(),
                success: function(data) {
                    $('#parent_city').empty();
                    $("#parent_city").append('<option value="" selected>Kota Agen</option>');
                    $.each(data, function(key, val) {
                        $("#parent_city").append('<option value="' + val.wc_id + '">' + val.wc_name + '</option>');
                    });
                    $('#parent_city').focus();
                    $('#parent_city').select2('open');
                }
            });
        });

        $('#parent_city').on('change', function() {
            RetrieveListAgents();
        });

        function RetrieveListAgents() {
            $.ajax({
                type: 'get',
                url: baseUrl + '/masterdatautama/agen/getAgenByCity/' + $('#parent_city').val(),
                success: function(data) {
                    $('#parent').empty();
                    $.each(data.data, function(key, val) {
                        if (val.a_code == null){
                            alert('Agen tidak ditemukan');
                        } else {
                            $("#parent").append('<option value="' + val.a_code + '">' + val.a_name + '</option>');
                        }
                    });
                    $('#parent').focus();
                    $('#parent').select2('open');
                }
            });
        }

        // hide datepicker after select any date
        $('#birthday').on('changeDate', function() {
            $(this).datepicker('hide');
        })

        // set request when area_prov changed
        // set option list area_city
        $('#area_prov').on('change', function() {
            $.ajax({
                type: 'get',
                url: baseUrl + '/masterdatautama/agen/cities/' + $('#area_prov').val(),
                success: function(data) {
                    $('#area_city').empty();
                    // $("#area_city").append('<option value="" selected="">-- Pilih Item --</option>');
                    $.each(data, function(key, val) {
                        $("#area_city").append('<option value="' + val.wc_id + '">' + val.wc_name + '</option>');
                    });
                    $('#area_city').focus();
                    $('#area_city').select2('open');
                }
            });
        })

        // set request when address_prov changed
        // set option list address_city
        $('#address_prov').on('change', function() {
            $.ajax({
                type: 'get',
                url: baseUrl + '/masterdatautama/agen/cities/' + $('#address_prov').val(),
                success: function(data) {
                    $('#address_city').empty();
                    $('#address_district').empty();
                    $('#address_village').empty();
                    $.each(data, function(key, val) {
                        $("#address_city").append('<option value="' + val.wc_id + '">' + val.wc_name + '</option>');
                    });
                    $('#address_city').focus();
                    $('#address_city').select2('open');
                }
            });
        })

        // set request when address_city changed
        // set option list address_district
        $('#address_city').on('change', function() {
            $.ajax({
                type: 'get',
                url: baseUrl + '/masterdatautama/agen/districts/' + $('#address_city').val(),
                success: function(data) {
                    $('#address_district').empty();
                    $('#address_village').empty();
                    $.each(data, function(key, val) {
                        $("#address_district").append('<option value="' + val.wk_id + '">' + val.wk_name + '</option>');
                    });
                    $('#address_district').focus();
                    $('#address_district').select2('open');
                }
            });
        })

        // set request when address_district changed
        // set option list address_village
        $('#address_district').on('change', function() {
            $.ajax({
                type: 'get',
                url: baseUrl + '/masterdatautama/agen/villages/' + $('#address_district').val(),
                success: function(data) {
                    $('#address_village').empty();
                    $.each(data, function(key, val) {
                        $("#address_village").append('<option value="' + val.wd_id + '">' + val.wd_name + '</option>');
                    });
                    $('#address_village').focus();
                    $('#address_village').select2('open');
                }
            });
        })

        function readURL(input, target) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(target).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#photo").change(function() {
            readURL(this, '#img-preview');
        });
        $('#img-preview').click(function() {
            $('#photo').click();
        });

        $('#btn_simpan').on('click', function() {
            SubmitForm(event);
        })
        // start: submit form to update data in db

        function SubmitForm(event) {
            loadingShow();
            event.preventDefault();
            form_data = new FormData($('#myForm')[0]);

            $.ajax({
                data: form_data,
                type: "post",
                processData: false,
                contentType: false,
                enctype: "multipart/form-data",
                url: $("#myForm").attr('action'),
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'berhasil') {
                        loadingHide();
                        messageSuccess('Berhasil', 'Data berhasil disimpan !');
                        // location.href = '{{ url("masterdatautama/agen/index") }}';
                        location.reload();
                    } else if (response.status == 'invalid') {
                        loadingHide();
                        messageFailed('Perhatian', response.message);
                    } else if (response.status == 'gagal') {
                        loadingHide();
                        messageWarning('Error', response.message);
                    }
                },
                error: function(e) {
                    loadingHide();
                    messageWarning('Gagal', 'Data gagal disimpan, hubungi pengembang !');
                }
            })

        }

        // set default/selected value for select2 (area, type agen, and address)
        class_hidden = $('#class_hidden').val();
        $("#a_class option[value='" + class_hidden + "']").prop("selected", true);
        type_hidden = $('#type_hidden').val();
        $("#type option[value='" + type_hidden + "']").prop("selected", true);
        area_prov_hidden = $('#area_prov_hidden').val();
        $("#area_prov option[value=" + area_prov_hidden + "]").prop("selected", true);
        area_city_hidden = $('#area_city_hidden').val();
        $("#area_city option[value=" + area_city_hidden + "]").prop("selected", true);
        console.log($('#address_village_hidden').val());

        if ($('#address_village_hidden').val() != "") {
            address_prov_hidden = $('#address_prov_hidden').val();
            $("#address_prov option[value=" + address_prov_hidden + "]").prop("selected", true);
            address_city_hidden = $('#address_city_hidden').val();
            $("#address_city option[value=" + address_city_hidden + "]").prop("selected", true);
            address_district_hidden = $('#address_district_hidden').val();
            $("#address_district option[value=" + address_district_hidden + "]").prop("selected", true);
            address_village_hidden = $('#address_village_hidden').val();
            $("#address_village option[value=" + address_village_hidden + "]").prop("selected", true);
        }

    </script>
@endsection
