@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Data Target </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
          / <span>Aktivitas Marketing</span>
          / <a href="{{route('penjualanpusat.index')}}"><span>Manajemen Penjualan Pusat</span></a>
          / <span class="text-primary" style="font-weight: bold;"> Target dan Realisasi</span>
          / <span class="text-primary" style="font-weight: bold;"> Tambah Data Target</span>
       </p>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-12">        
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block pull-right">
              <a href="{{route('penjualanpusat.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>

          <div class="card-block">
            <section>
              <form id="formAdd">
              <div class="row">
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Bulan/Tahun</label>
                </div>
                <div class="col-md-10 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-sm" id="datepicker" name="t_periode">
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Pilihan Cabang</label>
                </div>
                <div class="col-md-10 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <select name="t_comp[]" id="" class="form-control form-control-sm select2">
                      <option value="" selected="" disabled="">== Pilih Cabang ==</option>
                      @foreach($company as $comp)
                        <option value="{{$comp->c_id}}">{{$comp->c_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="container">
                  <hr style="border:0.7px solid grey; margin-bottom:30px;">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover" cellspacing="0" id="table_target">
                      <thead class="bg-primary">
                        <tr>
                      		  <th width="50%">Kode/Nama Barang</th>
                      		  <th width="20%">Satuan</th>
                      	  	<th width="20%">Jumlah Target</th>
                      		  <th class="text-center">Aksi</th>
                      	</tr>
                      </thead>
                      <tbody>
                      	<tr>
                      		<td>
                            <input type="text" name="barang[]" class="form-control form-control-sm barang" style="text-transform:uppercase">
                            <input type="hidden" name="idItem[]" class="itemid">
                            <input type="hidden" name="kode[]" class="kode">
                          </td>
                      		<td>
                            <select name="t_unit[]" class="form-control form-control-sm select2 satuan"></select>
                          </td>
                      		<td>
                            <input type="number" class="form-control form-control-sm" min="0" value="" name="t_qty[]">
                          </td>
                      		<td class="text-center">
                            <button class="btn btn-success btn-tambah btn-sm" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                      		</td>
                      	</tr>
                      </tbody>
                    </table>
                  </div>                                
                </div>
              </div>
              </form>
            </section>
          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary btn-submit" type="button">Simpan</button>
            <a href="{{route('penjualanpusat.index')}}" class="btn btn-secondary">Kembali</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</article>

@endsection

@section('extra_script')
<script type="text/javascript">
  var idItem    = [];
  var namaItem  = null;
  var kode      = null;
  var idxBarang = null;
  var icode     = [];
  $(document).ready(function(){
    $('.barang').on('click', function(e){
      idxBarang = $('.barang').index(this);
      setArrayCode();
    });

    $(".barang").eq(idxBarang).on("keyup", function () {
      $(".itemid").eq(idxBarang).val('');
      $(".kode").eq(idxBarang).val('');
    });

    $("#datepicker").datepicker( {
      format     : "dd/mm/yyyy"
      // viewMode   : "months", 
      // minViewMode: "months"
    });

    $(document).on('click', '.btn-hapus', function(){
      $(this).parents('tr').remove();
    });

    $('.btn-tambah').on('click',function(){
      var tbody       = $(this).parents('tbody');
      var last_row    = tbody.find('tr:last-child');
      var input       = last_row.find('td:eq(0) input');
      var termin      = input.val();
      termin          = parseInt( termin );
      var next_termin = termin + 1;
      $('#table_target')
      .append(
        '<tr>'+
          '<td>'+
            '<input type="text" name="barang[]" class="form-control form-control-sm barang" style="text-transform:uppercase">'+
            '<input type="hidden" name="idItem[]" class="itemid">'+
            '<input type="hidden" name="kode[]" class="kode">'+
          '</td>'+
          '<td>'+
            '<select name="t_unit[]" class="form-control form-control-sm select2 satuan">'+
            '</select>'+
          '</td>'+
          '<td>'+
            '<input type="number" class="form-control form-control-sm" min="0" name="t_qty[]">'+
          '</td>'+
          '<td class="text-center"><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>'+
        '</tr>'
      );

      $('.select2').select2({
        theme: "bootstrap",
        dropdownAutoWidth: true,
        width: '100%'
      });

      $('.barang').on('click', function(e){
        idxBarang = $('.barang').index(this);
        setArrayCode();
      });

      $(".barang").on("keyup", function () {
        $(".itemid").eq(idxBarang).val('');
        $(".kode").eq(idxBarang).val('');
      });
    });

    function setItem(info) {
      idItem   = info.data.i_id;
      namaItem = info.data.i_name;
      kode     = info.data.i_code;
      $(".kode").eq(idxBarang).val(kode);
      $(".itemid").eq(idxBarang).val(idItem);
      setArrayCode();
      $.ajax({
        url : '{{ url('/marketing/penjualanpusat/targetrealisasi/get-satuan/') }}'+'/'+idItem,
        type: 'GET',
        success: function( resp ) {
          $(".satuan").eq(idxBarang).find('option').remove();
          var option = '';
          if (resp.id1 != null) {
            option += '<option value="'+resp.id1+'">'+resp.unit1+'</option>'
          }
          if (resp.id2 != null && resp.id2 != resp.id1) {
            option += '<option value="'+resp.id2+'">'+resp.unit2+'</option>';
          }
          if (resp.id3 != null && resp.id3 != resp.id1) {
            option += '<option value="'+resp.id3+'">'+resp.unit3+'</option>';
          }
          $(".satuan").eq(idxBarang).append(option);
        }
      });
    }

    function setArrayCode() {
      var inputs = document.getElementsByClassName('kode'),
      code  = [].map.call(inputs, function( input ) {
        return input.value.toString();
      });

      for (var i=0; i < code.length; i++) {
        if (code[i] != "") {
          icode.push(code[i]);
        }
      }

      var item = [];
      var inpItemid = document.getElementsByClassName( 'itemid' ),
      item  = [].map.call(inpItemid, function( input ) {
        return input.value;
      });

      $( ".barang" ).autocomplete({
        source: function( request, response ) {
          $.ajax({
            url : "{{ url('/marketing/penjualanpusat/targetrealisasi/cari-barang') }}",
            data: {
              idItem: item,
              term  : $(".barang").eq(idxBarang).val()
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        minLength: 1,
        select: function(event, data) {
          setItem(data.item);
        }
      });
    }
    $(document).on('click', '.btn-submit', function (evt) {
      evt.preventDefault();
      $.ajax({
        url : baseUrl+"/marketing/penjualanpusat/targetrealisasi/store",
        type : "get",
        data : $('#formAdd').serialize(),
        beforeSend: function() {
          loadingShow();
        },
        success : function (response){
          if(response.status == 'sukses'){
            loadingHide();
            messageSuccess('Success', 'Data berhasil ditambahkan!');
            window.location.href = "{{route('penjualanpusat.index')}}";
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
  });
</script>
@endsection
