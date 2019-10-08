@extends('main')

@section('content')

@include('marketing.konsinyasipusat.penempatanproduk.modal')
@include('marketing.konsinyasipusat.monitoringpenjualan.modal-detail')
@include('marketing.konsinyasipusat.monitoringpenjualan.modal-search')
@include('marketing.konsinyasipusat.penerimaanuangpembayaran.modal-search')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Manajemen Konsinyasi Pusat </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Marketing</span> / <span class="text-primary" style="font-weight: bold;">Manajemen Konsinyasi Pusat</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

                <ul class="nav nav-pills mb-3" id="Tabzs">
                    <li class="nav-item">
                        <a href="#penempatanproduk" class="nav-link active" data-target="#penempatanproduk" aria-controls="penempatanproduk" data-toggle="tab" role="tab">Penempatan Produk</a>
                    </li>
                    <li class="nav-item">
                        <a href="#monitoringpenjualan" class="nav-link" data-target="#monitoringpenjualan" aria-controls="monitoringpenjualan" data-toggle="tab" role="tab">Monitoring Penjualan</a>
					</li>
                    <li class="nav-item">
                        <a href="#penerimaanuangpembayaran" class="nav-link" data-target="#penerimaanuangpembayaran" aria-controls="penerimaanuangpembayaran" data-toggle="tab" role="tab">Penerimaan Uang Pembayaran</a>
					</li>
                </ul>

                <div class="tab-content">

					@include('marketing.konsinyasipusat.penempatanproduk.index')
					@include('marketing.konsinyasipusat.monitoringpenjualan.index')
					@include('marketing.konsinyasipusat.penerimaanuangpembayaran.index')

	            </div>

			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<!-- ========================================================================-->
<!-- script for Penempatan-Produk -->
<script type="text/javascript">
	var table_sup, table_pus, table_monitoring;

	$(document).ready(function(){
		// set timeout
		setTimeout(function () {
			table_pus = $('#table_monitoringpenjualan').DataTable();

			table_monitoring = $('#detail-monitoring').DataTable( {
				"iDisplayLength" : 5
			});

			$("#search-list-agen").on("click", function() {
				$(".table-modal").removeClass('d-none');
			});

			$('#consignor').on('select2:select', function() {
				getListKonsinyasi();
			});
			getListKonsinyasi();
		}, 10);
	});

	function getListKonsinyasi() {
		let consignor = $('#consignor').val();
		$('#table_penempatan').dataTable().fnDestroy();
		table_sup = $('#table_penempatan').DataTable({
			responsive: true,
			autoWidth: false,
			serverSide: true,
			processing: true,
			ajax: {
				url: "{{ route('konsinyasipusat.getData') }}",
				type: "get",
				data: {
					consignor: consignor
				}
			},
			columns: [
				{data: 'tanggal'},
				{data: 'nota'},
				{data: 'konsigner'},
				{data: 'total', className: "text-right"},
				{data: 'action'}
			],
		});
	}

	function detailKonsinyasi(id) {
	    loadingShow();
	    var detail = false, tabel = false, err = null, tipe = "";
        if ($.fn.DataTable.isDataTable("#modal-penempatan")) {
            $('#modal-penempatan').dataTable().fnDestroy();
        }

        axios.get(baseUrl+'/marketing/konsinyasipusat/detail-konsinyasi/'+id+'/detail')
            .then(function (resp) {
                if (resp.data.tipe == "K") {
                    tipe = "KONSINYASI";
                } else {
                    tipe = "Cash";
                }
                $("#txt_tanggal").val(resp.data.tanggal);
                $("#txt_area").val(resp.data.area);
                $("#txt_nota").val(resp.data.nota);
                $("#txt_konsigner").val(resp.data.konsigner);
                $("#txt_tipe").val(tipe);
                $("#txt_total").val(resp.data.total);

                $('#modal-penempatan').DataTable({
                    responsive: true,
                    autoWidth: false,
                    serverSide: true,
										processing: true,
                    ajax: {
                        url: baseUrl+'/marketing/konsinyasipusat/detail-konsinyasi/'+id+'/table',
                        type: "get"
                    },
                    columns: [
                        {data: 'barang'},
                        {data: 'jumlah'},
                        {data: 'harga', className: "text-right"},
                        {data: 'diskon', className: "text-right"},
                        {data: 'total_harga', className: "text-right"}
                    ],
                    "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, 100]],
                    "drawCallback": function( settings ) {
                        loadingHide();
                        $("#detailKonsinyasi").modal('show');
                    }
                });
            })
            .catch(function (error) {
                loadingHide();
                messageWarning("Error", error);
            })
    }

    function editKonsinyasi(id) {
	    window.location = baseUrl+'/marketing/konsinyasipusat/penempatanproduk/edit/'+id;
    }

    function hapusKonsinyasi(id, nota) {
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
                        loadingShow();
                        axios.get('{{ route('penempatanproduk.delete') }}', {
                            params: {
                                id: id,
                                nota: nota
                            }
                        })
                            .then(function (response) {
                                if(response.data.status == 'Success'){
                                    loadingHide();
                                    messageSuccess("Berhasil", response.data.message);
                                    table_sup.ajax.reload();
                                }else{
                                    loadingHide();
                                    messageFailed("Gagal", response.data.message);
                                }
                            })
                            .catch(function (error) {
                                loadingHide();
                                messageWarning("Error", error);
                            });
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
</script>

<!-- ========================================================================-->
<!-- script for Data-Monitoring-Penjualan-Agen -->
<script type="text/javascript">
	$(document).ready(function() {
		// set timeout
		setTimeout(function () {
			const cur_date = new Date();
			const first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
			const last_day =   new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
			$('#date_from_mp').datepicker('setDate', first_day);
			$('#date_to_mp').datepicker('setDate', last_day);

			$('#date_from_mp').on('change', function() {
				TableListMP();
			});
			$('#date_to_mp').on('change', function() {
				TableListMP();
			});
			$('#btn_search_date_mp').on('click', function() {
				TableListMP();
			});
			$('#btn_refresh_date_mp').on('click', function() {
				$('#filter_agent_code_mp').val('');
				$('#filter_agent_name_mp').val('');
				$('#date_from_mp').datepicker('setDate', first_day);
				$('#date_to_mp').datepicker('setDate', last_day);
			});
			// retrieve data-table
			TableListMP();
			// filter agent based on area (province and city)
			$('.provMP').on('change', function() {
				getCitiesMP();
			});
			$('.citiesMP').on('change', function(){
				$(".table-modal").removeClass('d-none');
				appendListAgentsMP();
			});
			// filter agent field
			$('#filter_agent_name_mp').on('click', function() {
				$(this).val('');
				$('#filter_agent_code_mp').val('');
			});
			$('#filter_agent_name_mp').on('keyup', function() {
				findAgentsByAuMP();
			});
			// btn applyt filter agent
			$('#btn_filter_mp').on('click', function() {
				// console.log($('#filter_agent_code_mp').val());
				TableListMP();
			});
		}, 200);
	});

	// data-table -> function to retrieve DataTable server side
	var tb_listmp;
	function TableListMP()
	{
		// if ($('#filter_agent_code_mp').val() !== "") {
		// 	$('.table-monitoringpenjualan').removeClass('d-none');
		// } else {
		// 	$('.table-monitoringpenjualan').addClass('d-none');
		// 	return 0;
		// }

		// let consignee = $('#filter_agent_code_mp').val();
		let consignor = $('#consignorMP').val();
		let consignee = $('#consigneeMP').val();

		$('#table_monitoringpenjualan').dataTable().fnDestroy();
		tb_listmp = $('#table_monitoringpenjualan').DataTable({
			responsive: true,
			serverSide: true,
			processing: true,
			ajax: {
				url: "{{ route('monitoringpenjualan.getListMP') }}",
				type: "get",
				data: {
					"_token": "{{ csrf_token() }}",
					"date_from": $('#date_from_mp').val(),
					"date_to": $('#date_to_mp').val(),
					"consignor": consignor,
					"consignee": consignee
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'placement'},
				// {data: 'items'},
				{data: 'total_qty'},
				{data: 'total_price'},
				{data: 'sold_status'},
				// {data: 'sold_amount'}
				{data: 'items'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}
	// autocomple to find-agents
	function findAgentsByAuMP()
	{
		$('#filter_agent_name_mp').autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: baseUrl + '/marketing/konsinyasipusat/monitoringpenjualan/find-agents-au',
					data: {
						"termToFind": $("#filter_agent_name_mp").val()
					},
					dataType: 'json',
					success: function( data ) {
						response( data );
					}
				});
			},
			minLength: 1,
			select: function(event, data) {
				$('#filter_agent_code_mp').val(data.item.id);
				// console.log('agent-code: ' + $('#filter_agent_code_mp').val());
			}
		});
	}
	// get cities for search-agent
	function getCitiesMP()
	{
		var provId = $('.provMP').val();
		$.ajax({
			url: "{{ route('monitoringpenjualan.getCitiesMP') }}",
			type: "get",
			data:{
				provId: provId
			},
			success: function (response) {
				$('.citiesMP').empty();
				$(".citiesMP").append('<option value="" selected="" disabled="">=== Pilih Kota ===</option>');
				$.each(response.get_cities, function( key, val ) {
					$(".citiesMP").append('<option value="'+ val.wc_id +'">'+ val.wc_name +'</option>');
				});
				$('.citiesMP').focus();
				$('.citiesMP').select2('open');
			}
		});
	}
	// append data to table-list-agens
	function appendListAgentsMP()
	{
		$.ajax({
			url: "{{ route('monitoringpenjualan.getAgentsMP') }}",
			type: 'get',
			data: {
				cityId: $('.citiesMP').val()
			},
			success: function(response) {
				$('#table_search_mp tbody').empty();
				if (response.length <= 0) {
					return 0;
				}
				$.each(response, function(index, val) {
					listAgents = '<tr><td>'+ val.get_agent.get_province.wp_name +'</td>';
					listAgents += '<td>'+ val.get_agent.get_city.wc_name +'</td>';
					listAgents += '<td>'+ val.get_agent.a_name +'</td>';
					listAgents += '<td>'+ val.get_agent.a_type +'</td>';
					listAgents += '<td><button type="button" class="btn btn-sm btn-primary" onclick="addFilterAgentMP(\''+ val.c_id +'\',\''+ val.get_agent.a_name +'\')"><i class="fa fa-download"></i></button></td></tr>';
				});
				$('#table_search_mp > tbody:last-child').append(listAgents);
			}
		});
	}
	// add filter-agent
	function addFilterAgentMP(agentCode, agentName)
	{
		$('#filter_agent_name_mp').val(agentCode+ ' - ' +agentName);
		$('#filter_agent_code_mp').val(agentCode);
		$('#modalSearchAgentMP').modal('hide');
	}
	// show detail-penjualan
	function showDetailSalescomp(id)
	{
		$.ajax({
			url: baseUrl + "/marketing/konsinyasipusat/monitoringpenjualan/get-sales-detail/" + id,
			type: "get",
			beforeSend: function (){
				loadingShow();
			},
			success: function(resp) {
				$('#table_detailmp tbody').empty();
				$('#table_detailtransaksimp tbody').empty();

				let response = resp.detail;
				if (response.length <= 0) {
					return 0;
				}
				$('#nota_dtmp').val(response.sc_nota);
				$('#date_dtmp').val(response.dateFormated);
				$('#placement_dtmp').val(response.get_agent.c_name);
				$('#total_dtmp').val(parseInt(response.sc_total));
				// append detail konsinyasi
				$.each(response.get_sales_comp_dt, function(index, val) {
					let name = '<td>'+ val.get_item.i_code +' - '+ val.get_item.i_name +'</td>';
					let qty = '<td>'+ val.scd_qty +'</td>';
					let unit = '<td>'+ val.get_unit.u_name +'</td>';
					let price = '<td class="rupiah">'+ parseInt(val.scd_value) +'</td>';
					let total_price = '<td class="rupiah">'+ parseInt(val.scd_totalnet) +'</td>';
					$('#table_detailmp > tbody:last-child').append('<tr>'+ name + qty + unit + price + total_price +'</tr>');
				});

				// append detail transaksi using konsinyasi
				$.each(resp.listNota, function (idx, val) {
					let date = '<td>'+ resp.listDates[idx]  +'</td>';
					let nota = '<td>'+ resp.listNota[idx]  +'</td>';
					let pembeli = '<td>'+ resp.listBuyer[idx]  +'</td>';
					let qty = '<td class="text-right">'+ resp.listQtyItems[idx]  +'</td>';
					$('#table_detailtransaksimp > tbody:last-child').append('<tr>'+ date + nota + pembeli + qty +'</tr>');
				})

				// re-activate mask-money
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
				loadingHide();
				$('#modalDetailMP').modal('show');
			},
			error: function(e) {
				messageWarning('Perhatian', 'terjadi kesalahan saat pengambilan data detail penjualan !');
			},
			complete: function() {
				loadingHide();
			}
		})
	}
</script>

<!-- ========================================================================-->
<!-- script for Data-Penerimaan-Pembayaran -->
<script type="text/javascript">
	$(document).ready(function() {
		// set timeout
		setTimeout(function () {
			// filter agent based on area (province and city)
			$('.provPP').on('change', function() {
				getCitiesPP();
			});
			$('.citiesPP').on('change', function(){
				$(".table-modal").removeClass('d-none');
				appendListAgentsPP();
			});
			// filter agent field
			$('#filter_agent_name_pp').on('click', function() {
				resetFormPP();
				// console.log($('#listNotaPP').val());
			});
			$('#filter_agent_name_pp').on('keyup', function() {
				findAgentsByAuPP();
			});
			// get sales-comp-payment based on nota/id
			$('#listNotaPP').on('select2:select', function() {
				getPaymentPP();
			});
			$('#paymentValPP').on('keyup', function() {
				if (parseInt($(this).val()) > parseInt($('#restBillPP').val())) {
					$(this).val(parseInt($('#restBillPP').val()));
					messageWarning('Perhatian', '\'Nominal Pembayaran\' tidak boleh melebihi \'Sisa Tagihan\' !');
				} else if (parseInt($(this).val()) <= 0) {
					messageWarning('Perhatian', '\'Nominal Pembayaran\' tidak boleh 0 atau dibawah 0 !');
				}
			});
			$('#btn_simpanPP').on('click', function() {
			submitPayment();
		});
		}, 300);
	});

	// reset all field
	function resetFormPP()
	{
		$('#formAddPP')[0].reset();
		$('#listNotaPP').empty();
		$('#paymentTypePP option:first').attr('selected', 'selected').trigger('change');
		$('#paymentTypePP').select2();
		$('#cashAccountPP option:first').attr('selected', 'selected').trigger('change');
		$('#cashAccountPP').select2();
	}
	// autocomple to find-agents
	function findAgentsByAuPP()
	{
		$('#filter_agent_name_pp').autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: baseUrl + '/marketing/konsinyasipusat/monitoringpenjualan/find-agents-au',
					data: {
						"termToFind": $("#filter_agent_name_pp").val()
					},
					dataType: 'json',
					success: function( data ) {
						response( data );
					}
				});
			},
			minLength: 1,
			select: function(event, data) {
				$('#filter_agent_code_pp').val(data.item.id);
				// console.log('agent-code: ' + $('#filter_agent_code_pp').val());
				getListNotaPP();
			}
		});
	}
	// get cities for search-agent
	function getCitiesPP()
	{
		var provId = $('.provPP').val();
		$.ajax({
			url: "{{ route('monitoringpenjualan.getCitiesMP') }}",
			type: "get",
			data:{
				provId: provId
			},
			success: function (response) {
				$('.citiesPP').empty();
				$(".citiesPP").append('<option value="" selected="" disabled="">=== Pilih Kota ===</option>');
				$.each(response.get_cities, function( key, val ) {
					$(".citiesPP").append('<option value="'+ val.wc_id +'">'+ val.wc_name +'</option>');
				});
				$('.citiesPP').focus();
				$('.citiesPP').select2('open');
			}
		});
	}
	// append data to table-list-agens
	function appendListAgentsPP()
	{
		$.ajax({
			url: "{{ route('monitoringpenjualan.getAgentsMP') }}",
			type: 'get',
			data: {
				cityId: $('.citiesPP').val()
			},
			success: function(response) {
				$('#table_search_pp tbody').empty();
				if (response.length <= 0) {
					return 0;
				}
				$.each(response, function(index, val) {
					listAgents = '<tr><td>'+ val.get_province.wp_name +'</td>';
					listAgents += '<td>'+ val.get_city.wc_name +'</td>';
					listAgents += '<td>'+ val.a_name +'</td>';
					listAgents += '<td>'+ val.a_type +'</td>';
					listAgents += '<td><button type="button" class="btn btn-sm btn-primary" onclick="addFilterAgentPP(\''+ val.get_company.c_id +'\',\''+ val.a_name +'\')"><i class="fa fa-download"></i></button></td></tr>';
				});
				$('#table_search_pp > tbody:last-child').append(listAgents);
			}
		});
	}
	// add filter-agent
	function addFilterAgentPP(agentCode, agentName)
	{
		$('#filter_agent_name_pp').val(agentCode+ ' - ' +agentName);
		$('#filter_agent_code_pp').val(agentCode);
		$('#modalSearchAgentPP').modal('hide');
		getListNotaPP();
	}
	// get list nota based on selected konsigner
	function getListNotaPP()
	{
		$.ajax({
			url: "{{ route('penerimaanpembayaran.getListNotaPP') }}",
			data: {
				agentCode: $('#filter_agent_code_pp').val()
			},
			type: 'get',
			success: function (response) {
				// console.log(response);
				// append data to list-nota
				$('#listNotaPP').empty();
				$("#listNotaPP").append('<option value="" selected="" disabled="">Pilih Nota</option>');
				$.each(response, function( key, val ) {
					$("#listNotaPP").append('<option value="'+ val.sc_id +'">'+ val.sc_nota +'</option>');
				});
				$('#listNotaPP').focus();
				$('#listNotaPP').select2('open');
			},
			error: function(e) {
				messageWarning('Perhatian', 'Terjadi kesalahan saat mencari nota, hubungi pengembang !');
			}
		})
	}
	// get payment detail based on selected nota/id
	function getPaymentPP()
	{
		$.ajax({
			url: "{{ route('penerimaanpembayaran.getPaymentPP') }}",
			data: {
				salescompId: $('#listNotaPP').val()
			},
			type: 'get',
			success: function (response) {
				// console.log(response);
				// set some field with payment status
				$('#totalBillPP').val(parseInt(response.sc_total));
				$('#paidBillPP').val(parseInt(response.paidBill));
				$('#restBillPP').val(parseInt(response.restBill));
			},
			error: function(e) {
				messageWarning('Perhatian', 'Terjadi kesalahan saat mencari detail pembayaran, hubungi pengembang !');
			}
		});
	}
	// store payment
	function submitPayment()
	{
		$.ajax({
			url: "{{ route('penerimaanpembayaran.storePP') }}",
			data: {
				salescompId: $('#listNotaPP').val(),
				paymentType: $('#paymentTypePP').val(),
				cashAccount: $('#cashAccountPP').val(),
				info: $('#infoPP').val(),
				paymentVal: $('#paymentValPP').val(),
				restBill: $('#restBillPP').val()
			},
			type: 'post',
			success: function (response) {
				if (response.status == 'berhasil') {
					messageSuccess('Selamat', 'Data berhasil ditambahkan !');
					resetFormPP();
				} else if (response.status == 'invalid') {
					messageFailed('Perhatian', response.message);
				}
			},
			error: function(e) {
				messageWarning('Perhatian', 'Terjadi kesalahan saat mencari detail pembayaran, hubungi pengembang !');
			}
		});
	}
</script>

@endsection
