@extends('main')

@section('content')
    @include('notifikasiotorisasi.otorisasi.revisi.orderproduksi.detail')
    @include('notifikasiotorisasi.otorisasi.revisi.produk.modal-detail')
<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Otorisasi Revisi Data </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Notifikasi & Otorisasi</span>
	    	 / <a href="{{route('otorisasi')}}">Otorisasi</a>
	    	 / <span class="text-primary font-weight-bold">Otorisasi Revisi Data</span>
	     </p>
	</div>

	<section class="section">

	<div class="row">

		<div class="col-12">

		<ul class="nav nav-pills mb-3" id="Tabzs">
			<li class="nav-item">
				<a href="#dataproduk" class="nav-link active" data-target="#dataproduk" aria-controls="dataproduk" data-toggle="tab" role="tab">Data Produk</a>
			</li>
			<li class="nav-item">
				<a href="#datapenjualan" class="nav-link" data-target="#datapenjualan" aria-controls="datapenjualan" data-toggle="tab" role="tab">Data Penjualan</a>
			</li>
			<li class="nav-item">
				<a href="#orderproduksi" class="nav-link" data-target="#orderproduksi" aria-controls="orderproduksi" data-toggle="tab" role="tab">Data Order Produksi</a>
			</li>
		</ul>

		<div class="tab-content">

			@include('notifikasiotorisasi.otorisasi.revisi.produk.index')
			@include('notifikasiotorisasi.otorisasi.revisi.penjualan.index')
			@include('notifikasiotorisasi.otorisasi.revisi.orderproduksi.index')


		</div>

	</div>

</div>

</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
    var table_sup, table_bar, table_pus;

	$(document).ready(function(){
		table_sup = $('#table_dataproduk').DataTable();
		table_bar = $('#table_datapenjualan').DataTable();
		setTimeout(function () {
            table_pus = $('#table_orderproduksi').DataTable({
                responsive: true,
                autoWidth: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('getproduksi') }}",
                    type: "get"
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'date'},
                    {data: 'supplier'},
                    {data: 'nota'},
                    {data: 'aksi'}
                ],
            });
        },1000)
	});

	function detailOrderProduksi(id) {
        if ($.fn.DataTable.isDataTable("#tbl_dtlprod") && $.fn.DataTable.isDataTable("#tbl_dtlprodtermin")) {
            $('#tbl_dtlprod').DataTable().clear().destroy();
            $('#tbl_dtlprodtermin').DataTable().clear().destroy();
        }

        $('#tbl_dtlprod').DataTable({
            "paging":   false,
            "ordering": false,
            "info":     false,
            "searching":     false,
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ route('getproduksidetailitem') }}",
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

        $('#tbl_dtlprodtermin').DataTable({
            "paging":   false,
            "ordering": false,
            "info":     false,
            "searching":     false,
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ route('getproduksidetailtermin') }}",
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

        $("#dtlordprod").modal('show');
    }

    function hitungTotalNet() {
        var inpTotNet = document.getElementsByClassName( 'totalnetdetail' ),
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

    function agree(id) {
        return $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 2.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Konfirmasi!',
            content: 'Apakan Anda yakin akan menyetujui order produksi ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        loadingShow();
                        axios.get(baseUrl+'/notifikasiotorisasi/otorisasi/revisi/order-produksi-agree'+'/'+id)
                            .then(function (response) {
                                if (response.data.status == "Success") {
                                    loadingHide();
                                    messageSuccess("Berhasil", "Order produksi berhasil disetujui !");
                                } else {
                                    loadingHide();
                                    messageWarning("Gagal", response.data.message);
                                }
                            })
                            .catch(function (error) {
                                loadingHide();
                                messageFailed("Error", error);
                            })
                            .then(function () {
                                loadingHide();
                                table_pus.ajax.reload();
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

    function rejected(id) {
        return $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 2.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Konfirmasi!',
            content: 'Apakan Anda yakin akan menolak order produksi ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        loadingShow();
                        axios.get(baseUrl+'/notifikasiotorisasi/otorisasi/revisi/order-produksi-rejected'+'/'+id)
                            .then(function (response) {
                                if (response.data.status == "Success") {
                                    loadingHide();
                                    messageSuccess("Berhasil", "Order produksi berhasil ditolak !");
                                } else {
                                    loadingHide();
                                    messageWarning("Gagal", response.data.message);
                                }
                            })
                            .catch(function (error) {
                                loadingHide();
                                messageFailed("Error", error);
                            })
                            .then(function () {
                                loadingHide();
                                table_pus.ajax.reload();
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

<!-- Otorisasi Revisi Data Produk -->
<script type="text/javascript">
    $(document).ready(function() {
        TableRevProduk();
    });
    // data-table -> function to retrieve DataTable server side
    var tb_listrevproduk;
    function TableRevProduk()
    {
        console.log('TableRevProduk is called !');
    	$('#table_revdataproduk').dataTable().fnDestroy();
    	tb_listrevproduk = $('#table_revdataproduk').DataTable({
    		responsive: true,
    		serverSide: true,
    		ajax: {
    			url: "{{ route('revproduk.getListRevDataProduk') }}",
    			type: "get"
    		},
    		columns: [
    			{data: 'DT_RowIndex'},
    			{data: 'produk'},
    			{data: 'authorizationType'},
    			{data: 'action'}
    		],
    		pageLength: 10,
    		lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
    	});
    }
    // show modal detail revisi-produk
    function showDetailRevisiP(id)
    {
        loadingShow();
        $.ajax({
            url: baseUrl + '/notifikasiotorisasi/otorisasi/revisi/get-detail-dataproduk/' + id,
            type: 'get',
            success: function (response) {
                loadingHide();
                console.log(response);
                $('#detNameRP').val(response.ia_name);
                $('#detTypeRP').val(response.get_item_type.it_name);
                $('#detCodeRP').val(response.ia_code);
                $('#detKetRP').val(response.ia_detail);
                $('#detImgRP').attr('src', baseUrl +'/storage/uploads/produk/item-auth/'+ response.ia_id +'/'+ response.ia_image);
                $('#modalDetailRevProduk').modal('show');
            },
            error: function (e) {
                loadingHide();
                messageWarning('Perhatian', 'Terjadi kesalahan saat menampilkan detail revisi, hubungi pengembang !');
            }
        });
    }
    // accept revisi-produk
    function appRevisiP(id)
    {
        loadingShow();
        $.ajax({
            url: baseUrl + '/notifikasiotorisasi/otorisasi/revisi/approve-dataproduk/' + id,
            type: 'post',
            success: function (response) {
                loadingHide();
                console.log(response);
                if (response.status === 'berhasil') {
                    messageSuccess('Selamat', 'Penyetujuan perubahan item berhasil dijalankan !');
                    tb_listrevproduk.ajax.reload();
                } else if (response.status === 'gagal') {
                    messageWarning('Perhatian', response.message);
                }
            },
            error: function (e) {
                loadingHide();
                messageWarning('Perhatian', 'Terjadi kesalahan saat melakukan \'penyetujuan\' revisi, hubungi pengembang !');
            }
        });
    }
    // reject revisi-produk
    function rejRevisiP(id)
    {
        loadingShow();
        $.ajax({
            url: baseUrl + '/notifikasiotorisasi/otorisasi/revisi/reject-dataproduk/' + id,
            type: 'post',
            success: function (response) {
                loadingHide();
                console.log(response);
                if (response.status === 'berhasil') {
                    messageSuccess('Selamat', 'Penolakan perubahan item berhasil dijalankan !');
                    tb_listrevproduk.ajax.reload();
                } else if (response.status === 'gagal') {
                    messageWarning('Perhatian', response.message);
                }
            },
            error: function (e) {
                loadingHide();
                messageWarning('Perhatian', 'Terjadi kesalahan saat melakukan \'penolakan\' revisi, hubungi pengembang !');
            }
        });
    }


</script>
@endsection
