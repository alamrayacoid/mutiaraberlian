@extends('main')

@section('content')

<article class="content">

    <div class="title-block text-primary">
        <h1 class="title"> Tambah Barang Keluar </h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Aktivitas Inventory</span>
            / <a href="#"><span>Barang Keluar</span></a>
            / <span class="text-primary font-weight-bold">Tambah Barang Keluar</span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <div class="card">
                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title">Tambah Barang Keluar</h3>
                        </div>
                        <div class="header-block pull-right">
                            <a href="{{ route('barangkeluar.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>

                    <form action="{{ route('barangkeluar.store') }}" method="post" id="myForm" autocomplete="off">
                        <div class="card-block">
                            <section>

                                <div class="row">

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Nama Barang</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="hidden" name="itemId" id="itemId">
                                            <input type="text" class="form-control form-control-sm" name="itemName" id="itemName" style="text-transform:uppercase">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Jumlah Barang</label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm digits" name="qty">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Satuan</label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select name="unit" id="unit" class="form-control form-control-sm select2">
                                                <option value="" disabled selected>== Pilih Satuan ==</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Lokasi Barang</label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select name="position" id="position" class="form-control form-control-sm select2">
                                                <option value="" disabled selected>== Pilih Lokasi Barang ==</option>
                                                @foreach($data['company'] as $position)
                                                <option value="{{$position->c_id}}">{{$position->c_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Pemilik Barang</label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select name="owner" id="owner" class="form-control form-control-sm select2">
                                                <option value="" disabled selected>== Pilih Pemilik Barang ==</option>
                                                @foreach($data['company'] as $owner)
                                                <option value="{{$owner->c_id}}">{{$owner->c_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label>Keterangan Barang Keluar</label>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select class="form-control form-control-sm select2" name="mutcat">
                                                <option value="" disabled="" selected="">== Pilih Keterangan ==</option>
                                                @foreach($data['mutcat'] as $mutcat)
                                                <option value="{{$mutcat->m_id}}">{{$mutcat->m_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                            </section>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary btn-submit" type="button" id="btn_simpan">Simpan</button>
                            <a href="{{ route('barangkeluar.index') }}" class="btn btn-secondary">Kembali</a>
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

  $(document).ready(function(){

    $('#itemName').autocomplete({
      source: baseUrl+'/inventory/barangkeluar/getItems',
      minLength: 2,
      select: function(event, data){
        $('#itemId').val(data.item.id);
        $('#unit').find('option').not(':first').remove();
        (data.item.unit1_id != null) ? $('#unit').append('<option value="'+ data.item.unit1_id +'">'+ data.item.unit1_name +'</option>') : '';
        (data.item.unit2_id != null) ? $('#unit').append('<option value="'+ data.item.unit2_id +'">'+ data.item.unit2_name +'</option>') : '';
        (data.item.unit3_id != null) ? $('#unit').append('<option value="'+ data.item.unit3_id +'">'+ data.item.unit3_name +'</option>') : '';
      }
    });

    $('#unit').on('change', function() {
      console.log($(this).val());
    })

  });

  $('#btn_simpan').on('click', function() {
    SubmitForm(event);
  });

  // submit form to store data in db
  function SubmitForm(event)
  {
    loadingShow();
    event.preventDefault();
    form_data = $('#myForm').serialize();

    $.ajax({
      data : form_data,
      type : "post",
      url : $("#myForm").attr('action'),
      dataType : 'json',
      success : function (response){
        if(response.status == 'berhasil'){
          loadingHide();
          messageSuccess('Berhasil', 'Data berhasil ditambahkan !');
          location.reload();
        } else if (response.status == 'invalid') {
          loadingHide();
          messageFailed('Perhatian', response.message);
        } else if (response.status == 'gagal') {
          loadingHide();
          messageWarning('Error', response.message);
        }
      },
      error : function(e){
        loadingHide();
        messageWarning('Gagal', 'Data gagal ditambahkan, hubungi pengembang !');
      }
    })
  }
</script>
@endsection
