@extends('main')

@section('content')
<article class="content dashboard-page">
    <section class="section">
        <div class="row sameheight-container">
            <div class="col col-12 stats-col">
                <div class="card sameheight-item stats" data-exclude="xs">
                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Analisis ROE (Return on Equity) </h3>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="row mb-3">
                            <div class="col-1">
                                <label>Periode</label>
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control text-center" id="month_from" autocomplete="off">
                            </div>
                            <div class="col-1">
                                <label>sampai</label>
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control text-center" id="month_to" autocomplete="off">
                            </div>
                            <div class="col-2 pull-left text-left">
                                <button type="button" class="btn btn-primary" onclick="getData()"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="row row-sm stats-container">
                            <div class="col-4">
                                <div class="stat-icon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
                                <div class="stat">
                                    <div class="aset"> 0 </div>
                                    <div class="name"> Aset </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 100%;"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <div class="stat">
                                    <div class="sales"> 0 </div>
                                    <div class="name"> Sales </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 100%;"></div>
                                </div>
                            </div>
                            <div class="col-4 stat-col">
                                <div class="stat-icon">
                                    <i class="fa fa-etsy"></i>
                                </div>
                                <div class="stat">
                                    <div class="ekuitas"> 0 </div>
                                    <div class="name"> Equity </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 100%;"></div>
                                </div>
                            </div>
                            <div class="col-4 stat-col">
                                <div class="stat-icon">
                                    <i class="fa fa-line-chart"></i>
                                </div>
                                <div class="stat">
                                    <div class="netprofit"> 0 </div>
                                    <div class="name"> Net Profit </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 100%;"></div>
                                </div>
                            </div>
                            <div class="col-4 stat-col">
                                <div class="stat-icon">
                                    <i class="fa fa-wheelchair-alt"></i>
                                </div>
                                <div class="stat">
                                    <div class="efektivitas"> 0 </div>
                                    <div class="name"> Efektivitas </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 100%;"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-icon">
                                    <i class="fa fa-wheelchair"></i>
                                </div>
                                <div class="stat">
                                    <div class="efesiensi"> 0 </div>
                                    <div class="name"> Efesiensi </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 100%;"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-icon">
                                    <i class="fa fa-industry"></i>
                                </div>
                                <div class="stat">
                                    <div class="produktivitas"> 0 </div>
                                    <div class="name"> Produktivitas </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 100%;"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-icon">
                                    <i class="fa fa-level-up"></i>
                                </div>
                                <div class="stat">
                                    <div class="leverage"> 0 </div>
                                    <div class="name"> Leverage </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 100%;"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-icon">
                                    <i class="fa fa-registered"></i>
                                </div>
                                <div class="stat">
                                    <div class="roe"> 0 </div>
                                    <div class="name"> ROE </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 100%;"></div>
                                </div>
                            </div>
                        </div>
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
        $('#month_from').datepicker({
            format: "mm-yyyy",
            viewMode: "months",
            autoclose: true,
            minViewMode: "months",
            endDate: '+0d'
        });
        $('#month_to').datepicker({
            format: "mm-yyyy",
            viewMode: "months",
            autoclose: true,
            minViewMode: "months",
            endDate: '+0d'
        });
    })

    function getData(){
        let awal = $('#month_from').val();
        let akhir = $('#month_to').val();
        if (awal == '' || awal == null) {
            messageWarning("Perhatian", "Tanggal awal tidak boleh kosong");
            return false;
        }
        if (akhir == '' || akhir == null) {
            messageWarning("Perhatian", "Tanggal akhir tidak boleh kosong");
            return false;
        }
        loadingShow();
        axios.get('{{ route("roe.getData") }}', {
            params:{
                "awal": awal,
                "akhir": akhir
            }
        }).then(function(response){
            loadingHide();
            let data = response.data;

            $('.aset').html(data.aset);
            $('.sales').html(data.sales);
            $('.ekuitas').html(data.ekuitas);
            $('.netprofit').html(data.netprofit);
            $('.efektivitas').html(data.efektivitas);
            $('.efesiensi').html(data.efesiensi);
            $('.produktivitas').html(data.produktivitas);
            $('.leverage').html(data.leverage);
            $('.roe').html(data.roe);
        }).catch(function(error){
            loadingHide();
        })
    }
</script>
@endsection
