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
                    <form id="formdata">
                    <input type="hidden" name="po_comp" value="{{$data->po_comp}}">
                    <input type="hidden" name="po_agen" value="{{$data->po_agen}}">
                    <input type="hidden" name="po_nota" value="{{$data->po_nota}}">
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
                      @foreach ($dt as $key => $value)
                        <tr>
                            <td>
                              <input type="text" name="barang[]" id="barang0" data-counter="0" class="form-control form-control-sm namabarang" value="{{$value->i_code}} - {{$value->i_name}}">
                              <input type="hidden" name="idbarang[]" id="idbarang0" value="{{$value->i_id}}">
                            </td>
                            <td><input style="text-align:right;" type="number" name="request[]" id="request0" class="form-control form-control-sm" min="0" value="{{$value->pod_qty}}"></td>
                            <td>
                            <select id="satuan" name="satuan[]" id="satuan0" class="from-control form-control-sm select2">
                              <option value="" disabled selected> - Pilih Satuan - </option>
                              <option value="{{$unit1[$key]->u_id}}" @if($value->pod_unit == $unit1[$key]->u_id) selected @endif>{{$unit1[$key]->u_name}}</option>
                              <option value="{{$unit2[$key]->u_id}}" @if($value->pod_unit == $unit2[$key]->u_id) selected @endif>{{$unit2[$key]->u_name}}</option>
                              <option value="{{$unit3[$key]->u_id}}" @if($value->pod_unit == $unit3[$key]->u_id) selected @endif>{{$unit3[$key]->u_name}}</option>
                            </select>
                            </td>
                            <td><input style="text-align:right;" type="text" name="harga[]" id="harga0" class="form-control form-control-sm" readonly="" value="{{"Rp " . number_format((int)$value->pod_price,0,',','.')}}"></td>
                            <td align="center"></td>
                            <td align="center"><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>
                        <input type="hidden" name="counter" value="{{$key}}">
                      @endforeach
                    </tbody>
                    </table>

                    </div>
                    </form>
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
var counter = $('input[name=counter]').val();
  $(document).ready(function(){
    $(".namabarang").autocomplete({
      source: "{{route('distribusibarang.getItem')}}",
      select: function(event, ui) {
        var iam = $(this).data('counter');
        getdata(ui.item.id, iam);
      }
    });

    $(document).on('click', '.btn-hapus', function(){
      $(this).parents('tr').remove();
    });

    $('.btn-tambahp').on('click',function(){
      counter++
      $('#table_proses tbody')
      .append(
        '<tr>'+
          '<td><input type="text" name="barang[]" id="barang'+counter+'" class="form-control form-control-sm namabarang"><input type="hidden" name="idbarang[]" id="idbarang'+counter+'" value=""></td>'+
          '<td><input type="number" name="request[]" id="request'+counter+'" class="form-control form-control-sm" min="0"></td>'+
          '<td><select id="satuan" name="satuan[]" id="satuan'+counter+'" class="from-control form-control-sm select2"></select></td>'+
          '<td><input type="text" name="harga[]" id="harga'+counter+'" class="form-control form-control-sm input-rupiah" readonly=""></td>'+
          '<td align="center"></td>'+
          '<td align="center"><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>'+
        '</tr>'
        );

        $(".namabarang").autocomplete({
          source: "{{route('distribusibarang.getItem')}}",
          select: function(event, ui) {
            var iam = $(this).data('counter');
            getdata(ui.item.id, iam);
          }
        });

        $('.select2').select2({
            theme: "bootstrap",
            dropdownAutoWidth: true,
            width: '100%'
        });
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
