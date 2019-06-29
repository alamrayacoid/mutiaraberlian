@extends('main')

@section('content')

@include('marketing.penjualanpusat.returnpenjualan.modal-search')

@include('marketing.penjualanpusat.returnpenjualan.modal-detail')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Tambah Return Produksi </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktifitas Produksi</span>
	    	 / <a href="{{route('return.index')}}">Return Produksi</a>
	    	 / <span class="text-primary font-weight-bold">Tambah Return Produksi</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Tambah Return Produksi </h3>
                        </div>
                        <div class="header-block pull-right">
                			<a class="btn btn-secondary btn-sm" href="{{url('/marketing/penjualanpusat/index')}}"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-block"> 
                        <form id="formdata">                       
                            <input type="hidden" name="itemid" value="" id="itemid" >
                            <input type="hidde" name="member" id="member">
                            <div class="row">
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label>Kode Produksi</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="input-group">                                        
                                        <input type="text" style="text-transform:uppercase;" name="kodeproduksi" id="kodeproduksi" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-md btn-primary" id="go">Lanjutkan</button>
                                </div>
                                <hr>
                            </div>

                            <div class="row" id="div2" style="display:none">                                
                                    <div class="col-md-2 col-sm-6 col-12">
                                        <label>Nota Penjualan</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                                <select class="select2" onchange="notapenjualanup()" name="notapenjualan" id="notapenjualan" style="width:100%">
                                            
                                                </select>             
                                        </div>                                                                   
                                    </div>
                                    <hr>
                                </div>

                            <div id="div3" style="display:none">
                                <section>
                                <div class="row">
                                        <div class="col-md-2 col-sm-6 col-12">
                                                <label>Penjual</label>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="form-group">
                                                <input type="text" name="penjual" readonly class="form-control" id="penjual" value="">
                                                </div>
                                            </div>                                    
                                            <hr>

                                        <div class="col-md-2 col-sm-6 col-12">
                                                <label>Agen</label>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="form-group">
                                                <input type="text" name="agen" readonly class="form-control" id="agen" value="">
                                                </div>
                                            </div>                                    
                                            <hr>
                                        
    
                                                                       
                                            <div class="col-md-2 col-sm-6 col-12">
                                                    <label>Metode Pembayaran</label>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-12">
                                                    <div class="form-group">
                                                    <input type="text" readonly name="metodepembayaran" class="form-control" id="metodepembayaran" value="">
                                                    </div>
                                                </div>
                                            <hr>
                                            
                                    
                                        <div class="col-md-2 col-sm-6 col-12">
                                                <label>Tanggal Transaksi</label>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="form-group">
                                                <input type="text" readonly name="tanggaltransaksi" class="form-control" id="tanggaltransaksi" value="">
                                                </div>
                                            </div>
                                        <hr>

                                        <div class="col-md-2 col-sm-6 col-12">
                                                <label></label>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="form-group">
                                                
                                                </div>
                                            </div>
                                        <hr>

                                        <div class="col-md-2 col-sm-6 col-12">
                                                <label>Total</label>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="form-group">
                                                <input type="text" readonly name="total" class="form-control" id="total" value="">
                                                </div>
                                            </div>
                                        <hr>
                                </div>                                                                                  

                        </section>

                        <section style="margin-top:20px;">

                            <div class="row">
                                    <div class="col-md-2 col-sm-6 col-12">
                                            <label>Item Name</label>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                            <input type="text" readonly name="item" class="form-control" id="item" value="">
                                            </div>
                                        </div>
                                    <hr>

                                    <div class="col-md-2 col-sm-6 col-12">
                                            <label>Qty</label>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                            <input type="text" style="text-align:right;" name="qty" class="form-control digits" id="qty" value="">
                                            <input type="hidden" name="qtyhidden" id="qtyhidden">
                                        </div>
                                        </div>
                                    <hr>

                                    <div class="col-md-2 col-sm-6 col-12">
                                            <label>Jenis Return </label>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                            <select class="form-control" name="type" id="type">
                                                <option value="GB">Ganti Barang</option>
                                                <option value="GU">Ganti Uang</option>
                                                <option value="PN">Potong Nota</option>
                                            </select>
                                            </div>
                                        </div>
                                    <hr>

                                    <div class="col-md-2 col-sm-6 col-12">
                                            <label>Keterangan </label>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                            <input type="text" class="form-control" id="keterangan" name="keterangan">
                                            </div>
                                        </div>
                                    <hr>
                            </div>

                        </section>
                    </div>  
                    </form> 
                    </div>
                    <div class="card-footer text-right">
                    	<button class="btn btn-primary" type="button" id="simpan">Simpan</button>
                    	<a href="{{url('/marketing/penjualanpusat/index')}}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>

			</div>

		</div>

	</section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">

    $('#go').on('click', function(){
        var kodeproduksi = $('#kodeproduksi').val();
        kodeproduksi = kodeproduksi.toUpperCase();
        var html = '<option value="">- Pilih Nota Penjualan -</option>';
        $.ajax({
            type: 'get',
            data: {kodeproduksi},
            dataType: 'JSON',
            url: baseUrl + '/marketing/penjualanpusat/returnpenjualan/getnota',
            success : function(response){
                if (response.length == 0) {
                    messageWarning("Info", 'Kode Produksi Tidak Ditemukan!');
                    $('#notapenjualan').html('<option value="">- Pilih Nota Penjualan -</option>');
                } else { 
                    for (let index = 0; index < response.length; index++) {
                    html += '<option value="'+response[index].sc_nota+'">'+response[index].sc_nota+'</option>';                    
                }
                $('#itemid').val(response[0].ssc_item);
                $('#member').val(response[0].sc_member);
                $('#notapenjualan').html(html);
                $('#notapenjualan').select2();
                $('#div2').css('display', '');            
                }                               
            }
        }); 
    });

    function notapenjualanup(){
        var notapenjualan = $('#notapenjualan').val();
        var itemid = $('#itemid').val();
        $.ajax({
            type: 'get',
            data: {notapenjualan, itemid},
            dataType: 'json',
            url: baseUrl + '/marketing/penjualanpusat/returnpenjualan/getdata',
            success : function(response){
                $('#penjual').val(response.comp.c_name);
                $('#agen').val(response.agen.c_name);
                if (response.data.sc_type == 'C') {
                    $('#metodepembayaran').val('Cash');   
                } else { 
                    $('#metodepembayaran').val('Konsinyasi');   
                }
                $('#tanggaltransaksi').val(response.data.sc_date);
                $('#total').val(response.data.sc_total);
                $('#item').val(response.item.i_name);
                $('#qty').val(response.item.scd_qty);
                $('#qtyhidden').val(response.item.scd_qty);
                $('#div3').css('display', '');
            }
        });
    }

    $('#qty').on('keyup', function(){  
        var qty = $('#qty').val();
        var batas = $('#qtyhidden').val();
        batas = batas.replace( /\[\d+\]/g, '');
        if (parseFloat(qty) > parseFloat(batas)) {
            messageWarning('Info', 'Qty tidak boleh melebihi qty penjualan');
            $('#qty').val(batas);
        }
    });

    $('#simpan').on('click', function(){
        $.ajax({
            type: 'post',
            data: $('#formdata').serialize()+'&_token='+"{{csrf_token()}}",
            dataType: 'JSON',
            url: baseUrl + '/marketing/penjualanpusat/returnpenjualan/simpan',
            success : function(response){
                if (response.status == 'berhasil') {
                    messageSuccess('Info', 'Berhasil Disimpan');
                } else {
                    messageWarning('Info', 'Gagal Disimpan');
                }
            }
        });
    });
	// $(document).ready(function(){
    //     var table_returnp;

    //     table_returnp = $('#table_rp').DataTable();

    //     $('#go').on('click', function(){
    //         $('.table-returnp').removeClass('d-none');
    //     });

	// });
</script>
@endsection
