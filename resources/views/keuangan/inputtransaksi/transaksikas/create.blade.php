@extends('main')

@section('content')

@include('keuangan.inputtransaksi.transaksikas.modal')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Transaksi Kas </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Keuangan</span>
         / <a href="{{route('inputtransaksi.index')}}"><span>Manajemen Input Transaksi</span></a>
         / <span class="text-primary" style="font-weight: bold;">Transaksi Kas</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
      <div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Transaksi Kas</h3>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
        <div class="row">
            <div class="section1 col-md-6 col-sm-12">
            <div class="row">

                <div class="col-md-4 col-sm-12">
                    <label for="">Nomer Transaksi</label>
                </div>
                <div class="col-md-5 col-sm-12">
                    <input type="text" class="form-control form-control-sm mb-3" readonly="">
                </div>
                <div style="margin-top:3px;">
                    <i class="fa fa-search fa-lg" data-toggle="modal" data-target="#detail" type="button" title="Cari Group Berdasarkan Nomer dan Type Group" style="cursor: pointer;"></i>
                </div>

                <div class="col-md-4 col-sm-12">
                    <label for="">Type Transaksi Kas</label>
                </div>
                <div class="col-md-5 col-sm-12 mb-3">
                    <select name="" id="" class="form-control form-control-sm select2">
                    </select>
                </div>
                <div style="margin-top:3px; cursor:help">
                    <i class="fa fa-info-circle fa-lg" title="Parameter Type Group Digunakan Untuk Pencarian Data"></i>
                </div>

                <div class="col-md-4 col-sm-12">
                    <label for="">Tanggal Transaksi <span style="color:red;">*</span></label>
                </div>
                <div class="col-md-5 col-sm-12">
                    <input type="text" class="form-control form-control-sm mb-3 datepicker">
                </div>
                <div style="margin-top:3px; cursor:help">
                    <i class="fa fa-info-circle fa-lg" title="Parameter Type Group Digunakan Untuk Pencarian Data"></i>
                </div>

                <div class="horizontal-line mb-3"></div>

                <div class="col-md-4 col-sm-12" >
                    <label for="">Ket. Transaksi <span style="color:red;">*</span></label>
                </div>
                <div class="col-md-6 col-sm-12">
                    <input type="text" class="form-control form-control-sm mb-3" placeholder="contoh: Setoran Modal Investor">
                </div>

                <div class="col-md-4 col-sm-12">
                    <label for="">Pilih Akun Kas</label>
                </div>
                <div class="col-md-6 col-sm-12 mb-3">
                    <select name="" id="" class="form-control form-control-sm select2"></select>
                </div>

                <div class="col-md-4 col-sm-12">
                    <label for="">Nominal Transaksi</label>
                </div>
                <div class="col-md-6 col-sm-12">
                    <input type="text" class="form-control form-control-sm mb-3 input-rupiah" id="nominal-rupiah" value="Rp. 0,00">
                </div>
            </div>
            </div>
            <div class="section2 col-md-6 col-sm-12">
            <div class="table-responsive" style="font-size:9pt;">
            <table class="table table-stripped table-bordered table-mini" id="table-transaksi">
            <thead>
                <tr>
                    <th width="8%" style="text-align:center;">*</th>
                    <th width="20%">Akun</th>
                    <th width="10%">Debet</th>
                    <th width="10%">Kredit</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td align="center"><i class="fa fa-lock fa-lg"></i></td>
                    <td><select name="" id="" class="form-control form-control-sm select2" style="font-size:9pt;"></select></td>
                    <td><div id="another" class="input-rupiah"><input type="text" class="form-control text-right input-rupiah input-invis input-special" style="cursor:no-drop;" value="Rp. 0,00"></div></td>
                    <td><input type="text" class="form-control text-right input-rupiah input-invis" style="cursor:no-drop;" value="Rp. 0,00"></td>
                </tr>

                <tr>
                    <td align="center"><i class="fa fa-lock fa-lg"></i></td>
                    <td><select name="" id="" class="form-control form-control-sm select2" style="font-size:9pt;"></select></td>
                    <td><input type="text" class="form-control text-right input-rupiah input-invis" value="Rp. 0,00"></td>
                    <td><input type="text" class="form-control text-right input-rupiah input-invis" value="Rp. 0,00"></td>
                </tr>

            </tbody>
            </table>
            <table class="table table-stripped table-bordered table-mini">
            <thead>
                <tr>
                    <th style="vertical-align:middle; text-align:center;" width="16%"><button class="btn btn-sm add-transaksi"><i class="fa fa-plus"></i></button></th>
                    <th style="vertical-align:middle; font-style:italic;">Total Debit Kredit</th>
                    <td style="vertical-align:middle; padding:0px; padding-left:0px; margin-left:0px; padding-right:5px;"><div class="another2"><input class="form-control form-control-sm text-right input-invis input-rupiah" value="Rp. 0,00"></div></td>
                    <td style="vertical-align:middle; padding:0px; padding-top:15px; padding-right:5px;"><p class="input-rupiah">Rp. 0,00</p></td>
                </tr>
            </thead>
            </table>
            </div>
            </div>
        </div>
		</div>
        <div class="card-footer">
        <div class="pull-right">
        <button class="btn btn-primary btn-submit">Simpan</button>
        </div>
        </div>
	</div>

    </div>

  </section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
  $(document).ready(function(){
    $('#type_cus').change(function(){
      if($(this).val() === 'kontrak'){
        $('#label_type_cus').text('Jumlah Bulan');
        $('#jumlah_hari_bulan').val('');
        $('#pagu').val('');
        $('#armada').prop('selectedIndex', 0).trigger('change');
        $('.120mm').removeClass('d-none');
        $('.125mm').addClass('d-none');
        $('.122mm').removeClass('d-none');
      } else if($(this).val() === 'harian'){
        $('#label_type_cus').text('Jumlah Hari');
        $('#armada').prop('selectedIndex', 0).trigger('change');
        $('#pagu').val('');
        $('#jumlah_hari_bulan').val('');
        $('.122mm').addClass('d-none');
        $('.120mm').removeClass('d-none');
        $('.125mm').removeClass('d-none');
      } else {
        $('#jumlah_hari_bulan').val('');
        $('#armada').prop('selectedIndex', 0).trigger('change');
        $('#pagu').val('');
        $('.122mm').addClass('d-none');
        $('.120mm').addClass('d-none');
        $('.125mm').addClass('d-none');
      }
    });

    $(document).on('click', '.btn-hapus', function(){
      $(this).parents('tr').remove();
    });

    $('.add-transaksi').on('click',function(){
      $('#table-transaksi tbody')
      .append(
        '<tr>'+
          '<td align="center"><i class="fa fa-times fa-lg btn-hapus" style="color:red;"></i></td>'+
          '<td><select name="" id="" class="form-control form-control-sm select2" style="font-size:9pt;"></select></td>'+
          '<td><p class="input-rupiah">Rp. 0,00</p></td>'+
          '<td><p class="input-rupiah">Rp. 0,00</p></td>'+
        '</tr>'
        );
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
		})
  });
</script>
<script>
$(function() {
    $('#nominal-rupiah').keyup(function() {
        $('#another').text($(this).val());
    });
});

</script>
@endsection
