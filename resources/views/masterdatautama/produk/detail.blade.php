@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Detail Data Produk </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Master Data Utama</span>
         / <a href="{{route('dataproduk.index')}}"><span>Data Produk</span></a>
         / <span class="text-primary font-weight-bold">Detail Data Produk</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">

        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title">Detail Data Produk</h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('dataproduk.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <form action="{{ route('dataproduk.update', [$data['dataproduk']->i_id]) }}" method="post" id="myForm" autocomplete="off">
                      <div class="card-block">
                        <section>

                          <div class="row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nama Produk</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" style="text-transform: uppercase;" disabled class="form-control form-control-sm" name="dataproduk_name" value="{{ $data['dataproduk']->i_name }}">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Jenis Produk</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="hidden" value="{{ $data['dataproduk']->i_type }}" id="type">
                                <select class="form-control form-control-sm" disabled name="dataproduk_type" id="dataproduk_type">
                                  <option value="">--Pilih Jenis Produk--</option>
                                  @foreach ($jenis as $key => $value)
                                    <option value="{{$value->it_id}}" @if ($value->it_id == $data['dataproduk']->i_type)
                                      selected
                                    @endif>{{$value->it_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Kode Produk</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" disabled class="form-control form-control-sm" name="dataproduk_code"  value="{{ $data['dataproduk']->i_code }}">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">

                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">

                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Satuan Utama</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select class="form-control disabled form-control-sm" disabled name="dataproduk_satuanutama">
                                  <option value="">--Pilih--</option>
                                  @foreach ($satuan as $key => $value)
                                    <option value="{{$value->u_id}}" @if ($value->u_id == $data['dataproduk']->i_unit1)
                                      selected
                                    @endif>{{$value->u_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Isi Satuan Utama</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="number" disabled class="form-control-sm form-control" min="0" value="{{(int)$data['dataproduk']->i_unitcompare1}}" readonly name="dataproduk_isisatuanutama">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Satuan Alternatif 1</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select class="form-control form-control-sm" disabled name="dataproduk_satuanalt1">
                                  <option value="">--Pilih--</option>
                                  @foreach ($satuan as $key => $value)
                                    <option value="{{$value->u_id}}" @if ($value->u_id == $data['dataproduk']->i_unit2)
                                      selected
                                    @endif>{{$value->u_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Isi Satuan Alternatif 1</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="number" disabled class="form-control-sm form-control" min="0" value="{{(int)$data['dataproduk']->i_unitcompare2}}" name="dataproduk_isisatuanalt1">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Satuan Alternatif 2</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select class="form-control form-control-sm" disabled name="dataproduk_satuanalt2">
                                  <option value="">--Pilih--</option>
                                  @foreach ($satuan as $key => $value)
                                    <option value="{{$value->u_id}}" @if ($value->u_id == $data['dataproduk']->i_unit3)
                                      selected
                                    @endif>{{$value->u_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Isi Satuan Alternatif 2</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="number" class="form-control-sm form-control" disabled min="0" value="{{(int)$data['dataproduk']->i_unitcompare3}}" name="dataproduk_isisatuanalt2">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Keterangan</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <textarea class="form-control" disabled name="dataproduk_ket">{{ $data['dataproduk']->i_detail }}</textarea>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Gambar Produk</label>
                            </div>
                            <div class="col-12" align="center">
                              <div class="form-group">
                                  @if ($data['dataproduk']->i_image != null)
                                  <img
                                  src="{{ url('/storage/uploads/produk/original' ) . '/' . $data['dataproduk']->i_image}}"
                                  height="120px" width="130px" id="img-preview"
                                  style="cursor: pointer;">
                                  @else
                                  <img src="{{ asset('assets/img/add-image-icon.png') }}" height="120px"
                                  width="130px" id="img-preview" style="cursor: pointer;">
                                  @endif
                              </div>
                            </div>

                          </div>
                        </section>
                      </div>
                      <div class="card-footer text-right">
                        <a href="{{route('dataproduk.index')}}" class="btn btn-secondary">Kembali</a>
                      </div>
                    </form>

                </div>

      </div>

    </div>

  </section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });


  $(document).ready(function(){
    type = $('#type').val();
    $("#dataproduk_type option[value='"+ type +"']").prop('selected', 'selected');

    $('#detImgRP').attr('src', baseUrl +'/storage/uploads/produk/item-auth/'+ response.ia_image);
  });
</script>
@endsection
