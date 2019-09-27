@extends('main')

@section('content')

@include('produksi.penerimaanbarang.penerimaan.detail')
@include('produksi.penerimaanbarang.history.detail')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Penerimaan Barang </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktifitas Produksi</span> / <span class="text-primary" style="font-weight: bold;">Penerimaan Barang</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

                <ul class="nav nav-pills mb-3" id="Tabzs">
                    <li class="nav-item">
                        <a href="#penerimaan" class="nav-link active" data-target="#penerimaan" aria-controls="penerimaan" data-toggle="tab" role="tab">Penerimaan Barang</a>
                    </li>
                    <li class="nav-item">
                        <a href="#history" class="nav-link" data-target="#history" aria-controls="history" data-toggle="tab" role="tab">History Penerimaan Barang</a>
					</li>
                </ul>

                <div class="tab-content">

					@include('produksi.penerimaanbarang.penerimaan.index')
                    @include('produksi.penerimaanbarang.history.index')

	            </div>

			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<!-- History Penerimaan Barang -->
<script type="text/javascript">
	$(document).ready(function(){
	    let cur_date = new Date();
	    let first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
	    let last_day =   new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
		// history peneriman
	    $('#tgl_awal').datepicker('setDate', first_day);
	    $('#tgl_akhir').datepicker('setDate', last_day);

		var table_sup = $('#table_history').DataTable();

		$("#btn_search").on('click', function(evt){
			evt.preventDefault();
			if ($("#tgl_awal").val() == "" && $("#tgl_akhir").val() == "") {
				$("#tgl_awal").focus();
				messageWarning("Peringatan", "Masukkan tanggal pencarian");
			} else {
				loadingShow();
				if ($.fn.DataTable.isDataTable("#table_history")) {
					$('#table_history').DataTable().clear().destroy();
				}
				tbl_history = $('#table_history').DataTable({
					responsive: true,
					// language: dataTableLanguage,
					processing: true,
					serverSide: true,
					ajax: {
						url: "{{ route('penerimaan.histori') }}",
						type: "get",
						data: {
							"_token": "{{ csrf_token() }}",
							"tgl_awal": $("#tgl_awal").val(),
							"tgl_akhir": $("#tgl_akhir").val()
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
					lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']],
					drawCallback: function( settings ) {
						loadingHide();
					}
				});
			}
		});
	});

	function detailHistory(id) {
		$.ajax({
			url: "{{ route('penerimaan.getDetailHistory') }}",
			type: 'get',
			data: {
				id: id
			},
			beforeSend: function () {
				loadingShow();
			},
			success: function (resp) {
				console.log(resp);
				$('.formDetailHistory').trigger('reset');

				let data = resp.data;
				$('#modHisSupplier').val(data.get_production_order.get_supplier.s_name);
				$('#modHisNota').val(data.ir_notapo);
				$('#modHisDate').val(data.get_production_order.po_date);
				$('#modHisPayStats').val(data.get_production_order.po_status);

				$('#table_detailhistory > tbody').empty();
				$.each(data.get_i_r_detail, function (idx, val) {
					let date = '<td>'+ val.ird_date +'</td>';
					let nota = '<td>'+ val.ird_nota_do +'</td>';
					let item = '<td>'+ val.get_item.i_name +'</td>';
					let qty = '<td class="text-right">'+ val.ird_qty +'</td>';
					let unit = '<td>'+ val.get_unit.u_name +'</td>';
					let row = '<tr>'+ date + nota + item + qty + unit +'</tr>';
					$('#table_detailhistory > tbody').append(row);
				});

				$('#detailPenerimaanProduksi').modal('show');
			},
			error: function (err) {
				messageWarning('Error', 'Terjadi kesalahan : ' + err);
			},
			complete: function () {
				loadingHide();
			}
		})
	}

	function editHistory(id) {
		window.location.href = baseUrl + '/produksi/penerimaanbarang/edit-history-penerimaan/' + id;
	}
</script>

<!-- Penerimaan Baranag -->
<script type="text/javascript">
    var table, tbl_history;
	$(document).ready(function(){
		setTimeout(function () {
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
		}, 100);
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
