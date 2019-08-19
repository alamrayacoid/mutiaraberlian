@extends('main')
@section('tittle')
    Pengelolaan Barang Keluar
@endsection
@section('extra_style')
  <style>
      #table_barangkeluar th { font-size: 13px; }
      #table_barangkeluar td { font-size: 13px; }
      #table_barangkeluar td {
          padding: 5px;
      }
      #table_barangkeluar th {
          padding: 5px;
      }
      div.dt-buttons {
          position: relative;
          float: left;
      }
  </style>
@endsection
@section('content')
<article class="content">
  <div class="title-block text-primary">
    <h1 class="title"> Pengelolaan Barang Keluar </h1>
    <p class="title-description">
      <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
      / <span>Aktivitas Inventory</span>
      / <span class="text-primary font-weight-bold">Pengelolaan Barang Keluar</span>
    </p>
  </div>
  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block">
              <h3 class="title"> Pengelolaan Barang Keluar </h3>
            </div>
            <div class="header-block pull-right">
              {{--<a class="btn btn-primary" href="{{ route('barangkeluar.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>--}}
            </div>
          </div>
          <div class="card-block">
            <section>
              <div class="row">
                <div class="row col-12">
                  <div class="col-4">
                    <div class="input-group input-group-sm input-daterange">
                      <input type="text" class="form-control" id="date_from">
                      <span class="input-group-addon">-</span>
                      <input type="text" class="form-control" id="date_to">
                    </div>
                  </div>
                  <div class="col-4">
                    {{--<input type="text" class="form-control form-control-sm" id="filter_pemilik" placeholder="Pemilik">--}}
                    <select class="select2 form-control form-control-sm" name="pemilik" id="filter_pemilik">
                      <option value="semua" selected>== Semua Pemilik ==</option>
                      @foreach($pemilik as $comp)
                      <option value="{{ $comp->c_id }}">{{ $comp->c_name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-4">
                    {{--<input type="text" class="form-control form-control-sm" id="filter_posisi" placeholder="Posisi">--}}
                    <select class="select2 form-control form-control-sm" name="posisi" id="filter_posisi">
                      <option value="semua" selected>== Semua Posisi ==</option>
                      @foreach($posisi as $position)
                      <option value="{{ $position->c_id }}">{{ $position->c_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="row col-12">
                  <div class="col-4">
                    {{--<input type="text" class="form-control form-control-sm" id="filter_produk" placeholder="Produk">--}}
                    <select class="select2 form-control form-control-sm" name="produk" id="filter_produk">
                      <option value="semua" selected>== Semua Produk ==</option>
                      @foreach($produk as $item)
                      <option value="{{ $item->i_id }}">{{ $item->i_code }}
                      - {{ $item->i_name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-4">
                      <!-- <p>Kosongkan field jika ingin menampilkan semua hasil pencarian</p> -->
                      <select class="select2 form-control form-control-sm" name="kodeproduksi" id="filter_kodeproduksi">
                          <option value="semua" selected>== Semua Kode Produksi ==</option>
                          @foreach($kodeproduksi as $kp)
                              <option value="{{ $kp->sd_code }}">{{ $kp->sd_code }}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="col-4 input-group">
                    <select class="form-control form-control-sm" id="filter_mutcat" name="mutcat">
                      <option value="semua">== Semua Keterangan ==</option>
                      @foreach($mutcat as $keterangan)
                      <option value="{{ $keterangan->m_id }}">{{ $keterangan->m_name }}</option>
                      @endforeach
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-secondary btn-sm" type="button" id="btn_search_date"><i class="fa fa-search"></i></button>
                      <button class="btn btn-primary btn-sm" type="button" id="btn_refresh_date"><i class="fa fa-refresh"></i></button>
                    </div>
                  </div>
                  <!-- <div class="col-4">
                    <p>Kosongkan field jika ingin menampilkan semua hasil pencarian</p>
                  </div> -->
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_barangkeluar">
                  <thead class="bg-primary">
                    <tr>
                      <th>Tanggal Masuk</th>
                      <th>Pemilik Barang</th>
                      <th>Lokasi Keluar</th>
                      <th>Nama</th>
                      <th>Jumlah</th>
                      <th>Keterangan</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </section>
</article>
{{-- Modal Detail --}}
<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="codeB">Kode Barang</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm w-100 bg-light" disabled value="" readonly id="codeB">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="namaB">Nama Barang</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm w-100 bg-light" disabled value="" readonly id="namaB">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="pemilik">Pemilik Barang</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm w-100 bg-light" disabled style="width: 100%;" value="" readonly id="pemilikB">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="posisiB">Posisi Barang</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm w-100 bg-light" disabled value="" readonly id="posisiB">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="qtyB">Jumlah Barang</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm w-100 bg-light" disabled style="width: 100%;" value="" readonly id="qtyB">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="hppB">HPP Barang</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm w-100 bg-light" disabled value="" readonly id="hppB">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="satuanB">Satuan Barang</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm w-100 bg-light" disabled value="" readonly id="satuanB">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="notaB">Nota Barang</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm w-100 bg-light" disabled value="" readonly id="notaB">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-6">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered table-hover" id="table_detail" style="margin-top: 0px !important;">
                                    <thead>
                                        <tr>
                                            <th>Kode Produksi</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_script')
  <script type="text/javascript">
      $(document).ready(function () {
          const cur_date = new Date();
          const first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
          const last_day = new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
          $('#date_from').datepicker('setDate', first_day);
          $('#date_to').datepicker('setDate', last_day);

          $('#date_from').on('change', function () {
              TableBarangKeluar();
          });
          $('#date_to').on('change', function () {
              TableBarangKeluar();
          });
          $('#btn_search_date').on('click', function () {
              TableBarangKeluar();
          });
          $('#btn_refresh_date').on('click', function () {
              $('#date_from').datepicker('setDate', first_day);
              $('#date_to').datepicker('setDate', last_day);
          });

          TableBarangKeluar();
      });

      // set header token for ajax request
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      // /* Fungsi formatRupiah */
      // function formatRupiah(angka)
      // {
      // 	var number_string = angka.replace(/[^.\d]/g, '').toString();
      // 	split = number_string.split(',');
      // 	sisa = split[0].length % 3;
      // 	rupiah = split[0].substr(0, sisa);
      // 	ribuan = split[0].substr(sisa).match(/\d{3}/gi);
      // 	// tambahkan titik jika yang di input sudah menjadi angka ribuan
      // 	if(ribuan){
      // 		separator = sisa ? '.' : '';
      // 		rupiah += separator + ribuan.join('.');
      // 	}
      // 	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
      // 	return rupiah;
      // }

      var tb_barangkeluar;

      function TableBarangKeluar() {
          $('#table_barangkeluar').dataTable().fnDestroy();
          tb_barangkeluar = $('#table_barangkeluar').dataTable({
              responsive: true,
              serverSide: true,
              searching: false,
              ajax: {
                  url: "{{ route('barangkeluar.list') }}",
                  type: "get",
                  data: {
                      "_token": "{{ csrf_token() }}",
                      "date_from": $('#date_from').val(),
                      "date_to": $('#date_to').val(),
                      "pemilik": $('#filter_pemilik').val(),
                      "posisi": $('#filter_posisi').val(),
                      "produk": $('#filter_produk').val(),
                      "kodeproduksi" : $('#filter_kodeproduksi').val(),
                      "mutcat": $('#filter_mutcat').val()
                  }
              },
              columns: [
                  {data: 'sm_date', name: 'sm_date'},
                  {data: 'pemilik', name: 'pemilik'},
                  {data: 'posisi', name: 'posisi'},
                  {data: 'i_name', name: 'i_name'},
                  {data: 'sm_qty', name: 'sm_qty'},
                  {data: 'm_name', name: 'm_name'},
                  {data: 'action', name: 'action'}
              ],
              pageLength: 10,
              lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']],
              dom: 'Bfrtip',
              buttons: [
                  'excel', 'pdf', 'print'
              ],
          });
          $('.dt-button').addClass('btn-secondary btn btn-sm');
      }

      function detail(id, detail) {
        $('#modal_detail').modal('show');
        $.ajax({
            url: baseUrl + "/inventory/barangkeluar/detail" + "/" + id + "/" + detail,
            type: 'get',
            success: function (response) {
              loadingShow();
              console.log(response.data.nota)
              document.getElementById("namaB").setAttribute("value", response.data.i_name);
              document.getElementById("pemilikB").setAttribute("value", response.data.pemilik);
              document.getElementById("posisiB").setAttribute("value", response.data.posisi);
              document.getElementById("codeB").setAttribute("value", response.data.code);
              document.getElementById("qtyB").setAttribute("value", response.data.jumlah);
              document.getElementById("hppB").setAttribute("value", response.hpp);
              document.getElementById("satuanB").setAttribute("value", response.data.u_name);
              document.getElementById("notaB").setAttribute("value", response.data.nota);
              console.log(response.detail);
              $('#table_detail').DataTable().clear().destroy();
              var tb_detail = $('#table_detail').DataTable({
                  responsive: true,
                  info: false,
                  paging: false,
                  searching: false
              });
              tb_detail.columns.adjust();

              $.each(response.detail, function (key, val) {
                  tb_detail.row.add([
                      val.smd_productioncode,
                      val.smd_qty
                  ]).draw(false);
              });
              loadingHide();
            }
        });
      }
  </script>
@endsection
