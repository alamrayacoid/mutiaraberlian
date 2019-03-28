<!-- Modal -->
<div id="search" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Detail Item</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">    
      <section>
      <div class="row mb-3">
        <div class="col-md-5 col-sm-12">
          <select name="prov" id="prov_agen" class="form-control form-control-sm select2" onchange="getProvId()">
            <option value="" selected="" disabled="">=== Pilih Provinsi ===</option>
            @foreach($provinsi as $prov)
            <option value="{{$prov->wp_id}}">{{$prov->wp_name}}</option>
            @endforeach
          </select>
        </div>
        <span>-</span>
        <div class="col-md-5 col-sm-12">
          <select name="city" id="city_agen" class="form-control form-control-sm select2 city">
            <option value="" selected disabled>=== Pilih Kota ===</option>
          </select>
        </div>
        <div class="col-md-1 col-sm-12">
            <button class="btn btn-md btn-primary" id="search-list-agen">Cari</button>
        </div>
      </div>
      </section>  
        <div class="table-responsive table-modal d-none">
          <table class="table table-striped table-hover display nowrap data-table" cellspacing="0" id="modal-order" width="100%">
            <thead class="bg-primary">
              <tr>
                <th>Provinsi</th>
                <th>Kota</th>
                <th>Nama Agen</th>
                <th>Jenis</th>
              </tr>
            </thead>
            <tbody>     
               <tr>
                <td>Jawa Timur</td>
                <td>Surabaya</td>
                <td>Bambang</td>
                <td>~</td>
              </tr>     
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>