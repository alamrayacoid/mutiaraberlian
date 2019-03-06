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
                                        <h2 class="mb-4">Bambang</h2>
                                        <h4>Admin</h4>
                                        <p>Rungkut</p>
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
                                        <td>20/02/2019 01:31</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Last Logout</strong>
                                        </td>
                                        <td>29/01/2019 11:32 </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <label for="">Username</label>
                            <h2 class="no margins">BradPit</h2>
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
                                <tr>
                                    <td><strong>Pembelian</strong></td>
                                    <td><label><input class="checkbox rounded" checked="checked" type="checkbox"><span></span></label></td>
                                    <td><label><input class="checkbox rounded" checked="checked" type="checkbox"><span></span></label></td>
                                    <td><label><input class="checkbox rounded" type="checkbox"><span></span></label></td>
                                    <td><label><input class="checkbox rounded" type="checkbox"><span></span></label></td>
                                </tr>
                                <tr>
                                    <td class="pl-4">Rencana Pembelian</td>
                                    <td><label><input class="checkbox rounded" checked="checked" type="checkbox"><span></span></label></td>
                                    <td><label><input class="checkbox rounded" checked="checked" type="checkbox"><span></span></label></td>
                                    <td><label><input class="checkbox rounded" type="checkbox"><span></span></label></td>
                                    <td><label><input class="checkbox rounded" type="checkbox"><span></span></label></td>
                                </tr>
                                <tr>
                                    <td class="pl-4">Konfirmasi Order</td>
                                    <td><label><input class="checkbox rounded" checked="checked" type="checkbox"><span></span></label></td>
                                    <td><label><input class="checkbox rounded" checked="checked" type="checkbox"><span></span></label></td>
                                    <td><label><input class="checkbox rounded" type="checkbox"><span></span></label></td>
                                    <td><label><input class="checkbox rounded" type="checkbox"><span></span></label></td>
                                </tr>
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
</script>
@endsection
