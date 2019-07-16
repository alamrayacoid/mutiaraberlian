@extends('main')

@section('content')

    @include('produksi.returnproduksi.detail_return')
    @include('produksi.returnproduksi.edit_return')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Return Produksi </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktifitas Produksi</span>
	    	 / <span class="text-primary font-weight-bold">Return Produksi</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Return Produksi </h3>
                        </div>
                        <div class="header-block pull-right">
                			<a class="btn btn-primary" href="{{route('return.create')}}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                            <div class="row">
                                <div class="col-3"></div>
                                <div class="input-group input-daterange col-5">
                                    <input type="text" name="dateStart" id="dateStart" class="form-control form-control-sm" placeholder="Date Start">
                                    <label class="input-group-addon">-</label>
                                    <input type="text" name="dateEnd" id="dateEnd" class="form-control form-control-sm" placeholder="Date End">
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-md btn-primary" onclick="updateTableReturn()"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover" cellspacing="0" id="table_return">
	                                <thead class="bg-primary">
	                                    <tr>
	                                    	<th>Tgl Return</th>
	                                    	<th>Nota</th>
	                                    	<th>Metode</th>
	                                    	<th>Barang</th>
	                                    	<th>Qty</th>
	                                    	<th>Aksi</th>
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

@endsection

@section('extra_script')
<script type="text/javascript">
    var table;
	$(document).ready(function(){
        table = $('#table_return').DataTable({
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ route('return.list') }}",
                type: "post",
                data: {
                    '_token': '{{ @csrf_token() }}'
                }
            },
            columns: [
                {data: 'tanggal'},
                {data: 'nota'},
                {data: 'metode'},
                {data: 'barang'},
                {data: 'qty'},
                {data: 'action'}
            ],
        });

        $("#formEditReturn").on("submit", function (evt) {
            evt.preventDefault();
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Konfirmasi!',
                content: 'Apakah anda yakin akan memperbarui return produksi untuk barang ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            updateReturn();
                        }
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function () {
                            // tutup confirm
                        }
                    }
                }
            });
        })

	});
	
	function updateTableReturn() {
        if ( $.fn.DataTable.isDataTable('#table_return') ) {
            $('#table_return').DataTable().destroy();
            table = $('#table_return').DataTable({
                responsive: true,
                autoWidth: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('return.list') }}",
                    data: {awal: $("#dateStart").val(), akhir: $("#dateEnd").val()},
                    type: "post",
                    data: {
                        '_token': '{{ @csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'tanggal'},
                    {data: 'nota'},
                    {data: 'metode'},
                    {data: 'barang'},
                    {data: 'qty'},
                    {data: 'action'}
                ],
            });
        }
    }

	function detailReturn(id, detail) {
	    loadingShow();
	    axios.get(baseUrl+'/produksi/returnproduksi/detail-return/'+id+'/'+detail)
            .then(function (resp) {
                loadingHide();
                if (resp.data.status == "Failed") {
                    messageFailed("Gagal", resp.data.message);
                } else if (resp.data.status == "Success") {
                    $('#txt_tanggal').val(resp.data.message.tanggal);
                    $('#txt_nota').val(resp.data.message.nota);
                    $('#txt_barang').val(resp.data.message.barang);
                    $('#txt_qty').val(resp.data.message.qty);
                    $('#txt_metode').val(resp.data.message.metode);
                    $('#txt_ket').text(resp.data.message.keterangan);
                    $("#detailReturn").modal("show");
                }
            })
            .catch(function (error) {
                loadingHide();
                messageWarning("Error", error);
            })
    }

    function editReturn(id, detail, item) {
	    loadingShow();
	    axios.get(baseUrl+'/produksi/returnproduksi/get-editreturn/'+id+'/'+detail)
            .then(function (resp) {
                loadingHide();
                if (resp.data.status == "Success") {
                    $("#satuan_return_edit").find('option').remove();
                    var option = '<option value="">Pilih Satuan</option>';
                    option += '<option value="'+resp.data.satuan.original.id1+'">'+resp.data.satuan.original.unit1+'</option>';
                    if (resp.data.satuan.original.id2 != null && resp.data.satuan.original.id2 != resp.data.satuan.original.id1) {
                        option += '<option value="'+resp.data.satuan.original.id2+'">'+resp.data.satuan.original.unit2+'</option>';
                    }
                    if (resp.data.satuan.original.id3 != null && resp.data.satuan.original.id3 != resp.data.satuan.original.id1) {
                        option += '<option value="'+resp.data.satuan.original.id3+'">'+resp.data.satuan.original.unit3+'</option>';
                    }
                    $("#satuan_return_edit").append(option);

                    $('#idRPO_edit').val(id);
                    $('#idDetail_edit').val(detail);
                    $('#idItem_edit').val(item);
                    $('#txt_tanggal_edit').val(resp.data.message.tanggal);
                    $('#txt_nota_edit').val(resp.data.message.nota);
                    $('#txt_metode_edit').val(resp.data.message.txtmetode);
                    $('#txt_barang_edit').val(resp.data.message.barang);
                    $('#txt_qty_edit').val(resp.data.message.qty_return);
                    $('#qty_current').val(resp.data.message.qty);
                    $('#qty_return_edit').val(resp.data.message.qty);
                    $('#satuan_return_edit').val(resp.data.message.unit);
                    $('#methode_return_edit').val(resp.data.message.metode);
                    $('#note_return_edit').text(resp.data.message.keterangan);

                    $("#editReturn").modal({backdrop: 'static', keyboard: false});
                } else {
                    messageFailed("Gagal", resp.data.message);
                }
            })
            .catch(function (error) {
                loadingHide();
                messageWarning("Error", error);
            })
    }

    function updateReturn() {
        loadingShow();
        axios.post('{{ route('return.edit') }}', $("#formEditReturn").serialize())
            .then(function (resp) {
                loadingHide();
                if (resp.data.status == "Success") {
                    $('#formEditReturn')[0].reset();
                    $("#editReturn").modal("hide");
                    table.ajax.reload();
                    messageSuccess("Berhasil", resp.data.message);
                    window.open(baseUrl+'/produksi/returnproduksi/nota-return/'+resp.data.id+'/'+resp.data.detail);
                } else if (resp.data.status == "Failed") {
                    messageFailed("Gagal", resp.data.message);
                }
            })
            .catch(function (error) {
                loadingHide();
                messageWarning("Error", error);
            })
    }

    function hapusReturn(id, detail, qty) {
        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Konfirmasi!',
            content: 'Apakah anda yakin akan menghapus return produksi untuk barang ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        deleteReturn(id, detail, qty);
                    }
                },
                cancel: {
                    text: 'Tidak',
                    action: function () {
                        // tutup confirm
                    }
                }
            }
        });
    }

    function deleteReturn(id, detail, qty) {
	    loadingShow();
	    axios.get(baseUrl+'/produksi/returnproduksi/hapus-return/'+id+'/'+detail+'/'+qty)
            .then(function (resp) {
                loadingHide();
                if (resp.data.status == "Success") {
                    table.ajax.reload();
                    messageSuccess("Berhasil", resp.data.message);
                } else if (resp.data.status == "Failed") {
                    messageFailed("Gagal", resp.data.message);
                }
            })
            .catch(function (error) {
                loadingHide();
                messageWarning("Error", error);
            })
    }
</script>
@endsection
