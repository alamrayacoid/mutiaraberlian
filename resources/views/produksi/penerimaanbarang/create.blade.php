@extends('main')

@section('content')



<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Proses Penerimaan Barang</h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktifitas Produksi</span>
	    	 / <a href="{{route('penerimaan.index')}}">Penerimaan Barang</a>
	    	 / <span class="text-primary font-weight-bold">Proses Penerimaan Barang</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Proses Penerimaan Barang </h3>
                        </div>
                        <div class="header-block pull-right">
                        	<a href="{{route('penerimaan.index')}}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	<div class="row">
                        		<div class="col-md-3 col-sm-4 col-12">
                        			<label>Nota Order</label>
                        		</div>

                        		<div class="col-md-3 col-sm-8 col-12">
                        			<div class="form-group">
                        				<input type="text" class="form-control form-control-sm" readonly="" name="">
                        			</div>
                        		</div>	

                        		<div class="col-md-2 col-sm-4 col-12">
                        			<input type="text" readonly="" class="form-control form-control-sm" name="">
                        			<small>Jumlah</small>
                        		</div>
                        		<div class="col-md-2 col-sm-4 col-12">
                        			<input type="text" readonly="" class="form-control form-control-sm" name="">
                        			<small>Sudah di Bayar</small>
                        		</div>
                        		<div class="col-md-2 col-sm-4 col-12">
                        			<input type="text" readonly="" class="form-control form-control-sm" name="">
                        			<small>Kurang</small>
                        		</div>
                        	</div>

                        	<hr>

                        	<div class="row">
	                        	<div class="col-md-3 col-sm-4 col-12">
	                        		<label>Surat Jalan</label>	
	                        	</div>

	                        	<div class="col-md-3 col-sm-6 col-12">
                        			<div class="form-group">
                        				<input type="text" class="form-control form-control-sm" name="">
                        			</div>
                        		</div>	
	                        </div>

	                        <fieldset>
	                        	<div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped" id="table_penerimaan" cellspacing="0">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th>Barcode</th>
                                                <th>Nama Barang</th>
                                                <th>Qty Terima</th>
                                                <th>Qty Order</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
	                        </fieldset>

                        </section>
                    </div>
                    <div class="card-footer text-right">
                    	<button class="btn btn-primary" type="button">Simpan</button>
                    	<a href="{{route('penerimaan.index')}}" class="btn btn-secondary">Kembali</a>
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
        $('#table_penerimaan').DataTable();
    });

</script>
@endsection
