@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Data Target </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
          / <span>Aktivitas Marketing</span>
          / <a href="{{route('penjualanpusat.index')}}"><span>Manajemen Penjualan Pusat</span></a>
          / <span class="text-primary" style="font-weight: bold;"> Target dan Realisasi</span>
          / <span class="text-primary" style="font-weight: bold;"> Edit Data Target</span>
       </p>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-12">        
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block pull-right">
              <a href="{{route('penjualanpusat.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>

          <div class="card-block">
            <section>
              <form id="formAdd">
              <div class="row">
                {{-- <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Bulan/Tahun</label>
                </div>
                <div class="col-md-10 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-sm" id="datepicker" name="t_periode">
                  </div>
                </div> --}}
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Pilihan Cabang</label>
                </div>
                <div class="col-md-10 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <select name="t_comp[]" id="" class="form-control form-control-sm select2">
                      <option value="{{$target->st_comp}}" selected="">{{$target->c_name}}</option>
                      @foreach($company->where('c_id', '!=', $target->st_comp) as $comp)
                        <option value="{{$comp->c_id}}">{{$comp->c_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="container">
                  <hr style="border:0.7px solid grey; margin-bottom:30px;">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover" cellspacing="0" id="table_target">
                      <thead class="bg-primary">
                        <tr>
							<th width ="50%">Kode/Nama Barang</th>
							<th width ="30%">Satuan</th>
							<th width ="20%">Jumlah Target</th>
                      	</tr>
                      </thead>
                      <tbody>
                      	<tr>
                      		<td>
	                            <input type="text" name="barang[]" class="form-control form-control-sm barang" style="text-transform:uppercase" value="{{$target->i_code}} - {{$target->i_name}}">
	                            <input type="hidden" name="idItem[]" class="itemid" value="{{$target->std_item}}">
	                            <input type="hidden" name="kode[]" class="kode" value="{{$target->i_code}}">
                         	</td>
                      		<td>
                           		<select name="t_unit[]" class="form-control form-control-sm select2 satuan"></select>
                          	</td>
                      		<td>
                        		<input type="number" class="form-control form-control-sm" min="0" value="" name="t_qty[]" value="{{$target->std_qty}}">
                          	</td>
                      	</tr>
                      </tbody>
                    </table>
                  </div>                                
                </div>
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