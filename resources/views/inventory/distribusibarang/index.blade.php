@extends('main')

@section('content')

<!-- modal scoreboard pegawai -->
@include('sdm.penggajian.payrollmanajemen.modal_tambah')
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

                <ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a href="" class="nav-link active" data-target="#distribusibarang" aria-controls="distribusibarang" data-toggle="tab" role="tab">Distribusi Barang</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-target="#list_tunjangan" aria-controls="list_tunjangan" data-toggle="tab" role="tab">History Distribusi Barang</a>
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
    $(document).ready(function() {
        var table_sup = $('#table_manajemen').DataTable();
        var table_bar = $('#table_tunjangan').DataTable();
        var table_pus = $('#table_produksi').DataTable();
        var table_rab = $('#table_payrollmanajemen').DataTable();

        // MANAJEMEN
        $(document).on('click', '.btn-disable-manajemen', function() {
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
                            ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-manajemen" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

        $(document).on('click', '.btn-enable-manajemen', function() {
            $.toast({
                heading: 'Information',
                text: 'Data Berhasil di Aktifkan.',
                bgColor: '#0984e3',
                textColor: 'white',
                loaderBg: '#fdcb6e',
                icon: 'info'
            })
            $(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-manajemen" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
                '<button class="btn btn-danger btn-disable-manajemen" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')

        })

        // END
        // TUNJANGAN
        $(document).on('click', '.btn-disable-tunjangan', function() {
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
                            ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-tunjangan" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

        $(document).on('click', '.btn-enable-tunjangan', function() {
            $.toast({
                heading: 'Information',
                text: 'Data Berhasil di Aktifkan.',
                bgColor: '#0984e3',
                textColor: 'white',
                loaderBg: '#fdcb6e',
                icon: 'info'
            })
            $(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-tunjangan" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
                '<button class="btn btn-danger btn-disable-tunjangan" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')

        })
        // END
        // PRODUKSI
        $(document).on('click', '.btn-disable-produksi', function() {
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
                            ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-produksi" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

        $(document).on('click', '.btn-enable-produksi', function() {
            $.toast({
                heading: 'Information',
                text: 'Data Berhasil di Aktifkan.',
                bgColor: '#0984e3',
                textColor: 'white',
                loaderBg: '#fdcb6e',
                icon: 'info'
            })
            $(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-produksi" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
                '<button class="btn btn-danger btn-disable-produksi" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')

        })
        $(document).on('click', '.btn-disable-payrollmanajemen', function() {
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
                            ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-payrollmanajemen" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

        $(document).on('click', '.btn-enable-payrollmanajemen', function() {
            $.toast({
                heading: 'Information',
                text: 'Data Berhasil di Aktifkan.',
                bgColor: '#0984e3',
                textColor: 'white',
                loaderBg: '#fdcb6e',
                icon: 'info'
            })
            $(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-produksi" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
                '<button class="btn btn-danger btn-disable-produksi" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')

        })
        // $('#table_payrollmanajement body').on('click','.btn-edit-payrollmanajemen', function(){
        // 	window.location.href='{{route('produksi.edit')}}'
        // })
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
