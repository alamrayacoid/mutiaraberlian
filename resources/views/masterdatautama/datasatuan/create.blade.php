@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Data Satuan </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Master Data Utama</span>
         / <a href="{{route('datasatuan.index')}}"><span>Data Satuan</span></a>
         / <span class="text-primary" style="font-weight: bold;">Tambah Data Satuan</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">

        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title">Tambah Data Satuan</h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('datasatuan.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <form class="" action="{{ route('datasatuan.store') }}" method="post" id="myForm", autocomplete="off">
                      {{ csrf_field() }}
                      <div class="card-block">
                        <section>
                          <div class="row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Kode Satuan</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="satuan_id" value="{{ $id }}" readonly="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nama Satuan</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="satuan_name">
                              </div>
                            </div>
                          </div>

                        </section>
                      </div>
                      <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" id="btn_simpan" type="button">Simpan</button>
                      <a href="{{route('datasatuan.index')}}" class="btn btn-secondary">Kembali</a>
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
  // set header token for ajax request
  $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

  $('#btn_simpan').on('click', function()
  {
    SubmitForm(event);
  });

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
      success : function (response){
        if(response.status == 'berhasil'){
          $.toast({
            heading: 'Success',
            text: 'Data berhasil ditambahkan !',
            bgColor: '#00b894',
            textColor: 'white',
            loaderBg: '#55efc4',
            icon: 'success',
            stack: false,
            hideAfter: 1500,
            afterHidden: function() {
              window.location.href = "{{ route('datasatuan.index') }}";
            }
          });
        } else if (response.status == 'invalid') {
          $.toast({
            heading: 'Perhatian',
            text: response.message,
            bgColor: '#00b894',
            textColor: 'white',
            loaderBg: '#55efc4',
            icon: 'warning',
            stack: false,
            hideAfter: 2000
          });
        }
      },
      error : function(e){
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

</script>
@endsection
