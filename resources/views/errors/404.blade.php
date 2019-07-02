<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>404 Halaman tidak ditemukan</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{asset('assets/img/cv-mutiaraberlian-icon.png')}}">
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="{{asset('assets/css/vendor.css')}}">
    <!-- Theme initialization -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}">
</head>
<body>
<div class="main-wrapper" style="background-color: #F0F3F6;">
    <div style="background-color: #F0F3F6;" id="app">
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
        <div class="sidebar-mobile-menu-handle" id="sidebar-mobile-menu-handle"></div>
        <div class="mobile-menu-handle"></div>
        <article class="content error-404-page">
            <section class="section">
                <div class="error-card">
                    <div class="error-title-block">
                        <h1 class="error-title">404</h1>
                        <h2 class="error-sub-title"> Maaf, Halaman tidak bisa kami temukan </h2>
                    </div>
                    <div class="error-container">
                        <a class="btn btn-primary" href="{{ Route('home') }}">
                            <i class="fa fa-angle-left"></i> Kembali ke Dashboard</a>
                    </div>
                </div>
            </section>
        </article>
        <!-- /.modal -->
        <!-- /.modal -->
    </div>
</div>
<script src="{{asset('assets/js/vendor.js')}}"></script>
<script src="{{asset('assets/js/app.js')}}"></script>
</body>
</html>