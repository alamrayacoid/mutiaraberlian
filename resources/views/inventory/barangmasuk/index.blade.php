@extends('main')

@section('content')



<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Pengelolaan Barang Masuk </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktivitas Inventory</span>
	    	 / <span class="text-primary font-weight-bold">Pengelolaan Barang Masuk</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Pengelolaan Barang Masuk </h3>
                        </div>
                        <div class="header-block pull-right">
                        	
                			<a class="btn btn-primary" href="{{ route('barangmasuk.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_barangmasuk">
	                                <thead class="bg-primary">
	                                    <tr>
	                                		<th>Tanggal Masuk</th>
	                                		<th>Kode Barang</th>
	                                		<th>Jumlah Barang</th>
	                                		<th>Pemilik Barang</th>
											<th>Lokasi Masuk</th>
											<th>Keterangan</th>
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
	var tb_barangmasuk;
	$(document).ready(function(){
		TableCabang();
	});

	function TableCabang() {
        tb_barangmasuk = $('#table_barangmasuk').DataTable({
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ route('barangmasuk.list') }}",
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}"
                }
            },
            columns: [
                {data: 's_created_at', name: 's_created_at'},
                {data: 'i_code', name: 'i_code'},
                {data: 's_qty', name: 's_qty'},
                {data: 's_comp', name: 's_comp'},
                {data: 's_position', name: 's_position'},
                {data: 's_condition', name: 's_condition'},
                {data: 'action', name: 'action'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }
</script>
@endsection
