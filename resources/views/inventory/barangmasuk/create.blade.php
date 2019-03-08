@extends('main')

@section('content')

<article class="content">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Barang Masuk </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas Inventory</span>
         / <a href="#"><span>Barang Masuk</span></a>
         / <span class="text-primary font-weight-bold">Tambah Barang Masuk</span>
       </p>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block">
              <h3 class="title">Tambah Barang Masuk</h3>
            </div>
            <div class="header-block pull-right">
              <a href="{{ route('barangmasuk.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>
          <div class="card-block">
            <section>
              <form action="" id="formAdd" autocomplete="off">
              <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <label>Nama Barang</label>
                </div>

                <div class="col-md-9 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input type="hidden" name="idItem" id="idItem" onchange="getUnit()">
                    <input type="text" class="form-control form-control-sm" name="s_item" id="namaItem" style="text-transform:uppercase">
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                  <label>Keterangan Barang Masuk</label>
                </div>

                <div class="col-md-9 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <select class="form-control form-control-sm select2" name="m_mutcat">
                      <option value="" disabled="" selected="">== Pilih Keterangan ==</option>
                      @foreach($mutcat as $ket)
                        <option value="{{$ket->m_id}}">{{$ket->m_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                  <label>Satuan</label>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <select name="m_unit" id="satuan" class="form-control form-control-sm select2">
                      <option value="" disabled selected>== Pilih Satuan ==</option>
                    </select>
                  </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <label>Lokasi Barang</label>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="form-group">
                      <select name="s_position" id="lokasi" class="form-control form-control-sm select2">
                        <option value="" disabled selected>== Pilih Lokasi Barang ==</option>
                        @foreach($company as $lokasi)
                          <option value="{{$lokasi->c_id}}">{{$lokasi->c_name}}</option>
                        @endforeach
                      </select>
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                  <label>Jumlah Barang</label>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input type="number" class="form-control form-control-sm" name="s_qty">
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <label>Pemilik Barang</label>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <select name="s_comp" id="pemilik" class="form-control form-control-sm select2">
                      <option value="" disabled selected>== Pilih Pemilik Barang ==</option>
                      @foreach($company as $pemilik)
                        <option value="{{$pemilik->c_id}}">{{$pemilik->c_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                  <label>HPP Satuan Terkecil</label>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-sm input-hpp text-right" name="sm_hpp">
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                  <label>Kondisi Barang</label>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <select class="form-control form-control-sm select2" name="s_condition">
                      <option value="" disabled="" selected="">== Pilih Kondisi ==</option>
                      <option value="FINE">NORMAL</option>
                      <option value="BROKEN">RUSAK</option>
                    </select>
                  </div>
                </div>
              </div>
              </form>
            </section>
          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary btn-submit" type="button" id="btn-submit">Simpan</button>
            <a href="{{ route('barangmasuk.index') }}" class="btn btn-secondary">Kembali</a>
          </div>
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
  $(document).ready(function(){

    $('#namaItem').autocomplete({
      source: baseUrl+'/inventory/barangmasuk/autoItem',
      minLength: 2,
      select: function(event, data){
          $('#idItem').val(data.item.id).trigger('change');

      }
    });

    $(document).on('click', '.btn-submit', function(){
			$.toast({
				heading: 'Success',
				text: 'Data Berhasil di Simpan',
				bgColor: '#00b894',
				textColor: 'white',
				loaderBg: '#55efc4',
				icon: 'success'
			})
		});

    $('.input-hpp').maskMoney({
      thousands: ".",
      precision: 0,
      decimal: ","
    });
  });

  $('#btn-submit').on('click', function(){
    loadingShow();
    submitForm(event);
  });
    
  function submitForm(event){
    event.preventDefault();
    $.ajax({
      url   : "{{route('barangmasuk.store')}}",
      type  : "get",
      data  : $('#formAdd').serialize(),
      dataType : "json",
      beforeSend: function() {
        loadingShow();
      },
      beforeSend: function() {
        loadingShow();
      },
      success : function (response){
        if(response.status == 'sukses'){
          loadingHide();
          messageSuccess('Success', 'Data berhasil ditambahkan!');
          window.location.href = "{{route('barangmasuk.index')}}";
        } else {
          loadingHide();
          messageFailed('Gagal', response.message);
        }
      },
      error: function (e) {
        loadingHide();
        messageWarning('Peringatan', e.message);
      }
    });
  }

  function getUnit()
  {
    var u = document.getElementById("idItem").value;
    $.ajax({
      url : baseUrl+"/inventory/barangmasuk/getUnit/",
      type    : "get",
      data    : {id : u},
      dataType: "json",
      success : function(response){
        $('#satuan').html('');
        $('#satuan').append('<option value="" disabled selected>== Pilih Satuan ==</option>'+
                      '<option value="'+response.data.id1+'" id="unit1">'+response.data.name1+'</option>'+
                      '<option value="'+response.data.id2+'" id="unit2">'+response.data.name2+'</option>'+
                      '<option value="'+response.data.id3+'" id="unit3">'+response.data.name3+'</option>'); 
      }
    });
  }


</script>
@endsection