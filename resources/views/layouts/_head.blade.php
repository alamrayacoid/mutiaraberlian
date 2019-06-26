<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('tittle', 'Mutiara Berlian')</title>
        <meta name="description" content="">
  			<meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{asset('assets/img/cv-mutiaraberlian-icon.png')}}">
        <!-- Place favicon.ico in the root directory -->
        <link rel="stylesheet" href="{{asset('assets/css/vendor.css')}}">
        {{-- <link rel="stylesheet" type="text/css" href="{{asset('assets/jquery-ui/jquery-ui.css')}}"> --}}
        <link rel="stylesheet" href="{{asset('assets/datatables/datatables.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/datepicker/css/bootstrap-datepicker.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/select2/select2.css')}}">
        <link rel="stylesheet" href="{{asset('assets/select2/select2-bootstrap.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/jquery-confirm/jquery-confirm.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/jquery-toast/jquery.toast.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/jquery-ui/jquery-ui.min.css')}}">
        <link href="https://fonts.googleapis.com/css?family=Courgette" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/animate.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/hint.css')}}">


        <!-- Theme initialization -->
{{--         <script>
            var themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) :
            {};
            var themeName = themeSettings.themeName || '';


            var firstUrl = "{{ url('/') }}";

            if (themeName)
            {
                document.write('<link rel="stylesheet" id="theme-style" href="' + firstUrl +'/assets/css/app-' + themeName + '.css">');
            }
            else
            {
                document.write('<link rel="stylesheet" id="theme-style" href="' + firstUrl +'/assets/css/app.css">');
            }
        </script> --}}
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}">
        <style type="text/css">
            .sidebar .sidebar-menu > li > a i {

                width: 15px !important;

            }
            .sidebar .sidebar-menu > li > a i.arrow {

                width: 8px !important;

            }

            .checkbox-danger:checked + span:before {
                color: #ff4444;
                content: "\f058"; }

            .checkbox-info:checked + span:before {
                color: #52bcd3;
                content: "\f058"; }

            .checkbox-success:checked + span:before {
                color: #4bcf99;
                content: "\f058"; }

            .checkbox-warning:checked + span:before {
                color: #fe974b;
                content: "\f058"; }
            table.dataTable thead > tr > th {text-align: center;}
        </style>
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/alamraya-style.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom-style.css')}}">
</head>
