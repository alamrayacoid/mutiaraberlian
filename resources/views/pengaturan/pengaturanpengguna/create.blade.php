@extends('main')

@section('content')



<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Tambah Data User </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Pengaturan</span>
	    	 / <a href="{{route('pengaturanpengguna.index')}}">Pengaturan Pengguna</a>
	    	 / <span class="text-primary font-weight-bold">Tambah Data User</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Tambah Data User </h3>
                        </div>
                        <div class="header-block pull-right">
                			<a class="btn btn-secondary btn-sm" href="{{route('pengaturanpengguna.index')}}"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	<div class="row">
                        		<div class="col-md-3 col-sm-6 col-12">
                        			<label>Pilih Jenis</label>
                        		</div>

                        		<div class="col-md-3 col-sm-6 col-12">
                        			<div class="form-group">
                        				<select class="form-control form-control-sm select2" id="jenis">
                        					<option value="" disabled selected="">--Pilih Jenis--</option>
                        					<option value="1">Agen</option>
                        					<option value="2">Pegawai</option>
                        				</select>
                        			</div>
                        		</div>
                        	</div>

                            @include('pengaturan.pengaturanpengguna.create_agen')
                            @include('pengaturan.pengaturanpengguna.create_pegawai')

                        </section>
                    </div>
                    <div class="card-footer text-right">
                    	<button class="btn btn-primary" type="button">Simpan</button>
                    	<a href="{{route('return.index')}}" class="btn btn-secondary">Kembali</a>
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

        $('#jenis').change(function(){
            var jenis, agen, pegawai;
            jenis            = $(this).val();
            agen             = $('#agen');
            pegawai          = $('#pegawai');

            if (jenis === '1') {
                agen.removeClass('d-none');
                pegawai.addClass('d-none');
            } else if(jenis === '2'){
                agen.addClass('d-none');
                pegawai.removeClass('d-none');
            } else {
                agen.addClass('d-none');
                pegawai.addClass('d-none');
            }
        });
	});
</script>
@endsection
