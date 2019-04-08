@extends('main')

@section('content')

@include('marketing.konsinyasipusat.penempatanproduk.modal')
@include('marketing.konsinyasipusat.monitoringpenjualan.modal')
@include('marketing.konsinyasipusat.monitoringpenjualan.modal-search')

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
<script type="text/javascript">
    var table_sup, table_pus;
	$(document).ready(function(){
		table_sup = $('#table_penempatan').DataTable({
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ route('konsinyasipusat.getData') }}",
                type: "get"
            },
            columns: [
                {data: 'tanggal'},
                {data: 'nota'},
                {data: 'konsigner'},
                {data: 'total', className: "text-right"},
                {data: 'action'}
            ],
        });

		table_pus = $('#table_monitoringpenjualan').DataTable();

        $('#detail-monitoring').DataTable( {
            "iDisplayLength" : 5
        });

		$(document).on('click', '.btn-disable-pp', function(){
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

		$(document).on('click', '.btn-enable-pp', function(){
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

		$("#search-list-agen").on("click", function() {
			$(".table-modal").removeClass('d-none');
		});

	});

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
                    ajax: {
                        url: baseUrl+'/marketing/konsinyasipusat/detail-konsinyasi/'+id+'/table',
                        type: "get"
                    },
                    columns: [
                        {data: 'barang'},
                        {data: 'jumlah'},
                        {data: 'harga', className: "text-right"},
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
@endsection
