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

                    <form action="{{ route('cabang.create') }}" method="post" id="myForm" autocomplete="off">
                      <div class="card-block">
                        <section>

                          <div class="row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nama Cabang</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" id="cabang_name" name="cabang_name" style="text-transform: uppercase;">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Alamat Cabang</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <textarea type="text" class="form-control form-control-sm" id="cabang_address" name="cabang_address"></textarea>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>No Telp</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="number" class="form-control form-control-sm" id="cabang_telp" name="cabang_telp">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Tipe Cabang</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select id="cabang_type" class="form-control form-control-sm" name="cabang_type">
                                  <option value="PUSAT" selected="">Pusat</option>
                                  <option value="CABANG">Cabang</option>
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

  $('#btn_simpan').on('click', function() {
    SubmitForm(event);
  });

  function resetForm()
  {
    $('#cabang_name').val('');
    $('#cabang_address').val('');
    $('#cabang_telp').val('');
    $('#cabang_type').val('PUSAT');
  }

  // submit form to store data in db
  function SubmitForm(event)
  {
    event.preventDefault();
    form_data = $('#myForm').serialize();

    $.ajax({
      data : form_data,
      type : "post",
      url : $("#myForm").attr('action'),
      dataType : 'json',
      beforeSend: function() {
        loadingShow();
      },
      success : function (response){
        if(response.status == 'berhasil'){
          loadingHide();
          messageSuccess('Success', 'Data berhasil ditambahkan!');
          resetForm();
        } else if (response.status == 'invalid') {
          loadingHide();
          messageWarning('Perhatian', response.message);
        }
      },
      error : function(e){
        loadingHide();
        messageWarning('Warning', e.message);
      }
    })

  }


  // start: unused -> confirmed and deleted soon
  // $(document).ready(function(){
  //   $('#type_cus').change(function(){
  //     if($(this).val() === 'kontrak'){
  //       $('#label_type_cus').text('Jumlah Bulan');
  //       $('#jumlah_hari_bulan').val('');
  //       $('#pagu').val('');
  //       $('#armada').prop('selectedIndex', 0).trigger('change');
  //       $('.120mm').removeClass('d-none');
  //       $('.125mm').addClass('d-none');
  //       $('.122mm').removeClass('d-none');
  //     } else if($(this).val() === 'harian'){
  //       $('#label_type_cus').text('Jumlah Hari');
  //       $('#armada').prop('selectedIndex', 0).trigger('change');
  //       $('#pagu').val('');
  //       $('#jumlah_hari_bulan').val('');
  //       $('.122mm').addClass('d-none');
  //       $('.120mm').removeClass('d-none');
  //       $('.125mm').removeClass('d-none');
  //     } else {
  //       $('#jumlah_hari_bulan').val('');
  //       $('#armada').prop('selectedIndex', 0).trigger('change');
  //       $('#pagu').val('');
  //       $('.122mm').addClass('d-none');
  //       $('.120mm').addClass('d-none');
  //       $('.125mm').addClass('d-none');
  //     }
  //   });
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
@endsection
