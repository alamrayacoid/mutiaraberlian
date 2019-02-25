@extends('main')

@section('content')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title">Setting Tunjangan Pegawai Manajemen</h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktivitas SDM</span>
	    	 / <span class="text-primary" style="font-weight: bold;">Tunjangan</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
	                        <h3 class="title">Setting Tunjangan Pegawai Manajemen</h3>
	                    </div>
	                    <div class="header-block pull-right">
                    			<a class="btn btn-secondary" href="{{route('penggajian.index')}}"><i class="fa fa-arrow-left"></i>&nbsp;Kembali</a>
	                    	
	                    </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	
                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover" cellspacing="0" id="table_agen">
	                                <thead class="bg-primary">
	                                    <tr>
							                <th width="1%">Id</th>
							                <th>Nama</th>
							                <th>NIP</th>
							                <th>Divisi</th>
                                            <th>Jabatan</th>
                                            <th>Tunjangan</th>
                                            <th>Aksi</th>
							            </tr>
	                                </thead>
	                                <tbody>
	                                	<tr>
	                                		<td>1</td>
	                                		<td>Brad</td>
	                                		<td>4567890</td>
                                            <td>Marketing</td>
                                            <td>Sales</td>
                                            <td>
                                                <li>Kehadiran</li>
                                                <li>Uang Makan</li>
                                                <li>Tunjangan Anak</li>
                                                <li>Tunjangan Istri</li>
                                                <li>Kesehatan</li>
                                                <li>Transportasi</li>
                                            </td>
	                                		<td>
	                                			<div class="btn-group btn-group-sm">
	                                				<button class="btn btn-warning btn-edit" type="button" onclick="window.location.href='{{ route('tunjangan.setting.edit') }}'" title="Edit"><i class="fa fa-pencil"></i></button>
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
		var table = $('#table_agen').DataTable();
	});
</script>
@endsection
