@extends('main')

@section('content')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Pengaturan Pengguna </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Pengaturan</span>
	    	 / <span class="text-primary font-weight-bold">Pengaturan Pengguna</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

				<div class="card">
                    <div class="card-block">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="profil-image col-md-4">
                                    <img class="rounded-circle circle-border m-b-md" src="{{ asset('assets/img/default.jpg') }}" alt="profil" style="width:100%; height:100%;">
                                </div>
                                <div class="profil-info col-md-8">
                                    <div>
                                        <h2 class="mb-4">{{$nama}}</h2>
                                        <h4>{{$level->m_name}}</h4>
                                        <p>{{$address}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <table class="table small m-b-sm">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Perusahaan</strong>
                                        </td>
                                        <td>MUTIARA A</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Last Login</strong>
                                        </td>
                                        <td>{{Carbon\Carbon::parse(Auth::user()->u_lastlogin)->format('d/m/Y G:i:s')}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Last Logout</strong>
                                        </td>
                                        <td>{{Carbon\Carbon::parse(Auth::user()->u_lastlogout)->format('d/m/Y G:i:s')}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <label for="">Username</label>
                            <h2 class="no margins">{{Auth::user()->u_username}}</h2>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                    <h5>Akses Pengguna</h5>
                        <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_akses">
                            <thead class="bg-primary">
                                <tr>
                                    <th width="25%">Nama Fitur</th>
                                    <th width="5%">Read</th>
                                    <th width="5%">Insert</th>
                                    <th width="5%">Update</th>
                                    <th width="5%">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
															@php
																	$nomor=1;
															@endphp

															@foreach($menu as $index => $data)

															@if($data->a_parent == $data->a_id)
																	<tr style="background: #f7e8e8">
																			<td>
																					 <input type="hidden" name="id_access[]" value="{{$data->a_id}}">
																					{{$nomor}}. &nbsp; <strong>{{$data->a_name}}</strong>
																			</td>
																			<td>

																					 <input type="hidden" value="N" class="checkbox" name="read[]"  id="read-{{$data->a_id}}">
																					 <label><input class="checkbox checkbox-info rounded" onchange="simpanRead('{{$data->a_id}}')"  id="read-{{$data->a_id}}" type="checkbox"><span></span></label>

																			</td>
																			<td>
																					<input type="hidden" value="N" class="checkbox" name="insert[]" id="insert-{{$data->a_id}}">
																					<label><input class="checkbox rounded" onchange="simpanInsert('{{$data->a_id}}')"  id="insert-{{$data->a_id}}" type="checkbox"><span></span></label>
																			</td>
																			<td>
																					<input type="hidden" value="N" class="checkbox" name="update[]" id="update-{{$data->a_id}}">
																					<label><input class="checkbox rounded" onchange="simpanUpdate('{{$data->a_id}}')"  id="update-{{$data->a_id}}" type="checkbox"><span></span></label>
																			</td>
																			<td>
																					 <input type="hidden" value="N" class="checkbox" name="delete[]" id="delete-{{$data->a_id}}">
																					 <label><input class="checkbox rounded" onchange="simpanDelete('{{$data->a_id}}')"  id="delete-{{$data->a_id}}" type="checkbox"><span></span></label>
																			</td>
																					@php
																							$nomor++;
																					@endphp
																			</tr>
																	@else
																	<tr>
																			<td>
																			<input type="hidden" name="id_access[]" value="{{$data->a_id}}">
																					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$data->a_name}}
																			</td>
																			<td>
																			<input type="checkbox" class="checkbox" onchange="simpanRead('{{$data->a_id}}')"  id="read1-{{$data->a_id}}">
																			 <label><input class="checkbox rounded" value="N" name="read[]"  id="read-{{$data->a_id}}" type="checkbox"><span></span></label>
																			</td>
																			<td>
																					<input type="checkbox" class="checkbox" onchange="simpanInsert('{{$data->a_id}}')" id="insert1-{{$data->a_id}}">

																					<label><input class="checkbox rounded" value="N" name="insert[]"  id="insert-{{$data->a_id}}" type="checkbox"><span></span></label>
																			</td>
																			<td>
																					<input type="checkbox" class="checkbox" onchange="simpanUpdate('{{$data->a_id}}')" id="update1-{{$data->a_id}}">

																					 <label><input class="checkbox rounded" value="N" name="update[]"  id="update-{{$data->a_id}}" type="checkbox"><span></span></label>

																			</td>
																			<td>
																					<input type="checkbox" class="checkbox" onchange="simpanDelete('{{$data->a_id}}')" id="delete1-{{$data->a_id}}">

																					<label><input class="checkbox rounded" value="N" name="delete[]"  id="delete-{{$data->a_id}}" type="checkbox"><span></span></label>
																			</td>
																	</tr>
																	@endif


														 @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div class="card-footer text-right">
                    <button class="btn btn-primary btn-submit" type="button" id="btn_simpan">Simpan</button>
                    <a href="{{route('pengaturanpengguna.index')}}" class="btn btn-secondary">Kembali</a>
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
		var table = $('#table_akses').DataTable();
        "paging":   false,
        "ordering": false,
        "info":     false,
        "search":   false
	});

	function simpanRead(id){
if ($('#read1-'+id).prop('checked')) {
	 $('#read-'+id).val('Y')
} else {
	 $('#read-'+id).val('N')
}
}

function simpanInsert(id){
if ($('#insert1-'+id).prop('checked')) {
			$('#insert-'+id).val('Y')
} else {
	 $('#insert-'+id).val('N')
}
}


function simpanUpdate(id){

if ($('#update1-'+id).prop('checked')) {
	 $('#update-'+id).val('Y')
} else {
	 $('#update-'+id).val('N')
}
}

function simpanDelete(id){
if ($('#delete1-'+id).prop('checked')) {
 $('#delete-'+id).val('Y')
} else {
	 $('#delete-'+id).val('N')
}
}
</script>
@endsection
