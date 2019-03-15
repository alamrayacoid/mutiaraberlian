@extends('main')

@section('content')

    @include('produksi.penerimaanbarang.detail')
    @include('produksi.penerimaanbarang.penerimaan')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Penerimaan Barang</h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktifitas Produksi</span>
	    	 / <span class="text-primary" style="font-weight: bold;">Penerimaan Barang</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Penerimaan Barang </h3>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover table-bordered" cellspacing="0" id="table_penerimaan">
	                                <thead class="bg-primary">
	                                    <tr>
	                                    	<th width="1%">No</th>
	                                		<th>Nota Order</th>
                                            <th>Supplier</th>
                                            <th>Tanggal</th>
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

@endsection

@section('extra_script')
<script type="text/javascript">
    var table;
	$(document).ready(function(){
        table = $('#table_penerimaan').DataTable({
            responsive: true,
            // language: dataTableLanguage,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('produksi/penerimaanbarang/getnotapo') }}",
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}"
                }
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'nota'},
                {data: 'supplier'},
                {data: 'tanggal'},
                {data: 'action'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });

		$(document).on('click', '.btn-disable', function(){
			var ini = $(this);
			$.confirm({
				animation: 'RotateY',
				closeAnimation: 'scale',
				animationBounce: 1.5,
				icon: 'fa fa-exclamation-triangle',
				title: 'Peringatan!',
				content: 'Apa anda yakin mau menonaktifkan data ini?',
				theme: 'disable',
			    buttons: {
			        info: {
						btnClass: 'btn-blue',
			        	text:'Ya',
			        	action : function(){
							$.toast({
								heading: 'Information',
								text: 'Data Berhasil di Nonaktifkan.',
								bgColor: '#0984e3',
								textColor: 'white',
								loaderBg: '#fdcb6e',
								icon: 'info'
							})
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

		$(document).on('click', '.btn-enable', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Aktifkan.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
	                                		'<button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
		})


		// function table_hapus(a){
		// 	table.row($(a).parents('tr')).remove().draw();
		// }
	});

    function detail(id) {
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
                url: "{{ route('penerimaan.detailitem') }}",
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
                url: "{{ route('penerimaan.detailtermin') }}",
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

    function terima(id) {
        // $('#penerimaanOrderProduksi').modal('show');
        window.location = baseUrl+'/produksi/penerimaanbarang/terima-barang'+'/'+id;
    }

    function hitungTotalNet() {
        var inpTotNet = document.getElementsByClassName( 'totalnet' ),
            totNet  = [].map.call(inpTotNet, function( input ) {
                return parseInt(input.value);
            });

        var total = 0;
        for (var i =0; i < totNet.length; i++) {
            total += parseInt(totNet[i]);
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
            total += parseInt(totTermin[i]);
        }

        $("#totTermin").html(convertToRupiah(total));
    }
</script>
@endsection
