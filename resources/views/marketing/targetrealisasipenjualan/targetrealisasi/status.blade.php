@extends('main')

@section('content')
<article class="content animated fadeInLeft">

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
	                        <h3 class="title"> February/2019 </h3>
	                    </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover" cellspacing="0" id="table_pegawai">
	                                <thead class="bg-primary">
	                                    <tr>
							                <th>No</th>
							                <th>Kode/Nama Barang</th>
							                <th>Satuan</th>
							                <th>Target</th>
							                <th>Realisasi</th>
							                <th>Presentase</th>
							            </tr>
	                                </thead>
	                                <tbody>
	                                	<tr>
	                                		<td>1</td>
	                                		<td>BRG001/Rahasia</td>
	                                		<td>Pcs</td>
	                                		<td>1000</td>
	                                		<td>500</td>
	                                		<td class="bad">50%</td>
	                                	</tr>
	                                </tbody>
                                </table>
                                <div class="akumulasi">
                                    <section>
                                        <fieldset class="mt-2">
                                        <div class="row">
                                            <div class="container">
                                                <input type="text" class="pull-right form-control col-2" style="text-align:center;" value="50%" readonly="">
                                                <h3 class="pull-right mr-2 akumulasi-txt">Akumulasi Target</h3>
                                            </div>
                                        </div>
                                        </fieldset>
                                    </section>
                                </div>
	                        </div>
                        </section>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-secondary pull-right" onclick="window.location.href='{{ route('targetrealisasi.index') }}'">Kembali</button>
                    </div>
                </div>

			</div>

		</div>

	</section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
	$('#calendar_date').click(function(){
		$('.datepicker').datepicker('show');
	});
</script>
<script type="text/javascript">

$(document).ready(function(){
	var table = $('#table_pegawai').DataTable();

	$(document).on('click', '.btn-disable', function(){
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
						ini.parents('.btn-group').html('<button class="btn btn-success btn-enable" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

	$(document).on('click', '.btn-enable', function(){
		$.toast({
			heading: 'Information',
			text: 'Data Berhasil di Aktifkan.',
			bgColor: '#0984e3',
			textColor: 'white',
			loaderBg: '#fdcb6e',
			icon: 'info'
		})
		$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
										'<button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
	})

	// function table_hapus(a){
	// 	table.row($(a).parents('tr')).remove().draw();
	// }
});
</script>
@endsection
