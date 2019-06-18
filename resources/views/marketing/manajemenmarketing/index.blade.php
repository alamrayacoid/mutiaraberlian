@extends('main')

@section('content')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Manajemen Marketing </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Marketing</span> / <span class="text-primary" style="font-weight: bold;">Manajemen Marketing</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

                <ul class="nav nav-pills mb-3" id="Tabzs">
{{--                    <li class="nav-item">--}}
{{--                        <a href="#approval" class="nav-link active" data-target="#approval" aria-controls="approval" data-toggle="tab" role="tab">Approval Promosi</a>--}}
{{--                    </li>--}}
                    <li class="nav-item">
                        <a href="#promosi_tahunan" class="nav-link" data-target="#promosi_tahunan" aria-controls="promosi_tahunan" data-toggle="tab" role="tab">Promosi Tahunan</a>
                    </li>
                    <li class="nav-item">
                        <a href="#promosi_bulanan" class="nav-link active" data-target="#promosi_bulanan" aria-controls="promosi_bulanan" data-toggle="tab" role="tab">Promosi Bulanan</a>
                    </li>
                    <li class="nav-item">
                        <a href="#history_promosi" class="nav-link" data-target="#history_promosi" aria-controls="history_promosi" data-toggle="tab" role="tab">History Promosi</a>
                    </li>
                </ul>

                <div class="tab-content">

                    @include('marketing.manajemenmarketing.history.index')
                	@include('marketing.manajemenmarketing.tahunan.index')
					@include('marketing.manajemenmarketing.bulanan.index')

	            </div>

			</div>

		</div>

	</section>

</article>

@include('marketing.manajemenmarketing.modal')
@include('marketing.manajemenmarketing.modal_done')

@endsection
@section('extra_script')
<script type="text/javascript">
    var table_bulan;
    var table_tahun;
	$(document).ready(function(){
        setTimeout(function(){
            table_tahun = $('#table_tahunan').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('yearpromotion.data') }}",
                    type: "get"
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'p_reff'},
                    {data: 'p_name'},
                    {data: 'p_additionalinput'},
                    {data: 'p_budget'},
                    {data: 'p_isapproved'},
                    {data: 'action'}
                ],
            });
        }, 500);

        setTimeout(function(){
            table_bulan = $('#table_bulanan').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('monthpromotion.data') }}",
                    type: "get"
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'p_reff'},
                    {data: 'p_name'},
                    {data: 'p_additionalinput'},
                    {data: 'p_budget'},
                    {data: 'p_isapproved'},
                    {data: 'action'}
                ],
            });
        }, 1000);

		$(document).on('click', '.btn-rejected', function(){
			var ini = $(this);
			$.confirm({
				animation: 'RotateY',
				closeAnimation: 'scale',
				animationBounce: 1.5,
				icon: 'fa fa-exclamation-triangle',
				title: 'Peringatan!',
				content: 'Apa anda yakin?',
				theme: 'disable',
			    buttons: {
			        info: {
						btnClass: 'btn-blue',
			        	text:'Ya',
			        	action : function(){
							$.toast({
								heading: 'Information',
								text: 'Promosi Ditolak.',
								bgColor: '#0984e3',
								textColor: 'white',
								loaderBg: '#fdcb6e',
								icon: 'info'
							})
					        ini.parents('.btn-group').html('<button class="btn btn-danger btn-sm btn-cancel-reject">Batalkan Penelokan</button>');
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

		$(document).on('click', '.btn-cancel-reject', function(){
			$(this).parents('.btn-group').html('<button class="btn btn-success btn-approval" type="button" title="approve"><i class="fa fa-check"></i></button>'+
			'<button class="btn btn-danger btn-rejected" type="button" title="reject"><i class="fa fa-close"></i></button>')
		})

		$(document).on('click', '.btn-approval', function(){
			$.toast({
				heading: 'Information',
				text: 'Promosi Diterima.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-primary btn-sm btn-cancel-approve">Batalkan Penerimaan</button>')
		})

		$(document).on('click', '.btn-cancel-approve', function(){
			$(this).parents('.btn-group').html('<button class="btn btn-success btn-approval" type="button" title="approve"><i class="fa fa-check"></i></button>'+
			'<button class="btn btn-danger btn-rejected" type="button" title="reject"><i class="fa fa-close"></i></button>')
		})


	});
	
	function DetailPromosi(id) {
	    loadingShow();
	    axios.get('{{ route("monthpromotion.detailpromosi") }}', {
	        params:{
	            "id": id
            }
        }).then(function (response) {
            loadingHide();
            let data = response.data.data;
            $('#detail_judulpromosi').val(data.p_name);
            $('#detail_kodepromosi').val(data.p_reff);
            let jenis = '';
            if (data.p_type == 'B'){
                jenis = 'Bulanan';
                $('.tahun').hide();
                $('.bulan').show();
                $('#detail_bulanpromosi').val(data.p_additionalinput);
            } else if (data.p_type == 'T'){
                jenis = 'Tahunan';
                $('.tahun').show();
                $('.bulan').hide();
                $('#detail_tahunpromosi').val(data.p_additionalinput);
            }
            $('#detail_jenispromosi').val(jenis);
            $('#detail_biayausulanpromosi').val(parseInt(data.p_budget));
            $('#detail_biayasetujipromosi').val(parseInt(data.p_budgetrealization));
            let status = '';
            if (data.p_isapproved == 'P'){
                status = 'Diajukan';
            } else if (data.p_isapproved == 'Y'){
                status = 'Disetujui';
            }
            $('#detail_statuspromosi').val(status);
            $('#detail_outputpromosi').val(data.p_outputplan);
            $('#detail_outcomepromosi').val(data.p_outcomeplan);
            $('#detail_impactpromosi').val(data.p_impactplan);
            $('#detail_catatanpromosi').val(data.p_note);
            $('#detailpromosi').modal('show');
        }).catch(function (error) {
            loadingHide();
        });
    }

    function EditPromosi(id) {
        location.href = "{{ route('monthpromotion.edit') }}" + "?id=" + id;
    }

    function EditPromosiTahunan(id) {
        location.href = "{{ route('yearpromotion.edit') }}" + "?id=" + id;
    }
    
    function HapusPromosi(id) {
        return $.confirm({
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
                            url: "{{ route('monthpromotion.delete') }}",
                            data: {
                                "id": id
                            },
                            success: function (response) {
                                if (response.status == 'success') {
                                    messageSuccess('Berhasil', 'Data berhasil hapus!');
                                    table_bulan.ajax.reload();
                                    table_tahun.ajax.reload();
                                } else if (response.status == 'unauth'){
                                    messageWarning('Perhatian', 'Anda tidak memiliki akses');
                                }
                                else {
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
    
    function DonePromosi(id) {
        loadingShow();
        axios.get('{{ route("monthpromotion.detailpromosi") }}', {
            params:{
                "id": id
            }
        }).then(function (response) {
            loadingHide();
            let data = response.data.data;
            $('#done_judulpromosi').val(data.p_name);
            $('#done_kodepromosi').val(data.p_reff);
            $('#done_outputpromosi').val(data.p_outputplan);
            $('#done_outcomepromosi').val(data.p_outcomeplan);
            $('#done_impactpromosi').val(data.p_impactplan);
            $('#done_catatanpromosi').val(data.p_note);
            $('#modal_done').modal('show');
        }).catch(function (error) {
            loadingHide();
        });
    }

    $("#done_tanggalpromosi").datepicker({
        format: "dd-mm-yyyy",
        todayHighlight: true,
        autoclose: true
    });

	function done() {
	    let kode = $('#done_kodepromosi').val();
	    let tanggal = $('#done_tanggalpromosi').val();
        let outputreal = $('#done_outputreal').val();
        let outputpersen = $('#done_outputpersentase').val();
        let outcomereal = $('#done_outcomerealisasi').val();
        let outcomepersen = $('#done_outcomerealisasi').val();
        let impactreal = $('#done_impactrealisasi').val();
        let impactpersen = $('#done_impactpersentase').val();
        let catatan = $('#done_catatanpromosi').val();

        valid = 1;
        if (tanggal == ''){
            valid = 0;
            messageWarning("Perhatian", "Tanggal tidak boleh kosong");
            $('#done_tanggalpromosi').focus();
            return false;
        }
        if (outputreal == ''){
            valid = 0;
            messageWarning("Perhatian", "Output Realisasi tidak boleh kosong");
            $('#done_outputreal').focus();
            return false;
        }
        if (outputpersen == ''){
            valid = 0;
            messageWarning("Perhatian", "Persentase Output tidak boleh kosong");
            $('#done_outputpersentase').focus();
            return false;
        }
        if (outcomereal == ''){
            valid = 0;
            messageWarning("Perhatian", "Outcome Realisasi tidak boleh kosong");
            $('#done_outcomerealisasi').focus();
            return false;
        }
        if (outcomepersen == ''){
            valid = 0;
            messageWarning("Perhatian", "Persentase Outcome tidak boleh kosong");
            $('#done_outcomepersentase').focus();
            return false;
        }
        if (impactreal == ''){
            valid = 0;
            messageWarning("Perhatian", "Impact Realisasi tidak boleh kosong");
            $('#done_impactrealisasi').focus();
            return false;
        }
        if (impactpersen == ''){
            valid = 0;
            messageWarning("Perhatian", "Persentase Impact tidak boleh kosong");
            $('#done_outcomerealisasi').focus();
            return false;
        }

        if (valid == 1){
            axios.post('{{ route("donepromotion.done") }}', {
                "kode": kode,
                "tanggal": tanggal,
                "outputreal": outputreal,
                "outputpersen": outputpersen,
                "outcomereal": outcomereal,
                "outcomepersen": outcomepersen,
                "impactreal": impactreal,
                "impactpersen": impactpersen,
                "catatan": catatan,
                "_token": "{{ csrf_token() }}"
            }).then(function (response) {
                if (response.data.status == 'success'){
                    loadingHide();
                    messageSuccess("Sukses", "Data berhasil disimpan");
                    $('#modal_done').modal('hide');
                    table_tahun.ajax.reload();
                    table_bulan.ajax.reload();
                } else if (response.data.status == 'gagal'){
                    loadingHide();
                    messageFailed("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                alert('Error');
                console.log(error);
            })
        }

    }
</script>
@endsection
