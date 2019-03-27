@extends('main')

@section('content')

@include('produksi.returnproduksi.modal-search')

@include('produksi.returnproduksi.modal-detail')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Tambah Return Produksi </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktifitas Produksi</span>
	    	 / <a href="{{route('return.index')}}">Return Produksi</a>
	    	 / <span class="text-primary font-weight-bold">Tambah Return Produksi</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Tambah Return Produksi </h3>
                        </div>
                        <div class="header-block pull-right">
                			<a class="btn btn-secondary btn-sm" href="{{route('return.create')}}"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	<div class="row">
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label>No. Nota</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="">
                                    </div>
                                </div>
                        	</div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped display nowrap" cellspacing="0" id="tabel_return_next">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th width="40%">Nama Barang</th>
                                            <th>Kode Spesifik/Qty</th>
                                            <th width="20%">Status Return</th>
                                        </tr>
                                    </thead>	
                                    <tbody>
                                        <tr>
                                            <td>Obat</td>
                                            <td></td>
                                            <td>
                                                <select name="" id="" class="form-control form-control-sm">
                                                    <option value="">Tukar Barang</option>
                                                    <option value="">Potong Nota</option>
                                                    <option value="">Tukar Uang</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                    	<button class="btn btn-primary" type="button">Simpan</button>
                    	<a href="{{route('return.create')}}" class="btn btn-secondary">Kembali</a>
                    </div>
                    <!-- a -->
                </div>

			</div>

		</div>

	</section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
	$(document).ready(function(){
        $('#tabel_return_next').DataTable();
	});
</script>
@endsection
