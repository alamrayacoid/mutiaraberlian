@extends('main')
@section('extra_style')
    <style>
        #table_golonganharga_filter{
            margin-left: -100px;
            padding-left: -20px;
        }
        .toolbar {
            float:left;
        }
    </style>
@stop
@section('content')

@include('masterdatautama.harga.default.modal-info')

@include('masterdatautama.harga.default.modal-edit')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Master Harga </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
	    	/ <span>Master Data Utama</span> 
	    	/ <span class="text-primary" style="font-weight: bold;">Master Harga</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				<ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a href="" class="nav-link active" data-target="#golongan" aria-controls="golongan" data-toggle="tab" role="tab">Data Golongan</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-target="#default" aria-controls="default" data-toggle="tab" role="tab">Harga Default</a>
                    </li>
                </ul>				
		
				<div class="tab-content">		

					@include('masterdatautama.harga.golongan.index')
					@include('masterdatautama.harga.default.index')
					@include('masterdatautama.harga.golongan.addGolongan')
					@include('masterdatautama.harga.golongan.editGolongan')

		        </div>
			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
    var tbl_gln, tbl_item;
	$(document).ready(function(){
        tbl_gln = $('#table_golongan').DataTable({
			"paging":   false,
			"ordering": false,
			"info":     false,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('dataharga.getgolongan') }}",
                type: "get"
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'pc_name'},
                {data: 'action'}
            ],
            dom: 'l<"toolbar">frtip',
            initComplete: function(){
                $("div.toolbar")
                    .html('<button type="button" id="btngolongan" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Tambah</button>');
            }
        });

        tbl_item = $('#table_golonganharga').DataTable({
			"paging":   false,
			"ordering": false,
			"info":     false
    	});

		$(document).on('click','#btngolongan', function (evt) {
            evt.preventDefault();
            $('#addgolongan').modal('show');
        })

		$(document).on('click','.btn-edit-golonganharga',function(){
			window.location.href='{{route('golonganharga.edit')}}'
		});

		$(document).on('click', '.btn-disable-golonganharga', function(){
			var ini = $(this);
			$.confirm({
				animation: 'RotateY',
				closeAnimation: 'scale',
				animationBounce: 1.5,
				icon: 'fa fa-exclamation-triangle',
				title: 'Peringatan!',
				content: 'Apa anda yakin mau menonaktifkan data ini?',
				theme: 'disable',
			    buttons: {
			        info: {
						btnClass: 'btn-blue',
			        	text:'Ya',
			        	action : function(){
							$.toast({
								heading: 'Information',
								text: 'Data Berhasil di Nonaktifkan.',
								bgColor: '#0984e3',
								textColor: 'white',
								loaderBg: '#fdcb6e',
								icon: 'info'
							})
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-golonganharga" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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
		});

		$(document).on('click', '.btn-enable-golonganharga', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Enable.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-golonganharga" title="Edit" type="button"><i class="fa fa-pencil"></i></button>'+
											'<button class="btn btn-danger btn-disable-golonganharga" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
		})


		$(document).on('click','.btn-preview-pelamar',function(){
			window.location.href='{{route('rekruitmen.preview')}}'
		});

		$(document).on('click', '.btn-disable-pelamar', function(){
			var ini = $(this);
			$.confirm({
				animation: 'RotateY',
				closeAnimation: 'scale',
				animationBounce: 1.5,
				icon: 'fa fa-exclamation-triangle',
				title: 'Peringatan!',
				content: 'Apa anda yakin mau menonaktifkan data ini?',
				theme: 'disable',
			    buttons: {
			        info: {
						btnClass: 'btn-blue',
			        	text:'Ya',
			        	action : function(){
							$.toast({
								heading: 'Information',
								text: 'Data Berhasil di Nonaktifkan.',
								bgColor: '#0984e3',
								textColor: 'white',
								loaderBg: '#fdcb6e',
								icon: 'info'
							})
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-pelamar" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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
		});

		$(document).on('click', '.btn-enable-pelamar', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Enable.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-primary" data-toggle="modal" data-target="#list_barang_dibawa" type="button" title="Preview"><i class="fa fa-search"></i></button>'+
											'<button class="btn btn-warning btn-edit-pelamar" type="button" title="Process"><i class="fa fa-file-powerpoint-o"></i></button>'+
	                                		'<button class="btn btn-danger btn-disable-pelamar" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')
		})

		$(document).on('click', '.btn-simpan-modal', function(){
			$.toast({
				heading: 'Success',
				text: 'Data Berhasil di Simpan',
				bgColor: '#00b894',
				textColor: 'white',
				loaderBg: '#55efc4',
				icon: 'success'
			})
		})

        $(document).on('submit', '#formgln', function (evt) {
            evt.preventDefault();
            var data = $('#formgln').serialize();
            axios.post('{{route("dataharga.addgolongan")}}', data).then(function (response) {
                if (response.data.status == "Success") {
                    messageSuccess("Berhasil", "Data berhasil disimpan!");
                    $('#formgln').trigger("reset");
                    reloadTable();
                } else {
                    messageWarning("Gagal", "Data gagal disimpan");
                }
            })
        });

        $(document).on('submit', '#formedtgln', function (evt) {
            evt.preventDefault();
            var data = $('#formedtgln').serialize();
            axios.post('{{route("dataharga.editgolongan")}}', data).then(function (response) {
                if (response.data.status == "Success") {
                    messageSuccess("Berhasil", "Data berhasil perbarui!");
                    reloadTable();
                } else {
                    messageWarning("Gagal", "Data gagal diperbarui!");
                }
            })
        })

        $(".barang").autocomplete({
            source: baseUrl + '/masterdatautama/harga/cari-barang',
            minLength: 1,
            select: function (event, data) {
                setItem(data.item);
            }
        });

        $(document).on('submit', '#formsetharga', function (evt) {
            evt.preventDefault();
            var data = $('#formsetharga').serialize();
            axios.post('{{route("dataharga.addgolonganharga")}}', data).then(function (response) {
                console.log(response);
                if (response.data.status == "Success") {
                    messageSuccess("Berhasil", "Data berhasil disimpan!");
                    $("#formsetharga").trigger('reset');
                    $("#txtGol").text('~');
                    reloadTable();
                    $("#satuan").addClass('d-none');
                    $("#range").addClass('d-none');
                } else {
                    messageWarning("Gagal", "Data gagal disimpan!");
                }
            });
        });
	});

    function setItem(info) {
        // idItem = info.data.i_id;
        $("#idBarang").val(info.data.i_id)
        $.ajax({
            url: '{{ url('/masterdatautama/harga/get-satuan/') }}'+'/'+info.data.i_id,
            type: 'GET',
            success: function( resp ) {
                var option = '';
                option += '<option value="'+resp.id1+'">'+resp.unit1+'</option>';
                if (resp.id2 != null && resp.id2 != resp.id1) {
                    option += '<option value="'+resp.id2+'">'+resp.unit2+'</option>';
                }
                if (resp.id3 != null && resp.id3 != resp.id1) {
                    option += '<option value="'+resp.id3+'">'+resp.unit3+'</option>';
                }
                $("#satuanBarang").append(option);
                $("#satuanrange").append(option);
            }
        });
    }

	function reloadTable() {
        tbl_gln.ajax.reload();
        tbl_item.ajax.reload();
    }

    function editGolongan(id, name) {
	    $('#idGolongan').val(id);
	    $('#namaGolongan').val(name);
        $('#editgolongan').modal('show');
    }

	function hapusGolongan(id) {
        deleteConfirm(baseUrl+"/masterdatautama/harga/delete-golongan/"+id);
    }

    function addGolonganHarga(id, name) {
        $('#idGol').val(id);
        $('#txtGol').text(name);
        if ($.fn.DataTable.isDataTable("#table_golonganharga")) {
            $('#table_golonganharga').DataTable().clear().destroy();
        }
        tbl_item = $('#table_golonganharga').DataTable({
            "paging":   false,
            "ordering": false,
            "info":     false,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/masterdatautama/harga/get-golongan-harga/') }}"+"/"+$("#idGol").val(),
                type: "get"
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'item'},
                {data: 'jenis'},
                {data: 'range'},
                {data: 'satuan'},
                {data: 'harga'},
                {data: 'jenis_pembayaran'},
                {data: 'action'}
            ]
        });
    }
</script>

<script type="text/javascript">

$(document).ready(function(){
	$('#jenisharga').change(function(){
		var ini, satuan, range;
		ini             = $(this).val();
		satuan     		= $('#satuan');
		range     		= $('#range');

		if (ini === 'U') {
		    $("#qty").val(1);
		    $("#qty").attr('readonly', true);
			satuan.removeClass('d-none');
			range.addClass('d-none');
		} else if(ini === 'R'){
			satuan.addClass('d-none');
			range.removeClass('d-none');
		} else {
			satuan.addClass('d-none');
			range.addClass('d-none');
		}
	});
});
</script>
@endsection
