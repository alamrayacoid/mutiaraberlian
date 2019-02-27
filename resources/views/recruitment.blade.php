<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mutiara Berlian Recruitment</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{asset('assets/recruitment/css/recruitment.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          type="text/css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
</head>
<body>
<!-- Content -->
<!-- Header -->
<div class="row tron">
    <div class="continer-fluid animated fadeIn">
        <div class="green-side d-none d-xl-block"></div>
        <h1 class="hire-title">WE'RE HIRING!</h1>
        <div class="row header-button">
            <button class="btn btn-secondary btn-about" onclick="window.location.href='#about'">About Us</button>
            <button class="btn btn-secondary btn-join" onclick="window.location.href='#section-1'">Apply Now</button>
        </div>
        <div class="square-border d-none d-xl-block"></div>
        <div class="images">
            <img class="people-jumping d-none d-xl-block" src="assets/img/jumping-green.svg" alt="">
        </div>
    </div>
</div>
<button onclick="topFunction()" id="TopGan" title="Go to top"><i class="fa fa-angle-double-up" aria-hidden="true"></i>
</button>
<div class="container" id="section-1">

</div>
<form class="form cf">
    <div class="wizard">
        <div class="wizard-inner">
            <div class="connecting-line"></div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="nav-item">
                    <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1"
                       class="nav-link active">
                <span class="round-tab">
                <i class="fa fa-user"></i>
                </span>
                    </a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2"
                       class="nav-link disabled">
                <span class="round-tab">
                <i class="fa fa-history"></i>
                </span>
                    </a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3"
                       class="nav-link disabled">
                <span class="round-tab">
                <i class="fa fa-file"></i>
                </span>
                    </a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="Step 4"
                       class="nav-link disabled">
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
                        <form action="">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Nama<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Nomer Identitas<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Alamat<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Alamat Sekarang<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Tempat Lahir<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Tanggal Lahir<span style="color:red;">*</span></label>
                                    </div>
                                    <div class=" col-lg-9 col-sm-12 row form-group">
                                        <select id="dobday" class="form-control col-sm-2" style="margin-right: 5px;" name="tanggal" id="tanggal"></select>
                                        <select id="dobmonth" class="form-control col-sm-4" style="margin-right: 5px;" name="bulan" id="bulan"></select>
                                        <select id="dobyear" class="form-control col-sm-3" style="margin-right: 5px;" name="tahun" id="tahun"></select>
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Pendidikan<span style="color:red;">*</span></label>
                                    </div>
                                    <select class="form-control col-lg-9 col-sm-12" name="pendidikanterakhir" id="pendidikanterakhir">
                                        <option value="-" selected disabled>-- Pendidikan Terakhir --</option>
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
                                    <input type="text" class="form-control col-lg-7 col-sm-12">
                                    <div>
                                    <button class="btn btn-check">Cek Email</button>
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">No Telp/WA<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-7 col-sm-12">
                                    <div>
                                    <button class="btn btn-check">Cek Nomer</button>
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Agama<span style="color:red;">*</span></label>
                                    </div>
                                    <select class="form-control col-lg-9 col-sm-12" name="agama" id="agama">
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
                                        <option value="-" selected disabled>-- Pilih Status --</option>
                                        <option value="menikah" >Menikah</option>
                                        <option value="belum" >Belum Menikah</option>
                                    </select>
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Nama Suami/Istri<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Anak<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="number" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Deskripsi diri anda<span style="color:red;">*</span></label>
                                    </div>
                                    <textarea type="text" class="form-control col-lg-9 col-sm-12" row="5"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <ul class="list-inline text-md-center">
                    <li>
                        <button type="button" onclick="window.location.href='#wizard-form'"
                                class="btn btn-lg btn-common next-step next-button">Next
                        </button>
                    </li>
                </ul>
            </div>
            <div class="tab-pane" role="tabpanel" id="step2">
                <h1 class="text-md-center title-1">Pendidikan Terakhir</h1>
                <div class="row">
                    <div class="container">
                        <form action="">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Nama Sekolah<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Tahun<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Jurusan<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Nilai/IPK<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                </div>
                            </div>
                        </form>
                        <h1 class="text-md-center title-1">Daftar Riwayat Hidup</h1>
                        <form action="">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Nama Perusahaan</label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Tahun<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-9">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Job Desc<span style="color:red;">*</span></label>
                                    </div>
                                    <textarea type="text" class="form-control col-lg-9 col-sm-12"></textarea>
                                    <br>
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Nama Perusaah<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Tahun<span style="color:red;">*</span></label>
                                    </div>
                                    <input type="text" class="form-control col-lg-9 col-sm-12">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">Job Desc<span style="color:red;">*</span></label>
                                    </div>
                                    <textarea type="text" class="form-control col-lg-9 col-sm-12"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <ul class="list-inline text-md-center">
                    <li>
                        <button type="button" onclick="window.location.href='#wizard-form'"
                                class="btn btn-lg btn-common next-step next-button">Next Step
                        </button>
                    </li>
                </ul>
            </div>
            <div class="tab-pane" role="tabpanel" id="step3">
                <h1 class="text-md-center title-1">Upload Berkas</h1>
                <div class="row">
                    <div class="container">
                        <form action="">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">File Foto<span style="color:red;">*</span></label>
                                    </div>
                                    <div class="custom-file col-lg-9 col-sm-12 mb-3">
                                        <input type="file" class="custom-file-input">
                                        <label class="custom-file-label">Pilih Foto</label>
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">File KTP</label>
                                    </div>
                                    <div class="custom-file col-lg-9 col-sm-12 mb-3">
                                        <input type="file" class="custom-file-input">
                                        <label class="custom-file-label">Pilih File</label>
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">File Ijazah</label>
                                    </div>
                                    <div class="custom-file col-lg-9 col-sm-12 mb-3">
                                        <input type="file" class="custom-file-input">
                                        <label class="custom-file-label">Pilih File</label>
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="">File Lain-lain</label>
                                    </div>
                                    <div class="custom-file col-lg-9 col-sm-12 mb-3">
                                        <input type="file" class="custom-file-input">
                                        <label class="custom-file-label">Pilih File</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <ul class="list-inline text-md-center">
                    <li>
                        <button type="button" class="btn btn-lg btn-common next-step next-button">Next Step</button>
                    </li>
                </ul>
            </div>
            <div class="tab-pane" role="tabpanel" id="step4">
                <h1 class="text-md-center">Apakah anda yakin?</h1>
                <div class="row">
                </div>
                <ul class="list-inline text-md-center">
                    <li>
                        <button type="button" class="btn btn-lg btn-common next-step next-button">Simpan</button>
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
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
        crossorigin="anonymous"></script>
<script src="{{asset('assets/js/dobPicker.min.js')}}"></script>
<script>
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
</body>
</html>
