@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Edit Data Distribusi Barang </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Inventory</span>
                / <a href="{{route('distribusibarang.index')}}"><span>Pengelolaan Distribusi Barang</span></a>
                / <span class="text-primary" style="font-weight: bold;"> Edit Data Distribusi Barang</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Edit Data Distribusi Barang</h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('distribusibarang.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-block">
                            <section>

                                <div class="row">

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Tujuan</label>
                                    </div>

                                    <div class="col-md-10 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select name="" id="select-order" class="form-control form-control-sm select2" disabled>
                                                <option value="2" selected>Cabang</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="animated fadeIn col-12" id="cabang">
                                      <form id="datacabang">
                                        <input type="hidden" name="sd_nota" value="{{$data->sd_nota}}">
                                        <input type="hidden" name="sd_id" value="{{$data->sd_id}}">
                                        <input type="hidden" name="sd_from" value="{{$data->sd_from}}">
                                        <input type="hidden" name="sd_destination" value="{{$data->sd_destination}}">
                                        <input type="hidden" name="sd_date" value="{{$data->sd_date}}">
                                        <input type="hidden" name="sd_user" value="{{$data->sd_user}}">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <label>Cabang</label>
                                            </div>

                                            <div class="col-md-10 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <select name="cabang" id="pilihcabang" class="form-control form-control-sm select2" disabled>
                                                        <option value="" disabled selected>Pilih Cabang</option>
                                                        @foreach ($cabang as $key => $value)
                                                          <option value="{{$value->c_id}}" @if($data->sd_destination == $value->c_id) selected  @endif>{{$value->c_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover data-table" cellspacing="0" id="table_cabang">
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th>Kode Barang/Nama Barang</th>
                                                        <th>Satuan</th>
                                                        <th>Jumlah</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                  @foreach ($dt as $key => $value)
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="namabarang[]" @if($status[$key] == 'yes') readonly @endif id="namabarang{{$key}}" data-counter="0" class="form-control form-control-sm namabarang" value="{{$value->i_code}} - {{$value->i_name}}">
                                                            <input type="hidden" name="idbarang[]" id="idbarang{{$key}}" value="{{$value->i_id}}" class="idbarang">
                                                            <input type="hidden" name="sdd_detailid[]" value="{{$value->sdd_detailid}}">
                                                            <input type="hidden" name="stock[]" id="stock0" class="stock">
                                                        </td>
                                                        <td>
                                                            <select id="satuan{{$key}}" name="satuan[]" class="form-control form-control-sm select2">
                                                                <option value="" disabled selected>Pilih Satuan</option>
                                                                <option value="{{$unit1[$key]->u_id}}" @if($value->sdd_unit == $unit1[$key]->u_id) selected @endif>{{$unit1[$key]->u_name}}</option>
                                                                <option value="{{$unit2[$key]->u_id}}" @if($value->sdd_unit == $unit2[$key]->u_id) selected @endif>{{$unit2[$key]->u_name}}</option>
                                                                <option value="{{$unit3[$key]->u_id}}" @if($value->sdd_unit == $unit3[$key]->u_id) selected @endif>{{$unit3[$key]->u_name}}</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="qty[]" id="qty{{$key}}"  onkeyup="filterqty('{{$status[$key]}}', {{$key}})" class="form-control form-control-sm" value="{{$value->sdd_qty}}">
                                                            <input type="hidden" name="qtybatas" id="qtybatas{{$key}}" value="{{$batas[$key]}}">
                                                        </td>
                                                        @if ($status[$key] == 'yes')
                                                          <td class="badge badge-warning">Stock sudah digunakan</td>
                                                          <input type="hidden" name="status[]" value="{{$status[$key]}}">
                                                        @else
                                                          <td class="badge badge-primary">Stock belum digunakan</td>
                                                          <input type="hidden" name="status[]" value="{{$status[$key]}}">
                                                        @endif
                                                        <td>
                                                            <button class="btn btn-success btn-tambah-cabang btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                        </td>
                                                    </tr>
                                                    <input type="hidden" name="counter" id="counter" value="{{$key}}">
                                                  @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary btn-submit" onclick="simpan()" type="button">Simpan</button>
                            <a href="{{route('distribusibarang.index')}}" class="btn btn-secondary">Kembali</a>
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
      $(".namabarang").autocomplete({
        source: "{{route('distribusibarang.getItem')}}",
        select: function(event, ui) {
          getdata(ui.item.id, iam);
        }
      });
    });

          $(document).on('click', '.btn-hapus-cabang', function(){
              $(this).parents('tr').remove();
          });

          $('.btn-tambah-cabang').on('click',function(){
            var counter = $('#counter').val();
            counter++;
              $('#table_cabang')
                  .append(
                      '<tr>'+
                      '<td><input type="text" name="namabarang[]" id="namabarang'+counter+'" data-counter="'+counter+'" class="form-control form-control-sm namabarang"> <input type="hidden" name="idbarang[]" id="idbarang'+counter+'"><input type="hidden" name="sdd_detailid[]" value=""></td>'+
                      '<td><select name="satuan[]" id="satuan'+counter+'" class="form-control form-control-sm select2"><option value="" disabled selected>Pilih Satuan</option></select></td>'+
                      '<td><input type="number" name="qty[]" id="qty'+counter+'" class="form-control form-control-sm" value="0"></td>'+
                      '<td><input type="hidden" name="status[]" value="no"></td>'+
                      '<td><button class="btn btn-danger btn-hapus-cabang btn-sm rounded-circle" type="button"><i class="fa fa-trash-o"></i></button></td>'+
                      '</tr>'
                  );

              $(".namabarang").autocomplete({
                source: "{{route('distribusibarang.getItem')}}",
                select: function(event, ui) {
                  var iam = $(this).data('counter');
                  getdata(ui.item.id, iam);
                }
              });

            $('.select2').select2();
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

      function getdata(id, counter){
        var html = '<option value="" disabled selected>Pilih Satuan</option>';
        $.ajax({
          type: 'get',
          data: {id:id},
          dataType: 'json',
          url: baseUrl + '/inventory/distribusibarang/getsatuan',
          success : function(response){
            var myArray = $('.idbarang').get().map(function(el) { return el.value });
            var tmp = 'no';
            for (var i = 0; i < myArray.length; i++) {
              if (myArray[i] == id) {
                tmp = 'yes'
              }
            }

            if (tmp == 'no') {
              var iam = counter;
              $('#idbarang'+iam).val(id);
              $('#stock'+iam).val(response.stock);
              for (var i = 0; i < response.length; i++) {
                html += '<option value="'+response.unit[i].u_id+'">'+response.unit[i].u_name+'</option>';
              }
              $('#satuan'+counter).html(html);
            } else {
              var iam = counter;
                messageFailed('Failed', 'Barang tidak boleh sama!');
                $('#namabarang'+iam).val("");
            }

          }
        });
      }

      function simpan(){
            $.ajax({
              type: 'get',
              data: $('#datacabang').serialize(),
              dataType: 'json',
              url: baseUrl + '/inventory/distribusibarang/updatecabang',
              success : function(response){
                if (response.status == 'berhasil') {
                  loadingHide();
                  messageSuccess('Berhasil', 'Distribusi Cabang disimpan!');
                  setTimeout(function () {
                    window.location.href = baseUrl + '/inventory/distribusibarang/index';
                  }, 1000);
                } else {
                  messageFailed('Gagal!', 'Distribusi Cabang gagal disimpan!');
                }
              }
            });
          }

      function filterqty(status, index){
        var qty = $('#qty'+index).val();
        var batas = $('#qtybatas'+index).val();
        var stock = $('#stock'+index).val();
        if (parseInt(qty) < parseInt(batas)) {
          messageFailed('Notice!', 'Tidak boleh kurang dari qty yang sudah digunakan!');
          $('#qty'+index).val(parseInt(batas));
        } else if (parseInt(qty) > parseInt(stock)) {
          messageFailed('Notice!', 'Tidak boleh kurang dari stock!');
          $('#qty'+index).val(parseInt(stock));
        }
      }
  </script>
@endsection
