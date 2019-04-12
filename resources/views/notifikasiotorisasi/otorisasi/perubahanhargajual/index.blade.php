@extends('main')

@section('extra_style')
<style type="text/css">
	a:not(.btn){
		text-decoration: none;
	}
	.card img{
		margin: auto;
	}
	.card-custom{
		min-height: calc(100vh / 2);
	}
	.card-custom:hover,
	.card-custom:focus-within{
		background-color: rgba(255,255,255,.6);
	}
</style>
@endsection

@section('content')

@include('notifikasiotorisasi.otorisasi.perubahanhargajual.modal')

<article class="content">

    <div class="title-block text-primary">
	    <h1 class="title"> Otorisasi</h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Notifikasi & Otorisasi</span>
	    	 / <a href="{{route('otorisasi')}}">Otorisasi</a>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

				<ul class="nav nav-pills mb-3" id="Tabzs">
                    <li class="nav-item">
                        <a href="#hargajualkeagen" class="nav-link active" data-target="#hargajualkeagen" aria-controls="hargajualkeagen" data-toggle="tab" role="tab">Perubahan Harga Jual Ke Agen</a>
                    </li>
                    <li class="nav-item">
                        <a href="#hargajualagen" class="nav-link" data-target="#hargajualagen" aria-controls="hargajualagen" data-toggle="tab" role="tab">Perubahan Harga Jual Agen</a>
                    </li>
                </ul>


				<div class="tab-content">

					@include('notifikasiotorisasi.otorisasi.perubahanhargajual.keagen')
                    @include('notifikasiotorisasi.otorisasi.perubahanhargajual.agen')

	            </div>

			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
    var table1, table2, tableagen;
	$(document).ready(function(){
        table1 = $('#table_otorisasi').DataTable({
            responsive: true,
            // language: dataTableLanguage,
            // processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('notifikasiotorisasi/otorisasi/perubahanhargajual/getdataperubahan') }}",
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}"
                }
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'pc_name', name: 'pc_name'},
                {data: 'nama', name: 'name'},
                {data: 'pcad_payment', name: 'pcad_payment'},
                {data: 'qty', name: 'qty'},
                {data: 'pcad_price', name: 'pcad_price'},
                {data: 'aksi', name: 'aksi'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
		table2 = $('#table_detail').DataTable();

		$('#table_otorisasi tbody').on('click', '.btn-detail' ,function(){
			$('#detail').modal('show');
		})
	});

    tableagen = $('#table_otorisasi_agen').DataTable();

	function approve(id, detailid) {
        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apakah anda yakin akan menyetujui data ini?',
            theme: 'sukses',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        axios.get(baseUrl+'/notifikasiotorisasi/otorisasi/perubahanhargajual/approve'+'/'+id+'/'+detailid).then(function(response) {
                            loadingShow();
                            if(response.data.status == 'sukses'){
                                loadingHide();
                                messageSuccess("Berhasil", "Data Order Produksi Berhasil Disetujui");
                                table1.ajax.reload();
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
</script>
@endsection