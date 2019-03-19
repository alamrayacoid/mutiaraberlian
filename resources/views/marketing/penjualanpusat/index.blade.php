@extends('main')

@section('content')

<!-- Modal Terima Order -->
@include('marketing.penjualanpusat.terimaorder.modal')
@include('marketing.penjualanpusat.targetrealisasi.modal')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Manajemen Penjualan Pusat  </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Marketing</span> / <span class="text-primary" style="font-weight: bold;">Manajemen Penjualan Pusat</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12" id="choosetab">

                <ul class="nav nav-pills mb-3" id="Tabzs">
                    <li class="nav-item" id="tab1">
                        <a href="#terimaorder" class="nav-link active" data-target="#terimaorder" aria-controls="terimaorder" data-toggle="tab" role="tab">Terima Order Penjualan</a>
                    </li>
                    <li class="nav-item" id="tab2">
                        <a href="#promosi_tahunan" class="nav-link" data-target="#promosi_tahunan" aria-controls="promosi_tahunan" data-toggle="tab" role="tab">Distribusi Penjualan</a>
					</li>
                    <li class="nav-item" id="tab3">
                        <a href="#returnpenjualan" class="nav-link" data-target="#returnpenjualan" aria-controls="returnpenjualan" data-toggle="tab" role="tab">Return Penjualan Agen </a>
                    </li>
					<li class="nav-item" id="tab4">
                        <a href="#targetrealisasi" class="nav-link" data-target="#targetrealisasi" aria-controls="targetrealisasi" data-toggle="tab" role="tab">Target & Realisasi Penjualan</a>
                    </li>
                </ul>

                <div class="tab-content">

					@include('marketing.penjualanpusat.terimaorder.index')
					@include('marketing.penjualanpusat.returnpenjualan.index')
					@include('marketing.penjualanpusat.targetrealisasi.index')

	            </div>

			</div>

		</div>

	</section>

</article>
<!-- Modal -->
<div id="edittarget" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Edit Target</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Nama Barang : </label>
                        <span id="edit_namabarang">Agarillus</span>
                    </div>
                    <div class="col-md-12">
                        <label>Periode : </label>
                        <span id="edit_periode">Maret 2019</span>
                    </div>
                    <div class="col-md-12">
                        <label>Cabang : </label>
                        <span id="edit_cabang">Cabang</span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <label class="control-label" for="edit_targetawal">Target Awal</label>
                        <input type="number" class="form-control" id="edit_targetawal">
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <label class="control-label" for="edit_targetbaru">Target Baru</label>
                        <input type="number" class="form-control" id="edit_targetbaru">
                    </div>
                </div>
                <div class="form-group">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
@endsection
@section('extra_script')
<script type="text/javascript">

	$(document).ready(function(){

		var table_sup = $('#table_approval').DataTable();
		var table_bar= $('#table_tahunan').DataTable();
		var table_pus= $('#table_bulanan').DataTable();
		var table_par = $('#table_targetrealisasi').DataTable();

		$(document).on('click','.btn-preview-rekruitmen',function(){
			window.location.href='{{route('rekruitmen.preview')}}'
		});
		$(document).on('click','.btn-proses-rekruitmen',function(){
			window.location.href='{{route('rekruitmen.process')}}'
		});

		$(document).on('click', '.btn-rejected', function(){
			var ini = $(this);
			$.confirm({
				animation: 'RotateY',
				closeAnimation: 'scale',
				animationBounce: 1.5,
				icon: 'fa fa-exclamation-triangle',
				title: 'Peringatan!',
				content: 'Apa anda yakin?',
				theme: 'disable',
			    buttons: {
			        info: {
						btnClass: 'btn-blue',
			        	text:'Ya',
			        	action : function(){
							$.toast({
								heading: 'Information',
								text: 'Promosi Ditolak.',
								bgColor: '#0984e3',
								textColor: 'white',
								loaderBg: '#fdcb6e',
								icon: 'info'
							})
					        ini.parents('.btn-group').html('<button class="btn btn-danger btn-sm btn-cancel-reject">Batalkan Penelokan</button>');
				        }
			        },
			        cancel:{
			        	text: 'Tidak',
					    action: function () {
    			            // tutup confirm
    			        }
    			    }
			    }
			});
		});

		$("#datepicker").datepicker( {
			format: "mm/yyyy",
			viewMode: "months", 
			minViewMode: "months"
		});

		$(document).on('click', '.btn-cancel-reject', function(){
			$(this).parents('.btn-group').html('<button class="btn btn-success btn-approval" type="button" title="approve"><i class="fa fa-check"></i></button>'+
			'<button class="btn btn-danger btn-rejected" type="button" title="reject"><i class="fa fa-close"></i></button>')
		})

		$(document).on('click', '.btn-approval', function(){
			$.toast({
				heading: 'Information',
				text: 'Promosi Diterima.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-primary btn-sm btn-cancel-approve">Batalkan Penerimaan</button>')
		})

		$(document).on('click', '.btn-cancel-approve', function(){
			$(this).parents('.btn-group').html('<button class="btn btn-success btn-approval" type="button" title="approve"><i class="fa fa-check"></i></button>'+
			'<button class="btn btn-danger btn-rejected" type="button" title="reject"><i class="fa fa-close"></i></button>')
		})
		targetReal();
	});
	function targetReal() {
	    tb_barangmasuk = $('#table_target').DataTable({
	        responsive: true,
	        serverSide: true,
	        ajax: {
	            url : "{{ route('targetReal.list') }}",
	            type: "get",
	            data: {
	                "_token": "{{ csrf_token() }}"
	            }
	        },
	        columns: [
	            {data: 'st_periode'},
	            {data: 'c_name'},
	            {data: 'i_name'},
	            {data: 'std_qty'},
                {data: 'realisasi'},
	            {data: 'status'},
	            {data: 'action'}
	        ],
	        pageLength: 10,
	        lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
	    });
	}
    function editTarget(st_id, dt_id) {
	    $('#edittarget').modal('show');
        //window.location = baseUrl + "/marketing/penjualanpusat/targetrealisasi/editTarget/" +st_id+"/"+dt_id;
    }
</script>
@endsection
