@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Tambah Data Distribusi Barang </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Inventory</span>
                / <a href="{{route('mngagen.index')}}"><span>Pengelolaan Distribusi Barang</span></a>
                / <span class="text-primary" style="font-weight: bold;"> Tambah Data Distribusi Barang</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Tambah Data Distribusi Barang</h3>
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
                                            <select name="" id="select-order" readonly class="form-control form-control-sm select2">
                                                <option value="2" selected>Cabang</option>
                                            </select>
                                        </div>
                                    </div>
                                    @include('inventory.distribusibarang.distribusi.cabang')
                                </div>
                            </section>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary " onclick="simpan()" type="button">Simpan</button>
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
          source: '{{route('distribusibarang.getitem')}}',
          select: function(event, ui) {
            var iam = $(this).data('counter');
            getdata(ui.item.id, iam);
          }
        });
      });

        $(document).ready(function(){
            $('#type_cus').change(function(){
                if($(this).val() === 'kontrak'){
                    $('#label_type_cus').text('Jumlah Bulan');
                    $('#jumlah_hari_bulan').val('');
                    $('#pagu').val('');
                    $('#armada').prop('selectedIndex', 0).trigger('change');
                    $('.120mm').removeClass('d-none');
                    $('.125mm').addClass('d-none');
                    $('.122mm').removeClass('d-none');
                } else if($(this).val() === 'harian'){
                    $('#label_type_cus').text('Jumlah Hari');
                    $('#armada').prop('selectedIndex', 0).trigger('change');
                    $('#pagu').val('');
                    $('#jumlah_hari_bulan').val('');
                    $('.122mm').addClass('d-none');
                    $('.120mm').removeClass('d-none');
                    $('.125mm').removeClass('d-none');
                } else {
                    $('#jumlah_hari_bulan').val('');
                    $('#armada').prop('selectedIndex', 0).trigger('change');
                    $('#pagu').val('');
                    $('.122mm').addClass('d-none');
                    $('.120mm').addClass('d-none');
                    $('.125mm').addClass('d-none');
                }
            });


            $(document).on('click', '.btn-hapus-agen', function(){
                $(this).parents('tr').remove();
            });

            $('.btn-tambah-agen').on('click',function(){
                $('#table_agen')
                    .append(
                        '<tr>'+
                        '<td><input type="text" class="form-control form-control-sm"></td>'+
                        '<td><select name="#" id="#" class="form-control form-control-sm select2"><option value=""></option></select></td>'+
                        '<td><input type="number" class="form-control form-control-sm" value="0"></td>'+
                        '<td><input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 0"></td>'+
                        '<td><input type="text" class="form-control form-control-sm" readonly=""></td>'+
                        '<td><button class="btn btn-danger btn-hapus-agen btn-sm rounded-circle" type="button"><i class="fa fa-trash-o"></i></button></td>'+
                        '</tr>'
                    );
                    $('.select2').select2();
            });

            $(document).on('click', '.btn-hapus-cabang', function(){
                $(this).parents('tr').remove();
            });

            $('.btn-tambah-cabang').on('click',function(){
              var counter = 1;
                $('#table_cabang')
                    .append(
                        '<tr>'+
                        '<td><input type="text" name="namabarang[]" id="namabarang'+counter+'" data-counter="'+counter+'" class="form-control form-control-sm namabarang"> <input type="hidden" name="idbarang[]" id="idbarang'+counter+'"></td>'+
                        '<td><select id="satuan'+counter+'" name="satuan[]" class="form-control form-control-sm select2"><option value="" disabled selected>Pilih Satuan</option></select></td>'+
                        '<td><input type="number" name="qty[]" id="qty'+counter+'" onkeyup="filterqty('+counter+')" class="form-control form-control-sm" value="0"></td>'+
                        '<td><button class="btn btn-danger btn-hapus-cabang btn-sm rounded-circle" type="button"><i class="fa fa-trash-o"></i></button></td>'+
                        '</tr>'
                    );

                $(".namabarang").autocomplete({
                  source: '{{route('distribusibarang.getitem')}}',
                  select: function(event, ui) {
                    var iam = $(this).data('counter');
                    getdata(ui.item.id, iam);
                  }
                });

                counter++;

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
        });

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
                $('#stock'+iam).val(parseInt(response.stock));
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

        function filterqty(index){
          var qty = $('#qty'+index).val();
          var stock = $('#stock'+index).val();
           if (parseInt(qty) > parseInt(stock)) {
            messageFailed('Notice!', 'Tidak boleh kurang dari stock!');
            $('#qty'+index).val(parseInt(stock));
          }
        }

        function simpan(){
          var tmp = $('#select-order').val();

          if (tmp == 2) {
            if ($('#pilihcabang').val() != null) {
              $.ajax({
                type: 'get',
                data: $('#datacabang').serialize(),
                dataType: 'json',
                url: baseUrl + '/inventory/distribusibarang/simpancabang',
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
            } else {
              messageFailed('Alert!', 'Mohon pilih cabang yang dituju!');
            }
          } else if (tmp == 1) {

          }
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
                var ini, agen, cabang;
                ini         = $('#select-order').val();
                agen        = $('#agen');
                cabang      = $('#cabang');

                if (ini === '1') {
                    agen.removeClass('d-none');
                    cabang.addClass('d-none');
                } else if(ini === '2'){
                    agen.addClass('d-none');
                    cabang.removeClass('d-none');
                } else {
                    agen.addClass('d-none');
                    cabang.addClass('d-none');
                }
        });
    </script>
@endsection
