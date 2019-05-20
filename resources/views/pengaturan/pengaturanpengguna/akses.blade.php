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
                                        <td>{{$company->c_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Last Login</strong>
                                        </td>
                                        <td>{{Carbon\Carbon::parse($user->u_lastlogin)->format('d/m/Y G:i:s')}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Last Logout</strong>
                                        </td>
                                        <td>{{Carbon\Carbon::parse($user->u_lastlogout)->format('d/m/Y G:i:s')}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <label for="">Username</label>
                            <h2 class="no margins">{{$user->u_username}}</h2>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                    <h5>Akses Pengguna</h5>
										<form id="dataakses">
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
															@foreach($menu as $data)
																<tr>
																	<td>@if($data->a_parent == $data->a_id) <strong>{{ $data->a_name }}</strong> @else<span
																							style="margin-left: 20px;">{{ $data->a_name }}</span> @endif
																							<input type="hidden" name="idaccess[]" value="{{$data->a_id}}">
																	</td>
																	<td>
																			<label><input @if($data->a_parent == $data->a_id) id="read{{ $data->a_parent }}" class="checkbox checkbox-info rounded headread{{ $data->a_parent }}" onchange="handleChange(this);" @else onchange="checkParent(this, 'read{{$data->a_parent}}', 'read{{ $data->a_id }}');"
																			class="checkbox checkbox-info rounded subread{{ $data->a_parent }}" @endif type="checkbox" @if($data->ua_read == 'Y') checked @endif><span></span></label>
																				@if($data->a_parent == $data->a_id)
																				<input type="hidden" id="hread{{$data->a_id}}" class="hread{{ $data->a_parent }}" name="read[]" value="N">
																				@else
																				<input type="hidden" id="sread{{$data->a_id}}" class="sread{{ $data->a_parent }}" name="read[]" value="N">
																				@endif
																	</td>
																	<td>
																		<label><input @if($data->a_parent == $data->a_id) id="insert{{ $data->a_parent }}" class="checkbox checkbox-success rounded headinsert{{ $data->a_parent }}" onchange="handleChange(this);" @else onchange="checkParent(this, 'insert{{$data->a_parent}}', 'insert{{ $data->a_id }}');"
																		class="checkbox checkbox-success rounded subinsert{{ $data->a_parent }}" @endif type="checkbox" @if($data->ua_create == 'Y') checked @endif><span></span></label>
																			@if($data->a_parent == $data->a_id)
																			<input type="hidden" id="hinsert{{$data->a_id}}" class="hinsert{{ $data->a_parent }}" name="insert[]" value="N">
																			@else
																			<input type="hidden" id="sinsert{{$data->a_id}}" class="sinsert{{ $data->a_parent }}" name="insert[]" value="N">
																			@endif
																	</td>
																	<td>
																		<label><input @if($data->a_parent == $data->a_id) id="update{{ $data->a_parent }}" class="checkbox checkbox-warning rounded headupdate{{ $data->a_parent }}" onchange="handleChange(this);" @else onchange="checkParent(this, 'update{{$data->a_parent}}', 'update{{ $data->a_id }}');"
																		class="checkbox checkbox-warning rounded subupdate{{ $data->a_parent }}" @endif type="checkbox" @if($data->ua_update == 'Y') checked @endif><span></span></label>
																			@if($data->a_parent == $data->a_id)
																			<input type="hidden" id="hupdate{{$data->a_id}}" class="hupdate{{ $data->a_parent }}" name="update[]" value="N">
																			@else
																			<input type="hidden" id="supdate{{$data->a_id}}" class="supdate{{ $data->a_parent }}" name="update[]" value="N">
																			@endif
																	</td>
																	<td>
																		<label><input @if($data->a_parent == $data->a_id) id="delete{{ $data->a_parent }}" class="checkbox checkbox-danger rounded headdelete{{ $data->a_parent }}" onchange="handleChange(this);" @else onchange="checkParent(this, 'delete{{$data->a_parent}}', 'delete{{ $data->a_id }}');"
																		class="checkbox checkbox-danger rounded subdelete{{ $data->a_parent }}" @endif type="checkbox" @if($data->ua_delete == 'Y') checked @endif><span></span></label>
																			@if($data->a_parent == $data->a_id)
																			<input type="hidden" id="hdelete{{$data->a_id}}" class="hdelete{{ $data->a_parent }}" name="delete[]" value="N">
																			@else
																			<input type="hidden" id="sdelete{{$data->a_id}}" class="sdelete{{ $data->a_parent }}" name="delete[]" value="N">
																			@endif
																	</td>
															</tr>
													@endforeach
                            </tbody>
                        </table>
												</form>
                    </div>
                    </div>
                    <div class="card-footer text-right">
                    <button class="btn btn-primary btn-submit" type="button" onclick="simpan({{$id}})" id="btn_simpan">Simpan</button>
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

function handleChange(checkbox) {
		if (checkbox.checked) {
				var klas = $(checkbox).attr('id');
				$('.sub'+klas).prop("checked", true);
				$('.h'+klas).val('Y');
				$('.s'+klas).val('Y');
		} else {
				var klas = $(checkbox).attr('id');
				$('.sub'+klas).prop("checked", false);
				$('.h'+klas).val('N');
				$('.s'+klas).val('N');
		}
}

function checkParent(checkbox, parent, id){
		if (checkbox.checked) {
				$('.head'+parent).prop("checked", true);
				$('.h'+parent).val('Y');
				$('#s'+id).val('Y');
		} else {
			$('#s'+id).val('N');
		}
}

function simpan(id){
	loadingShow();
	$.ajax({
		type: 'get',
		data: $('#dataakses').serialize()+'&id='+id,
		dataType: 'JSON',
		url: baseUrl + '/pengaturan/pengaturanpengguna/simpanakses',
		success : function(response){
			if (response.status == 'berhasil') {
				loadingHide();
				messageSuccess('Berhasil', 'Akses berhasil disimpan!');
				setTimeout(function () {
					window.location.href = "{{url('/pengaturan/pengaturanpengguna/index')}}";
				}, 800);
			} else {
				loadingHide();
				messageFailed('Gagal', 'Akses gagal disimpan!');
			}
		}
	});
}
</script>
@endsection
