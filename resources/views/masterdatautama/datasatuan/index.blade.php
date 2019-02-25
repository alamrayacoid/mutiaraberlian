@extends('main')

@section('content')

<article class="content animated fadeInLeft">
	<div class="title-block text-primary">
	    <h1 class="title"> Data Satuan </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	/ <span>Master Data Utama</span>
	    	/ <span class="text-primary font-weight-bold">Data Satuan</span>
	    </p>
	</div>
	<section class="section">
		<div class="row">
			<div class="col-12">
				<div class="card">
	              	<div class="card-header bordered p-2">
		              	<div class="header-block">
		                    <h3 class="title"> Data Satuan </h3>
		                </div>
		                <div class="header-block pull-right">
							<button type="button" data-toggle="modal" data-target="#addSatuan" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
		                </div>
	              	</div>
	              	<div class="card-block">
	                  	<section>
	                  		<div class="table-responsive">
	                        	<table class="table table-sm table-striped table-hover display nowrap" cellspacing="0" id="table_satuan">
	                            	<thead class="bg-primary">
	                                	<tr>
	                                		<th width="1%">No</th>
											<th>Nama Satuan</th>
											<th width="15%" class="text-center">Aksi</th>
										</tr>
	                            	</thead>
	                            	<tbody>

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
{{-- Modal Add Satuan --}}
<div class="modal fade" id="addSatuan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Tmbah Data Satuan</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label for="inputPassword" class="col-sm-3 col-form-label">Nama Satuan</label>
					<div class="col-sm-9">
						<input type="hidden" name="satuan_id" id="s_id" value="{{$id}}">
						<input type="text" class="form-control form-control-sm" name="s_name" id="s_name">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="tambahSatuan()">
					<span class="glyphicon glyphicon-floppy-disk"></span> Simpan
				</button>
			</div>
		</div>
	</div>
</div>
{{-- Modal Edit Satuan --}}
<div class="modal fade" id="editSatuan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Edit Data Satuan</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label for="inputPassword" class="col-sm-3 col-form-label">Nama Satuan</label>
					<div class="col-sm-9">
						<input type="hidden" name="satuan_id" id="u_id">
						<input type="text" class="form-control form-control-sm" name="s_name" id="u_name">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="updateSatuan()">
					<span class="glyphicon glyphicon-floppy-disk"></span> Update
				</button>
			</div>
		</div>
	</div>
</div>

@endsection
@section('extra_script')
<script type="text/javascript">
	// set header token for ajax request
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	var tb_satuan;
	setTimeout(function() {
		TableSatuan();
	}, 1500);

	// function to retrieve DataTable server side
	function TableSatuan()
	{
		$('#table_satuan').dataTable().fnDestroy();
		tb_satuan = $('#table_satuan').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ url('/masterdatautama/datasatuan/list') }}",
				type: "get",
				data: {
					"_token": "{{ csrf_token() }}"
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'u_name', name: 'u_name'},
				{data: 'action', name: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}

	function tambahSatuan(){
        var s_id = $('#s_id').val();
        var s_name = $('#s_name').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: baseUrl + '/masterdatautama/datasatuan/store',
			type: 'get',
			data: {id: s_id, name: s_name},
			dataType: "JSON",
			success: function(data)
			{
            	if(data.status =='sukses'){
                	$('#addSatuan').modal('hide');
                	messageSuccess('Success', 'Data berhasil disimpan');
                   	$('#table_satuan').DataTable().ajax.reload();
            	} else {
                    $('#addSatuan').modal('hide');
                    $('#table_satuan').DataTable().ajax.reload();
                }
            },
        });
    }

    function editSatuan(id,name)
    {
    	$('#editSatuan').modal('show');
    	$('#u_id').val(id);
    	$('#u_name').val(name);
    }

    function updateSatuan()
    {
        var s_id = $('#u_id').val();
        var s_name = $('#u_name').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: baseUrl + '/masterdatautama/datasatuan/update',
			type: 'get',
			data: {id: s_id, name: s_name},
			dataType: "JSON",
			success: function(data)
			{
            	if(data.status =='sukses'){
                	$('#editSatuan').modal('hide');
                	messageSuccess('Success', 'Data berhasil diupdate');
                   	$('#table_satuan').DataTable().ajax.reload();
            	} else {
                    $('#editSatuan').modal('hide');
                    $('#table_satuan').DataTable().ajax.reload();
                }
            },
        });

    }
	// function to execute delete request
	function deleteSatuan(id)
	{
		var url_hapus = baseUrl + "/masterdatautama/datasatuan/delete/" + id;

		$.confirm({
			animation: 'RotateY',
			closeAnimation: 'scale',
			animationBounce: 1.5,
			icon: 'fa fa-exclamation-triangle',
			title: 'Peringatan!',
			content: 'Apakah anda yakin ingin menghapus data ini ?',
			theme: 'disable',
			buttons: {
				info: {
					btnClass: 'btn-blue',
					text:'Ya',
					action : function(){
						return $.ajax({
							type : "post",
							url : url_hapus,
							success : function (response){
								if(response.status == 'sukses'){
									$.toast({
										heading: 'Success',
										text: 'Data berhasil dihapus !',
										bgColor: '#00b894',
										textColor: 'white',
										loaderBg: '#55efc4',
										icon: 'success',
										stack: false
									});
									tb_satuan.ajax.reload();
								}
							},
							error : function(e){
								$.toast({
									heading: 'Warning',
									text: e.message,
									bgColor: '#00b894',
									textColor: 'white',
									loaderBg: '#55efc4',
									icon: 'warning',
									stack: false
								});
							}
						});

					}
				},
				cancel:{
					text: 'Tidak',
					action: function () {
						// tutup confirm
					}
				}
			}
		});
	}

</script>
@endsection
