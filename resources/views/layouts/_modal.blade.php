@if(App\Helper\keuangan\periode\periode::emptyData())
    <div class="modal fade" id="modal_periode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content modal-lg" style="border-radius: 5px;">
                <div class="modal-header">
                        <span class="modal-title keuangan" id="myModalLabel">Periode Keuangan Belum Dibuat</span>
                </div>
                <form action="{{ Route('keuangan.periode.proses') }}" method="POST" id="modul_keuangan_form_periode">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" readonly>
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" readonly>
                    <div class="col-md-12" style="margin-top: 0px;">
                        Sistem Kami Tidak Bisa Menemukan Satu Pun Periode Keuangan Yang Ada. Agar Semua Proses Pada Aplikasi Ini Dapat Berjalan Tanpa Kendala, Anda Harus Membuat Satu Periode Keuangan Sesuai Dengan Bulan Pembukaan (cut off) Yang Diinginkan. Nantinya Anda Juga Harus Membuat Periode Keuangan Baru Setiap Bulannya. 
                    </div>

                    <div class="col-md-12 text-center" style="margin-top: 25px; background: #eee; padding: 10px 0px; border-radius: 10px;">
                        <div class="row">
                            <div class="col-md-1 offset-1" style="background: none; padding-top: 10px">
                                <i class="fa fa-calendar" style="font-size: 16pt;"></i>
                            </div>

                            <div class="col-md-3" style="padding: 10px 0px 0px 0px; font-style: italic; font-weight: bold">
                                Pilih Bulan Cut Off
                            </div>

                            <div class="col-md-7" style="padding: 7px 0px 0px 0px; font-style: italic; font-weight: bold;">
                                <div class="row">

                                    <div class="col-md-4">
                                        <select class="form-control" name="bulan" style="font-size: 9pt; height: 30px; cursor: pointer;">
                                            @for($a = (date('m') - 1); $a >= 0; $a--)
                                                
                                                <option value="{{ date('m', strtotime('-'.$a.' month')) }}">{{ date('M', strtotime('-'.$a.' month')) }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="col-md-1" style="padding: 5px 0px;"><i class="fa fa-minus"></i></div>

                                    <div class="col-md-4">
                                        <select class="form-control modul-keuangan" name="tahun" style="font-size: 9pt; height: 30px; cursor: pointer;">
                                            <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="this.disabled">
                        <span class="glyphicon glyphicon-floppy-disk"></span> Simpan
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if(App\Helper\keuangan\periode\periode::missing())
    <div class="modal fade" id="modal_periode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content modal-lg" style="border-radius: 5px;">
                <div class="modal-header">
                        <span class="modal-title keuangan" id="myModalLabel">Periode Keuangan Belum Terupdate</span>
                </div>
                <form action="{{ Route('keuangan.periode.proses') }}" method="POST" id="modul_keuangan_form_periode">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" readonly>
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" readonly>
                    <div class="col-md-12" style="margin-top: 0px;">
                        Sepertinya Periode Keuangan Telah Memasuki Bulan Baru. Anda Tidak Bisa Menggunakan Fitur-FItur Pada Sistem Sebelum Membuat Periode Baru Tersebut. Buat Sekarang ? 
                    </div>

                    <div class="col-md-12 text-center" style="margin-top: 25px; background: #eee; padding: 10px 0px; border-radius: 10px;">
                        <div class="row">
                            <div class="col-md-1 offset-1" style="background: none; padding-top: 10px">
                                <i class="fa fa-calendar" style="font-size: 16pt;"></i>
                            </div>

                            <div class="col-md-3" style="padding: 10px 0px 0px 0px; font-style: italic; font-weight: bold">
                                Pilih Bulan Cut Off
                            </div>

                            <div class="col-md-7" style="padding: 7px 0px 0px 0px; font-style: italic; font-weight: bold;">
                                <div class="row">

                                    <div class="col-md-4">
                                        <select class="form-control" name="bulan" style="font-size: 9pt; height: 30px; cursor: pointer;">
                                                <option value="{{ date('m') }}">{{ date('M') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-1" style="padding: 5px 0px;"><i class="fa fa-minus"></i></div>

                                    <div class="col-md-4">
                                        <select class="form-control modul-keuangan" name="tahun" style="font-size: 9pt; height: 30px; cursor: pointer;">
                                            <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="this.disabled">
                        <span class="glyphicon glyphicon-floppy-disk"></span> Simpan
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endif


@if(Session::has('cutoff_status') && Session::get('cutoff_status') == 'berhasil')
    <div class="modal fade" id="modal_periode_awal_success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 28%;">
            <div class="modal-content" style="border-radius: 5px;">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                            <span style="font-size: 14pt; font-weight: 600;">Periode Keuangan Berhasil Dibuat</span>
                        </div>

                        <div class="col-md-12" style="margin-top: 10px;">
                            <p><b>Anda berhasil membuat periode keuangan di tanggal : </b></p>
                            <p><center style="font-size: 24pt; color: #aaa; font-weight: bold;">{{ date('d / m / Y', strtotime(Session::get('cutoff_tgl'))) }}</center></p>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-primary">
                        <span class="glyphicon glyphicon-floppy-disk"></span> Simpan
                    </button>
                </div> -->
            </div>
        </div>
    </div>
@endif