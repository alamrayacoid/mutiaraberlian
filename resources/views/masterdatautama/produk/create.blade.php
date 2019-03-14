@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Tambah Data Produk </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Master Data Utama</span>
                / <a href="{{route('dataproduk.index')}}"><span>Data Produk</span></a>
                / <span class="text-primary font-weight-bold">Tambah Data Produk</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title">Tambah Data Produk</h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('dataproduk.index')}}" class="btn btn-secondary"><i
                                        class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <form action="{{ route('dataproduk.store') }}" method="post" id="myForm" autocomplete="off"
                              enctype="multipart/form-data">
                            <div class="card-block">
                                <section>
                                    <div class="row">

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Nama Produk</label>
                                        </div>
                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" style="text-transform: uppercase;"
                                                       class="form-control form-control-sm" name="dataproduk_name">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Jenis Produk</label>
                                        </div>
                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select class="form-control select2 form-control-sm" name="dataproduk_type">
                                                    <option value="" selected="" disabled="">--Pilih Jenis Produk--
                                                    </option>
                                                    @foreach ($jenis as $key => $value)
                                                        <option value="{{$value->it_id}}">{{$value->it_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Kode Produk</label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm"
                                                       name="dataproduk_code">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">

                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">

                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Satuan Utama</label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select class="form-control select2 form-control-sm"
                                                        name="dataproduk_satuanutama">
                                                    <option value="">--Pilih--</option>
                                                    @foreach ($satuan as $key => $value)
                                                        <option value="{{$value->u_id}}">{{$value->u_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Isi Satuan Utama</label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="number" class="form-control-sm form-control" min="0"
                                                       value="1" name="dataproduk_isisatuanutama" readonly="">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Satuan Alternatif 1</label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select class="form-control select2 form-control-sm"
                                                        name="dataproduk_satuanalt1">
                                                    <option value="">--Pilih--</option>
                                                    @foreach ($satuan as $key => $value)
                                                        <option value="{{$value->u_id}}">{{$value->u_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Isi Satuan Alternatif 1</label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="number" class="form-control-sm form-control" min="0"
                                                       name="dataproduk_isisatuanalt1">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Satuan Alternatif 2</label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select class="form-control select2 form-control-sm"
                                                        name="dataproduk_satuanalt2">
                                                    <option value="">--Pilih--</option>
                                                    @foreach ($satuan as $key => $value)
                                                        <option value="{{$value->u_id}}">{{$value->u_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Isi Satuan Alternatif 2</label>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="number" class="form-control-sm form-control" min="0"
                                                       name="dataproduk_isisatuanalt2">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Keterangan</label>
                                        </div>
                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <textarea class="form-control" name="dataproduk_ket"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Foto</label>
                                        </div>
                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="file" class="form-control form-control-sm" name="e_foto"
                                                       id="foto" multiple accept="image/*">
                                            </div>
                                        </div>

                                        <div class="col-12" align="center">
                                            <div class="form-group">
                                                <img src="{{asset('assets/img/add-image-icon.png')}}" height="120px"
                                                     width="130px" id="img-preview" style="cursor: pointer;">
                                            </div>
                                        </div>

                                    </div>
                                </section>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary btn-submit" type="button" id="btn_simpan">Simpan</button>
                                <a href="{{route('dataproduk.index')}}" class="btn btn-secondary">Kembali</a>
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

        $('#btn_simpan').on('click', function () {
            SubmitForm(event);
        });

        // submit form to store data in db
        function SubmitForm(event)
        {
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apakah anda yakin ingin menyimpan data?, satuan dan isi satuan tidak dapat diubah,',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text:'Ya',
                        action : function(){
                          event.preventDefault();
                          var file_data = $('#foto').prop('files')[0];
                          var form_data = new FormData();
                          form_data.append('file', file_data);
                          $.ajax({
                              data: form_data,
                              type: "post",
                              url: $("#myForm").attr('action') + '?' + $('#myForm').serialize(),
                              dataType: 'json',
                              cache: false,
                              contentType: false,
                              processData: false,
                              success: function (response) {
                                  if (response.status == 'berhasil') {
                                      $.toast({
                                          heading: 'Success',
                                          text: 'Data berhasil ditambahkan!, data akan membutuhkan otorisasi terlebih dahulu',
                                          bgColor: '#00b894',
                                          textColor: 'white',
                                          loaderBg: '#55efc4',
                                          icon: 'success',
                                          stack: false,
                                          hideAfter: 1500,
                                          afterHidden: function () {
                                              window.location.href = "{{ route('dataproduk.index') }}";
                                          }
                                      });
                                  } else if (response.status == 'invalid') {
                                      messageWarning('Warning', response.message);
                                  }
                              },
                              error: function (e) {
                                  $.toast({
                                      heading: 'Warning',
                                      text: e.message,
                                      bgColor: '#00b894',
                                      textColor: 'white',
                                      loaderBg: '#55efc4',
                                      icon: 'warning',
                                      stack: false
                                  });
                              }
                          });
                        }
                    },
                    cancel:{
                        text: 'Tidak',
                        action: function () {
                            // tutup confirm
                        }
                    }
                }
            });
        }


        // start: unused -> confirmed and deleted soon
        // $(document).ready(function(){
        //   $(document).on('click', '.btn-submit', function(){
        // 		$.toast({
        // 			heading: 'Success',
        // 			text: 'Data Berhasil di Simpan',
        // 			bgColor: '#00b894',
        // 			textColor: 'white',
        // 			loaderBg: '#55efc4',
        // 			icon: 'success'
        // 		})
        // 	})
        // });
        // end: unused


    </script>
    <script type="text/javascript">
        $(document).ready(function () {

            function readURL(input, target) {

                if (input.files && input.files[0]) {
                    var fsize = $('#foto')[0].files[0].size;
                    if (fsize > 1048576) //do something if file size more than 1 mb (1048576)
                    {
                        messageWarning('Warning', 'File is to big!');
                        return false;
                    } else {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $(target).attr('src', e.target.result);
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }
            }

            $("#foto").change(function () {
                readURL(this, '#img-preview');
            });

            $('#img-preview').click(function () {

                $('#foto').click();

            });

        });
    </script>
@endsection
