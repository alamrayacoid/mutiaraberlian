@extends('main')
@section('extra_style')
    <style>
        #table_golonganharga_filter{
            margin-left: -100px;
            padding-left: -20px;
        }
        table {
            width: 100% !important;
        }
        .toolbar {
            float:left;
        }
        .toolbarhpa {
            float:left;
        }
        .toolbarap {
            float:left;
        }
        .btn-khusus {
            padding: 3px !important;
            border-radius: 5px !important;
            color: white !important;
            cursor: text !important;
            font-size: 9pt !important;
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
				<ul class="nav nav-pills mb-3" id="Tabzs">
                    <li class="nav-item">
                        <a href="#golongan" class="nav-link active" data-target="#golongan" aria-controls="golongan" data-toggle="tab" role="tab">Harga Pembelian Agen</a>
                    </li>
                    {{--<li class="nav-item">--}}
                        {{--<a href="" class="nav-link" data-target="#default" aria-controls="default" data-toggle="tab" role="tab">Harga Default</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                        {{--<a href="#needapprove" class="nav-link" data-target="#needapprove" aria-controls="needapprove" data-toggle="tab" role="tab">Belum Disetujui</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                        {{--<a href="#haa" class="nav-link" data-target="#haa" aria-controls="haa" data-toggle="tab" role="tab">Harga Agen ke Agen</a>--}}
                    {{--</li>--}}
                    <li class="nav-item">
                        <a href="#hpa" class="nav-link" data-target="#hpa" aria-controls="hpa" data-toggle="tab" role="tab">Harga Penjualan ke Customer</a>
                    </li>
                </ul>

				<div class="tab-content">

					@include('masterdatautama.harga.golongan.index')
					@include('masterdatautama.harga.default.index')
					@include('masterdatautama.harga.golongan.addGolongan')
					@include('masterdatautama.harga.golongan.addGolonganHPA')
					@include('masterdatautama.harga.golongan.editGolongan')
					@include('masterdatautama.harga.golongan.editGolonganHPA')
					@include('masterdatautama.harga.golongan.hargaAgen')
					@include('masterdatautama.harga.golongan.editGolHrgUnit')
					@include('masterdatautama.harga.golongan.editGolHrgUnitHPA')
					@include('masterdatautama.harga.golongan.editGolHrgRange')
					@include('masterdatautama.harga.golongan.editGolHrgRangeHPA')
                    @include('masterdatautama.harga.pending.index')

                    @include('masterdatautama.harga.golongan.agenPriceTab')
                    @include('masterdatautama.harga.golongan.addAgenPrice')

		        </div>
			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
    var tbl_gln, tbl_item, tbl_itemHPA, tbl_napp, tbl_glnHPA, tbl_agenprice;
	$(document).ready(function(){
	    if ($("#idGol").val() == "") {
	        $(".barang").attr('disabled', true);
	        $("#jenisharga").attr('disabled', true);
        } else {
            $(".barang").attr('disabled', false);
            $("#jenisharga").attr('disabled', false);
        }

	    //
        if ($("#idGolHPA").val() == "") {
            $(".barangHPA").attr('disabled', true);
            $("#jenishargaHPA").attr('disabled', true);
        } else {
            $(".barangHPA").attr('disabled', false);
            $("#jenishargaHPA").attr('disabled', false);
        }

        tbl_napp = $('#table-needappr').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('dataharga.getdataneedapprove') }}",
                type: "get"
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'pc_name'},
                {data: 'item'},
                {data: 'jenis'},
                {data: 'range'},
                {data: 'satuan'},
                {data: 'harga'},
                {data: 'jenis_pembayaran'}
            ]
        });

        tbl_gln = $('#table_golongan').DataTable({
			paging:   false,
			info:     false,
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
            aoColumnDefs: [
                {'bSortable': false, 'aTargets': [0]},
                {'bSearchable': false, 'aTargets': [0]},
                {'bOrderable': true, 'aTargets': [0]}
            ],
            dom: 'l<"toolbar">frtip',
            initComplete: function(){
                $("div.toolbar").html('<button type="button" id="btngolongan" class="btn btn-primary" title="Tambah Golongan"><i class="fa fa-plus"></i></button>');
            }
        });

        tbl_glnHPA = $('#table_golonganHPA').DataTable({
            "paging":   false,
            "info":     false,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('dataharga.getgolonganhpa') }}",
                type: "get"
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'sp_name'},
                {data: 'action'}
            ],
            aoColumnDefs: [
                {'bSortable': false, 'aTargets': [0]},
                {'bSearchable': false, 'aTargets': [0]},
                {'bOrderable': true, 'aTargets': [0]}
            ],
            dom: 'l<"toolbarhpa">frtip',
            initComplete: function(){
                $("div.toolbarhpa").html('<button type="button" id="btngolonganHPA" class="btn btn-primary" title="Tambah Golongan"><i class="fa fa-plus"></i></button>');
            }
        });

        tbl_agenprice = $('#table_agenpricename').DataTable({
            "paging":   false,
            "ordering": false,
            "info":     false,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('dataharga.getgolonganap') }}",
                type: "get"
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'ap_name'},
                {data: 'action'}
            ],
            dom: 'l<"toolbarap">frtip',
            initComplete: function(){
                $("div.toolbarap").html('<button type="button" id="btnagenprice" class="btn btn-primary" title="Tambah Golongan"><i class="fa fa-plus"></i></button>');
            }
        });

        tbl_item = $('#table_golonganharga').DataTable({
			"paging":   true,
			"info":     false,
            "searching": true,
    	});

        tbl_itemHPA = $('#table_golonganhargaHPA').DataTable({
            "paging":   true,
            "ordering": true,
            "info":     false,
            "searching": true,
        });

		$(document).on('click','#btngolongan', function (evt) {
            evt.preventDefault();
            $('#addgolongan').modal('show');
        })

        $(document).on('click','#btngolonganHPA', function (evt) {
            evt.preventDefault();
            $('#addgolonganHPA').modal('show');
        })

        $(document).on('click','#btnagenprice', function (evt) {
            evt.preventDefault();
            $('#addagenprice').modal('show');
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
            if ($("#nama").val() == "") {
                messageWarning("Peringatan", "Kolom nama golongan tidak boleh kosong");
            } else {
                var data = $('#formgln').serialize();
                axios.post('{{route("dataharga.addgolongan")}}', data).then(function (response) {
                    if (response.data.status == "Success") {
                        messageSuccess("Berhasil", "Data berhasil disimpan!");
                        $('#formgln').trigger("reset");
                        $('#addgolongan').modal('hide');
                        reloadTable();
                    } else if (response.data.status == 'unauth'){
                        messageWarning("Perhatian", "Anda tidak memiliki akses");
                        $('#addgolongan').modal('hide');
                    } else {
                        messageWarning("Gagal", "Data gagal disimpan");
                    }
                })
            }
        });

        $(document).on('submit', '#formglnHPA', function (evt) {
            evt.preventDefault();
            if ($("#namaHPA").val() == "") {
                messageWarning("Peringatan", "Kolom nama golongan tidak boleh kosong");
            } else {
                var data = $('#formglnHPA').serialize();
                axios.post('{{route("dataharga.addgolonganhpa")}}', data).then(function (response) {
                    if (response.data.status == "Success") {
                        messageSuccess("Berhasil", "Data berhasil disimpan!");
                        $('#formglnHPA').trigger("reset");
                        $('#addgolonganHPA').modal('hide');
                        tbl_glnHPA.ajax.reload();
                    } else if (response.data.status == 'unauth'){
                        messageWarning("Perhatian", "Anda tidak memiliki akses");
                        $('#addgolongan').modal('hide');
                    } else {
                        messageWarning("Gagal", "Data gagal disimpan");
                    }
                })
            }
        });

        $(document).on('submit', '#formap', function (evt) {
            evt.preventDefault();
            if ($("#namaap").val() == "") {
                messageWarning("Peringatan", "Kolom nama golongan tidak boleh kosong");
            } else {
                var data = $('#formap').serialize();
                axios.post('{{route("dataharga.addgolonganpa")}}', data).then(function (response) {
                    if (response.data.status == "Success") {
                        messageSuccess("Berhasil", "Data berhasil disimpan!");
                        $('#formap').trigger("reset");
                        $('#addagenprice').modal('hide');
                        tbl_agenprice.ajax.reload();
                    } else if (response.data.status == 'unauth'){
                        messageWarning("Perhatian", "Anda tidak memiliki akses");
                        $('#addgolongan').modal('hide');
                    } else {
                        messageWarning("Gagal", "Data gagal disimpan");
                    }
                })
            }
        });

        $(document).on('submit', '#formedtgln', function (evt) {
            evt.preventDefault();
            loadingShow();
            var data = $('#formedtgln').serialize();
            axios.post('{{route("dataharga.editgolongan")}}', data).then(function (response) {
                loadingHide();
                if (response.data.status == "Success") {
                    messageSuccess("Berhasil", "Data berhasil perbarui!");
                    reloadTable();
                } else {
                    loadingHide();
                    messageWarning("Gagal", "Data gagal diperbarui!");
                }
            })
        })

        $(document).on('submit', '#formedtglnHPA', function (evt) {
            evt.preventDefault();
            loadingShow();
            var data = $('#formedtglnHPA').serialize();
            axios.post('{{route("dataharga.editgolonganhpa")}}', data).then(function (response) {
                loadingHide();
                if (response.data.status == "Success") {
                    messageSuccess("Berhasil", "Data berhasil perbarui!");
                    tbl_glnHPA.ajax.reload();
                } else {
                    messageWarning("Gagal", "Data gagal diperbarui!");
                }
            })
        })

        $(document).on('submit', '#formedtglnPA', function (evt) {
            evt.preventDefault();
            loadingShow();
            var data = $('#formedtglnPA').serialize();
            axios.post('{{route("dataharga.editgolonganpa")}}', data).then(function (response) {
                loadingHide();
                if (response.data.status == "Success") {
                    messageSuccess("Berhasil", "Data berhasil perbarui!");
                    tbl_agenprice.ajax.reload();
                    $('#editgolonganPA').modal('hide');
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

        $(".barangHPA").autocomplete({
            source: baseUrl + '/masterdatautama/harga/cari-barang',
            minLength: 1,
            select: function (event, data) {
                setItemHPA(data.item);
            }
        });

        $(document).on('submit', '#formsetharga', function (evt) {
            evt.preventDefault();
            if ($("#jenisharga").val() == "U") {
                if ($("#idBarang").val() == "") {
                    messageWarning("Peringatan", "Masukkan nama barang dengan benar!");
                } else if ($("#satuanBarang").val() == "") {
                    messageWarning("Peringatan", "Pilih satuan barang!");
                } else if ($("#jenis_pembayaran").val() == "") {
                    messageWarning("Peringatan", "Pilih jenis pembayaran!");
                } else if ($("#harga").val() == "" || $("#harga").val() == "Rp. 0") {
                    messageWarning("Peringatan", "Masukkan harga barang dengan benar!");
                } else {
                    loadingShow();
                    var data = $('#formsetharga').serialize();
                    axios.post('{{route("dataharga.addgolonganharga")}}', data).then(function (response) {
                        loadingHide();
                        if (response.data.status == "Success") {
                            messageSuccess("Berhasil", "Data berhasil disimpan!");
                            $("#idBarang").val("");
                            $(".barang").val("");
                            $("#jenisharga").val("");
                            $("#select2-jenisharga-container").text('Pilih Jenis Harga');
                            tbl_item.ajax.reload();
                            $("#rangestart").val("");
                            $("#rangeend").val("");
                            $("#hargarange").val("");
                            $("#harga").val("");
                            $("#satuanrange option").remove();
                            $("#satuanrange").prepend('<option value="">Pilih Satuan</option>');
                            $("#satuanrange").val(null);
                            $("#select2-satuanrange-container").text('Pilih Satuan');
                            $("#rangeend").attr("readonly", true);
                            $("#satuan").addClass('d-none');
                            $("#range").addClass('d-none');
                        } else if (response.data.status == "Failed") {
                            messageWarning("Gagal", "Data gagal disimpan!");
                        } else if (response.data.status == "Range Ada") {
                            messageWarning("Peringatan", "Barang ini sudah dibuatkan harga untuk jenis harga, range dan satuan tersebut!");
                        } else if (response.data.status == "Unit Ada") {
                            messageWarning("Peringatan", "Barang ini sudah dibuatkan harga untuk jenis harga dan satuan tersebut!");
                        } else if (response.data.status == 'unauth'){
                            messageWarning("Peringatan", "Anda tidak memiliki akses");
                        } else {
                            messageWarning("Gagal", "Data gagal disimpan!");
                        }
                    });
                }
            } else if ($("#jenisharga").val() == "R") {
                if ($("#idBarang").val() == "") {
                    messageWarning("Peringatan", "Masukkan nama barang dengan benar!");
                } else if ($("#rangestart").val() == "") {
                    messageWarning("Peringatan", "Masukkan range awal dengan benar!");
                } else if ($("#rangeend").val() == "") {
                    messageWarning("Peringatan", "Masukkan range akhir dengan benar!");
                } else if ($("#satuanrange").val() == "") {
                    messageWarning("Peringatan", "Pilih satuan barang!");
                } else if ($("#hargarange").val() == "" || $("#hargarange").val() == "Rp. 0") {
                    messageWarning("Peringatan", "Masukkan harga barang dengan benar!");
                } else {
                    // loadingShow();
                    var data = $('#formsetharga').serialize();
                    axios.post('{{route("dataharga.addgolonganharga")}}', data).then(function (response) {
                        if (response.data.status == "Success") {
                            loadingHide();
                            messageSuccess("Berhasil", "Data berhasil disimpan!");
                            $("#idBarang").val("");
                            $(".barang").val("");
                            $("#jenisharga").val("");
                            $("#select2-jenisharga-container").text('Pilih Jenis Harga');
                            tbl_item.ajax.reload();
                            $("#rangestart").val("");
                            $("#rangeend").val("");
                            $("#hargarange").val("");
                            $("#satuanrange option").remove();
                            $("#satuanrange").prepend('<option value="">Pilih Satuan</option>');
                            $("#satuanrange").val(null);
                            $("#select2-satuanrange-container").text('Pilih Satuan');
                            $("#rangeend").attr("readonly", true);
                            $("#satuan").addClass('d-none');
                            $("#range").addClass('d-none');
                        } else if (response.data.status == "Failed") {
                            loadingHide();
                            messageWarning("Gagal", "Data gagal disimpan!");
                        } else if (response.data.status == "Range Ada") {
                            loadingHide();
                            messageWarning("Peringatan", "Barang ini sudah dibuatkan harga untuk jenis harga, range, satuan dan jenis pembayaran tersebut!");
                        } else if (response.data.status == "Unit Ada") {
                            loadingHide();
                            messageWarning("Peringatan", "Barang ini sudah dibuatkan harga untuk jenis harga dan satuan tersebut!");
                        }
                    });
                }
            }
        });

        $(document).on('submit', '#formsethargaHPA', function (evt) {
            evt.preventDefault();
            if ($("#jenishargaHPA").val() == "U") {
                if ($("#idBarangHPA").val() == "") {
                    messageWarning("Peringatan", "Masukkan nama barang dengan benar!");
                } else if ($("#satuanBarangHPA").val() == "") {
                    messageWarning("Peringatan", "Pilih satuan barang!");
                } else if ($("#jenis_pembayaranHPA").val() == "") {
                    messageWarning("Peringatan", "Pilih jenis pembayaran!");
                } else if ($("#hargaHPA").val() == "" || $("#hargaHPA").val() == "Rp. 0") {
                    messageWarning("Peringatan", "Masukkan harga barang dengan benar!");
                } else {
                    loadingShow();
                    var data = $('#formsethargaHPA').serialize();
                    axios.post('{{route("dataharga.addgolonganhargahpa")}}', data).then(function (response) {
                        loadingHide();
                        if (response.data.status == "Success") {
                            messageSuccess("Berhasil", "Data berhasil disimpan!");
                            $("#idBarangHPA").val("");
                            $(".barangHPA").val("");
                            $("#jenishargaHPA").val("");
                            $("#select2-jenishargaHPA-container").text('Pilih Jenis Harga');
                            tbl_itemHPA.ajax.reload();
                            $("#rangestartHPA").val("");
                            $("#rangeendHPA").val("");
                            $("#hargarangeHPA").val("");
                            $("#satuanrangeHPA option").remove();
                            $("#satuanrangeHPA").prepend('<option value="">Pilih Satuan</option>');
                            $("#satuanrangeHPA").val(null);
                            $("#select2-satuanrangeHPA-container").text('Pilih Satuan');
                            $("#rangeendHPA").attr("readonly", true);
                            $("#satuanHPA").addClass('d-none');
                            $("#rangeHPA").addClass('d-none');
                        } else if (response.data.status == "Failed") {
                            messageWarning("Gagal", "Data gagal disimpan!");
                        } else if (response.data.status == "Range Ada") {
                            messageWarning("Peringatan", "Barang ini sudah dibuatkan harga untuk jenis harga, range dan satuan tersebut!");
                        } else if (response.data.status == "Unit Ada") {
                            messageWarning("Peringatan", "Barang ini sudah dibuatkan harga untuk jenis harga dan satuan tersebut!");
                        } else if (response.data.status == 'unauth'){
                            messageWarning("Peringatan", "Anda tidak memiliki akses");
                        } else {
                            messageWarning("Gagal", "Data gagal disimpan!");
                        }
                    });
                }
            } else if ($("#jenishargaHPA").val() == "R") {
                if ($("#idBarangHPA").val() == "") {
                    messageWarning("Peringatan", "Masukkan nama barang dengan benar!");
                } else if ($("#rangestartHPA").val() == "") {
                    messageWarning("Peringatan", "Masukkan range awal dengan benar!");
                } else if ($("#rangeendHPA").val() == "") {
                    messageWarning("Peringatan", "Masukkan range akhir dengan benar!");
                } else if ($("#satuanrangeHPA").val() == "") {
                    messageWarning("Peringatan", "Pilih satuan barang!");
                } else if ($("#hargarangeHPA").val() == "" || $("#hargarangeHPA").val() == "Rp. 0") {
                    messageWarning("Peringatan", "Masukkan harga barang dengan benar!");
                } else {
                    loadingShow();
                    var data = $('#formsethargaHPA').serialize();
                    axios.post('{{route("dataharga.addgolonganhargahpa")}}', data).then(function (response) {
                        loadingHide();
                        if (response.data.status == "Success") {
                            messageSuccess("Berhasil", "Data berhasil disimpan!");
                            $("#idBarangHPA").val("");
                            $(".barangHPA").val("");
                            $("#jenishargaHPA").val("");
                            $("#select2-jenishargaHPA-container").text('Pilih Jenis Harga');
                            tbl_itemHPA.ajax.reload();
                            $("#rangestartHPA").val("");
                            $("#rangeendHPA").val("");
                            $("#hargarangeHPA").val("");
                            $("#satuanrangeHPA option").remove();
                            $("#satuanrangeHPA").prepend('<option value="">Pilih Satuan</option>');
                            $("#satuanrangeHPA").val(null);
                            $("#select2-satuanrangeHPA-container").text('Pilih Satuan');
                            $("#rangeendHPA").attr("readonly", true);
                            $("#satuanHPA").addClass('d-none');
                            $("#rangeHPA").addClass('d-none');
                        } else if (response.data.status == "Failed") {
                            messageWarning("Gagal", "Data gagal disimpan!");
                        } else if (response.data.status == "Range Ada") {
                            messageWarning("Peringatan", "Barang ini sudah dibuatkan harga untuk jenis harga, range, satuan dan jenis pembayaran tersebut!");
                        } else if (response.data.status == "Unit Ada") {
                            messageWarning("Peringatan", "Barang ini sudah dibuatkan harga untuk jenis harga dan satuan tersebut!");
                        }
                    });
                }
            }
        });

        $(document).on('keyup', '#rangestart', function (evt) {
            evt.preventDefault();
            if ($(this).val() != "") {
                $("#rangeend").removeAttr('readonly');
            } else {
                $("#rangeend").attr('readonly', true);
            }
        });

        $(document).on('keyup', '#rangestartHPA', function (evt) {
            evt.preventDefault();
            if ($(this).val() != "") {
                $("#rangeendHPA").removeAttr('readonly');
            } else {
                $("#rangeendHPA").attr('readonly', true);
            }
        });

        $(document).on('submit', '#formEditGolHrgUnit', function (evt) {
            evt.preventDefault();
            var data = $('#formEditGolHrgUnit').serialize();
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 2.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apakah anda yakin ingin memperbarui data ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            loadingShow();
                            return axios.post('{{route("dataharga.editgolonganhargaunit")}}', data).then(function (response) {
                                loadingHide();
                                if (response.data.status == "Success") {
                                    messageSuccess("Berhasil", "Data berhasil perbarui!");
                                    tbl_item.ajax.reload();
                                } else {
                                    messageWarning("Gagal", "Data gagal diperbarui!");
                                }
                            });
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

        $(document).on('submit', '#formEditGolHrgUnitHPA', function (evt) {
            evt.preventDefault();
            var data = $('#formEditGolHrgUnitHPA').serialize();
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 2.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apakah anda yakin ingin memperbarui data ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            loadingShow();
                            return axios.post('{{route("dataharga.editgolonganhargaunithpa")}}', data).then(function (response) {
                                loadingHide();
                                if (response.data.status == "Success") {
                                    messageSuccess("Berhasil", "Data berhasil perbarui!");
                                    tbl_itemHPA.ajax.reload();
                                } else {
                                    messageWarning("Gagal", "Data gagal diperbarui!");
                                }
                            });
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

        $(document).on('submit', '#formEditGolHrgRange', function (evt) {
            evt.preventDefault();
            var data = $('#formEditGolHrgRange').serialize();
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 2.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apakah anda yakin ingin memperbarui data ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            loadingShow();
                            return axios.post('{{route("dataharga.editgolonganhargarange")}}', data).then(function (response) {
                                loadingHide();
                                if (response.data.status == "Success") {
                                    messageSuccess("Berhasil", "Data berhasil perbarui!");
                                    tbl_item.ajax.reload();
                                } else if (response.data.status == "Range Ada") {
                                    messageWarning("Peringatan", "Barang ini sudah dibuatkan harga untuk jenis harga, range dan satuan tersebut!");
                                } else {
                                    messageWarning("Gagal", "Data gagal diperbarui!");
                                }
                            });
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

        $(document).on('submit', '#formEditGolHrgRangeHPA', function (evt) {
            evt.preventDefault();
            var data = $('#formEditGolHrgRangeHPA').serialize();
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 2.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apakah anda yakin ingin memperbarui data ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            loadingShow();
                            return axios.post('{{route("dataharga.editgolonganhargarangehpa")}}', data).then(function (response) {
                                loadingHide();
                                if (response.data.status == "Success") {
                                    messageSuccess("Berhasil", "Data berhasil perbarui!");
                                    tbl_itemHPA.ajax.reload();
                                } else if (response.data.status == "Range Ada") {
                                    messageWarning("Peringatan", "Barang ini sudah dibuatkan harga untuk jenis harga, range dan satuan tersebut!");
                                } else {
                                    messageWarning("Gagal", "Data gagal diperbarui!");
                                }
                            });
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

    function setItem(info) {
        // idItem = info.data.i_id;
        $("#idBarang").val(info.data.i_id)
        $("#satuanBarang").find('option').remove();
        $("#satuanrange").find('option').remove();
        console.log(info);
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

    function setItemHPA(info) {
        $("#idBarangHPA").val(info.data.i_id)
        $("#satuanBarangHPA").find('option').remove();
        $("#satuanrangeHPA").find('option').remove();
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
                $("#satuanBarangHPA").append(option);
                $("#satuanrangeHPA").append(option);
            }
        });
    }

	function reloadTable() {
        tbl_gln.ajax.reload();
        tbl_glnHPA.ajax.reload();
    }

    function editGolongan(id, name) {
	    $('#idGolongan').val(id);
	    $('#namaGolongan').val(name);
        $('#editgolongan').modal('show');
    }

    function editGolonganHPA(id, name) {
        $('#idGolonganHPA').val(id);
        $('#namaGolonganHPA').val(name);
        $('#editgolonganHPA').modal('show');
    }

    function editGolonganPA(id, name) {
        $('#idGolonganPA').val(id);
        $('#namaGolonganPA').val(name);
        $('#editgolonganPA').modal('show');
    }

	function hapusGolongan(id) {
        deleteConfirm(baseUrl+"/masterdatautama/harga/delete-golongan/"+id);
    }

    function hapusGolonganHPA(id) {
        deleteConfirm(baseUrl+"/masterdatautama/harga/delete-golongan-hpa/"+id);
    }

    function hapusGolonganPA(id) {
        deleteConfirm(baseUrl+"/masterdatautama/harga/delete-golongan-pa/"+id);
    }

    function editGolonganHarga(id, detail, item, harga, satuan, tipe, rangestart, rangeEnd, status) {
        if (tipe == "U") {
            $("#satuanBarangUnitEdit").find('option').remove();
            $.ajax({
                url: '{{ url('/masterdatautama/harga/get-satuan/') }}'+'/'+item,
                type: 'GET',
                data: {
                    "id": id,
                    "detail": detail
                },
                success: function( resp ) {
                    var option = '';
                    if (resp.id1 == satuan) {
                        option += '<option value="'+resp.id1+'" selected>'+resp.unit1+'</option>';
                    } else {
                        option += '<option value="'+resp.id1+'" >'+resp.unit1+'</option>';
                    }

                    if (resp.id2 != null && resp.id2 != resp.id1) {
                        if (resp.id2 == satuan) {
                            option += '<option value="'+resp.id2+'" selected>'+resp.unit2+'</option>';
                        } else {
                            option += '<option value="'+resp.id2+'">'+resp.unit2+'</option>';
                        }

                    }
                    if (resp.id3 != null && resp.id3 != resp.id1) {
                        if (resp.id3 == satuan) {
                            option += '<option value="'+resp.id3+'">'+resp.unit3+'</option>';
                        } else {
                            option += '<option value="'+resp.id3+'" selected>'+resp.unit3+'</option>';
                        }

                    }
                    $("#satuanBarangUnitEdit").append(option);
                }
            });
            $("#golId").val(id);
            $("#golDetail").val(detail);
            $("#txtEditGolHrg").val(harga);
            $("#status").val(status);
            $('#editGolHrgUnit').modal({
                backdrop: 'static',
                keyboard: false
            });
        } else {
            $("#satuanBarangRangeEdit").find('option').remove();
            $.ajax({
                url: '{{ url('/masterdatautama/harga/get-satuan/') }}'+'/'+item,
                data: {
                    "id": id,
                    "detail": detail
                },
                type: 'GET',
                success: function( resp ) {
                    var option = '';
                    if (resp.id1 == satuan) {
                        option += '<option value="'+resp.id1+'" selected>'+resp.unit1+'</option>';
                    } else {
                        option += '<option value="'+resp.id1+'" >'+resp.unit1+'</option>';
                    }

                    if (resp.id2 != null && resp.id2 != resp.id1) {
                        if (resp.id2 == satuan) {
                            option += '<option value="'+resp.id2+'" selected>'+resp.unit2+'</option>';
                        } else {
                            option += '<option value="'+resp.id2+'">'+resp.unit2+'</option>';
                        }

                    }
                    if (resp.id3 != null && resp.id3 != resp.id1) {
                        if (resp.id3 == satuan) {
                            option += '<option value="'+resp.id3+'">'+resp.unit3+'</option>';
                        } else {
                            option += '<option value="'+resp.id3+'" selected>'+resp.unit3+'</option>';
                        }

                    }
                    $("#satuanBarangRangeEdit").append(option);
                }
            });
            $("#golIdRange").val(id);
            $("#golDetailRange").val(detail);
            $("#golItemRange").val(item);
            $("#rangestartawal").val(rangestart);
            $("#rangestartedit").val(rangestart);
            $("#rangestartakhir").val(rangeEnd);
            $("#rangeendedit").val(rangeEnd);
            $("#txtEditGolHrgRange").val(harga);
            $("#statusRange").val(status);
            $('#editGolHrgRange').modal({
                backdrop: 'static',
                keyboard: false
            });
        }

    }

    function editGolonganHargaHPA(id, detail, item, harga, satuan, tipe, rangestart, rangeEnd, status) {
        if (tipe == "U") {
            $("#satuanBarangUnitEdiHPAt").find('option').remove();
            $.ajax({
                url: '{{ url('/masterdatautama/harga/get-satuan/') }}'+'/'+item,
                type: 'GET',
                success: function( resp ) {
                    var option = '';
                    if (resp.id1 == satuan) {
                        option += '<option value="'+resp.id1+'" selected>'+resp.unit1+'</option>';
                    } else {
                        option += '<option value="'+resp.id1+'" >'+resp.unit1+'</option>';
                    }

                    if (resp.id2 != null && resp.id2 != resp.id1) {
                        if (resp.id2 == satuan) {
                            option += '<option value="'+resp.id2+'" selected>'+resp.unit2+'</option>';
                        } else {
                            option += '<option value="'+resp.id2+'">'+resp.unit2+'</option>';
                        }

                    }
                    if (resp.id3 != null && resp.id3 != resp.id1) {
                        if (resp.id3 == satuan) {
                            option += '<option value="'+resp.id3+'">'+resp.unit3+'</option>';
                        } else {
                            option += '<option value="'+resp.id3+'" selected>'+resp.unit3+'</option>';
                        }

                    }
                    $("#satuanBarangUnitEditHPA").append(option);
                }
            });
            $("#golIdHPA").val(id);
            $("#golDetailHPA").val(detail);
            $("#txtEditGolHrgHPA").val(harga);
            $("#statusHPA").val(status);
            $('#editGolHrgUnitHPA').modal({
                backdrop: 'static',
                keyboard: false
            });
        } else {
            $("#satuanBarangRangeEditHPA").find('option').remove();
            $.ajax({
                url: '{{ url('/masterdatautama/harga/get-satuan/') }}'+'/'+item,
                type: 'GET',
                success: function( resp ) {
                    var option = '';
                    if (resp.id1 == satuan) {
                        option += '<option value="'+resp.id1+'" selected>'+resp.unit1+'</option>';
                    } else {
                        option += '<option value="'+resp.id1+'" >'+resp.unit1+'</option>';
                    }

                    if (resp.id2 != null && resp.id2 != resp.id1) {
                        if (resp.id2 == satuan) {
                            option += '<option value="'+resp.id2+'" selected>'+resp.unit2+'</option>';
                        } else {
                            option += '<option value="'+resp.id2+'">'+resp.unit2+'</option>';
                        }

                    }
                    if (resp.id3 != null && resp.id3 != resp.id1) {
                        if (resp.id3 == satuan) {
                            option += '<option value="'+resp.id3+'">'+resp.unit3+'</option>';
                        } else {
                            option += '<option value="'+resp.id3+'" selected>'+resp.unit3+'</option>';
                        }

                    }
                    $("#satuanBarangRangeEditHPA").append(option);
                }
            });
            $("#golIdRangeHPA").val(id);
            $("#golDetailRangeHPA").val(detail);
            $("#golItemRangeHPA").val(item);
            $("#rangestartawalHPA").val(rangestart);
            $("#rangestarteditHPA").val(rangestart);
            $("#rangestartakhirHPA").val(rangeEnd);
            $("#rangeendeditHPA").val(rangeEnd);
            $("#txtEditGolHrgRangeHPA").val(harga);
            $("#statusRangeHPA").val(status);
            $('#editGolHrgRangeHPA').modal({
                backdrop: 'static',
                keyboard: false
            });
        }

    }

    function hapusGolonganHarga(id, detail, status) {
        // deleteConfirm(baseUrl+"/masterdatautama/harga/delete-golongan-harga/"+id+"/"+detail);
        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 2.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apakah anda yakin ingin menghapus data ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        return $.ajax({
                            type: "get",
                            url: baseUrl+"/masterdatautama/harga/delete-golongan-harga/"+id+"/"+detail+"/"+status,
                            success: function (response) {
                                if (response.status == 'Success') {
                                    messageSuccess('Berhasil', 'Data berhasil hapus!');
                                    tbl_item.ajax.reload();
                                } else {
                                    messageWarning('Gagal', 'Gagal menghapus data!');
                                }
                            },
                            error: function (e) {
                                messageFailed('Peringatan', e.message);
                            }
                        });
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

    function hapusGolonganHargaHPA(id, detail, status) {
        // deleteConfirm(baseUrl+"/masterdatautama/harga/delete-golongan-harga/"+id+"/"+detail);
        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 2.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apakah anda yakin ingin menghapus data ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        return $.ajax({
                            type: "get",
                            url: baseUrl+"/masterdatautama/harga/delete-golongan-harga-hpa/"+id+"/"+detail+"/"+status,
                            success: function (response) {
                                if (response.status == 'Success') {
                                    messageSuccess('Berhasil', 'Data berhasil hapus!');
                                    tbl_itemHPA.ajax.reload();
                                } else {
                                    messageWarning('Gagal', 'Gagal menghapus data!');
                                }
                            },
                            error: function (e) {
                                messageFailed('Peringatan', e.message);
                            }
                        });
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

    function addGolonganHarga(id, name) {
        $(".barang").attr('disabled', false);
        $("#jenisharga").attr('disabled', false);
        $('#idGol').val(id);
        $('#txtGol').text(name);
        if ($.fn.DataTable.isDataTable("#table_golonganharga")) {
            $('#table_golonganharga').DataTable().clear().destroy();
        }
        tbl_item = $('#table_golonganharga').DataTable({
            "paging":   true,
            "info":     false,
            "searching": true,
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
                {data: 'status'},
                {data: 'action'}
            ]
        });
    }

    function addGolonganHargaHPA(id, name) {
        $(".barangHPA").attr('disabled', false);
        $("#jenishargaHPA").attr('disabled', false);
        $('#idGolHPA').val(id);
        $('#txtGolHPA').text(name);
        if ($.fn.DataTable.isDataTable("#table_golonganhargaHPA")) {
            $('#table_golonganhargaHPA').DataTable().clear().destroy();
        }
        tbl_itemHPA = $('#table_golonganhargaHPA').DataTable({
            "paging":   true,
            "info":     false,
            "ordering": true,
            "searching": true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/masterdatautama/harga/get-golongan-harga-hpa/') }}"+"/"+$("#idGolHPA").val(),
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
                {data: 'status'},
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

	$('#jenishargaHPA').change(function(){
		var ini, satuan, range;
		ini             = $(this).val();
		satuan     		= $('#satuanHPA');
		range     		= $('#rangeHPA');

		if (ini === 'U') {
		    $("#qtyHPA").val(1);
		    $("#qtyHPA").attr('readonly', true);
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
