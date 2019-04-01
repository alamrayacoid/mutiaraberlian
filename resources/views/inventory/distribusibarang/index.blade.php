@extends('main')

@section('content')

<!-- modal distribusi barang -->
@include('inventory.distribusibarang.distribusi.modal')
<!-- end -->
<!-- modal history -->
@include('inventory.distribusibarang.history.modal')
<!-- end -->

<article class="content">

    <div class="title-block text-primary">
        <h1 class="title"> Pengelolaan Distribusi Barang </h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Inventory</span> / <span class="text-primary" style="font-weight: bold;">Pengelolaan Distribusi Barang</span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <ul class="nav nav-pills mb-3" id="Tabzs">
                    <li class="nav-item">
                        <a href="#distribusibarang" class="nav-link active" data-target="#distribusibarang" aria-controls="distribusibarang" data-toggle="tab" role="tab">Distribusi Barang</a>
                    </li>
                </ul>

                <div class="tab-content">

                    @include('inventory.distribusibarang.distribusi.index')

                </div>

            </div>

        </div>

    </section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
var table;
var history;
    $(document).ready(function() {
      cur_date = new Date();
      first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
      last_day =   new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
      $('#rekrut_from').datepicker('setDate', first_day);
      $('#rekrut_to').datepicker('setDate', last_day);
      tabledistribusi();

        $(document).on('click', '.btn-enable-distribusi', function() {
            $.toast({
                heading: 'Information',
                text: 'Data Berhasil di Aktifkan.',
                bgColor: '#0984e3',
                textColor: 'white',
                loaderBg: '#fdcb6e',
                icon: 'info'
            })
            $(this).parents('.btn-group').html('<button class="btn btn-primary btn-modal-detail" data-toggle="modal" data-target="#detail"><i class="fa fa-folder"></i></button>'+
                                               '<button class="btn btn-warning btn-edit-distribusi" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
                '<button class="btn btn-danger btn-disable-distribusi" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')

        })

        // END

        $(document).on('click', '.btn-simpan-modal', function() {
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

    function tabledistribusi(start, end)
  	{
  		$('#table_distribusi').dataTable().fnDestroy();
  		table = $('#table_distribusi').DataTable({
  			responsive: true,
  			serverSide: true,
  			ajax: {
  				url: baseUrl + '/inventory/distribusibarang/table',
  				type: "get",
  				data: {
  					"_token": "{{ csrf_token() }}",
            "date_from" : $('#rekrut_from').val(),
  					"date_to" : $('#rekrut_to').val()
  				}
  			},
  			columns: [
  				{data: 'DT_RowIndex'},
  				{data: 'tanggal'},
  				{data: 'tujuan'},
  				{data: 'sd_nota'},
  				{data: 'type'},
  				{data: 'action', name: 'action'}
  			],
  			pageLength: 10,
  			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
  		});
  	}

    $(document).ready(function() {
        $('.datepicker').datepicker();
    })

    function printNota(id)
	{
		window.location.href='{{ url('inventory/distribusibarang/nota') }}?id='+id;
	}

    function hapus(id){
      $.confirm({
          animation: 'RotateY',
          closeAnimation: 'scale',
          animationBounce: 1.5,
          icon: 'fa fa-exclamation-triangle',
          title: 'Peringatan!',
          content: 'Apa anda yakin mau menonaktifkan data ini?',
          theme: 'disable',
          buttons: {
              info: {
                  btnClass: 'btn-blue',
                  text: 'Ya',
                  action: function() {
                    $.ajax({
                      type: 'get',
                      data: {id},
                      dataType: 'json',
                      url: baseUrl + '/inventory/distribusibarang/hapus',
                      success : function(response){
                        if (response.status == 'berhasil') {
                          $.toast({
                              heading: 'Information',
                              text: 'Data Berhasil di Nonaktifkan.',
                              bgColor: '#0984e3',
                              textColor: 'white',
                              loaderBg: '#fdcb6e',
                              icon: 'info'
                          })
                          table.ajax.reload();
                        } else if (response.status == 'failed') {
                          messageFailed('Failed', response.ex);
                        } else {
                          messageFailed('Failed', 'Data Berhasil Gagal di Nonaktifkan');
                        }
                      }
                    });
                  }
              },
              cancel: {
                  text: 'Tidak',
                  action: function() {
                      // tutup confirm
                  }
              }
          }
      });
    }

    $('#rekrut_from').on('change', function() {
      tabledistribusi();
    })
    $('#rekrut_to').on('change', function() {
      tabledistribusi();
    })

     function edit(id){
      window.location.href = baseUrl + '/inventory/distribusibarang/edit/'+id;
    }

</script>
@endsection
