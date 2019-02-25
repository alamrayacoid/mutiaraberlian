@extends('main')

@section('content')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Edit Data Adonan </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
	    	/ <span>Stok</span> 
	    	/ <a href="{{route('dataadonan')}}"><span>Data Adonan</span> </a>
	    	/ <span class="text-primary font-weight-bold">Edit Data Adonan</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Edit Data Adonan </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a href="{{route('dataadonan')}}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>

                        	<fieldset>
                        		<div class="row">
                        			<div class="col-lg-12">
                        				<label>Bahan</label>
                        				<div class="form-group">
                    					
                        					<select class="form-control form-control-sm select2" id="bahan">
                        						<option value="">--Pilih--</option>
                        						<option value="1">Semen</option>
                        						<option value="2">Air</option>
                        						<option value="3">Pasir</option>
                        					</select>	
	                        					
                        				</div>
                        			</div>
                        			<div class="col-lg-12 text-right">
                                        <button class="btn btn-primary btn-tambah" type="button"><i class="fa fa-plus"></i> Tambah</button>
                						<button class="btn btn-primary btn-update d-none" type="button"><i class="fa fa-plus"></i> Update</button>
                					</div>
                        		</div>
                        	</fieldset>

                        	<div class="table-responsive mt-3">
                        		<table class="table table-hover table-bordered table-striped data-table" cellspacing="0" id="table_adonan">
                        			<thead class="bg-primary">
                        				<tr>
                        					<th>Bahan</th>
                        					<th width="1%"></th>
                        				</tr>
                        			</thead>
                                    <tbody></tbody>
                        		</table>
                        	</div>

                        </section>
                    </div>

                    <div class="card-footer text-right">
                    	<button class="btn btn-primary" type="button">Simpan</button>
                    	<a href="{{route('dataadonan')}}" class="btn btn-secondary">Kembali</a>
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

        var bahan = $('#bahan');
        var table = $('#table_adonan').DataTable();
        var int = 0;        


        function func_tabel_bahan(){
            table.row.add([
                $('#bahan option:selected').text() +
                '<input type="hidden" class="bahan" value="'+ $('#bahan').val() +'">',
                '<div class="btn-group">'+
                    '<button class="btn btn-warning btn-edit" title="Edit"><i class="fa fa-pencil"></i></button>'+
                    '<button class="btn btn-danger btn-hapus" title="Delete"><i class="fa fa-trash-o"></i></button>'+
                '</div>'
                ]).draw(false);

            int++;

            bahan.prop('selectedIndex', 0).trigger('change');
        }


        $('.btn-tambah').on('click', function(){


            if (bahan.val() === '') {
                $.toast({
                    text: 'Isi Bahan terlebih dahulu', // Text that is to be shown in the toast
                    heading: 'Gagal', // Optional heading to be shown on the toast
                    icon: 'error'// if dont set bgColor or textColor background color same as icon type (warning, info, success, error)
                });
            } else {
                func_tabel_bahan();
            }
        });

        $(document).on('click', '.btn-edit', function(){
            $('.btn-update').removeClass('d-none');
            $('.btn-tambah').addClass('d-none');

            $('.btn-edit').attr('disabled', true);

            var hasil = $(this).parents('tr').find('.bahan').val();

            $('#bahan').val(hasil).trigger('change');
            table.row($(this).parents('tr')).remove().draw();
        });

        $('.btn-update').on('click', function(){
            func_tabel_bahan();
            $('.btn-update').addClass('d-none');
            $('.btn-tambah').removeClass('d-none');

            $('.btn-edit').attr('disabled', false);

        });

        $('#table_adonan tbody').on('click', '.btn-hapus', function(){
            table.row($(this).parents('tr')).remove().draw();
        });
    });
</script>
@endsection