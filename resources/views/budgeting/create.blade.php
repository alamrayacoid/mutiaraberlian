@extends('main')
@section('extra_style')
    <style>
        #table_agen td {
            padding: 5px;
        }
    </style>
@stop
@section('content')

<article class="content animated fadeInLeft">

    <div class="title-block text-primary">
        <h1 class="title"> Budgeting</h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Budgeting</span>
            / <a href="{{ route('budgeting.index') }}"><span>Manajemen Perencanaan</span></a>
            / <span class="text-primary" style="font-weight: bold;">Tambah Perencanaan</span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <div class="card">
                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Tambah Perencanaan </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a href="{{route('budgeting.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
<!-- start : form -->
                    <div class="card-block">
                        <section>
                            <div class="row mb-5">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>Status Agen</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <select name="status" id="status" class="form-control form-control-sm select2">
                                        <option value="">Semua</option>
                                        <option value="Y" selected>Aktif</option>
                                        <option value="N">Non Aktif</option>
                                    </select>
                                </div>
                            </div>

                        </section>
                    </div>
                </div>

            </div>

        </div>

    </section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function () {

        }, 100);
    });

</script>
@endsection
