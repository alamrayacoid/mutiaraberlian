@extends('main')

@section('content')

@include('marketing.penjualanpusat.returnpenjualan.modal-search')

@include('marketing.penjualanpusat.returnpenjualan.modal-detail')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Tambah Return Penjualan </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktifitas Marketing</span>
	    	 / <a href="{{route('returnpenjualanagen.index')}}">Return Penjualan</a>
	    	 / <span class="text-primary font-weight-bold">Tambah Return Penjualan</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Tambah Return Penjualan </h3>
                        </div>
                        <div class="header-block pull-right">
                			<a class="btn btn-secondary btn-sm" href="{{url('/marketing/penjualanpusat/index')}}"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-block">
                        <form id="formdata">
							<div class="row">
								<div class="col-md-2 col-sm-6 col-xs-12">
									<label>Area</label>
								</div>
								<div class="col-md-10 col-sm-6 col-xs-12">
									<div class="row">
										<div class="form-group col-6">
											<select name="prov" id="prov" class="form-control form-control-sm select2">
												<option value="" selected="" disabled="">=== Pilih Provinsi ===</option>
												@foreach($provinsi as $prov)
													<option value="{{ $prov->wp_id }}">{{ $prov->wp_name }}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group col-6">
											<select name="city" id="city" class="form-control form-control-sm select2 city">
												<option value="" selected disabled>=== Pilih Kota ===</option>
											</select>
										</div>
									</div>
								</div>

								<div class="col-md-2 col-sm-6 col-xs-12">
									<label>Agen</label>
								</div>
								<div class="col-md-10 col-sm-6 col-xs-12">
									<div class="form-group">
										<select name="agent" id="agent" class="form-control form-control-sm select2 agent">
											<option value="" selected disabled>=== Pilih Cabang ===</option>
										</select>
									</div>
								</div>
							</div>

                            <input type="hidden" name="itemId" value="" id="itemId" >
                            <!-- <input type="hidden" name="member" id="member"> -->
                            <div class="row">
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label>Kode Produksi</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
									<div class="form-group">
										<select name="kodeproduksi" id="kodeproduksi" class="form-control form-control-sm select2 prodcode">
											<option value="" selected disabled>=== Pilih Kode Produksi ===</option>
										</select>
									</div>
                                </div>
								<div class="col-md-2 col-sm-6 col-12">
									<label>Nota</label>
								</div>
								<div class="col-md-4 col-sm-6 col-12">
									<div class="form-group">
										<select name="nota" id="nota" class="form-control form-control-sm select2 nota">
											<option value="" selected disabled>=== Pilih Nota ===</option>
										</select>
									</div>
								</div>
                            </div>

							<div class="row" id="div2" style="display:none">
								<div class="col-md-2 col-sm-6 col-12">
									<label>Nota Penjualan</label>
								</div>
								<div class="col-md-4 col-sm-6 col-12">
									<div class="form-group">
										<select class="select2" onchange="notapenjualanup()" name="notapenjualan" id="notapenjualan" style="width:100%">

										</select>
									</div>
								</div>
								<hr>
							</div>
							<!-- detail penjualan -->
							<div id="div3" style="display:none">
								<section>
									<div class="row">
										<div class="col-md-2 col-sm-6 col-12">
											<label>Penjual</label>
										</div>
										<div class="col-md-4 col-sm-6 col-12">
											<div class="form-group">
												<input type="text" name="penjual" readonly class="form-control" id="penjual" value="">
											</div>
										</div>
										<hr>

										<div class="col-md-2 col-sm-6 col-12">
											<label>Metode Pembayaran</label>
										</div>
										<div class="col-md-4 col-sm-6 col-12">
											<div class="form-group">
												<input type="text" readonly name="metodepembayaran" class="form-control" id="metodepembayaran" value="">
											</div>
										</div>
										<hr>

										<div class="col-md-2 col-sm-6 col-12">
											<label>Tanggal Transaksi</label>
										</div>
										<div class="col-md-4 col-sm-6 col-12">
											<div class="form-group">
												<input type="text" readonly name="tanggaltransaksi" class="form-control" id="tanggaltransaksi" value="">
											</div>
										</div>
										<hr>

										<div class="col-md-2 col-sm-6 col-12">
											<label>Total</label>
										</div>
										<div class="col-md-4 col-sm-6 col-12">
											<div class="form-group">
												<input type="text" readonly name="total" class="form-control" id="total" value="">
											</div>
										</div>
										<hr>
									</div>
								</section>

								<section style="margin-top:20px;">
									<div class="row">
										<div class="col-md-2 col-sm-6 col-12">
											<label>Nama Barang</label>
										</div>
										<div class="col-md-4 col-sm-6 col-12">
											<div class="form-group">
												<input type="text" readonly name="item" class="form-control" id="item" value="">
											</div>
										</div>
										<hr>

										<div class="col-md-2 col-sm-6 col-12">
											<label>Jumlah</label>
										</div>
										<div class="col-md-4 col-sm-6 col-12">
											<div class="form-group">
												<input type="text" style="text-align:right;" name="qty" class="form-control digits" id="qty" value="" readonly>
											</div>
										</div>
										<hr>

										<div class="col-md-2 col-sm-6 col-12">
											<label>Jenis Pengembalian</label>
										</div>
										<div class="col-md-4 col-sm-6 col-12">
											<div class="form-group">
												<select class="form-control select2" name="type" id="type">
													<option value="GB">Ganti Barang</option>
													<option value="GU">Ganti Uang</option>
													<option value="PN">Potong Nota</option>
												</select>
											</div>
										</div>
										<hr>

										<div class="col-md-2 col-sm-6 col-12">
											<label>Jumlah return</label>
										</div>
										<div class="col-md-4 col-sm-6 col-12">
											<div class="form-group">
												<input type="text" style="text-align:right;" name="qtyReturn" class="form-control digits" id="qtyReturn" value="">
											</div>
										</div>
										<hr>

										<div class="col-md-2 col-sm-6 col-12">
											<label>Keterangan </label>
										</div>
										<div class="col-md-10 col-sm-6 col-12">
											<div class="form-group">
												<input type="text" class="form-control" id="keterangan" name="keterangan">
											</div>
										</div>
										<hr>
									</div>

								</section>
							</div>
						</form>
					</div>
                    <div class="card-footer text-right">
                    	<button class="btn btn-primary" type="button" id="simpan">Simpan</button>
                    	<a href="{{url('/marketing/penjualanpusat/index')}}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>

			</div>

		</div>

	</section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
	$(document).ready(function() {
		$('#prov').on('change', function() {
			$('#agent').attr('disabled', true);
			$('#kodeproduksi').attr('disabled', true);
			$('#nota').attr('disabled', true);
			$('#div3').css('display', 'none');
			getCity();
		});
		$('#city').on('change', function() {
			$('#agent').attr('disabled', false);
			$('#kodeproduksi').attr('disabled', true);
			$('#nota').attr('disabled', true);
			$('#div3').css('display', 'none');
			getAgent();
		});
		$('#agent').on('change', function() {
			$('#kodeproduksi').attr('disabled', false);
			$('#nota').attr('disabled', true);
			$('#div3').css('display', 'none');
			getProdCode();
		});
		$('#kodeproduksi').on('change', function() {
			$('#nota').attr('disabled', false);
			$('#div3').css('display', 'none');
			getNota();
		});
		$('#nota').on('select2:select', function() {
			let itemId = $('#nota').find('option:selected').data('itemid');
			console.log('itemId: '+ itemId);
			$('#itemId').val(itemId);
			getDataSalesComp();
		});

		$('#qtyReturn').on('keyup', function(){
			var qtyReturn = $('#qtyReturn').val();
			var batas = $('#qty').val();
			batas = batas.replace( /\[\d+\]/g, '');
			if (parseFloat(qtyReturn) > parseFloat(batas)) {
				messageWarning('Info', 'Jumlah Return tidak boleh melebihi Jumlah penjualan');
				$('#qtyReturn').val(batas);
			}
			else if (parseFloat(qtyReturn) < 0 || qtyReturn == '') {
				messageWarning('Info', 'Jumlah Return tidak boleh kurang dari 0');
				$('#qtyReturn').val(0);
			}
		});

		$('#simpan').on('click', function(){
			store();
		});
	});

	// get city
	function getCity()
	{
		let provId = $('#prov').val();
		$.ajax({
			url: "{{ route('returnpenjualanagen.getCity') }}",
			type: "get",
			data:{
				provId: provId
			},
			beforeSend: function () {
				loadingShow();
			},
			success: function (response) {
				loadingHide();
				$('#city').empty();
				if (response.data.length == 0) {
					$("#city").append('<option value="" selected disabled>=== Pilih Kota ===</option>');
				} else {
					$("#city").append('<option value="" selected disabled>=== Pilih Kota ===</option>');
					$.each(response.data, function( key, val ) {
						$("#city").append('<option value="'+val.wc_id+'">'+val.wc_name+'</option>');
					});
				}
				$('#city').focus();
				$('#city').select2('open');
			}
		});
	}
	// get agent
	function getAgent()
	{
		let cityId = $('#city').val();
		$.ajax({
			url: "{{ route('returnpenjualanagen.getAgent') }}",
			type: "get",
			data:{
				cityId: cityId
			},
			beforeSend: function () {
				loadingShow();
			},
			success: function (response) {
				loadingHide();
				$('#agent').empty();
				if (response.data.length == 0) {
					$("#agent").append('<option value="" selected disabled>=== Pilih Agen ===</option>');
				} else {
					$("#agent").append('<option value="" selected disabled>=== Pilih Agen ===</option>');
					$.each(response.data, function( key, val ) {
						$("#agent").append('<option value="'+val.c_id+'">'+val.c_name+'</option>');
					});
				}
				$('#agent').focus();
				$('#agent').select2('open');
			}
		});
	}
	// get production-code
	function getProdCode()
	{
		let agentCode = $('#agent').val();
		$.ajax({
			url: "{{ route('returnpenjualanagen.getProdCode') }}",
			type: "get",
			data: {
				term: $('#kodeproduksi').val(),
				agentCode: agentCode
			},
			beforeSend: function () {
				loadingShow();
			},
			success: function (response) {
				loadingHide();
				$('#kodeproduksi').empty();
				if (response.length == 0) {
					$("#kodeproduksi").append('<option value="" selected disabled>=== Pilih Kode Produksi ===</option>');
				} else {
					$("#kodeproduksi").append('<option value="" selected disabled>=== Pilih Kode Produksi ===</option>');
					$.each(response, function( key, val ) {
						$("#kodeproduksi").append('<option value="'+val.ssc_code+'">'+val.ssc_code+'</option>');
					});
				}
				$('#kodeproduksi').focus();
				$('#kodeproduksi').select2('open');
			}
		});
	}
	// get nota based on production-code
	function getNota()
	{
		let kodeproduksi = $('#kodeproduksi').val();
		kodeproduksi = kodeproduksi.toUpperCase();
		let agentCode = $('#agent').val();
		$.ajax({
			url: "{{ route('returnpenjualanagen.getNota') }}",
			type: "get",
			data: {
				term: $('#nota').val(),
				prodCode: kodeproduksi,
				agentCode: agentCode
			},
			beforeSend: function () {
				loadingShow();
			},
			success: function (response) {
				console.log(response);
				loadingHide();
				$('#nota').empty();
				if (response.length == 0) {
					$("#nota").append('<option value="" selected disabled>=== Pilih Nota ===</option>');
				} else {
					$("#nota").append('<option value="" selected disabled>=== Pilih Nota ===</option>');
					$.each(response, function( key, val ) {
						$("#nota").append('<option value="'+val.get_sales_comp_by_id.sc_nota+'" data-itemid="'+ val.ssc_item +'">'+val.get_sales_comp_by_id.sc_nota+'</option>');
						console.log(val);
					});
				}
				$('#nota').focus();
				$('#nota').select2('open');
			}
		});

		// // get detail nota
		// $.ajax({
		// 	type: 'get',
		// 	data: {
		// 		prodCode: kodeproduksi
		// 	},
		// 	dataType: 'JSON',
		// 	url: "{{ route('returnpenjualanagen.getNota') }}",
		// 	success : function(response){
		// 		if (response.length == 0) {
		// 			messageWarning("Info", 'Kode Produksi Tidak Ditemukan!');
		// 			$('#notapenjualan').html('<option value="">- Pilih Nota Penjualan -</option>');
		// 		} else {
		// 			// for (let index = 0; index < response.length; index++) {
		// 			// 	html += '<option value="'+response[index].sc_nota+'">'+response[index].sc_nota+'</option>';
		// 			// }
		// 			// $('#itemid').val(response[0].ssc_item);
		// 			// $('#member').val(response[0].sc_member);
		// 			// $('#notapenjualan').html(html);
		// 			// $('#notapenjualan').select2();
		// 			// $('#div2').css('display', '');
		// 		}
		// 	}
		// });
	}
	// get data that will be processed
	function getDataSalesComp()
	{
		loadingShow();
		let nota = $('#nota').val();
		let itemId = $('#itemId').val();
		let prodCode = $("#kodeproduksi").val();

		$.ajax({
			url: "{{ route('returnpenjualanagen.getData') }}",
			data: {
				nota: nota,
				itemId: itemId,
				prodCode: prodCode
			},
			type: 'get',
			success: function(resp) {
				loadingHide();
				console.log(resp);
				$('#penjual').val(resp.data.get_comp.c_name);
				$('#agen').val(resp.data.get_agent.c_name);
				if (resp.data.sc_type == 'C') {
					$('#metodepembayaran').val('Cash');
				} else {
					$('#metodepembayaran').val('Konsinyasi');
				}
				$('#tanggaltransaksi').val(resp.data.sc_date);
				$('#total').val(resp.data.sc_total);
				$('#item').val(resp.data.get_sales_comp_dt[0].get_item.i_name);
				$('#qty').val(resp.data.get_sales_comp_dt[0].get_prod_code[0].ssc_qty);
				// $('#qtyhidden').val(resp.data.get_sales_comp_dt[0].get_prod_code[0].ssc_qty);
				$('#div3').css('display', '');
			},
			error: function(e) {
				loadingHide();
				messageWarning('Error', 'Gagal mengambil detail penjualan, hubungi pengembang !')
			}
		});
	}
	// store data to Database
	function store() {
		data = $('#formdata').serialize();
		$.ajax({
			type: 'post',
			data: data,
			dataType: 'JSON',
			url: baseUrl + '/marketing/penjualanpusat/returnpenjualan/simpan',
			success : function(response){
				if (response.status == 'berhasil') {
					messageSuccess('Info', 'Berhasil Disimpan');
				}
				else {
					messageWarning('Info', 'Gagal Disimpan: '+ response.message);
				}
			},
			error: function(e) {
				messageWarning('Info', 'Terjadi kesalahan : '+ e.message);
			}
		});
	}

	// $(document).ready(function(){
    //     var table_returnp;
    //     table_returnp = $('#table_rp').DataTable();
    //     $('#go').on('click', function(){
    //         $('.table-returnp').removeClass('d-none');
    //     });
	// });
</script>
@endsection
