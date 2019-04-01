@extends('main')

@section('content')

@include('masterdatautama.member.modal-edit')
<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Edit Data Member </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Master Data Utama</span>
         / <a href="{{route('member.index')}}"><span>Master Member</span></a>
         / <span class="text-primary" style="font-weight: bold;">Edit Data Member</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title">Edit Data Member </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('member.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>
                    <div class="card-block">
                        <section>

                          <div class="row">

                            <div class="col-md-2 col-sm-6 col-xs-12">
                              <label>Nama</label>
                            </div>

                            <div class="col-md-10 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm">
                              </div>
                            </div>
                            
                            <div class="col-md-2 col-sm-6 col-xs-12">
                              <label>NIK</label>
                            </div>

                            <div class="col-md-10 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm">
                              </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-12">
                              <label>Nomer Telp</label>
                            </div>

                            <div class="col-md-10 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm">
                              </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-12">
                              <label>Provinsi</label>
                            </div>

                            <div class="col-md-4 col-sm-6 col-xs-12">
                              <div class="form-group">
                               <select name="" id="" class="form-control form-control-sm select2">
                                  <option value="">Pilih Provinsi</option>
                               </select>
                              </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-12">
                              <label>Kota</label>
                            </div>

                            <div class="col-md-4 col-sm-6 col-xs-12">
                              <div class="form-group">
                               <select name="" id="" class="form-control form-control-sm select2">
                                  <option value="">Pilih Kota</option>
                               </select>
                              </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-12">
                              <label>Alamat</label>
                            </div>

                            <div class="col-md-10 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm">
                              </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-12">
                              <label>Agen</label>
                            </div>

                            <div class="col-md-10 col-sm-6 col-xs-12">
                              <div class="input-group">
                                <input type="text" class="form-control form-control-sm">
                                <button class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal-edit"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                          </div>

                        </section>
                    </div>

                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{route('member.index')}}" class="btn btn-secondary">Kembali</a>
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

    $(document).on('click', '.btn-submit', function(){
        $.toast({
            heading: 'Success',
            text: 'Data Berhasil di Edit',
            bgColor: '#00b894',
            textColor: 'white',
            loaderBg: '#55efc4',
            icon: 'success'
        })
	})

    $("#search-list-agen").on("click", function() {
        $(".table-modal").removeClass('d-none');
    });

  });
</script>
@endsection