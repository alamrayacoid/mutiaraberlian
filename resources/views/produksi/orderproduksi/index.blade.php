@extends('main')

@section('content')

@include('produksi.orderproduksi.modal')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Order Produksi </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktifitas Produksi</span>
	    	 / <span class="text-primary" style="font-weight: bold;">Order Produksi</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Order Produksi </h3>
                        </div>
                        <div class="header-block pull-right">	
                			<a class="btn btn-primary" href="{{ route('order.create')  }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover" cellspacing="0" id="table_order">
	                                <thead class="bg-primary">
	                                    <tr>
	                                    	<th>No</th>
	                                		<th>Nota Order</th>
	                                		<th>Produsen</th>
	                                		<th>Nilai Order</th>
	                                		<th>Total Bayar</th>
                                            <th>Status</th>
	                                		<th>Aksi</th>
	                                	</tr>
	                                </thead>
	                                <tbody id="bodyTableIndex">
	                                	
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
@endsection

@section('extra_script')
<script type="text/javascript">
	var tblOrder;
	$(document).ready(function(){
        tblOrder = $('#table_order').DataTable();
		TableIndex();
	});

	function TableIndex(){

        if ($.fn.DataTable.isDataTable("#table_order")) {
            $('#table_order').dataTable().fnDestroy();
        }
        tblOrder = $('#table_order').DataTable({
			responsive: true,
			autoWidth: false,
			serverSide: true,
			ajax: {
				url: "{{ route('order.getOrderProd') }}",
				type: "get"
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'po_nota'},
				{data: 's_company'},
				{data: 'totalnet'},
				{data: 'bayar'},
				{data: 'status'},
				{data: 'aksi'}
			],
		});
	}

	function detailOrder(id){
        if ($.fn.DataTable.isDataTable("#tbl_dtlitem") && $.fn.DataTable.isDataTable("#tbl_dtltermin")) {
            $('#tbl_dtlitem').DataTable().clear().destroy();
            $('#tbl_dtltermin').DataTable().clear().destroy();
        }

        $('#tbl_dtlitem').DataTable({
            "paging":   false,
            "ordering": false,
            "info":     false,
            "searching":     false,
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ route('order.detailitem') }}",
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                }
            },
            columns: [
                {data: 'item'},
                {data: 'unit'},
                {data: 'qty'},
                {data: 'value'},
                {data: 'totalnet'}
            ],
            drawCallback: function( settings ) {
                hitungTotalNet();
            }
        });

        $('#tbl_dtltermin').DataTable({
            "paging":   false,
            "ordering": false,
            "info":     false,
            "searching":     false,
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ route('order.detailtermin') }}",
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                }
            },
            columns: [
                {data: 'termin'},
                {data: 'date'},
                {data: 'value'}
            ],
            drawCallback: function( settings ) {
                hitungTotalTermin();
            }
        });

		$('#detailOrderProduksi').modal('show');
	}

    function hitungTotalNet() {
        var inpTotNet = document.getElementsByClassName( 'totalnet' ),
            totNet  = [].map.call(inpTotNet, function( input ) {
                return parseInt(input.value);
            });

        var total = 0;
        for (var i =0; i < totNet.length; i++) {
            total += parseInt(totNet);
        }

        $("#totNet").html(convertToRupiah(total));
    }

    function hitungTotalTermin() {
        var inpTotTermin = document.getElementsByClassName( 'totaltermin' ),
            totTermin  = [].map.call(inpTotTermin, function( input ) {
                return parseInt(input.value);
            });

        var total = 0;
        for (var i =0; i < totTermin.length; i++) {
            total += parseInt(totTermin);
        }

        $("#totTermin").html(convertToRupiah(total));
    }

	function edit(id){
		window.location.href = baseUrl+'/produksi/orderproduksi/edit?id='+id;
	}

	function hapus(id){
		$.confirm({
			animation: 'RotateY',
			closeAnimation: 'scale',
			animationBounce: 1.5,
			icon: 'fa fa-exclamation-triangle',
			title: 'Peringatan!',
			content: 'Apakah anda yakin ingin menghapus data ini?',
			theme: 'disable',
			buttons: {
				info: {
					btnClass: 'btn-blue',
					text: 'Ya',
					action: function () {
						axios.get(baseUrl+'/produksi/orderproduksi/hapus'+'/'+id).then(function(response) {
							loadingShow();
							if(response.data.status == 'Success'){
								loadingHide();
								messageSuccess("Berhasil", "Data Order Produksi Berhasil Dihapus");
								TableIndex();
							}else{
								loadingHide();
								messageFailed("Gagal", "Data Order Produksi Gagal Dihapus");
							}
						})
					}
				},
				cancel: {
					text: 'Tidak',
					action: function () {
						// tutup confirm
					}
				}
			}
		});		
	}

	function printNota(id) {
        window.open('{{url('/produksi/orderproduksi/nota/')}}'+'/'+id, '_blank');
    }
</script>
@endsection
