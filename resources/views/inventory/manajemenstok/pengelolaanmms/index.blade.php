@extends('main')

@section('content')

@include('inventory.manajemenstok.pengelolaanmms.modal')

<article class="content animated fadeInLeft">


    <div class="title-block text-primary">
	    <h1 class="title"> Manajemen Penjualan Stok  </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Inventory</span> / <span class="text-primary" style="font-weight: bold;">Pengelolaan Manajemen Stok</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
	                        <h3 class="title"> Penglolaan Data Max/Min Stok, Safety Stok </h3>
	                    </div>
	                    <div class="header-block pull-right">
                    			<a class="btn btn-primary" href="{{route('pengelolaanmms.create')}}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
	                    </div>
                    </div>
                    <div class="card-block">
                        <section>
                        <div class="top-section mb-3">
                        <fieldset>
                        <div class="row">
                            <div class="col-md-1 col-sm-12">
                                <label for="">Pemilik</label>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <select name="" id="" class="form-control form-control-sm select2">
                                    <option value="">Pilih</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-12">
                                <label for="">Posisi</label>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <select name="" id="" class="form-control form-control-sm select2">
                                    <option value="">Pilih</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-12">
                                <label for="">Barang</label>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <input type="text" class="form-control form-control-sm">
                            </div>
                            <div class="col-1">
                                <button class="btn btn-md btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                            
                        </div>
                        </fieldset>
                        </div>
                        	
                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_pengelolaanmms">
	                                <thead class="bg-primary">
	                                    <tr>
							                <th width="1%">No</th>
											<th>Pemilik</th>
											<th>Posisi</th>
							                <th>Barang</th>
											<th>Qty</th>
							                <th>Aksi</th>
							            </tr>
	                                </thead>
	                                <tbody>
	                                	<tr>
	                                		<td>1</td>
											<td>Agung</td>
	                                		<td>Rungkut</td>
											<td>Kode - Nama Barang</td>
											<td>12</td>
	                                		<td>
	                                			<div class="btn-group btn-group-sm">
	                                				<button class="btn btn-primary btn-detail" type="button" title="Detail" data-toggle="modal" data-target="#detail"><i class="fa fa-folder"></i></button>
	                                				<button class="btn btn-warning btn-edit" type="button" title="Edit" onclick="window.location.href='{{route('pengelolaanmms.edit')}}'"><i class="fa fa-pencil"></i></button>
	                                			</div>
	                                		</td>
	                                	</tr>
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

	$(document).ready(function(){
		var table = $('#table_pengelolaanmms').DataTable();
	});
</script>
@endsection
