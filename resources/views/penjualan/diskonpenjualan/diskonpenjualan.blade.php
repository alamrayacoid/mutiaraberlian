@extends('main')

@section('content')


<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Diskon Penjualan </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Penjualan</span> / <span class="text-primary" style="font-weight: bold;">Diskon Penjualan</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
	                        <h3 class="title"> Diskon Penjualan </h3>
	                    </div>
	                    <div class="header-block pull-right">
                			<a class="btn btn-primary" href="{{route('tambah_diskonpenjualan')}}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
	                    	
	                    </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	
                        	<div class="table-responsive">
	                            <table class="table data-table table-hover" cellspacing="0">
	                                <thead class="bg-primary">
	                                    <tr>
							                <th>No</th>
							                <th>Tanggal</th>
							                <th>Tipe Diskon</th>
							                <th>Keterangan</th>
							                <th>Aksi</th>
							            </tr>
	                                </thead>
	                                <tbody>
	                                	<tr>
	                                		<td>1</td>
	                                		<td>21-11-2018</td>
	                                		<td>Barang</td>
	                                		<td>Semen</td>
	                                		<td>
	                                			<div class="btn-group">
	                                				<button class="btn btn-warning" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
	                                				<button class="btn btn-danger" type="button" title="Delete"><i class="fa fa-trash-o"></i></button>
	                                			</div>
	                                		</td>
	                                	</tr>
	                                	<tr>
	                                		<td>2</td>
	                                		<td>20-11-2018</td>
	                                		<td>Nota</td>
	                                		<td>POS/20181121/1</td>
	                                		<td>
	                                			<div class="btn-group">
	                                				<button class="btn btn-warning" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
	                                				<button class="btn btn-danger" type="button" title="Delete"><i class="fa fa-trash-o"></i></button>
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