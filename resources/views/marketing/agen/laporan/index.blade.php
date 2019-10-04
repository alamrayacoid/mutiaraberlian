<div class="tab-pane animated fadeIn show" id="dataLaporan">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Laporan Keuangan Sederhana Agen</h3>
                <input type="hidden" class="current_user_type" value="{{ $user->u_user }}">
            </div>

            <div class="header-block pull-right">
                <select class="form-control" style="height: 30px; cursor: pointer;" id="option-cabang">
                    @if(Session::get('isPusat'))
                        <option value="all">Menampilkan Semua Agen/Cabang</option>
                    @endif
                    
                    @foreach($cabang as $key => $cab)
                        <option value="{{ $cab->c_id }}">{{ $cab->c_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-block">
            <section>
                <div class="row">
                    <div class="col-md-4" style="padding-left: 5px;">
                        <div class="col-md-12" style="border: 1px solid #eee; border-radius: 5px;">
                            <div class="row" style="border-bottom: 1px solid #eee; padding: 10px 0px;">
                                <div class="col-md-12">
                                    <span style="text-decoration: none; font-size: 10pt; font-weight: bold;">Summary Keuangan Pada Bulan Ini</span>
                                </div>
                                <div class="col-md-12">
                                    <small style="text-decoration: none;">
                                        Informasi tentang keuangan sederhana
                                    </small>
                                </div>
                            </div>

                            <div class="row" style="padding: 0px;">
                                <div class="col-md-6" style="padding: 0px; background: white; border-left: 1px solid #eee">
                                    <div class="col-md-12" style="padding: 10px 10px 15px 10px;">
                                        <table width="100%" border="0">
                                            <thead>
                                                    </td>
                                                    <td>
                                                        Rp. <span id="totPenjualan">---</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="font-weight: normal; border-bottom: 1px solid #0099CC;">
                                                        <small>Total Penjualan</small>
                                                    </td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-6" style="padding: 0px; background: white; border-right: 1px solid #eee">
                                    <div class="col-md-12" style="padding: 10px 10px 5px 10px;">
                                        <table width="100%" border="0">
                                            <thead>
                                                    </td>
                                                    <td>
                                                        Rp. <span id="totHutang">---</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="font-weight: normal; border-bottom: 1px solid #0099CC;">
                                                        <small>Sisa Hutang Pada Pusat</small>
                                                    </td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-6" style="padding: 0px; background: white; border-right: 1px solid #eee">
                                    <div class="col-md-12" style="padding: 10px 10px 5px 10px;">
                                        <table width="100%" border="0">
                                            <thead>
                                            </td>
                                            <td>
                                                Rp. <span id="saldoAgen">---</span>
                                            </td>
                                            </tr>

                                            <tr>
                                                <td style="font-weight: normal; border-bottom: 1px solid #0099CC;">
                                                    <small>Saldo di Pusat</small>
                                                </td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-6" style="padding: 0px; background: white; border-right: 1px solid #eee">
                                    <div class="col-md-12" style="padding: 10px 10px 5px 10px;">
                                        <table width="100%" border="0">
                                            <thead>
                                            </td>
                                            <td>
                                                Rp. <span id="hutangCabang">---</span>
                                            </td>
                                            </tr>

                                            <tr>
                                                <td style="font-weight: normal; border-bottom: 1px solid #0099CC;">
                                                    <small>Sisa Hutang Pada Cabang</small>
                                                </td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-8" style="padding: 0px; padding-left: 5px;">
                        <div clas="col-md-12" style="padding: 10px 10px; border: 1px solid #eee; border-radius: 5px;">
                            <p class="title-description"> History Summary Keuangan 5 Bulan Terakhir </p>
                            <canvas id="canvasku" style="margin-top: 10px;"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
