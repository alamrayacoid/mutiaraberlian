@extends('main')

@section('content')
    {{--
    <!-- modal-code-production -->
    <!-- <form class="formCodeProd">
        @include('produksi.returnproduksi.new-return.modal-code-prod')
        @include('produksi.returnproduksi.new-return.modal-code-prod-base')
    </form> -->
    --}}


<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Tambah Return Produksi </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktifitas Produksi</span>
	    	 / <a href="{{ route('return.index') }}">Return Produksi</a>
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
                			<a class="btn btn-secondary btn-sm" href="{{ route('return.index') }}"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-block">
                        <form id="formdata">
                            {{--
                            <!-- <div class="col-md-2 col-sm-6 col-xs-12">
                                <label>Area</label>
                            </div>
                            <div class="col-md-10 col-sm-6 col-xs-12">
                                <div class="row">
                                    <div class="form-group col-6">
                                        <select name="prov" id="prov" class="form-control form-control-sm select2">
                                            <option value="" selected="" disabled="">=== Pilih Provinsi ===</option>
                                            @foreach($provinsi as $prov)
                                                <option value="{{ $prov->wp_id }}">{{ $prov->wp_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-6">
                                        <select name="city" id="city" class="form-control form-control-sm select2 city">
                                            <option value="" selected disabled>=== Pilih Kota ===</option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            --}}

							<div class="row">
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <label>Tanggal Return</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                        </div>
                                        <input type="text" name="returnDate" class="form-control form-control-sm datepicker" autocomplete="off" id="returnDate" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                    </div>
                                </div>
                                <div class="col-md-6"></div>

								<div class="col-md-2 col-sm-6 col-xs-12">
									<label>Supplier</label>
								</div>
								<div class="col-md-10 col-sm-6 col-xs-12">
									<div class="form-group">
										<select name="supplier" id="supplier" class="form-control form-control-sm select2 supplier">
											<option value="" selected disabled>=== Pilih Supplier ===</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 col-sm-6 col-12">
                      <label>Tipe Pengembalian</label>
                  </div>
                  <div class="col-md-4 col-sm-6 col-12">
									<div class="form-group">
                  <input type="checkbox" id="brokenItemToggle" value="broken"> Barang rusak ?<br>
                  <input type="hidden" name="statusItem" id="statusItem" value="FINE">
										<select id="returnType" name="returnType" class="form-control form-control-sm select2 prodcode">
											<option value="" selected disabled>=== Pilih Tipe Pengembalian ===</option>
											<option value="SB">Stock Baru</option>
											{{-- <option value="SL">Stock Lama</option> --}}
										</select>
									</div>
                  </div>
							</div>

                            <!-- form for 'stok lama'  -->
                            <div class="row formSL d-none">
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label>Nama Barang</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="hidden" name="itemIdSL" id="itemIdSL">
                                        <input type="text" name="itemNameSL" class="form-control form-control-sm" id="itemNameSL">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label>Kode Produksi</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" name="prodCodeSL" class="form-control form-control-sm" id="prodCodeSL" style="text-transform: uppercase;">
                                    </div>
                                </div>
                            </div>
                            <div class="row formSL d-none">
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label>Jumlah Return</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" name="qtyReturnSL" class="form-control form-control-sm digits" id="qtyReturnSL">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label>Harga per Item</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" name="itemPriceSL" class="form-control form-control-sm rupiah" id="itemPriceSL">
                                    </div>
                                </div>
                            </div>
                            <div class="row formSL d-none">
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label>Jenis Penggantian</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <select class="form-control select2" name="typeSL" id="typeSL">
                                            <option value="" selected disabled>=== Pilih Jenis Penggantian ===</option>
                                            <option value="PN">Potong Nota Order Selanjutnya</option>
                                            <option value="GB">Ganti Barang</option>
                                            <!-- <option value="GU">Ganti Uang</option> -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- form for 'stock baru' -->
                            <input type="hidden" name="itemId" value="" id="itemId">
                            <div class="row formSB">
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label>Kode Produksi</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <select name="kodeproduksi" id="kodeproduksi" class="form-control form-control-sm select2 prodcode">
                                            <option value="" selected disabled>=== Pilih Kode Produksi ===</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

							<!-- detail penjualan -->
							<div class="d-none" id="div3">
								<section>
                                    <div class="row">
                                        <div class="col-md-2 col-sm-6 col-12">
                                            <label>Nama Barang</label>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                <input type="text" readonly name="item" class="form-control" id="item" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-12">
                                            <label>Qty Barang</label>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                <input type="text" style="text-align:right;" name="qty" class="form-control digits" id="qty" value="" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-12">
                                            <label>Harga per Item</label>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                <input type="text" name="itemPriceSB" class="form-control rupiah" id="itemPriceSB" value="">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <!-- empty -->
                                        </div>

										<div class="col-md-2 col-sm-6 col-12">
											<label>Jenis Penggantian</label>
										</div>
										<div class="col-md-4 col-sm-6 col-12">
											<div class="form-group">
												<select class="form-control select2" name="type" id="type">
													<option value="" selected disabled>=== Pilih Jenis Penggantian ===</option>
													<option value="PN">Potong Nota Order Selanjutnya</option>
													<option value="PT">Potong Tagihan</option>
													<option value="GB">Ganti Barang</option>
													<!-- <option value="GU">Ganti Uang</option> -->
												</select>
											</div>
										</div>

										<div class="col-md-2 col-sm-6 col-12">
											<label>Qty return</label>
										</div>
										<div class="col-md-4 col-sm-6 col-12">
											<div class="form-group">
												<input type="hidden" id="itemPrice" name="itemPrice" class="rupiah">
												<input type="text" style="text-align:right;" name="qtyReturn" class="form-control digits" id="qtyReturn" value="">
											</div>
										</div>

										<hr>
									</div>
								</section>
							</div>
                            <div class="row">

                                <div class="col-md-2 col-sm-6 col-xs-12 detailGB d-none">
                                    <label>Total Nilai Pengganti</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12 detailGB d-none">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm rupiah" name="subsValue" id="subsValue" value="Rp. 0" readonly>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12 detailGB d-none">
                                    <label>Total Nilai Return</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12 detailGB d-none">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm rupiah" name="returnValue" id="returnValue" value="Rp. 0" readonly>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 text-info detailGB d-none container">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-hover table-striped diplay nowrap w-100" style="width: 100%;" id="table_gantibarang">
                                            <thead class="bg-primary">
                                                <tr>
                                                    <th>Kode/Nama Barang</th>
                                                    <th width="10%">Satuan</th>
                                                    <th width="10%">Jumlah</th>
                                                    <!-- <th width="15%">Kode Produksi</th> -->
                                                    <th width="13%">Harga Satuan</th>
                                                    <th width="15%">Sub Total</th>
                                                    <th width="5%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="idItem[]" class="itemid">
                                                        <input type="hidden" name="kode[]" class="kode">
                                                        <input type="hidden" name="idStock[]" class="idStock">
                                                        <input type="text" name="barang[]" class="form-control form-control-sm barang" autocomplete="off">
                                                    </td>
                                                    <td><select name="satuan[]" class="form-control form-control-sm select2 satuan">
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="jumlah[]" min="0" class="form-control form-control-sm jumlah" value="0" readonly>
                                                </td>
                                                <!-- <td>
                                                    <button class="btn btn-primary btnCodeProd btn-sm rounded" type="button"><i class="fa fa-plus"></i> kode produksi </button>
                                                </td> -->
                                                <td>
                                                    <input type="text" name="harga[]" class="form-control form-control-sm rupiah harga">
                                                    <p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;"> Harga tidak ditemukan!</p>
                                                </td>
                                                <td>
                                                    <input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="Rp. 0" readonly>
                                                    <input type="hidden" name="sbtotal[]" class="sbtotal">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success rounded-circle btn-addRow">
                                                        <i class="fa fa-plus"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <!-- end: detail Ganti Barang -->

																{{-- Potong Tagihan --}}
																<div class="col-md-2 col-sm-6 col-12 d-none" id="lblNota">
																		<label>Nota </label>
																</div>
																<div class="col-md-10 col-sm-6 col-12  d-none" id="choiceNota" >
																		<div class="form-group">
																				<select class="form-control select2 choiceNota d-none" id="nota" name="nota">
																					<option value="" selected disabled>=== Pilih Nota ===</option>

																				</select>
																		</div>
																</div>
																{{-- End Potong Tagihan --}}
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label>Keterangan </label>
                                </div>
                                <div class="col-md-10 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="keterangan" name="keterangan">
                                    </div>
                                </div>



                            </div>

						</form>
					</div>
                    <div class="card-footer text-right">
                    	<button class="btn btn-primary" type="button" id="simpan">Simpan</button>
                    	<a href="{{ route('return.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>

			</div>

		</div>

	</section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
	var idStock = [];
	var idItem = [];
	var namaItem = null;
	var kode = null;
	var idxBarang = 0;
	var icode = [];
	var checkitem = null;

	$(document).ready(function() {
		$('#supplier').on('select2:select', function() {
			$('#kodeproduksi').attr('disabled', false);
      $('#div3').addClass('d-none');
			$('#returnType').attr('disabled', false);
      $('#returnType').val('').trigger('change.select2');
      $('#returnType').select2('open');
		});
        $('#brokenItemToggle').on('click', function() {
            let checkedIt = $(this).prop('checked');
            if (checkedIt === true) {
                $('#statusItem').val('BROKEN');
            }
            else {
                $('#statusItem').val('FINE');
            }
            $('#returnType').val('SB').trigger('change.select2');
            $('#returnType').trigger('select2:select');
        });
        $('#returnType').on('select2:select', function() {
            if ($(this).val() == 'SL') {
                $('#type').val('').trigger('change');
                $('#typeSL').val('').trigger('change');
                $('.formSB').addClass('d-none');
                $('#div3').addClass('d-none');
                $('#lblNota').addClass('d-none');
                $('.detailGB').addClass('d-none');
                $('#choiceNota').addClass('d-none');
                $('.formSL').removeClass('d-none');
            }
            else if ($(this).val() == 'SB') {
                $('#type').val('').trigger('change');
                $('#typeSL').val('').trigger('change');
                $('.formSB').removeClass('d-none');
                $('.detailGB').addClass('d-none');
                $('#choiceNota').addClass('d-none');
                $('#lblNota').addClass('d-none');
                $('.formSL').addClass('d-none');
                getProdCode();
            }
        });
        // form SL
        $("#itemNameSL").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ route('return.findAllItem') }}",
                    data: {
                        supplier: $('#supplier').val(),
                        term: $("#itemNameSL").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            select: function (event, data) {
                $('#itemIdSL').val(data.item.id);
            }
        });
        // calculate total nilai return
    $('#qtyReturnSL').on('keyup', function() {
      let qtyReturn = $('#qtyReturnSL').val();
			let itemPrice = parseFloat($('#itemPriceSL').val());
			let totalReturn = qtyReturn * itemPrice;
			$('#returnValue').val(totalReturn);
        });
        $('#itemPriceSL').on('keyup', function() {
            $('#qtyReturnSL').trigger('keyup');
        });
        $('#typeSL').on('select2:select', function() {
					if ($(this).val() == 'GB') {
						$('.detailGB').removeClass('d-none');
						$('#choiceNota').addClass('d-none');
						$('#lblNota').addClass('d-none');
					}else {
						$('#choiceNota').addClass('d-none');
						$('#lblNota').addClass('d-none');
						$('.detailGB').addClass('d-none');
					}
        });

        // form SB
		$('#kodeproduksi').on('select2:select', function() {
            $('#type').val('').trigger('change');
            $('#typeSL').val('').trigger('change');

            let itemId = $(this).find('option:selected').data('itemid');
            $('#itemId').val(itemId);
            getDataStock();
		});
        $('#itemPriceSB').on('keyup', function() {
            $('#qtyReturn').trigger('keyup');
        });
		$('#qtyReturn').on('keyup', function(){
			let qtyReturn = $('#qtyReturn').val();
			let batas = $('#qty').val();

			batas = batas.replace( /\[\d+\]/g, '');
			if (parseFloat(qtyReturn) > parseFloat(batas)) {
				messageWarning('Info', 'Jumlah Return tidak boleh melebihi Jumlah penjualan');
				$('#qtyReturn').val(batas);
			}
			else if (parseFloat(qtyReturn) < 0 || qtyReturn == '') {
				messageWarning('Info', 'Jumlah Return tidak boleh kurang dari 0');
				$('#qtyReturn').val(0);
			}
			// calculate total nilai return
      qtyReturn = $('#qtyReturn').val();
			let itemPrice = parseFloat($('#itemPriceSB').val());
			let totalReturn = qtyReturn * itemPrice;

			$('#returnValue').val(totalReturn);
		});
		$('#type').on('select2:select', function() {
			if ($(this).val() == 'GB') {
				$('.detailGB').removeClass('d-none');
				$('#choiceNota').addClass('d-none');
				$('#lblNota').addClass('d-none');

			}else if ($(this).val() == 'PT') {
				$('#choiceNota').removeClass('d-none');
				$('#lblNota').removeClass('d-none');
				$('.detailGB').addClass('d-none');
				getNota();
			}
			else {
				$('#choiceNota').addClass('d-none');
				$('#lblNota').addClass('d-none');
				$('.detailGB').addClass('d-none');
			}
		});
		// append a new row to insert more items
		$('.btn-addRow').on('click', function () {
			addRow();
		});

		// $('#qtyGB').on('keyup', function() {
		// 	if (parseInt($(this).val()) > parseInt($('#qtyReturn').val())) {
		// 		messageWarning('Perhatian', 'Jumlah pengganti tidak boleh melebihi jumlah return');
		// 		$(this).val($('#qtyReturn').val());
		// 	}
		// 	else if(parseInt($(this).val()) < 0) {
		// 		messageWarning('Perhatian', 'Jumlah pengganti tidak boleh lebih kecil dari 0');
		// 		$(this).val(0);
		// 	}
		// });
		$('#simpan').on('click', function(){
			confirmStore();
		});
		// re-init events for some class or id
		getEventsReady();
		getSupplier();
	});

// Start: script for 'ganti barang' =========================================
	function addRow() {
		var row = '';
		let harga = '';
		row = '<tr>' +
    		'<td><input type="text" name="barang[]" class="form-control form-control-sm barang" autocomplete="off"><input type="hidden" name="idItem[]" class="itemid"><input type="hidden" name="kode[]" class="kode"><input type="hidden" name="idStock[]" class="idStock"></td>' +
    		'<td>' +
    		'<select name="satuan[]" class="form-control form-control-sm select2 satuan">' +
    		'</select>' +
    		'</td>' +
    		'<td><input type="number" name="jumlah[]" min="0" class="form-control form-control-sm jumlah" value="0" readonly></td>';
    		// '<td><button class="btn btn-primary btnCodeProd btn-sm rounded" type="button"><i class="fa fa-plus"></i> kode produksi</button></td>';
		harga = '<td><input type="text" name="harga[]" class="form-control form-control-sm rupiah harga"><p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p></td>';
		row = row + harga + '<td><input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="Rp. 0" readonly><input type="hidden" name="sbtotal[]" class="sbtotal"></td>' +
    		'<td>' +
    		'<button class="btn btn-danger btn-hapus btn-sm" type="button">' +
    		'<i class="fa fa-remove" aria-hidden="true"></i>' +
    		'</button>' +
    		'</td>' +
    		'</tr>';
		$('#table_gantibarang tbody').append(row);
		// clone modal-code-production and insert new one
		$('#modalCodeProdBase').clone().prop('id', 'modalCodeProd').addClass('modalCodeProd').insertAfter($('.modalCodeProd').last());
		// re-init events for some class or id
		getEventsReady();
	}
	// re-init events for some class or id
	function getEventsReady()
	{
		$(".satuan").off();
		$('.btn-hapus').off();
		// $('.btnCodeProd').off();
		$('.btnAddProdCode').off();
		$('.btnRemoveProdCode').off();
		$('.qtyProdCode').off();

		$('.barang').on('click', function (e) {
			idxBarang = $('.barang').index(this);
			setItemAutocomplete();
		});
		$('.barang').on('keyup', function (evt) {
			idxBarang = $('.barang').index(this);
			if (evt.which == 8 || evt.which == 46) {
				$(".itemid").eq(idxBarang).val('');
				$(".kode").eq(idxBarang).val('');
				$(".idStock").eq(idxBarang).val('');
				setItemAutocomplete();
				if ($(".itemid").eq(idxBarang).val() == "") {
					$(".jumlah").eq(idxBarang).val(0);
					$(".harga").eq(idxBarang).val("Rp. 0");
					$(".subtotal").eq(idxBarang).val("Rp. 0");
					$(".jumlah").eq(idxBarang).attr("readonly", true);
					$(".satuan").eq(idxBarang).find('option').remove();
					updateTotalSubstitute();
				} else {
					$(".jumlah").eq(idxBarang).val(0);
					$(".harga").eq(idxBarang).val("Rp. 0");
					$(".subtotal").eq(idxBarang).val("Rp. 0");
					$(".jumlah").eq(idxBarang).attr("readonly", false);
					updateTotalSubstitute();
				}
			} else if (evt.which <= 90 && evt.which >= 48) {
				$(".itemid").eq(idxBarang).val('');
				$(".kode").eq(idxBarang).val('');
				$(".idStock").eq(idxBarang).val('');
				setItemAutocomplete();
				if ($(".itemid").eq(idxBarang).val() == "") {
					$(".jumlah").eq(idxBarang).val(0);
					$(".harga").eq(idxBarang).val("Rp. 0");
					$(".jumlah").eq(idxBarang).attr("readonly", true);
					$(".satuan").eq(idxBarang).find('option').remove();
					updateTotalSubstitute();
				} else {
					$(".jumlah").eq(idxBarang).val(0);
					$(".harga").eq(idxBarang).val("Rp. 0");
					$(".jumlah").eq(idxBarang).attr("readonly", false);
					updateTotalSubstitute();
				}
			}
		});
		// remove a row from table
		$('.btn-hapus').on('click', function () {
			// get index of clicked element and delete a production-code-modal
			idxBarang = $('.btnRemoveItem').index(this);
			$('.modalCodeProd').eq(idxBarang).remove();
			$(this).parents('tr').remove();
			updateTotalSubstitute();
			setItemAutocomplete();
		});
		// // event to show modal to display list of code-production
		// $('.btnCodeProd').on('click', function () {
		// 	idxBarang = $('.btnCodeProd').index(this);
		// 	// get unit-cmp from selected unit
		// 	let unitCmp = parseInt($('.satuan').eq(idxBarang).find('option:selected').data('unitcmp')) || 0;
		// 	let qty = parseInt($('.jumlah').eq(idxBarang).val()) || 0;
		// 	let qtyUnit = qty * unitCmp;
		// 	// pass qtyUnit to modal
		// 	$('.modalCodeProd').eq(idxBarang).find('.QtyH').val(qtyUnit);
		// 	$('.modalCodeProd').eq(idxBarang).find('.usedUnit').val($('.satuan').eq(idxBarang).find('option:first-child').text());
		// 	calculateProdCodeQty();
		// 	$('.modalCodeProd').eq(idxBarang).modal('show');
		// });
		// // event to add more row to insert production-code
		// $('.btnAddProdCode').on('click', function () {
		// 	prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]"></input></td>';
		// 	qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="0"></input></td>';
		// 	action = '<td><button class="btn btn-danger btnRemoveProdCode btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
		// 	listProdCode = '<tr>' + prodCode + qtyProdCode + action + '</tr>';
		// 	// idxBarang is referenced from btnCodeProd above
		// 	// $(listProdCode).insertBefore($('.modalCodeProd:eq('+ idxBarang +')').find('.table_listcodeprod .rowBtnAdd'));
		// 	$('.modalCodeProd:eq(' + idxBarang + ')').find('.table_listcodeprod').append(listProdCode);
		// 	getEventsReady();
		// });
		// // event to remove an prod-code from table_listcodeprod
		// $('.btnRemoveProdCode').on('click', function () {
		// 	idxProdCode = $('.btnRemoveProdCode').index(this);
		// 	$(this).parents('tr').remove();
		// 	calculateProdCodeQty();
		// });
		// update total qty without production-code
		$('.qtyProdCode').on('keyup', function () {
			idxProdCode = $('.qtyProdCode').index(this);
			calculateProdCodeQty();
		});
		$('.select2').select2({
			theme: "bootstrap",
			dropdownAutoWidth: true,
			width: '100%'
		});
		// inputmask rupiah without decimal
		$('.rupiah').inputmask("currency", {
			radixPoint: ",",
			groupSeparator: ".",
			digits: 0,
			autoGroup: true,
			prefix: ' Rp ', //Space after $, this will not truncate the first character.
			rightAlign: true,
			autoUnmask: true,
			nullable: false,
			// unmaskAsNumber: true,
		});
		// inputmask-digits
		$('.digits').inputmask("currency", {
			radixPoint: ",",
			groupSeparator: ".",
			digits: 0,
			autoGroup: true,
			prefix: '', //Space after $, this will not truncate the first character.
			rightAlign: true,
			autoUnmask: true,
			nullable: false,
			// unmaskAsNumber: true,
		});
		$('.harga').on('keyup', function (evt) {
			let idx = $('.harga').index(this);
			let harga = $('.harga').eq(idx).val();
			let jumlah = $('.jumlah').eq(idx).val();
			let subharga = (parseInt(convertToAngka(harga))) * parseInt(jumlah);
			$('.subtotal').eq(idx).val(convertToRupiah(subharga));
			updateTotalSubstitute();
		});
		changeUnit();
		changeQtyItem();
		updateTotalSubstitute();
		setItemAutocomplete();
	}
	// set autocomplete to find item
	function setItemAutocomplete() {
		var inputs = document.getElementsByClassName('kode'),
		code = [].map.call(inputs, function (input) {
			return input.value.toString();
		});

		for (var i = 0; i < code.length; i++) {
			if (code[i] != "") {
				icode.push(code[i]);
			}
		}

		var inpItemid = document.getElementsByClassName('itemid'),
		item = [].map.call(inpItemid, function (input) {
			return input.value;
		});

		let supplierCode = $('#supplier').val();

		$(".barang").eq(idxBarang).autocomplete({
			source: function (request, response) {
				$.ajax({
					url: "{{ route('return.findItem') }}",
					data: {
						idItem: item,
						supplier: supplierCode,
						term: $(".barang").eq(idxBarang).val()
					},
					success: function (data) {
						response(data);
					}
				});
			},
			minLength: 1,
			select: function (event, data) {
				getUnit(data.item);
			}
		});
	}
	// get item-unit
	function getUnit(info) {
		idStock = info.stock
		idItem = info.data.i_id;
		namaItem = info.data.i_name;
		kode = info.data.i_code;
		$(".kode").eq(idxBarang).val(kode);
		$(".itemid").eq(idxBarang).val(idItem);
		$(".idStock").eq(idxBarang).val(idStock);
		setItemAutocomplete();
		$.ajax({
			url: baseUrl +'/produksi/returnproduksi/get-unit/'+ idItem,
			type: 'GET',
			success: function (resp) {
				$(".satuan").eq(idxBarang).find('option').remove();
				var option = '';
				option += '<option value="' + resp.i_unit1 + '" data-unitcmp="' + resp.i_unitcompare1 + '">' + resp.get_unit1.u_name + '</option>';
				if (resp.i_unit2 != null && resp.i_unit2 != resp.i_unit1) {
					option += '<option value="' + resp.i_unit2 + '" data-unitcmp="' + resp.i_unitcompare2 + '">' + resp.get_unit2.u_name + '</option>';
				}
				if (resp.i_unit3 != null && resp.i_unit3 != resp.i_unit2 && resp.i_unit3 != resp.i_unit1) {
					option += '<option value="' + resp.i_unit3 + '" data-unitcmp="' + resp.i_unitcompare3 + '">' + resp.get_unit3.u_name + '</option>';
				}
				$(".satuan").eq(idxBarang).append(option);
				if ($(".itemid").eq(idxBarang).val() == "") {
					$(".jumlah").eq(idxBarang).attr("readonly", true);
					$(".satuan").eq(idxBarang).find('option').remove();
				} else {
					$(".jumlah").eq(idxBarang).attr("readonly", false);
				}
			}
		});
	}
	// check item stock
	function changeUnit() {
		// $(".satuan").on("change", function (evt) {
		// 	loadingShow();
		// 	var idx = $('.satuan').index(this);
		// 	var jumlah = $('.jumlah').eq(idx).val();
		// 	if (jumlah < 0) {
        //         $(".jumlah").eq(idx).val(0);
		// 	}
        //     $(".jumlah").eq(idx).trigger('input');
		// 	// // check stock using marketing controller
		// 	// axios.get(baseUrl + '/produksi/returnproduksi/cek-stok/' + $(".idStock").eq(idx).val() + '/' + $(".itemid").eq(idx).val() + '/' + $(".satuan").eq(idx).val() + '/' + jumlah)
		// 	// .then(function (resp) {
		// 	// 	loadingHide();
		// 	// 	$(".jumlah").eq(idx).val(resp.data);
		// 	// 	// trigger on-input 'jumlah'
        //     //
		// 	// })
		// 	// .catch(function (error) {
		// 	// 	loadingHide();
		// 	// 	messageWarning("Error", error);
		// 	// })
		// });
	}
	// trigger qty and get price
	function changeQtyItem() {
		// set-off first to prevent duplicate request from the previous item
		$(".jumlah").off();
		$(".jumlah").on('input', function (evt) {
			var idx = $('.jumlah').index(this);
			var jumlah = $('.jumlah').eq(idx).val();
			if (jumlah == "") {
				jumlah = null;
			}
			// trigger price
			$(".harga").trigger('keyup');
		})
	}
	// update total return value
	function updateTotalSubstitute() {
		var total = 0;

		var inputs = document.getElementsByClassName('subtotal'),
		subtotal = [].map.call(inputs, function (input) {
			return input.value;
		});

		for (var i = 0; i < subtotal.length; i++) {
			total += parseInt(subtotal[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", ""));
		}
		// $("#tot_hrg").val(total);
		if (isNaN(total)) {
			total = 0;
		}
		$("#subsValue").val(convertToRupiah(total));
	}
	// check production code qty each item
	function calculateProdCodeQty() {
		let QtyH = parseInt($('.modalCodeProd').eq(idxBarang).find('.QtyH').val());
		let qtyWithProdCode = getQtyWithProdCode();
		let restQty = QtyH - qtyWithProdCode;

		if (restQty < 0) {
			$(':focus').val(0);
			qtyWithProdCode = getQtyWithProdCode();
			restQty = QtyH - qtyWithProdCode;
			$('.modalCodeProd').eq(idxBarang).find('.restQty').val(restQty);
			messageWarning('Perhatian', 'Jumlah item untuk penetapan kode produksi tidak boleh melebihi jumlah item yang ada !');
		} else {
			$('.modalCodeProd').eq(idxBarang).find('.restQty').val(restQty);
		}
	}
	// get qty item that has set the production-code
	function getQtyWithProdCode() {
		qtyWithProdCode = 0;
		$.each($('.modalCodeProd:eq(' + idxBarang + ') .table_listcodeprod').find('.qtyProdCode'), function (key, val) {
			qtyWithProdCode += parseInt($(this).val());
		});
		return qtyWithProdCode;
	}
	// check if there is any empty value in table
	function validateItemExchange() {
		var inpItemid = document.getElementsByClassName('itemid'),
		item = [].map.call(inpItemid, function (input) {
			return input.value;
		});
		var inpHarga = document.getElementsByClassName('harga'),
		harga = [].map.call(inpHarga, function (input) {
			return input.value;
		});
		var inpJumlah = document.getElementsByClassName('jumlah'),
		jumlah = [].map.call(inpJumlah, function (input) {
			return parseInt(input.value);
		});

		for (var i = 0; i < item.length; i++) {
			if (item[i] == "" || harga[i] == "Rp. 0" || jumlah[i] == 0) {
				return "cek form";
				break;
			} else {
				checkitem = "true";
				continue;
			}
		}
		return checkitem;
	}
// End: script for 'ganti barang' =========================================

	// get supplier
	function getSupplier()
	{
		// let cityId = $('#city').val();
		$.ajax({
			url: "{{ route('return.getSupplier') }}",
			type: "get",
			data:{
				// cityId: cityId
			},
			beforeSend: function () {
				loadingShow();
			},
			success: function (response) {
				$('#supplier').empty();
				if (response.data.length == 0) {
					$("#supplier").append('<option value="" selected disabled>=== Pilih Supplier ===</option>');
				} else {
					$("#supplier").append('<option value="" selected disabled>=== Pilih Supplier ===</option>');
					$.each(response.data, function( key, val ) {
						$("#supplier").append('<option value="'+ val.s_id +'">'+ val.s_company +'</option>');
					});
				}
				$('#supplier').focus();
				$('#supplier').select2('open');
			},
            error: function (err) {
                messageWarning('Error', 'Terjadi kesalahan saat mencari supplier, muat ulang halaman !');
            },
            complete: function () {
                loadingHide();
            }
		});
	}
	// get production-code
	function getProdCode()
	{
		let supplierCode = $('#supplier').val();
    let statusItem = $('#statusItem').val();
		$.ajax({
			url: "{{ route('return.getProdCode') }}",
			type: "get",
			data: {
            itemStatus: statusItem,
						supplierCode: supplierCode
			},
			beforeSend: function () {
			loadingShow();
			},
			success: function (response) {
				// fill kodeproduksi option
				$('#kodeproduksi').empty();
				if (response.length == 0) {
					$("#kodeproduksi").append('<option value="" selected disabled>=== Pilih Kode Produksi ===</option>');
				} else {
					$("#kodeproduksi").append('<option value="" selected disabled>=== Pilih Kode Produksi ===</option>');
					$.each(response, function( key, val ) {
						$("#kodeproduksi").append('<option value="'+ val.sd_code +'" data-itemid="'+ val.get_stock.s_item +'">'+ val.sd_code +'</option>');
					});
				}
				$('#kodeproduksi').focus();
				$('#kodeproduksi').select2('open');
			},
            error: function (err) {
                messageWarning('Error', 'Terjadi kesalahan : '+ err.message);
            },
            complete: function () {
                loadingHide();
            }
		});
	}
	function getDataStock()
	{
		let itemId = $('#itemId').val();
		let prodCode = $("#kodeproduksi").val();
    let agentCode = $('#agent').val();
		let condition = $('#statusItem').val();

		$.ajax({
			url: "{{ route('return.getData') }}",
			data: {
				agentCode: agentCode,
				itemId: itemId,
				prodCode: prodCode,
				condition: condition
			},
			type: 'get',
            beforeSend: function(){
            loadingShow();
            },
			success: function(resp) {
				$('#item').val(resp.data.get_item.i_name);
				$('#qty').val(resp.data.sd_qty);
                $('#qtyReturn').val(0);
                $('#returnValue').val(0);
                $('#subsValue').val(0);
                $('#keterangan').val('');
				// $('#itemPrice').val(parseFloat(resp.data.get_sales_comp_dt[0].scd_value) - parseFloat(resp.data.get_sales_comp_dt[0].scd_discvalue));
				$('#div3').removeClass('d-none');
			},
			error: function(e) {
				messageWarning('Error', 'Terjadi kesalahan, hubungi teknisi !');
			},
            complete: function(){
                loadingHide();
            }
		});
	}
	// get nota
	function getNota()
	{
		// let cityId = $('#city').val();
		let prod_code = $("#kodeproduksi").val();
		$.ajax({
			url: "{{ route('return.getNota') }}",
			type: "get",
			data: {
				prod_code : prod_code
			},
			beforeSend: function () {
				loadingShow();
			},
			success: function (response) {
				$('#nota').empty();
				if (response.data.length == 0) {
					$("#nota").append('<option value="" selected disabled>=== Pilih Nota ===</option>');
				} else {
					$("#nota").append('<option value="" selected disabled>=== Pilih Nota ===</option>');
					$.each(response.data, function( key, val ) {
						$("#nota").append('<option value="'+ val.po_nota +'">'+ val.po_nota +'</option>');
					});
				}
				$('#nota').focus();
				$('#nota').select2('open');
			},
            error: function (err) {
                messageWarning('Error', 'Terjadi kesalahan saat mencari Nota, muat ulang halaman !');
            },
            complete: function () {
                loadingHide();
            }
		});
	}

	// set confirm before send data to controller
	function confirmStore()
	{
        let type = '';
        if ($('#returnType').val() == 'SB') {
            type = $('#type').val();
        } else if ($('#returnType').val() == 'SL') {
            type = $('#typeSL').val();
        }

		if (type == 'GB') {
			if (validateItemExchange() == "cek form") {
				messageWarning('Peringatan', 'Data item pengganti masih ada yang kosong !');
				return false;
			}
		}

		$.confirm({
			animation: 'RotateY',
			closeAnimation: 'scale',
			animationBounce: 1.5,
			icon: 'fa fa-exclamation-triangle',
			title: 'Konfirmasi!',
			content: 'Apakah anda yakin akan menyimpan data return penjualan ini ?',
			theme: 'disable',
			buttons: {
				info: {
					btnClass: 'btn-blue',
					text: 'Ya',
					action: function () {
						store();
					}
				},
				cancel: {
					text: 'Tidak',
					action: function () {
						// tutup confirm
					}
				}
			}
		});
	}
	// store data to db
	function store()
	{
		let data = $('#formdata').serialize();
		// // get list of production-code
		// $.each($('.table_listcodeprod'), function (key, val) {
		// 	// get length of production-code each items
		// 	let prodCodeLength = $('.table_listcodeprod:eq(' + key + ') :input.qtyProdCode').length;
		// 	$('.modalCodeProd:eq(' + key + ')').find('.prodcode-length').val(prodCodeLength);
		// 	inputs = $('.table_listcodeprod:eq(' + key + ') :input').serialize();
		// 	data = data + '&' + inputs;
		// });
		$.ajax({
			type: 'post',
			data: data,
			dataType: 'JSON',
			url: "{{ route('return.store') }}",
            beforeSend: function() {
                loadingShow();
            },
			success : function(response){
				if (response.status == 'berhasil') {
					messageSuccess('Info', 'Berhasil Disimpan');
                    location.reload();
				}
				else {
					messageWarning('Info', 'Gagal Disimpan: '+ response.message);
				}
			},
			error: function(e) {
				messageWarning('Info', 'Terjadi kesalahan : '+ e.message);
			},
            complete: function () {
                loadingHide();
            }
		});
	}

    {{--
    // <script type="text/javascript">
    //     var tbl_rp, tbl_gu, tbl_pn;
    //     $(document).ready(function(){
    //
    //         tbl_rp = $('#table_rp').DataTable();
    //         tbl_gu = $('#tabel_gu').DataTable();
    //         tbl_pn = $('#tabel_pn').DataTable();
    //
    //         $('#searchBy').on('change', function() {
    //             if ($(this).val() == 'nota')
    //             {
    //                 $('#q_nota').clone().attr('type', 'text').insertAfter('#q_nota').prev().remove();
    //                 $('#q_prodcode').clone().attr('type', 'hidden').insertAfter('#q_prodcode').prev().remove();
    //                 $('#btn_searchsupplier').attr('disabled', false);
    //                 $('#btn_searchsupplier').removeClass('d-none');
    //                 $('#searchLabel').text('No Nota');
    //                 $('#q_nota').val('');
    //                 $('#q_prodcode').val('');
    //             }
    //             else if ($(this).val() == 'kodeproduksi')
    //             {
    //                 $('#q_nota').clone().attr('type', 'hidden').insertAfter('#q_nota').prev().remove();
    //                 $('#q_prodcode').clone().attr('type', 'text').insertAfter('#q_prodcode').prev().remove();
    //                 $('#btn_searchsupplier').attr('disabled', true);
    //                 $('#btn_searchsupplier').addClass('d-none');
    //                 $('#searchLabel').text('Kode Produksi');
    //                 $('#q_nota').val('');
    //                 $('#q_prodcode').val('');
    //             }
    //             // re-initialize event (keyup or another)
    //             initEvents();
    //         });
    //
    //         if ($("#q_idpo").val() == "") {
    //             $("#go").attr("disabled", true);
    //         }
    //         else {
    //             $("#go").attr("disabled", false);
    //         }
    //
    //         // init event keyup for autocomplete
    //         initEvents();
    //
    //         $("#go").on("click", function() {
    //             searchItems();
    //         });
    //         $("#supplier").on("keyup", function () {
    //             $("#idSupplier").val('');
    //         });
    //         $("#supplier").autocomplete({
    //             source: function (request, response) {
    //                 $.ajax({
    //                     url: '{{ route('return.carisupplier') }}',
    //                     data: {
    //                         term: $("#supplier").val()
    //                     },
    //                     success: function (data) {
    //                         response(data);
    //                     }
    //                 });
    //             },
    //             minLength: 1,
    //             select: function (event, data) {
    //                 $("#idSupplier").val(data.item.id);
    //             }
    //         });
    //
    //         $("#btn_searchNotainTbl").on("click", function (evt) {
    //             evt.preventDefault();
    //             var dateStart = $("#dateStart").val(), dateEnd = $("#dateEnd").val(), supplier = $("#idSupplier").val();
    //             if (dateStart == "" && dateEnd == "" && supplier == "") {
    //                 messageWarning("Peringatan", "Masukkan parameter pencarian");
    //                 $("#dateStart").focus();
    //             } else {
    //                 loadingShow();
    //                 if ($.fn.DataTable.isDataTable("#tbl_nota")) {
    //                     $('#tbl_nota').DataTable().clear().destroy();
    //                 }
    //                 $('#tbl_nota').DataTable({
    //                     responsive: true,
    //                     serverSide: true,
    //                     processing: true,
    //                     ajax: {
    //                         url: "{{ route('return.getnota') }}",
    //                         data: {
    //                             dateStart: dateStart,
    //                             dateEnd: dateEnd,
    //                             supplier: supplier
    //                         },
    //                         type: "get"
    //                     },
    //                     columns: [
    //                         {data: 'supplier'},
    //                         {data: 'tanggal'},
    //                         {data: 'nota'},
    //                         {data: 'action'}
    //                     ],
    //                     drawCallback: function( settings ) {
    //                         loadingHide();
    //                     }
    //                 });
    //             }
    //         })
    //         $("#btn_searchsupplier").on("click", function (evt) {
    //             evt.preventDefault();
    //             console.log('abc');
    //             appendModalSearchSupplier();
    //         })
    //
    //         $("#formCreateReturn").on("submit", function (evt) {
    //             evt.preventDefault();
    //             if ($("#qty_return").val() == "" || $("#qty_return").val() == 0) {
    //                 messageWarning("Peringatan", "Masukkan qty return");
    //                 $("#qty_return").focus();
    //             } else if ($("#satuan_return").val() == "") {
    //                 messageWarning("Peringatan", "Pilih satuan barang");
    //                 $("#satuan_return").focus();
    //             } else {
    //                 $.confirm({
    //                     animation: 'RotateY',
    //                     closeAnimation: 'scale',
    //                     animationBounce: 1.5,
    //                     icon: 'fa fa-exclamation-triangle',
    //                     title: 'Konfirmasi!',
    //                     content: 'Apakah anda yakin akan membuat return produksi untuk barang ini?',
    //                     theme: 'disable',
    //                     buttons: {
    //                         info: {
    //                             btnClass: 'btn-blue',
    //                             text: 'Ya',
    //                             action: function () {
    //                                 createReturn();
    //                             }
    //                         },
    //                         cancel: {
    //                             text: 'Tidak',
    //                             action: function () {
    //                                 // tutup confirm
    //                             }
    //                         }
    //                     }
    //                 });
    //             }
    //         })
    //
    //         $('#header-metodereturn').change(function(){
    //             var ini, potong_nota, ganti_uang, ganti_barang;
    //             ini             = $(this).val();
    //             potong_nota     = $('#potong_nota');
    //             ganti_uang     = $('#ganti_uang');
    //             ganti_barang     = $('#ganti_barang');
    //             if (ini === 'GB') {
    //                 potong_nota.addClass('d-none');
    //                 ganti_uang.addClass('d-none');
    //                 ganti_barang.removeClass('d-none');
    //             } else if(ini === 'GU'){
    //                 potong_nota.addClass('d-none');
    //                 ganti_uang.removeClass('d-none');
    //                 ganti_barang.addClass('d-none');
    //             } else if(ini === 'PN'){
    //                 potong_nota.removeClass('d-none');
    //                 ganti_uang.addClass('d-none');
    //                 ganti_barang.addClass('d-none');
    //             }
    //         });
    //
    //         // reset modal-return when modal is hidden
    //         $('#createReturn').on('hidden.bs.modal', function() {
    //             $('#formCreateReturn')[0].reset();
    //         });
    //     });
    //     // init autocomplete for search by nota or prod-code
    //     function initEvents()
    //     {
    //         $("#q_prodcode").off();
    //         $("#q_prodcode").on("keyup", function () {
    //             $("#q_idpo").val('');
    //             $(".table-returnp").addClass('d-none');
    //             if ($("#q_idpo").val() == "") {
    //                 $("#go").attr("disabled", true);
    //             } else {
    //                 $("#go").attr("disabled", false);
    //             }
    //         });
    //         $("#q_prodcode").autocomplete({
    //             source: function (request, response) {
    //                 $.ajax({
    //                     url: "{{ route('return.cariprodkode') }}",
    //                     data: {
    //                         term: $("#q_prodcode").val()
    //                     },
    //                     success: function (data) {
    //                         response(data);
    //                     }
    //                 });
    //             },
    //             minLength: 1,
    //             select: function (event, data) {
    //                 $("#q_idpo").val(data.item.id);
    //                 $('#q_nota').val(data.item.nota);
    //                 $("#q_prodcode").val(data.item.prodCode);
    //                 $("#notaPO").val(data.item.nota);
    //                 $("#prodCode").val(data.item.prodCode);
    //                 searchItems();
    //             }
    //         });
    //
    //         $("#q_nota").off();
    //         $("#q_nota").on("keyup", function () {
    //             $("#q_idpo").val('');
    //             $(".table-returnp").addClass('d-none');
    //             if ($("#q_idpo").val() == "") {
    //                 $("#go").attr("disabled", true);
    //             } else {
    //                 $("#go").attr("disabled", false);
    //             }
    //         });
    //         $("#q_nota").autocomplete({
    //             source: function (request, response) {
    //                 $.ajax({
    //                     url: "{{ route('return.carinota') }}",
    //                     data: {
    //                         term: $("#q_nota").val()
    //                     },
    //                     success: function (data) {
    //                         response(data);
    //                     }
    //                 });
    //             },
    //             minLength: 1,
    //             select: function (event, data) {
    //                 $("#q_idpo").val(data.item.id);
    //                 $('#q_nota').val(data.item.nota);
    //                 $("#q_prodcode").val(data.item.prodCode);
    //                 $("#notaPO").val(data.item.nota);
    //                 $("#prodCode").val(data.item.prodCode);
    //                 searchItems();
    //             }
    //         });
    //     }
    //
    //     function appendModalSearchSupplier()
    //     {
    //         loadingShow();
    //         if ($.fn.DataTable.isDataTable("#tbl_nota")) {
    //             $('#tbl_nota').DataTable().clear().destroy();
    //         }
    //         $('#tbl_nota').DataTable({
    //             responsive: true,
    //             serverSide: true,
    //             processing: true,
    //             ajax: {
    //                 url: "{{ route('return.getnota') }}",
    //                 type: "get"
    //             },
    //             columns: [
    //                 {data: 'supplier'},
    //                 {data: 'tanggal'},
    //                 {data: 'nota'},
    //                 {data: 'action'}
    //             ],
    //             drawCallback: function( settings ) {
    //                 loadingHide();
    //                 $("#search-modal").modal({backdrop: 'static', keyboard: false});
    //             }
    //         });
    //     }
    //
    //     function detail(id)
    //     {
    //         loadingShow();
    //         if ($.fn.DataTable.isDataTable("#tbl_detailnota")) {
    //             $('#tbl_detailnota').DataTable().clear().destroy();
    //         }
    //         $('#tbl_detailnota').DataTable({
    //             responsive: true,
    //             serverSide: true,
    //             processing: true,
    //             ajax: {
    //                 url: "{{ url('/produksi/returnproduksi/detail-nota') }}"+"/"+id,
    //                 type: "get"
    //             },
    //             columns: [
    //                 {data: 'barang'},
    //                 {data: 'qty'},
    //                 {data: 'harga'}
    //             ],
    //             drawCallback: function( settings ) {
    //                 loadingHide();
    //             }
    //         });
    //         $("#detail").modal("show");
    //     }
    //     // apply search nota using modal-search by supplier
    //     function pilih(id, nota)
    //     {
    //         $("#q_nota").val(nota);
    //         $("#q_idpo").val(id);
    //         $("#notaPO").val(nota);
    //         loadingShow();
    //         if ($.fn.DataTable.isDataTable("#table_rp")) {
    //             $('#table_rp').DataTable().clear().destroy();
    //         }
    //         $('#table_rp').DataTable({
    //             responsive: true,
    //             serverSide: true,
    //             processing: true,
    //             ajax: {
    //                 url: "{{ url('/produksi/returnproduksi/cari-barang-po') }}",
    //                 data: {
    //                     id: $("#q_idpo").val(),
    //                     prodCode: $('#q_prodcode').val(),
    //                     nota: $('#q_nota').val(),
    //                     searchBy: $('#searchBy').val()
    //                 },
    //                 type: "get"
    //             },
    //             columns: [
    //                 {data: 'barang'},
    //                 {data: 'qty'},
    //                 {data: 'harga'},
    //                 {data: 'total'},
    //                 {data: 'action'}
    //             ],
    //             drawCallback: function( settings ) {
    //                 loadingHide();
    //                 $(".table-returnp").removeClass('d-none');
    //             }
    //         });
    //         $("#search-modal").modal("hide");
    //         if ($("#q_idpo").val() == "") {
    //             $("#go").attr("disabled", true);
    //         } else {
    //             $("#go").attr("disabled", false);
    //         }
    //     }
    //
    //     // fill list items in dataTable after using search nota or searh prod-code
    //     function searchItems()
    //     {
    //         loadingShow();
    //         if ($.fn.DataTable.isDataTable("#table_rp")) {
    //             $('#table_rp').DataTable().clear().destroy();
    //         }
    //         $('#table_rp').DataTable({
    //             responsive: true,
    //             serverSide: true,
    //             processing: true,
    //             ajax: {
    //                 url: "{{ url('/produksi/returnproduksi/cari-barang-po') }}",
    //                 data: {
    //                     id: $("#q_idpo").val(),
    //                     prodCode: $('#q_prodcode').val(),
    //                     nota: $('#q_nota').val(),
    //                     searchBy: $('#searchBy').val()
    //                 },
    //                 type: "get"
    //             },
    //             columns: [
    //                 {data: 'barang'},
    //                 {data: 'qty'},
    //                 {data: 'harga'},
    //                 {data: 'total'},
    //                 {data: 'action'}
    //             ],
    //             drawCallback: function( settings ) {
    //                 loadingHide();
    //                 $(".table-returnp").removeClass('d-none');
    //             }
    //         });
    //         $("#search-modal").modal("hide");
    //         if ($("#q_idpo").val() == "") {
    //             $("#go").attr("disabled", true);
    //         } else {
    //             $("#go").attr("disabled", false);
    //         }
    //     }
    //     // select an item and show the modal to create return
    //     function selectItem (searchMethod, poid, item, barang, qty, harga, total)
    //     {
    //         console.log(poid, item, barang, qty, harga, total);
    //         loadingShow();
    //         $("#searchMethod").val(searchMethod);
    //         $("#idPO").val(poid);
    //         $("#idItem").val(item);
    //         $("#txt_barang").val(barang);
    //         $("#txt_qty").val(qty);
    //         $("#txt_harga").val(parseFloat(harga));
    //         $("#txt_total").val(parseFloat(total))
    //         axios.get(baseUrl+"/produksi/returnproduksi/set-satuan/"+item)
    //             .then(function (resp) {
    //                 loadingHide();
    //                 $("#satuan_return").find('option').remove();
    //                 var option = '<option value="">Pilih Satuan</option>';
    //                 option += '<option value="'+resp.data.id1+'">'+resp.data.unit1+'</option>';
    //                 if (resp.data.id2 != null && resp.data.id2 != resp.data.id1) {
    //                     option += '<option value="'+resp.data.id2+'">'+resp.data.unit2+'</option>';
    //                 }
    //                 if (resp.data.id3 != null && resp.data.id3 != resp.data.id1) {
    //                     option += '<option value="'+resp.data.id3+'">'+resp.data.unit3+'</option>';
    //                 }
    //                 $("#satuan_return").append(option);
    //                 $("#createReturn").modal({backdrop: 'static', keyboard: false});
    //             })
    //             .catch(function (error) {
    //                 loadingHide();
    //                 messageWarning("Error", error);
    //             })
    //
    //     }
    //     // create return
    //     function createReturn()
    //     {
    //         loadingShow();
    //         axios.post('{{ route('return.add') }}', $("#formCreateReturn").serialize())
    //         .then(function (resp) {
    //             loadingHide();
    //             if (resp.data.status == "Failed") {
    //                 messageWarning("Gagal", resp.data.message);
    //             }
    //             else if (resp.data.status == "Success") {
    //                 messageSuccess("Berhasil", resp.data.message);
    //                 $("#qty_return").val(0);
    //                 $("#note_return").val('');
    //                 $("#createReturn").modal("hide");
    //                 window.open(baseUrl+'/produksi/returnproduksi/nota-return/'+resp.data.id+'/'+resp.data.detail);
    //                 window.location.href = '{{ route("return.index") }}';
    //             }
    //             else {
    //                 messageFailed("Perhatian", resp.data.error);
    //             }
    //         })
    //         .catch(function (error) {
    //             loadingHide();
    //             messageWarning("Error", error);
    //         })
    //     }
    // </script>
    --}}
</script>

@endsection
