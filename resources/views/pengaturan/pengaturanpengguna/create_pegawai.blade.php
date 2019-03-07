<div class="d-none animated fadeIn" id="pegawai">
  <form id="formpegawai">
    <fieldset class="mb-3">
        <div class="row mt-4" style="margin-bottom:0px;">
            <div class="col-md-3 col-sm-6 col-12">
                <label>Pegawai</label>
            </div>
            <div class="col-md-9 col-sm-6 col-12">
                <div class="form-group">
                    <select class="form-control form-control-sm select2" name="pegawai" id="spegawai">
                        <option value="" disabled selected="">--Pilih Pegawai--</option>
                        @foreach ($employee as $key => $value)
                          <option value="{{$value->e_id}}">{{$value->e_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset>
    <div class="row">

        <div class="col-md-3 col-sm-6 col-12">
            <label>Cabang</label>
        </div>

        <div class="col-md-9 col-sm-6 col-12">
            <div class="form-group">
              <select name="cabang" id="cabang" class="select2">
              <option value="" disabled selected="">--Pilih Cabang--</option>
              @foreach ($company as $key => $value)
                <option value="{{$value->c_id}}">{{$value->c_name}}</option>
              @endforeach
              </select>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <label>Level</label>
        </div>

        <div class="col-md-9 col-sm-6 col-12">
            <div class="form-group">
                <select name="level" id="level" class="select2">
                <option value="" disabled selected="">--Pilih Level--</option>
                @foreach ($level as $key => $value)
                  <option value="{{$value->m_id}}">{{$value->m_name }}</option>
                @endforeach
                </select>
            </div>
        </div>


        <div class="col-md-3 col-sm-6 col-12">
            <label>Username</label>
        </div>
        <div class="col-md-9 col-sm-6 col-12">
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" name="username" id="username">
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <label>Password</label>
        </div>
        <div class="col-md-8 col-sm-6 col-12">
            <div class="form-group">
                <input type="password" id="password" class="form-control form-control-sm" name="password">
            </div>
            <label><input class="checkbox rounded" onclick="showpassword()" type="checkbox"><span>Show Password</span></label>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <label>Confirm Password</label>
        </div>
        <div class="col-md-8 col-sm-6 col-12">
            <div class="form-group">
                <input type="password" id="confirmpassword" onkeyup="matching()" class="form-control form-control-sm" name="confirmpassword">
            </div>
            <label><input class="checkbox rounded" onclick="showconfirm()" type="checkbox"><span>Show Confirm Password</span></label>
        </div>
        <div class="col-md-1">
          <span class="fa fa-check" id="check" style="font-size:20px; color:rgb(32, 186, 98); display:none"></span>
        </div>
    </div>
    </fieldset>
    </form>
</div>
