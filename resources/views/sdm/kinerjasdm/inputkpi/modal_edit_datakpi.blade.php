<!-- Modal -->
<div id="edit_datakpi" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Tambah Data Scoreboard</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

        <section>

          <form id="form_edit_pki">

            <div class="row">
              <div class="col-md-5 col-sm-6 col-xs-12">
                <label>Tanggal</label>
              </div>

              <div class="col-md-7 col-sm-6 col-xs-12">
                <div class="form-group">
                  <input type="text" class="form-control form-control-sm datepicker"name="">
                </div>
              </div>

              <div class="col-md-5 col-sm-6 col-xs-12">
                <label>Divisi</label>
              </div>

              <div class="col-md-7 col-sm-6 col-xs-12">
                <div class="form-group">
                  <input type="text" class="form-control form-control-sm" readonly="" name="" value="Sales Man">
                </div>
              </div>


              <div class="col-md-5 col-sm-6 col-xs-12">
                <label>Jabatan</label>
              </div>

              <div class="col-md-7 col-sm-6 col-xs-12">
                <div class="form-group">
                  <input type="text" class="form-control form-control-sm" readonly="" value="Admin Gudang" name="">
                </div>
              </div>
            </div>

            <div class="row" id="div_pki_pegawai">
              <div class="col-md-5 col-sm-6 col-xs-12">
                <label>Pegawai</label>
              </div>

              <div class="col-md-7 col-sm-6 col-xs-12">
                <div class="form-group">
                  <input type="text" class="form-control form-control-sm" readonly="" value="Ucok" name="">
                </div>
              </div>
            </div>

            <div class="row" id="div_pki_realisasi">
              <div class="col-md-12 col-sm-12 col-12">
                <label>JUMLAH CUSTOMER 
                  | Bobot : <span class="text-primary">20</span> 
                  | Target : <span class="text-primary">100</span> 
                  | Deadline : <span class="text-primary">31-01-2019</span>
                </label>
                <div class="form-group">
                  <input type="text" class="form-control form-control-sm" placeholder="Realisasi..." name="">
                </div>
              </div>
            </div>

          </form>  

        </section>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>