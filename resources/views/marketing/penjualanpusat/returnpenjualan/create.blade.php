@extends('main')

@section('content')



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
                			<a class="btn btn-secondary btn-sm" href="{{route('pusat.index')}}"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	<div class="row">
                        		<div class="col-md-3 col-sm-6 col-12">
                        			<label>Metode Return</label>
                        		</div>

                        		<div class="col-md-3 col-sm-6 col-12">
                        			<div class="form-group">
                        				<select class="form-control form-control-sm" id="header-metodereturn">
                        					<option value="">--Pilih Metode Return--</option>
                        					<option value="1">Potong Nota</option>
                        					<option value="2">Tukar Barang</option>
                        					<option value="3">Salah Barang</option>
                        					<option value="4">Salah Alamat</option>
                        					<option value="5">Kurang Barang</option>
                        				</select>
                        			</div>
                        		</div>
                        	</div>
                            @include('marketing.penjualanpusat.returnpenjualan.tab_potongnota')
                            @include('marketing.penjualanpusat.returnpenjualan.tab_tukarbarang')
                            @include('marketing.penjualanpusat.returnpenjualan.tab_salahbarang')
                            @include('marketing.penjualanpusat.returnpenjualan.tab_salahalamat')
                        	@include('marketing.penjualanpusat.returnpenjualan.tab_kurangbarang')
                        </section>
                    </div>
                    <div class="card-footer text-right">
                    	<button class="btn btn-primary" type="button">Simpan</button>
                    	<a href="{{route('pusat.index')}}" class="btn btn-secondary">Kembali</a>
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
        var eueue, crmpie, table3, table4, table5, table_salahkirim_1, table_salahkirim_2;

        eueue = $('#tabel_return_1').DataTable();
        crmpie = $('#tabel_return_2').DataTable();
        table3 = $('#tabel_return_3').DataTable();
        table4 = $('#tabel_return_4').DataTable();
		table5 = $('#tabel_return_5').DataTable();
        table_salahkirim_1 = $('#table_salahkirim_1').DataTable();
        table_salahkirim_2 = $('#table_salahkirim_2').DataTable();

        $('#header-metodereturn').change(function(){
            var ini, potong_nota, tukar_barang, salah_barang, salah_alamat, kurang_barang;
            ini             = $(this).val();
            potong_nota     = $('#potong_nota');
            tukar_barang     = $('#tukar_barang');
            salah_barang     = $('#salah_barang');
            salah_alamat     = $('#salah_alamat');
            kurang_barang     = $('#kurang_barang');

            if (ini === '1') {
                potong_nota.removeClass('d-none');
                tukar_barang.addClass('d-none');
                salah_barang.addClass('d-none');
                salah_alamat.addClass('d-none');
                kurang_barang.addClass('d-none');
            } else if(ini === '2'){
                potong_nota.addClass('d-none');
                tukar_barang.removeClass('d-none');
                salah_barang.addClass('d-none');
                salah_alamat.addClass('d-none');
                kurang_barang.addClass('d-none');
            } else if(ini === '3'){
                potong_nota.addClass('d-none');
                tukar_barang.addClass('d-none');
                salah_barang.removeClass('d-none');
                salah_alamat.addClass('d-none');
                kurang_barang.addClass('d-none');
            } else if(ini === '4'){
                potong_nota.addClass('d-none');
                tukar_barang.addClass('d-none');
                salah_barang.addClass('d-none');
                salah_alamat.removeClass('d-none');
                kurang_barang.addClass('d-none');
            } else if(ini === '5'){
                potong_nota.addClass('d-none');
                tukar_barang.addClass('d-none');
                salah_barang.addClass('d-none');
                salah_alamat.addClass('d-none');
                kurang_barang.removeClass('d-none');
            } else {

                potong_nota.addClass('d-none');
                tukar_barang.addClass('d-none');
                salah_barang.addClass('d-none');
                salah_alamat.addClass('d-none');
                kurang_barang.addClass('d-none');
            }
        });
	});
</script>
@endsection
