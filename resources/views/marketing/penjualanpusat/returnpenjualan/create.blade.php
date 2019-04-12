@extends('main')

@section('content')

@include('marketing.penjualanpusat.returnpenjualan.modal-search')

@include('marketing.penjualanpusat.returnpenjualan.modal-detail')

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
                			<a class="btn btn-secondary btn-sm" href="{{route('penjualanpusat.index')}}"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                            <div class="row">
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label>No. Nota</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="input-group">
                                        <input type="hidden" name="" id="">
                                        <input type="text" name="" id="" class="form-control form-control-sm">
                                        <button class="btn btn-md btn-secondary" title="Pencarian No. Nota" id="btn_searchnota" style="border-left:none;" data-toggle="modal" data-target="#search-modal"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-md btn-primary" id="go">Lanjutkan</button>
                                </div>
                                <hr>
                            </div>
                            <div class="table-responsive table-returnp d-none">
                                <table class="table table-striped table-hover" cellspacing="0" id="table_rp">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Qty</th>
                                        <th>Harga @</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Obat</td>
                                        <td>1</td>
                                        <td>Rp. 50,000.000</td>
                                        <td>Rp. 50,000.000</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#create"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                    	<button class="btn btn-primary" type="button">Simpan</button>
                    	<a href="{{route('penjualanpusat.index')}}" class="btn btn-secondary">Kembali</a>
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
        var table_returnp;

        table_returnp = $('#table_rp').DataTable();

        $('#go').on('click', function(){
            $('.table-returnp').removeClass('d-none');
        });

	});
</script>
@endsection
