@extends('main')

@section('content')

<article class="content animated fadeInLeft">
  <div class="title-block text-primary">
    <h1 class="title"> Edit Data Order Produk ke Pusat </h1>
    <p class="title-description">
      <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
      / <span>Marketing</span>
      / <a href="{{route('marketingarea.index')}}"><span>Manajemen Marketing Area </span></a>
      / <span class="text-primary" style="font-weight: bold;"> Edit Data Order Produk ke Pusat </span>
    </p>
  </div>
  <section class="section">
    <div class="row">
      <div class="col-12">
        
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block">
              <h3 class="title"> Edit Data Order Produk ke Pusat </h3>
            </div>
            <div class="header-block pull-right">
              <a href="{{route('marketingarea.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>
          <div class="card-block">
            <section>
              
              <div class="row">
                
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Nama Barang</label>
                </div>
                <div class="col-md-10 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input type="text" name="barang" class="form-control form-control-sm barang" style="text-transform:uppercase" autocomplete="off" value="{{$produk->i_name}}">
                    <input type="hidden" name="idItem" class="itemId" value="{{$produk->i_id}}">
                    <input type="hidden" name="kode" class="kode" value="{{$produk->i_code}}">
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Satuan</label>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <select name="po_unit" class="form-control form-control-sm select2 satuan">
                    </select>
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Jumlah</label>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input type="number" name="po_qty" min="0" class="form-control form-control-sm jumlah" value="0">
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Harga Satuan</label>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input type="text" name="po_harga" class="form-control form-control-sm input-rupiah harga" value="Rp. 0">
                    <input type="hidden" name="po_hrg" class="po_hrg">
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Total Harga</label>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-sm" name="total_harga" id="total_harga" readonly>
                    <input type="hidden" name="tot_hrg" id="tot_hrg">
                  </div>
                </div>
              </div>
            </section>
          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary btn-submit" type="button">Simpan</button>
            <a href="{{route('marketingarea.index')}}" class="btn btn-secondary">Kembali</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</article>

@endsection

@section('extra_script')
<script type="text/javascript">
    var idItem    = [];
    var namaItem  = null;
    var kode      = null;
    var icode     = [];
    // Document Ready -------------------------------------------------
    $(document).ready(function() {
        changeJumlah();
        changeSatuan();

        // AutoComplete Item ------------------------------------------
        $('.barang').on('click', function (e) {
            setArrayCode();
        });

        $(".barang").on("keyup", function () {
            $(".itemId").val('');
            $(".kode").val('');
        });

        function setArrayCode() {
            var inputs = document.getElementsByClassName('kode'),
                code   = [].map.call(inputs, function (input) {
                    return input.value.toString();
                });

            for (var i = 0; i < code.length; i++) {
                if (code[i] != "") {
                    icode.push(code[i]);
                }
            }

            var item = [];
            var inpItemId = document.getElementsByClassName('itemId'),
                item      = [].map.call(inpItemId, function (input) {
                    return input.value;
                });

            $(".barang").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "{{ url('/marketing/marketingarea/orderproduk/cari-barang') }}",
                        data: {
                            idItem: item,
                            term: $(".barang").val()
                        },
                        success: function (data) {
                            response(data);
                            console.log(data);
                        }
                    });
                },
                minLength: 1,
                select: function (event, data) {
                    setItem(data.item);
                }
            });
        }

        function setItem(info) {
          idItem   = info.data.i_id;
          namaItem = info.data.i_name;
          kode     = info.data.i_code;
          $(".kode").val(kode);
          $(".itemId").val(idItem);
          setArrayCode();
          $.ajax({
            url: '{{ url('/marketing/marketingarea/orderproduk/get-satuan') }}' + '/' + idItem,
            type: 'GET',
            success: function (resp) {
              $(".satuan").find('option').remove();
              var option = '';
              if (resp.id1 != null) {
                  option += '<option value="' + resp.id1 + '">' + resp.unit1 + '</option>'
              }
              if (resp.id2 != null && resp.id2 != resp.id1) {
                  option += '<option value="' + resp.id2 + '">' + resp.unit2 + '</option>';
              }
              if (resp.id3 != null && resp.id3 != resp.id1) {
                  option += '<option value="' + resp.id3 + '">' + resp.unit3 + '</option>';
              }
              $(".satuan").append(option);
            }
          });
          everyChange();
        }
        // End AutoComplete -------------------------------------------
    });
    // End Document Ready ---------------------------------------------
    
    // Merubah Sub Total Berdasarkan Jumlah Item ----------------------
    function changeJumlah() {
      $(".jumlah").on('input', function (evt) {
          evt.preventDefault();
          everyChange();
      });
    }
    // End Code -------------------------------------------------------
    
    // Merubah Sub Total Berdasarkan Jumlah Item ----------------------
    function changeSatuan() {
      $(".satuan").on('change', function (evt) {
          evt.preventDefault();
          everyChange();
      });
    }
    // End Code -------------------------------------------------------

    function everyChange()
    {
      var inpBarang = document.getElementsByClassName( 'barang' ),
          barang    = [].map.call(inpBarang, function( input ) {
              return parseInt(input.value);
          });
      var inpSatuan = document.getElementsByClassName( 'satuan' ),
          satuan    = [].map.call(inpSatuan, function( input ) {
              return parseInt(input.value);
          });
      var inpJumlah = document.getElementsByClassName( 'jumlah' ),
          jumlah    = [].map.call(inpJumlah, function( input ) {
              return parseInt(input.value);
          });
          
      $.ajax({
          url: "{{url('/marketing/marketingarea/orderproduk/get-price')}}",
          type: "GET",
          data: {
              item : $('.itemId').val(),
              unit: $('.satuan').val(),
              qty: $('.jumlah').val()
          },
          success:function(res)
          {                    
            var price = res.data;
            if (isNaN(price)) {
                price = 0;
            }
            $('.harga').val(convertToRupiah(price));
            $('.po_hrg').val(price);

            var inpHarga = document.getElementsByClassName( 'harga' ),
                harga    = [].map.call(inpHarga, function( input ) {
                    return input.value;
                });

            for (var i = 0; i < jumlah.length; i++) {
              var hasil = 0;
              var hrg = harga[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "");
              var jml = jumlah[i];

              if (jml == "") {
                  jml = 0;
              }

              hasil += parseInt(hrg) * parseInt(jml);

              if (isNaN(hasil)) {
                  hasil = 0;
              }
              $("#tot_harga").eq(i).val(hasil);
              hasil = convertToRupiah(hasil);
              $("#total_harga").eq(i).val(hasil);
            }                    
          }
      });
    }
</script>
@endsection
