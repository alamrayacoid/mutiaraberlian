@extends('main')
@section('extra_style')
<style type="text/css">
	thead > tr > th > select {
		width: 100%;
	}
	thead>tr>td {
		font-size: 14px;
		font-weight: bold;
	}
</style>
@endsection
@section('content')

@include('marketing.agen.kelolapenjualan.modal-search')
@include('marketing.agen.kelolapenjualan.modal')
<article class="content animated fadeInLeft">
	<div class="title-block text-primary">
		<h1 class="title"> Manajemen Marketing Area  </h1>
		<p class="title-description">
			<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Marketing</span> / <span class="text-primary" style="font-weight: bold;">Manajemen Marketing Area</span>
		</p>
	</div>
	<section class="section">
		<div class="row">
			<div class="col-12">
				<ul class="nav nav-pills mb-3" id="Tabzs">
					<li class="nav-item">
						<a href="#orderprodukagenpusat" class="nav-link active" data-target="#orderprodukagenpusat" aria-controls="orderprodukagenpusat" data-toggle="tab" role="tab">Order Produk ke Agen / Cabang</a>
					</li>
					<li class="nav-item">
						<a href="#kelolapenjualan" class="nav-link" data-target="#kelolapenjualan" aria-controls="kelolapenjualan" data-toggle="tab" role="tab">Kelola Penjualan Langsung </a>
					</li>
					<li class="nav-item">
						<a href="#monitoringpenjualanagen" class="nav-link" data-target="#monitoringpenjualanagen" aria-controls="monitoringpenjualanagen" data-toggle="tab" role="tab">Kelola Penjualan Via Website</a>
					</li>
					<li class="nav-item">
						<a href="#datacanvassing" class="nav-link" data-target="#datacanvassing" aria-controls="datacanvassing" data-toggle="tab" role="tab">Kelola Laporan Keuangan Sederhana </a>
					</li>
					<li class="nav-item">
						<a href="#inventoryagen" class="nav-link" data-target="#inventoryagen" aria-controls="inventoryagen" data-toggle="tab" role="tab">Kelola Data Inventory Agen</a>
					</li>
				</ul>
				<div class="tab-content">
					@include('marketing.agen.orderproduk.index')
					@include('marketing.agen.inventoryagen.index')
					@include('marketing.agen.kelolapenjualan.index')
				</div>
			</div>
		</div>
	</section>
</article>

@endsection
@section('extra_script')
<script type="text/javascript">

	$(document).ready(function(){
		// Code Dummy ----------------------------------------------
		var table_sup = $('#table_orderprodukagenpusat').DataTable();
		var table_pus = $('#table_kelolapenjualan').DataTable();
		var table_modal_detail = $('#detail-kelola').DataTable();
		//var table_pus = $('#table_inventoryagen').DataTable();

		$(document).on('click','.btn-edit',function(){
			window.location.href='{{ route('orderagenpusat.edit') }}'
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

			$(document).ready(function() {
				$('#modal-order').DataTable( {
					"iDisplayLength" : 5
				});
			});

			$(document).ready(function() {
				$('#detail-monitoring').DataTable( {
					"iDisplayLength" : 5
				});
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
		// End Code Dummy -----------------------------------------

		$("#search-list-agen").on("click", function() {
			$(".table-modal").removeClass('d-none');
		});

		$('#table_inventoryagen').DataTable({
      initComplete: function () {
        this.api().columns().every( function () {
            var column = this;
            var select = $('<select class="filter select2"><option value=""></option></select>')
                .appendTo( $(column.header()).empty() ).on( 'change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );

                    column
                        .search( val ? '^'+val+'$' : '', true, false )
                        .draw();
                } );

            column.data().unique().sort().each( function ( d, j ) {
                select.append( '<option value="'+d+'">'+d+'</option>' )
            } );
        } );
        $('.filter').select2();
      }
    });
	});

  function getProvId() {
    var id = document.getElementById("prov").value;
    $.ajax({
        url: "{{route('orderProduk.getCity')}}",
        type: "get",
        data:{
            provId: id
        },
        success: function (response) {
            $('#city').empty();
            $("#city").append('<option value="" selected disabled>=== Pilih Kota ===</option>');
            $.each(response.data, function( key, val ) {
                $("#city").append('<option value="'+val.wc_id+'">'+val.wc_name+'</option>');
            });
            $('#city').focus();
            $('#city').select2('open');
        }
    });
  }

  $('#city').on('change', function(){
  	var city = $('#city').val();
  	$.ajax({
  		url: "{{url('/marketing/agen/get-agen')}}"+"/"+city,
  		type: "get",
      success: function (response) {
          $('#agen').empty();
          $("#agen").append('<option value="" selected disabled>=== Pilih Agen ===</option>');
          $.each(response.data, function( key, val ) {
              $("#agen").append('<option value="'+val.c_id+'">'+val.a_name+'</option>');
          });
          $('#agen').focus();
          $('#agen').select2('open');
      }
  	});
  });

  function filterData() {
  	var id = $('#agen').val();

    $('#table_inventoryagen').DataTable().clear().destroy();
    table_agen = $('#table_inventoryagen').DataTable({
      responsive: true,
      serverSide: true,
      ajax: {
          url: "{{ url('/marketing/agen/filter-data') }}"+"/"+id,
          type: "post",
          data: {
              "_token": "{{ csrf_token() }}"
          }
      },
      columns: [
          {data: 'agen'},
          {data: 'comp'},
          {data: 'i_name'},
          {data: 'kondisi'},
          {data: 'qty'}
      ],
      pageLength: 10,
      lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']],

      initComplete: function () {
        this.api().columns().every( function () {
            var column = this;
            var select = $('<select class="filter select2"><option value=""></option></select>')
                .appendTo( $(column.header()).empty() ).on( 'change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );

                    column
                        .search( val ? '^'+val+'$' : '', true, false )
                        .draw();
                } );

            column.data().unique().sort().each( function ( d, j ) {
                select.append( '<option value="'+d+'">'+d+'</option>' )
            } );
        } );
        $('.filter').select2();
      }
    });
  }
</script>

<!-- kelola penjualan -->
<script type="text/javascript">
$(document).ready(function() {
	cur_date = new Date();
	first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
	last_day =   new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
	$('#date_from_kpl').datepicker('setDate', first_day);
	$('#date_to_kpl').datepicker('setDate', last_day);

	$('#date_from_kpl').on('change', function() {
		TableListKPL();
	});
	$('#date_to_kpl').on('change', function() {
		TableListKPL();
	});
	$('#btn_search_date_kpl').on('click', function() {
		TableListKPL();
	});
	$('#btn_refresh_date_kpl').on('click', function() {
		$('#date_from_kpl').datepicker('setDate', first_day);
		$('#date_to_kpl').datepicker('setDate', last_day);
	});
	TableListKPL();
});

// data-table -> function to retrieve DataTable server side
var tb_listkpl;
function TableListKPL()
{
	$('#table_kelolapenjualan').dataTable().fnDestroy();
	tb_listkpl = $('#table_kelolapenjualan').DataTable({
		responsive: true,
		serverSide: true,
		ajax: {
			url: "{{ route('kelolapenjulan.getListKPL') }}",
			type: "get",
			data: {
				"_token": "{{ csrf_token() }}",
				"date_from" : $('#date_from_kpl').val(),
				"date_to" : $('#date_to_kpl').val()
			}
		},
		columns: [
			{data: 'DT_RowIndex'},
			{data: 'date'},
			{data: 's_nota'},
			{data: 'member', width: "40%"},
			{data: 'total'},
			{data: 'action'}
		],
		pageLength: 10,
		lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
	});
}
// show detail penjualan
function showDetailPenjualan(idPenjualan) {
	$.ajax({
		url: "{{ route('kelolapenjulan.getDetailPenjualan') }}",
		type: 'get',
		data: {
			'id': idPenjualan
		},
		success: function(response) {
			console.log(response.get_sales_dt);
			$.each(response.get_sales_dt, function(key, val) {
				$('#table_detail_kelola tbody').empty();
				$('#table_detail_kelola > tbody:last-child').append('<tr><td>'+ val.get_item.i_name +'</td><td>'+ val.get_unit.u_name +'</td><td class="digits">'+ val.sd_qty +'</td><td> (dummy) </td><td class="rupiah">'+ val.sd_value +'</td></tr>');
			});
		    $('.rupiah').inputmask("currency", {
		        radixPoint: ",",
		        groupSeparator: ".",
		        digits: 2,
		        autoGroup: true,
		        prefix: ' Rp ', //Space after $, this will not truncate the first character.
		        rightAlign: true,
		        autoUnmask: true,
		        nullable: false,
		        // unmaskAsNumber: true,
		    });
		    $('.digits').inputmask("currency", {
		        radixPoint: ",",
		        groupSeparator: ".",
		        digits: 0,
		        autoGroup: true,
		        prefix: '', //Space after $, this will not truncate the first character.
		        rightAlign: true,
		        autoUnmask: true,
		        nullable: false,
		        // unmaskAsNumber: true,
		    });
	        $('#detailkpl').modal('show');
		},
		error: function(e) {
			console.error(e);
		}

	});
}

</script>
@endsection
