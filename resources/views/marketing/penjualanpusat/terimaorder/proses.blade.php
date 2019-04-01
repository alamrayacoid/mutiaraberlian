@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Proses Data Order Penjualan</h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas Marketing</span>
         / <a href="{{route('penjualanpusat.index')}}"><span>Manajemen Penjualan Pusat</span></a>
         / <span>Terima Order Penjualan</span>
         / <span class="text-primary" style="font-weight: bold;">Proses Data Order Penjualan</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">

            <div class="card-header bordered p-2">
                <div class="header-block">
                <h3 class="title">Proses Data Order Penjualan</h3>
                </div>
                <div class="header-block pull-right">
                <a href="{{route('penjualanpusat.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>

            <div class="card-block">
                <section>

                    <div class="pull-right mb-3">
                        <button class="btn btn-primary btn-tambahp"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
                    </div>

                    <div class="table-responsive mt-3">
                    <table class="table table-hover table-striped" id="table_proses">
                    <thead class="bg-primary">
                        <tr>
                            <th width="50%">Nama Barang</th>
                            <th width="10%">Request</th>
                            <th width="10%">Satuan</th>
                            <th width="20%">Harga @</th>
                            <th width="10%" style="text-align:center;">Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" class="form-control form-control-sm"></td>
                            <td><input type="number" class="form-control form-control-sm" min="0"></td>
                            <td><select name="" id="" class="from-control form-control-sm select2"></select></td>
                            <td><input type="text" class="form-control form-control-sm input-rupiah" readonly=""></td>
                            <td align="center"><i class="fa fa-check"></i></td>
                            <td align="center"><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>
                        <tr>
                            <td><input type="text" class="form-control form-control-sm"></td>
                            <td><input type="number" class="form-control form-control-sm" min="0"></td>
                            <td><select name="" id="" class="from-control form-control-sm select2"></select></td>
                            <td><input type="text" class="form-control form-control-sm input-rupiah" readonly=""></td>
                            <td align="center"><i class="fa fa-times"></i></td>
                            <td align="center"><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>
                    </tbody>
                    </table>

                    </div>
                </section>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                <a href="{{route('penjualanpusat.index')}}" class="btn btn-secondary">Kembali</a>
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
    $(document).on('click', '.btn-hapus', function(){
      $(this).parents('tr').remove();
    });

    $('.btn-tambahp').on('click',function(){
      $('#table_proses tbody')
      .append(
        '<tr>'+
          '<td><input type="text" class="form-control form-control-sm"></td>'+
          '<td><input type="number" class="form-control form-control-sm" min="0"></td>'+
          '<td><select name="" id="" class="from-control form-control-sm select2"></select></td>'+
          '<td><input type="text" class="form-control form-control-sm input-rupiah" readonly=""></td>'+
          '<td align="center"><i class="fa fa-times"></i></td>'+
          '<td align="center"><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>'+
        '</tr>'
        );
    });

    $(document).on('click', '.btn-submit', function(){
			$.toast({
				heading: 'Success',
				text: 'Data Berhasil di Simpan',
				bgColor: '#00b894',
				textColor: 'white',
				loaderBg: '#55efc4',
				icon: 'success'
			})
		})
  });
</script>
@endsection
