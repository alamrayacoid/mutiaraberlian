@extends('main')

@section('title', 'Master Akun')

@section(modulSetting()['extraStyles'])

	<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/bootstrap_datatable_v_1_10_18/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendor/toast/dist/jquery.toast.min.css') }}">
    
@endsection


@section('content')
    <div class="content-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb bg-info">
              <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="#">Home</a></li>
              <li class="breadcrumb-item">Master</li>
              <li class="breadcrumb-item active" aria-current="page">Master Data Akun</li>
            </ol>
          </nav>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Master Data Akun</h4>
                {{-- @if (App\mMember::akses('MASTER DATA VENDOR', 'tambah')) --}}
                  <div class="col-md-12 col-sm-12 col-xs-12" align="right" style="margin-bottom: 15px;">
                    <a href="{{ route('akun.create') }}">
                        <button class="btn btn-info btn-sm">Tambah / Edit Data Akun</button>
                    </a>
                  </div>
                {{-- @endif --}}

                <div class="table-responsive">
                    <table class="table table-bordered table-stripped" id="data-sample">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Kode Akun</th>
                                <th width="25%">Nama Akun</th>
                                <th width="15%">Kelompok</th>
                                <th width="8%">D/K</th>
                                <th width="15%">Saldo Opening</th>
                                <th width="15%">Tanggal Buat</th>
                                <th width="10">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($data as $key => $akun)
                                <?php 
                                    $bg     = '#eee';
                                    $color  = '#aaa';

                                    if($akun->ak_isactive == '1'){
                                        $bg     = 'none';
                                        $color  = 'none';
                                    }
                                ?>

                                <tr style="background: {{ $bg  }}; color: {{  $color }};">
                                    <td class="text-center">{{ ($key+1) }}</td>
                                    <td>{{ $akun->ak_id }}</td>
                                    <td>{{ $akun->ak_nama }}</td>
                                    <td>{{ $akun->kelompok }}</td>

                                    <?php 
                                        if($akun->ak_posisi == 'D')
                                            $posisi = 'DEBET';
                                        else
                                            $posisi = 'KREDIT';
                                    ?>

                                    <td class="text-center">{{ $posisi }}</td>
                                    <td class="text-right">{{ number_format($akun->ak_opening, 2) }}</td>
                                    <td class="text-center">{{ date('d/m/Y', strtotime($akun->created_at)) }}</td>
                                    <td class="text-center">
                                        {{-- <button class="btn btn-secondary btn-sm" title="Edit Data Group">
                                            <i class="fa fa-edit"></i>
                                        </button> --}}

                                        @if($akun->ak_status == 'locked')
                                            <button class="btn btn-default btn-sm" title="Akun Sedang Dikunci" style="cursor: no-drop;">
                                                <i class="fa fa-lock"></i>
                                            </button>
                                        @elseif($akun->ak_isactive == '1')
                                            <button class="btn btn-success btn-sm aktifkanData" title="Nonaktifkan" data-id="{{ $akun->ak_id }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-danger btn-sm aktifkanData" title="Aktifkan" data-id="{{ $akun->ak_id }}">
                                                <i class="fa fa-square-o"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
@endsection


@section(modulSetting()['extraScripts'])
	
	<script src="{{ asset('modul_keuangan/js/options.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/toast/dist/jquery.toast.min.js') }}"></script>
	<script src="{{ asset('modul_keuangan/js/vendor/bootstrap_datatable_v_1_10_18/datatables.min.js') }}"></script>
    <script src="{{ asset('modul_keuangan/js/vendor/axios_0_18_0/axios.min.js') }}"></script>

	<script type="text/javascript">

		$(document).ready(function() {
		    $('#data-sample').DataTable({
		    	"language": {
		            "lengthMenu": "Tampilkan _MENU_ Data Per Halaman",
		            "zeroRecords": "Tidak Bisa Menemukan Apapun . :(",
		            "info": "Menampilkan Halaman _PAGE_ dari _PAGES_",
		            "infoEmpty": "Tidak Ada Data Apapun",
		            "infoFiltered": "(Difilter Dari _MAX_ total records)",
		            "oPaginate": {
				        "sFirst":    "Pertama",
				        "sPrevious": "Sebelumnya",
				        "sNext":     "Selanjutnya",
				        "sLast":     "Terakhir"
				    }
		        }
		    });

            $('.aktifkanData').click(function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                var context = $(this);
                var cfrm = confirm('Apakah Anda Yakin ?');

                if(cfrm){
                    $('.aktifkanData').attr('disabled', 'disabled');

                    axios.post('{{ route('akun.delete') }}', { ak_id: context.data('id'), _token: '{{ csrf_token() }}' })
                            .then((response) => {
                                console.log(response.data);
                                
                                if(response.data.status == 'berhasil'){
                                    $.toast({
                                        text: response.data.message,
                                        showHideTransition: 'slide',
                                        position: 'top-right',
                                        icon: 'success',
                                        hideAfter: 5000
                                    });

                                    if(response.data.active == '0'){
                                        context.removeClass('btn-success');
                                        context.addClass('btn-danger');
                                        context.html('<i class="fa fa-square-o"></i>');
                                        context.closest('tr').css({
                                            'background': '#eee',
                                            'color'     : '#aaa'
                                        });
                                        context.attr('title', 'Aktifkan');
                                    }else{
                                        context.removeClass('btn-danger');
                                        context.addClass('btn-success');
                                        context.html('<i class="fa fa-check-square-o"></i>');
                                        context.closest('tr').css({
                                            'background': 'none',
                                            'color'     : '#6f6f6f'
                                        });
                                        context.attr('title', 'Nonaktifkan');
                                    }

                                }else{
                                    $.toast({
                                        text: response.data.message,
                                        showHideTransition: 'slide',
                                        position: 'top-right',
                                        icon: 'error',
                                        hideAfter: false
                                    });
                                }

                            })
                            .catch((err) => {
                                alert('Ups. Sistem Mengalami kesalahan. Message: '+err);
                            })
                            .then(() => {
                                $('.aktifkanData').removeAttr('disabled');
                            })
                }
            })
		});

    </script>

@endsection