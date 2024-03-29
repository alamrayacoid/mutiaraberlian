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
			/ <span class="text-primary" style="font-weight: bold;"> Edit Data Target</span>
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
							<div class="container">
								<hr style="border:0.7px solid grey; margin-bottom:30px;">
								<div class="table-responsive">
									<table class="table table-striped table-hover" cellspacing="0" id="table_target">
										<thead class="bg-primary">
											<tr>
												<th width ="50%">Kode/Nama Barang</th>
												<th width ="30%">Satuan</th>
												<th width ="20%">Jumlah Target</th>
											</tr>
										</thead>
										<tbody>
											<form action="" id="formUpdate">
												<tr>
													<td>
														<input type="text" name="barang[]" class="form-control form-control-sm barang" style="text-transform:uppercase" value="{{$target->i_code}}-{{$target->i_name}}">
														<input type="hidden" name="idItem[]" class="itemid">
														<input type="hidden" name="kode[]" class="kode">
													</td>
													<td>
														<select name="t_unit[]" class="form-control form-control-sm select2 satuan">
															<option value="{{$target->u_id}}">{{$target->u_name}}</option>
														</select>
													</td>
													<td>
														<input type="number" class="form-control form-control-sm" min="0" name="t_qty[]" value="{{$target->std_qty}}">
													</td>
												</tr>
											</form>
										</tbody>
									</table>
								</div>
							</div>
						</section>
					</div>
					<div class="card-footer text-right">
						<button class="btn btn-primary btn-submit" type="button" onclick="updateTarget('{{Crypt::encrypt($target->std_salestarget)}}','{{Crypt::encrypt($target->std_detailid)}}')">Simpan</button>
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
$.ajaxSetup({
	headers: {
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
$(document).ready(function(){
	$('.barang').on('click', function(e){
		idxBarang = $('.barang').index(this);
		setArrayCode();
	});

	$(".barang").eq(idxBarang).on("keyup", function () {
		$(".itemid").eq(idxBarang).val('');
		$(".kode").eq(idxBarang).val('');
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
});

function updateTarget(st_id,dt_id)
{
	$.ajax({
		url: baseUrl + "/marketing/penjualanpusat/targetrealisasi/updateTarget/" + st_id + "/" + dt_id,
		type: "get",
		data: $('#formUpdate').serialize(),
		dataType : 'json',
		beforeSend: function() {
			loadingShow();
		},
		success : function (response){
			if(response.status == 'sukses'){
			  	loadingHide();
			  	messageSuccess('Success', 'Data berhasil diperbarui!');
            	window.location.href = "{{route('penjualanpusat.index')}}";
			} else if (response.status == 'invalid') {
				loadingHide();
				messageWarning('Perhatian', response.message);
			}
		},
		error : function(e){
			loadingHide();
			messageWarning('Warning', e.message);
		}

	});
}
</script>
@endsection