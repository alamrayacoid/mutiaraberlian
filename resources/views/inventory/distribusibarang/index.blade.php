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
                    <li class="nav-item">
                        <a href="#historybarang" class="nav-link" data-target="#historybarang" aria-controls="historybarang" data-toggle="tab" role="tab">History Distribusi Barang</a>
                    </li>
                </ul>

                <div class="tab-content">

                    @include('inventory.distribusibarang.distribusi.index')
                    @include('inventory.distribusibarang.history.index')


                </div>

            </div>

        </div>

    </section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
    $(document).ready(function() {
        var table_sup = $('#table_distribusi').DataTable();
        var table_bar = $('#table_history').DataTable();

        $(document).on('click', '.btn-disable-distribusi', function() {
            var ini = $(this);
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
                            $.toast({
                                heading: 'Information',
                                text: 'Data Berhasil di Nonaktifkan.',
                                bgColor: '#0984e3',
                                textColor: 'white',
                                loaderBg: '#fdcb6e',
                                icon: 'info'
                            })
                            ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-distribusi type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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
        });

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

    $(document).ready(function() {
        $('.datepicker').datepicker();
    })

</script>
@endsection
