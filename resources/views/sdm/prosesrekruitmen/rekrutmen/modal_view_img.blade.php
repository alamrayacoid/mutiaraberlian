<!-- Modal -->
<div id="view_img" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" style="background-color:transparent;">
    <!-- Modal content-->
    <div class="modal-content border-0" style="background-color:transparent;">
      <div style="background-color:transparent;">
        <button type="button" class="close" data-dismiss="modal" style="padding:10px; color:white; opacity:unset;" title="Exit">&times;</button>
        <button id="button" class="btn btn-secondary btn-sm" style="float:right; background-color:transparent; border:none; color:white; margin-top:11px; outline:unset; box-shadow:unset;" title="Rotate"><i class="fa fa-repeat" aria-hidden="true"></i></button>
      </div>
      <div class="modal-body p-0 col-12">
        <section>
          <div class="col-12 p-0 text-center">
            <img id="img_priview" src="" alt="" class="img-fluid" style="max-height: 550px;">
            <div id="overlay" onmousemove="zoomIn(event)" style=""></div>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>