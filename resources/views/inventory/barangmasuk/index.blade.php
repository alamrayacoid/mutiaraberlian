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
{{-- Modal Detail --}}
<div class="modal fade" id="mDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <label for="pemilik">Pemilik Barang</label>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <input type="text" class="form-control" value="" id="pemilikB">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="posisiB">Posisi Barang</label>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <input type="text" class="form-control" value="" id="posisiB">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="codeB">Kode Barang</label>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <input type="text" class="form-control" value="" id="codeB">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="namaB">Nama Barang</label>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <input type="text" class="form-control" value="" id="namaB">
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

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
                {data: 'sm_date', name: 'sm_date'},
                {data: 'sm_qty', name: 'sm_qty'},
                {data: 'pemilik', name: 'pemilik'},
                {data: 'posisi', name: 'posisi'},
                {data: 's_condition', name: 's_condition'},
                {data: 'action', name: 'action'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }

    function detail(stock, detail)
    {
        $('#mDetail').modal('show');
        $.ajax({
            url : baseUrl+"/inventory/barangmasuk/getDetail",
            type: "get",
            data :{
                stock: stock,
                detail : detail
            },
            dataType : "json",
            success : function(response){
                document.getElementById("pemilikB").setAttribute("value", response.data.pemilik);
                document.getElementById("posisiB").setAttribute("value", response.data.posisi);
                document.getElementById("codeB").setAttribute("value", response.data.i_code);
                document.getElementById("namaB").setAttribute("value", response.data.i_name);
            }
        })
    }
</script>
@endsection
