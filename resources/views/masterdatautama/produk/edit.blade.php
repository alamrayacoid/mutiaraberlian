@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Edit Data Produk </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Master Data Utama</span>
         / <a href="{{route('dataproduk.index')}}"><span>Data Produk</span></a>
         / <span class="text-primary font-weight-bold">Edit Data Produk</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">

        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title">Edit Data Produk</h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('dataproduk.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <form action="{{ route('dataproduk.update', [$data['dataproduk']->i_id]) }}" method="post" id="myForm" autocomplete="off">
                      <div class="card-block">
                        <section>

                          <div class="row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nama Produk</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="dataproduk_name" value="{{ $data['dataproduk']->i_name }}">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Jenis Produk</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="hidden" value="{{ $data['dataproduk']->i_type }}" id="type">
                                <select class="form-control form-control-sm" name="dataproduk_type" id="dataproduk_type">
                                  <option value="">--Pilih Jenis Produk--</option>
                                  <option value="BB">Bahan Baku</option>
                                  <option value="SP">Spare Part</option>
                                  <option value="BJ">Barang Jual</option>
                                  <option value="LL">Lain-lain</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Kode Produk</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="dataproduk_code"  value="{{ $data['dataproduk']->i_code }}">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Min Stock</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="number" class="form-control form-control-sm" name="dataproduk_minstock">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Satuan Utama</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select class="form-control form-control-sm" name="dataproduk_satuanutama">
                                  <option value="">--Pilih--</option>
                                  <option value="1">Kardus</option>
                                  <option value="2">Pcs</option>
                                  <option value="3">Kg</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Isi Satuan Utama</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="number" class="form-control-sm form-control" min="0" name="dataproduk_isisatuanutama">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Satuan Alternatif 1</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select class="form-control form-control-sm" name="dataproduk_satuanalt1">
                                  <option value="">--Pilih--</option>
                                  <option value="1">Kardus</option>
                                  <option value="2">Pcs</option>
                                  <option value="3">Kg</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Isi Satuan Alternatif 1</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="number" class="form-control-sm form-control" min="0" name="dataproduk_isisatuanalt1">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Satuan Alternatif 2</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select class="form-control form-control-sm" name="dataproduk_satuanalt2">
                                  <option value="">--Pilih--</option>
                                  <option value="1">Kardus</option>
                                  <option value="2">Pcs</option>
                                  <option value="3">Kg</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Isi Satuan Alternatif 2</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="number" class="form-control-sm form-control" min="0" name="dataproduk_isisatuanalt2">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Keterangan</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <textarea class="form-control" name="dataproduk_ket">{{ $data['dataproduk']->i_det }}</textarea>
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

  $('#btn_simpan').on('click', function() {
    SubmitForm(event);
  })
  // start: submit form to update data in db
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
            text: 'Data berhasil disimpan !',
            bgColor: '#00b894',
            textColor: 'white',
            loaderBg: '#55efc4',
            icon: 'success',
            stack: false,
            hideAfter: 1500
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
    })

  }
  // end: submit form

  $(document).ready(function(){
    type = $('#type').val();
    $("#dataproduk_type option[value='"+ type +"']").prop('selected', 'selected');

    // $(document).on('click', '.btn-submit', function(){
		// 	$.toast({
		// 		heading: 'Success',
		// 		text: 'Data Berhasil di Edit',
		// 		bgColor: '#00b894',
		// 		textColor: 'white',
		// 		loaderBg: '#55efc4',
		// 		icon: 'success'
		// 	})
		// })
  });
</script>
@endsection
