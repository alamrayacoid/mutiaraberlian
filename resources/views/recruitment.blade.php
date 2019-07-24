<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mutiara Berlian Recruitment</title>
    <link rel="shortcut icon" href="{{asset('assets/img/cv-mutiaraberlian-icon.png')}}">
    <!-- CSS -->
    <link rel="stylesheet" href="{{asset('assets/recruitment/css/recruitment.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          type="text/css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/jquery-confirm/jquery-confirm.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/jquery-toast/jquery.toast.css')}}">
</head>

<style type="text/css">
#cover-spin {
  position:fixed;
  width:100%;
  left:0;right:0;top:0;bottom:0;
  background-color: rgba(255,255,255,0.7);
  z-index:9999;
  display:none;
}

@-webkit-keyframes spin {
  from {-webkit-transform:rotate(0deg);}
  to {-webkit-transform:rotate(360deg);}
}

@keyframes spin {
  from {transform:rotate(0deg);}
  to {transform:rotate(360deg);}
}

#cover-spin::after {
  content:'';
  display:block;
  position:absolute;
  left:48%;top:40%;
  width:40px;height:40px;
  border: 16px solid #f3f3f3; /* Light grey */
  border-top: 16px solid #40739E;
  border-bottom: 16px solid #40739E;
  border-radius: 50%;
  width: 120px;
  height: 120px;
  -webkit-animation: spin .8s linear infinite;
  animation: spin .8s linear infinite;
}
</style>
<body>
<!-- Content -->
<div id="cover-spin"></div>
<!-- Header -->
<div class="row tron">
    <div class="continer-fluid animated fadeIn">
        <div class="green-side d-none d-xl-block"></div>
        <h1 class="hire-title">WE'RE HIRING!</h1>
        <div class="row header-button">
            <button class="btn btn-secondary btn-about" onclick="window.location.href='#about'">About Us</button>
            <button class="btn btn-secondary btn-join" onclick="window.location.href='#register'">Apply Now</button>
        </div>
        <div class="square-border d-none d-xl-block"></div>
        <div class="images">
            <img class="people-jumping d-none d-xl-block" src="assets/img/jumping-green.svg" alt="">
        </div>
    </div>
</div>
<button onclick="topFunction()" id="TopGan" title="Go to top"><i class="fa fa-angle-double-up" aria-hidden="true"></i>
</button>
<div class="container" id="register">

</div>
<form class="form cf" action="{{ route('recruitment.store') }}" method="post" id="myForm" autocomplete="off" enctype="multipart/form-data">
  @csrf
    <div class="wizard">
        <div class="wizard-inner">
            <div class="connecting-line"></div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="nav-item">
                    <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1"
                       class="nav-link active" id="a_step1">
                <span class="round-tab">
                <i class="fa fa-user"></i>
                </span>
                    </a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2"
                       class="nav-link disabled" id="a_step2">
                <span class="round-tab">
                <i class="fa fa-history"></i>
                </span>
                    </a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3"
                       class="nav-link disabled" id="a_step3">
                <span class="round-tab">
                <i class="fa fa-file"></i>
                </span>
                    </a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="Step 4"
                       class="nav-link disabled" id="a_step4">
                <span class="round-tab">
                <i class="fa fa-check"></i>
                </span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="wizard-form">
            <div class="tab-pane active" role="tabpanel" id="step1">
                <h1 class="text-md-center title-1">Data Diri</h1>
                <div class="row">
                    <div class="container">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Posisi Yang Dilamar<span style="color:red;">*</span></label>
                                </div>
                                <select name="applicant" id="applicant" class="form-control col-lg-9 col-sm-12 select2">
                                    <option value="" selected="" disabled="">=== Pilih Posisi ===</option>
                                    @foreach($posisi as $key => $pos)
                                        <option value="{{$pos->ss_id}}">{{$pos->j_name}}</option>
                                    @endforeach
                                </select>

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Nama<span style="color:red;">*</span></label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12" name="name">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Nomor Identitas (NIK)<span style="color:red;">*</span></label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12 nik" name="nik">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Alamat<span style="color:red;">*</span></label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12" name="address">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Alamat Sekarang<span style="color:red;">*</span></label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12" name="addressnow">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Tempat Lahir<span style="color:red;">*</span></label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12" name="birthplace">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Tanggal Lahir<span style="color:red;">*</span></label>
                                </div>
                                <div class=" col-lg-9 col-sm-12 row form-group">
                                    <select id="dobday" class="form-control col-sm-2" style="margin-right: 5px;" name="birthdate" id="tanggal"></select>
                                    <select id="dobmonth" class="form-control col-sm-4" style="margin-right: 5px;" name="birthmonth" id="bulan"></select>
                                    <select id="dobyear" class="form-control col-sm-3" style="margin-right: 5px;" name="birthyear" id="tahun"></select>
                                </div>

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Pendidikan<span style="color:red;">*</span></label>
                                </div>
                                <select class="form-control col-lg-9 col-sm-12" name="lasteducation" id="pendidikanterakhir">
                                    <option value="-" selected disabled>-- Jenjang Pendidikan Terakhir --</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                    <option value="SMK">SMK</option>
                                    <option value="D1">D1</option>
                                    <option value="D2">D2</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                </select>

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Email<span style="color:red;">*</span></label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12 email" name="email" id="email">
                                <input type="hidden" value="1" id="isEmailDuplicated">
                                <div>
                                <!-- <button class="btn btn-check" id="btn_checkemail">Cek Email</button> -->
                                </div>

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">No Telp/WA<span style="color:red;">*</span></label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12 hp" name="telp" id="telp">
                                <input type="hidden" value="1" id="isTelpDuplicated">
                                <div>
                                <!-- <button class="btn btn-check" id="btn_checktelp">Cek Nomer</button> -->
                                </div>

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Agama<span style="color:red;">*</span></label>
                                </div>
                                <select class="form-control col-lg-9 col-sm-12" name="religion" id="agama">
                                    <option value="-" selected disabled>-- Pilih Agama --</option>
                                    <option value="islam" >Islam</option>
                                    <option value="kristen" >Kristen</option>
                                    <option value="katolik" >Katolik</option>
                                    <option value="budha" >Budha</option>
                                    <option value="hindu" >Hindu</option>
                                    <option value="konghuchu" >Konghuchu</option>
                                </select>

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Status<span style="color:red;">*</span></label>
                                </div>
                                <select class="form-control col-lg-9 col-sm-12" name="status" id="status">
                                    <!-- <option value="-" selected disabled>- - Pilih Status - -</option> -->
                                    <option value="M" >Menikah</option>
                                    <option value="S" selected="">Belum Menikah</option>
                                </select>

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Nama Suami/Istri</label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12" name="partner">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Anak</label>
                                </div>
                                <input type="number" class="form-control col-lg-9 col-sm-12" name="childcount">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Deskripsi diri anda</label>
                                </div>
                                <textarea type="text" class="form-control col-lg-9 col-sm-12" row="5" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="list-inline text-md-center">
                    <li>
                        <button type="button" onclick="window.location.href='#wizard-form'"
                                class="btn btn-lg btn-common next-step next-button" id="btn_next1">Selanjutnya
                        </button>
                    </li>
                </ul>
            </div>
            <div class="tab-pane" role="tabpanel" id="step2">
                <h1 class="text-md-center title-1">Pendidikan Terakhir</h1>
                <div class="row">
                    <div class="container">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Nama Sekolah<span style="color:red;">*</span></label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12" name="schoolname">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Tahun Masuk<span style="color:red;">*</span></label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12" name="yearin" id="datepickers">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Tahun Keluar<span style="color:red;">*</span></label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12" name="yearout" id="datepickeryear">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Jurusan<span style="color:red;">*</span></label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12" name="majors">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Nilai/IPK<span style="color:red;">*</span></label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12 ipk" name="finalscore">
                            </div>
                        </div>
                        <h1 class="text-md-center title-1">Riwayat Pekerjaan</h1>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Nama Perusahaan</label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12" name="companyname1">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Tahun</label>
                                </div>
                                <input type="text" class="form-control col-9" name="yearsofwork1" id="datepickeryears">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Job Desc</label>
                                </div>
                                <textarea type="text" class="form-control col-lg-9 col-sm-12" name="jobdesc1"></textarea>

                                <br />
                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Nama Perusahaan</label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12"name="companyname2">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Tahun</label>
                                </div>
                                <input type="text" class="form-control col-lg-9 col-sm-12" name="yearsofwork2" id="datepickeryearss">

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">Job Desc</label>
                                </div>
                                <textarea type="text" class="form-control col-lg-9 col-sm-12" name="jobdesc2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="list-inline text-md-center">
                    <li>
                        <button type="button" onclick="window.location.href='#wizard-form'"
                                class="btn btn-lg btn-common next-step next-button">Selanjutnya
                        </button>
                    </li>
                </ul>
            </div>
            <div class="tab-pane" role="tabpanel" id="step3">
                <h1 class="text-md-center title-1">Upload Berkas</h1>
                <div class="row">
                    <div class="container">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3 col-sm-12">
                                    <label for="">File Foto<span style="color:red;">*</span></label>
                                </div>
                                <div class="custom-file col-lg-9 col-sm-12 mb-3">
                                    <input type="file" class="custom-file-input" name="filephoto" accept="image/*" id="imageupload">
                                    <label class="custom-file-label">Pilih Foto</label>
                                </div>

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">File KTP</label>
                                </div>
                                <div class="custom-file col-lg-9 col-sm-12 mb-3">
                                    <input type="file" class="custom-file-input" name="filektp" accept="image/*" id="ktpupload">
                                    <label class="custom-file-label">Pilih File</label>
                                </div>

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">File Ijazah</label>
                                </div>
                                <div class="custom-file col-lg-9 col-sm-12 mb-3">
                                    <input type="file" class="custom-file-input" name="fileijazah" accept="image/*" id="ijazahupload">
                                    <label class="custom-file-label">Pilih File</label>
                                </div>

                                <div class="col-lg-3 col-sm-12">
                                    <label for="">File Lain-lain</label>
                                </div>
                                <div class="custom-file col-lg-9 col-sm-12 mb-3">
                                    <input type="file" class="custom-file-input" name="fileanother" accept="image/*" id="etcupload">
                                    <label class="custom-file-label">Pilih File</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="list-inline text-md-center">
                    <li>
                        <button type="button" class="btn btn-lg btn-common next-step next-button">Selanjutnya</button>
                    </li>
                </ul>
            </div>
            <div class="tab-pane" role="tabpanel" id="step4">
                <h1 class="text-md-center">Apakah anda yakin?</h1>
                <div class="row">
                </div>
                <ul class="list-inline text-md-center">
                    <li>
                        <button type="button" class="btn btn-lg btn-common next-step next-button" id="btn_simpan">Simpan</button>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>

    </div>
</form>
<!-- About -->
<div class="container-fluid about-section" id="about">
    <div>
        <h3 class="text-center title-about">About Us</h3>
    </div>
    <div class="content-about">
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsum eos non sequi sit omnis. Expedita ad, labore
            esse fuga animi sit perferendis perspiciatis aut distinctio commodi, tempore, consectetur dolore
            voluptatibus!</p>
    </div>
</div>
<div class="footer">
    <div class="container">
        <span class="footer-text">Copyright © Alamraya Sebar Barokah</span>
    </div>
</div>
<!-- JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{asset('assets/jquery-confirm/jquery-confirm.js')}}"></script>
<script src="{{asset('assets/jquery-toast/jquery.toast.js')}}"></script>
<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
        crossorigin="anonymous"></script>
<script src="{{asset('assets/js/dobPicker.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script src="{{asset('assets/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.datepicker').datepicker();
        $('#applicant').select2();
    })

    // mask nik
    $('.nik').inputmask("999999 999999 9999", {
        autoUnmask: true,
        placeholder: ""
    });
    // mask email
    $('.email').inputmask({alias: "email"});
    // mask telp-number
    $('.hp').inputmask("9999 9999 9999 9", {
        autoUnmask: true,
        placeholder: ""
    });
    // mask ipk
    $('.ipk').inputmask("9.99", {
        autoUnmask: true,
        placeholder: "4.00"
    });



    $("#datepickers").datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    autoclose: true,
    });
    $("#datepickeryear").datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    autoclose: true,
    });
    $("#datepickeryears").datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    autoclose: true,
    });
    $("#datepickeryearss").datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    autoclose: true,
    });

    $.dobPicker({
        // Selectopr IDs
        daySelector: '#dobday',
        monthSelector: '#dobmonth',
        yearSelector: '#dobyear',

        // Default option values
        dayDefault: 'Tangal',
        monthDefault: 'Bulan',
        yearDefault: 'Tahun',

        // Minimum age
        minimumAge: 10,

        // Maximum age
        maximumAge: 80
    });

    $('.nav-tabs > li a[title]').tooltip();

    // Show name File Upload
    $('#imageupload').on('change',function(){
        var fileName = $(this).val().replace('C:\\fakepath\\', " ");;
        $(this).next('.custom-file-label').html(fileName);
    })
    $('#ktpupload').on('change',function(){
        var fileName = $(this).val().replace('C:\\fakepath\\', " ");;
        $(this).next('.custom-file-label').html(fileName);
    })
    $('#ijazahupload').on('change',function(){
        var fileName = $(this).val().replace('C:\\fakepath\\', " ");;
        $(this).next('.custom-file-label').html(fileName);
    })
    $('#etcupload').on('change',function(){
        var fileName = $(this).val().replace('C:\\fakepath\\', " ");;
        $(this).next('.custom-file-label').html(fileName);
    })

    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {


        var $target = $(e.target);
        if ($target.hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {
        var $active = $('.wizard .nav-tabs .nav-item .active');
        var $activeli = $active.parent("li");

        $($activeli).next().find('a[data-toggle="tab"]').removeClass("disabled");
        $($activeli).next().find('a[data-toggle="tab"]').click();
    });


    $(".prev-step").click(function (e) {

        var $active = $('.wizard .nav-tabs .nav-item .active');
        var $activeli = $active.parent("li");

        $($activeli).prev().find('a[data-toggle="tab"]').removeClass("disabled");
        $($activeli).prev().find('a[data-toggle="tab"]').click();

    });
</script>
<script>
    window.onscroll = function () {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("TopGan").style.display = "block";
        } else {
            document.getElementById("TopGan").style.display = "none";
        }
    }

    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>

<script type="text/javascript">

    function loadingShow()
    {
        $('#cover-spin').fadeIn(200);
    }

    function loadingHide()
    {
        $('#cover-spin').fadeOut(200);
    }

    function messageSuccess(title, message)
    {
        $.toast({
            heading: title,
            text: message,
            bgColor: '#00b894',
            textColor: 'white',
            loaderBg: '#3C415E',
            icon: 'success',
            stack: false,
            hideAfter: 3000,
            position: 'top-right'
        });
    }

    function messageFailed(title, message)
    {
        $.toast({
            heading: title,
            text: message,
            bgColor: '#FF4444',
            textColor: 'white',
            loaderBg: '#3C415E',
            icon: 'warning',
            stack: false,
            hideAfter: 3000,
            position: 'top-right'
        });
    }

    function messageWarning(title, message)
    {
        $.toast({
            heading: title,
            text: message,
            bgColor: '#FF4444',
            textColor: 'white',
            loaderBg: '#3C415E',
            icon: 'error',
            stack: false,
            hideAfter: 3000,
            position: 'top-right'
        });
    }
</script>
<script type="text/javascript">
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $(document).ready(function() {
    EnableNext();
  })

  $('#btn_simpan').on('click', function() {
    loadingShow();
    SubmitForm(event);
  });

  $('#email').focusout(function() {
    if($(this).val() != "") {
      loadingShow();
      CheckDuplicated(event, 'email');
    }
  });

  $('#telp').focusout(function() {
    if($(this).val() != "") {
      loadingShow();
      CheckDuplicated(event, 'telp');
    }
  });

  // enable btn_next1 when ther is no duplicated email and no telp
  function EnableNext() {
    if ($('#isEmailDuplicated').val() == 0 && $('#isTelpDuplicated').val() == 0) {
      $('#btn_next1').prop('disabled', false);
      $('#a_step2').removeClass('disabled');
      $('#a_step3').removeClass('disabled');
      $('#a_step4').removeClass('disabled');
    } else {
      $('#a_step2').addClass('disabled');
      $('#a_step3').addClass('disabled');
      $('#a_step4').addClass('disabled');
      $('#btn_next1').prop('disabled', true);
    }
  }

  // check is data is used by other user
  function CheckDuplicated(event, field)
  {
    event.preventDefault();
    myUrl = "{{ url('/recruitment/isduplicated/') }}";
    myUrl = myUrl + '/' + field + '/' + $('#' + field).val();

    $.ajax({
      type : "get",
      url : myUrl,
      dataType : 'json',
      success : function (response){
        if(response.status == 'valid'){
          loadingHide();
          if (field == 'email') {
            $('#isEmailDuplicated').val('0');
          } else if (field == 'telp') {
            $('#isTelpDuplicated').val('0');
          }
          EnableNext();
        } else if (response.status == 'invalid') {
          loadingHide();
          if (field == 'email') {
            $('#isEmailDuplicated').val('1');
            messageWarning('Perhatian', 'Email sudah digunakan, gunakan yang lain !');
          } else if (field == 'telp') {
            $('#isTelpDuplicated').val('1');
            messageWarning('Perhatian', 'No telp sudah digunakan, gunakan yang lain !');
          }
          EnableNext();
        }
      },
      error : function(e){
        loadingHide();
        messageWarning('Gagal', 'Check isDuplicated() gagal, hubungi pengembang !');
      }
    })
  }

  // submit form to store data in db
  function SubmitForm(event)
  {
    event.preventDefault();
    form_datax = new FormData($('#myForm')[0]);
    
    $.ajax({
      data : form_datax,
      type : "post",
      processData: false,
      contentType: false,
      enctype: 'multipart/form-data',
      url : $("#myForm").attr('action'),
      dataType : 'json',
      success : function (response){
        if(response.status == 'berhasil'){
          loadingHide();
          messageSuccess('Berhasil', 'Registrasi berhasil !');
          $('#myForm :input').prop('disabled', true);
        } else if (response.status == 'invalid') {
          loadingHide();
          messageWarning('Perhatian', response.message);
        } else if (response.status == 'gagal') {
          loadingHide();
          messageWarning('Error', response.message);
        }
      },
      error : function(e){
        loadingHide();
        messageWarning('Gagal', 'Data gagal ditambahkan, hubungi pengembang !');
      }
    })
  }
</script>
</body>
</html>
