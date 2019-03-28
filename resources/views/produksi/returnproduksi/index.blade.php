@extends('main')

@section('content')

    @include('produksi.returnproduksi.detail_return')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Return Produksi </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktifitas Produksi</span>
	    	 / <span class="text-primary font-weight-bold">Return Produksi</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Return Produksi </h3>
                        </div>
                        <div class="header-block pull-right">
                			<a class="btn btn-primary" href="{{route('return.create')}}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover" cellspacing="0" id="table_return">
	                                <thead class="bg-primary">
	                                    <tr>
	                                    	<th>Tgl Return</th>
	                                    	<th>Nota</th>
	                                    	<th>Metode</th>
	                                    	<th>Barang</th>
	                                    	<th>Qty</th>
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
        table = $('#table_return').DataTable({
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ route('return.list') }}",
                type: "get"
            },
            columns: [
                {data: 'tanggal'},
                {data: 'nota'},
                {data: 'metode'},
                {data: 'barang'},
                {data: 'qty'},
                {data: 'action'}
            ],
        });

	});

	function detailReturn(id, detail) {
	    loadingShow();
	    axios.get(baseUrl+'/produksi/returnproduksi/detail-return/'+id+'/'+detail)
            .then(function (resp) {
                loadingHide();
                if (resp.data.status == "Failed") {
                    messageFailed("Gagal", resp.data.message);
                } else if (resp.data.status == "Success") {
                    $('#txt_tanggal').val(resp.data.message.tanggal);
                    $('#txt_nota').val(resp.data.message.nota);
                    $('#txt_barang').val(resp.data.message.barang);
                    $('#txt_qty').val(resp.data.message.qty);
                    $('#txt_metode').val(resp.data.message.metode);
                    $('#txt_ket').text(resp.data.message.keterangan);
                    $("#detailReturn").modal("show");
                }
            })
            .catch(function (error) {
                loadingHide();
                messageWarning("Error", error);
            })
    }

    function editReturn(id, detail) {

    }

    function hapusReturn(id, detail) {

    }
</script>
@endsection
