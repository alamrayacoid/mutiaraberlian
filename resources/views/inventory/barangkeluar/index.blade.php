@extends('main')

@section('content')



<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Pengelolaan Barang Keluar </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktivitas Inventory</span>
	    	 / <span class="text-primary font-weight-bold">Pengelolaan Barang Keluar</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Pengelolaan Barang Keluar </h3>
                        </div>
                        <div class="header-block pull-right">

                			<a class="btn btn-primary" href="{{ route('barangkeluar.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>

                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_barangkeluar">
	                                <thead class="bg-primary">
	                                    <tr>
	                                    	<th>No</th>
																				<th>Tanggal Keluar</th>
		                                		<th>Kode Barang</th>
		                                		<th>Nama Barang</th>
																				<th>Jumlah Barang</th>
		                                		<th>Satuan</th>
																				<th>Lokasi Keluar</th>
		                                		<th>Aksi</th>
		                                	</tr>
	                                </thead>
	                                <tbody>

	                                </tbody>
	                            </table>
	                        </div>
                        </section>
                    </div>
                </div>
			</div>
		</div>
	</section>
</article>

{{-- Modal Detail --}}
<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Barang Keluar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
									<div class="col-md-3">
										<label for="d_code">Kode</label>
									</div>
									<div class="col-md-9">
										<div class="form-group">
											<input type="text" class="form-control form-control-sm w-100 bg-light" disabled value="" readonly id="d_code">
										</div>
									</div>

									<div class="col-md-3">
										<label for="d_name">Nama</label>
									</div>
									<div class="col-md-9">
										<div class="form-group">
											<input type="text" class="form-control form-control-sm w-100 bg-light" disabled value="" readonly id="d_name">
										</div>
									</div>

									<div class="col-md-3">
										<label for="d_nota">Nota Pengeluaran</label>
									</div>
									<div class="col-md-9">
										<div class="form-group">
											<input type="text" class="form-control form-control-sm w-100 bg-light" disabled value="" readonly id="d_nota">
										</div>
									</div>

                	<div class="table-responsive">
                      <table class="table table-bordered table-striped table-hover display" cellspacing="0" id="table_detail">
                          <thead class="bg-primary">
                              <tr>
                              	<th>No</th>
																<th>Reff</th>
																<th>Qty</th>
																<th>Satuan</th>
                            		<th>HPP</th>
                            	</tr>
                          </thead>
                          <tbody>

                          </tbody>
                      </table>
                  </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra_script')
<script type="text/javascript">
	// set header token for ajax request
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	/* Fungsi formatRupiah */
	function formatRupiah(angka){
		var number_string = angka.replace(/[^.\d]/g, '').toString();
		split = number_string.split(',');
		sisa = split[0].length % 3;
		rupiah = split[0].substr(0, sisa);
		ribuan = split[0].substr(sisa).match(/\d{3}/gi);
		// tambahkan titik jika yang di input sudah menjadi angka ribuan
		if(ribuan){
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return rupiah;
	}

	var tb_barangkeluar;
	function TableBarangKeluar()
	{
		$('#table_barangkeluar').dataTable().fnDestroy();
		tb_barangkeluar = $('#table_barangkeluar').dataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ route('barangkeluar.list') }}",
				type: "get",
				data: {
					"_token": "{{ csrf_token() }}"
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'io_date'},
				{data: 'code'},
				{data: 'name'},
				{data: 'io_qty'},
				{data: 'unit'},
				{data: 'mutcat'},
				{data: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}

	function Detail(id, nota)
	{
		$.ajax({
			url: baseUrl + "/inventory/barangkeluar/detail/" + id,
			type: 'get',
			success: function(response) {
				unit_name = response.get_item.get_unit1.u_name;
				$('#d_code').val(response.get_item.i_code);
				$('#d_name').val(response.get_item.i_name);
				$('#d_nota').val(response.io_nota);
				$('#table_detail tbody').empty();
				$.each(response.get_mutation_detail, function(i, val) {
					index = i + 1;
					hpp = formatRupiah(val.sm_hpp, 'Rp')
					$('#table_detail > tbody:last-child').append('<tr><td>'+ index +'</td><td>'+ val.sm_reff +'</td><td>'+ val.sm_qty +'</td><td>'+ unit_name +'</td><td><span class="float-left">Rp </span><span class="float-right">'+ hpp +'</span></td></tr>');
				});
				$('#modal_detail').modal('show');
			}
		});
	}

	$(document).ready(function(){
		TableBarangKeluar();

	});
</script>
@endsection
