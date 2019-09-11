<!-- Reference block for JS -->
<div class="ref" id="ref">
    <div class="color-primary"></div>
    <div class="awalUrl">
        <input type="hidden" id="awalUrl" value="{{url('/')}}">
    </div>
    <div class="chart">
        <div class="color-primary"></div>
        <div class="color-secondary"></div>
    </div>
</div>
<script src="{{asset('assets/js/vendor.js')}}"></script>
<script src="{{asset('assets/js/app.js')}}"></script>
{{-- <script type="text/javascript" src="{{asset('assets/jquery-ui/jquery-ui.js')}}"></script> --}}
<script src="{{asset('assets/datatables/datatables.min.js')}}"></script>
<script src="{{asset('assets/datatables/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('assets/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/datatables/dataTables.buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/jszip/jszip.min.js')}}"></script>
<script src="{{asset('assets/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/datatables/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/datatables/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/select2/select2.js')}}"></script>
<script src="{{asset('assets/js/jquery.maskMoney.min.js')}}"></script>
<script src="{{asset('assets/jquery-confirm/jquery-confirm.js')}}"></script>
<script src="{{asset('assets/jquery-toast/jquery.toast.js')}}"></script>
<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
{{--<script src="{{asset('assets/jquery/jquery-3.1.0.min.js')}}"></script>--}}
<script src="{{asset('assets/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/bootstrap-datetimepicker/js/moment.js')}}"></script>
<script src="{{asset('assets/datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{asset('assets/bootstrapvalidator/bootstrapValidator.min.js') }}"></script>
<script src="{{asset('assets/js/dobPicker.min.js')}}"></script>
<script src="{{asset('assets/js/vue.js')}}"></script>
<script src="{{asset('assets/js/axios/axios.min.js')}}"></script>
<script src="{{asset('assets/pushjs/bin/push.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.6.3/mousetrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.6.3/plugins/bind-dictionary/mousetrap-bind-dictionary.min.js"></script>
<script src="{{asset('assets/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{asset('assets/JTimepicker-JohnHRTN/jquery.timepicker.min.js')}}"></script>
<script src="{{asset('assets/js/chartjs/dist/chart.min.js')}}"></script>

<script type="text/javascript">
    var getstorage;
    $('#sidebar-collapse-btn, #sidebar-overlay').click(function () {
        getstorage = localStorage.getItem('sidebar-collapse-storage');

        // console.log(getstorage);

        (getstorage) ? (localStorage.removeItem('sidebar-collapse-storage')) : (localStorage.setItem('sidebar-collapse-storage', 'sidebar-open'));

    });
    //set sidebar ketika di refresh
    getstorage = localStorage.getItem('sidebar-collapse-storage');
    if (getstorage) {
        $('#app').addClass(getstorage);
    }


</script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var baseUrl = "{{url('/')}}";

    function loadingShow() {
        $('#cover-spin').fadeIn(200);
    }

    function loadingHide() {
        $('#cover-spin').fadeOut(200);
    }

    function messageSuccess(title, message) {
        $.toast({
            heading: title,
            text: message,
            bgColor: '#00b894',
            textColor: 'white',
            loaderBg: '#3C415E',
            icon: 'success',
            stack: false,
            hideAfter: 3000
        });
    }

    function messageFailed(title, message) {
        $.toast({
            heading: title,
            text: message,
            bgColor: '#FF4444',
            textColor: 'white',
            loaderBg: '#3C415E',
            icon: 'warning',
            stack: false,
            hideAfter: 3000
        });
    }

    function messageWarning(title, message) {
        $.toast({
            heading: title,
            text: message,
            bgColor: '#FF4444',
            textColor: 'white',
            loaderBg: '#3C415E',
            icon: 'error',
            stack: false,
            hideAfter: 3000
        });
    }

    function deleteConfirm(uri) {
        return $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 2.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apakah anda yakin ingin menghapus data ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        return $.ajax({
                            type: "get",
                            url: uri,
                            success: function (response) {
                                if (response.status == 'Success') {
                                    messageSuccess('Berhasil', 'Data berhasil hapus!');
                                    reloadTable();
                                } else if (response.status == 'unauth'){
                                    messageWarning('Perhatian', 'Anda tidak memiliki akses');
                                }
                                else {
                                    messageWarning('Gagal', 'Gagal menghapus data!');
                                }
                            },
                            error: function (e) {
                                messageFailed('Peringatan', e.message);
                            }
                        });
                    }
                },
                cancel: {
                    text: 'Tidak',
                    action: function () {
                        // tutup confirm
                    }
                }
            }
        });
    }

    function convertToRupiah(angka) {
        var rupiah = '';
        var angkarev = angka.toString().split('').reverse().join('');
        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
        var hasil = 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
        return hasil;

    }

    function convertToCurrency(angka) {
        var currency = '';
        var angkarev = angka.toString().split('').reverse().join('');
        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) currency += angkarev.substr(i,3)+'.';
        var hasil = currency.split('',currency.length-1).reverse().join('');
        return hasil;

    }

    function convertToAngka(rupiah)
    {
        return parseInt(rupiah.replace(/,.*|[^0-9]/g, ''), 10);
    }

    function convertToRibuan(angka) {
        var number_string = angka.toString(),
            sisa    = number_string.length % 3,
            rupiah  = number_string.substr(0, sisa),
            ribuan  = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return rupiah;
    }

    function handleInput(e) {
        var ss = e.target.selectionStart;
        var se = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.selectionStart = ss;
        e.target.selectionEnd = se;
    }

    function find_duplicate_in_array(arra1) {
        var object = {};
        var result = [];

        arra1.forEach(function (item) {
            if(!object[item])
                object[item] = 0;
            object[item] += 1;
        })

        for (var prop in object) {
            if(object[prop] >= 2) {
                result.push(prop);
            }
        }
        return result;
    }

    $(document).ready(function () {

        @if(Session::has('message'))
            alert('{{ Session::get('message') }}');
        @endif

        $('#modal_periode').modal('show');
        $('#modal_periode_awal_success').modal('show');

        $("input[type='number']").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl/cmd+A
                (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl/cmd+C
                (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl/cmd+X
                (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
        $.extend($.fn.dataTable.defaults, {
            "responsive": true,

            "pageLength": 10,
            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
            "language": {
                "searchPlaceholder": "Cari Data",
                "emptyTable": "Tidak ada data",
                "sInfo": "Menampilkan _START_ - _END_ Dari _TOTAL_ Data",
                "sSearch": '<i class="fa fa-search"></i>',
                "sLengthMenu": "Menampilkan &nbsp; _MENU_ &nbsp; Data",
                "infoEmpty": "",
                "zeroRecords": "Tidak Dapat Menemukan Data",
                "paginate": {
                    "previous": "Sebelumnya",
                    "next": "Selanjutnya",
                }
            }

        });
        $('.data-table').DataTable();

        $('.datepicker').datepicker({
            format: "dd-mm-yyyy",
            enableOnReadonly: false,
            todayHighlight: true,
            autoclose: true
        });

        $('#search-mobile').click(function () {

            $('#search-container').toggle('display');

        });

        $(document).click(function (eve) {
            if (!$(eve.target).closest('header').length && $(window).width() <= 768) {
                $('#search-container').hide('slow');
            }
        });

        $(window).on('resize', function () {

            if ($(window).width() > 768) {
                $('#search-container').css('display', 'block');
            }

        });

        $('.input-daterange').datepicker({
            format: 'dd-mm-yyyy',
            enableOnReadonly: false,
            autoclose: true
        });

        $('.datetimepicker').datetimepicker({
            format: "D-M-Y HH:mm:ss",
            disabledTimeIntervals: false
        });
        // $('.modal.fade').on('scroll', function(){
        //     if($(this).hasClass('show')=== true){
        //         $('.datepicker').datepicker('hide');
        //         // console.log('b');
        //     }
        // });

        @if(!Request::is('keuangan/*'))
            $('.select2').select2({
                theme: "bootstrap",
                dropdownAutoWidth: true,
                width: '100%'
            });
        @endif

        $('.input-rupiah').maskMoney({
            thousands: ".",
            precision: 0,
            decimal: ",",
            prefix: "Rp. "
        });

        //mask money
        $('.rupiah').inputmask("currency", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            prefix: ' Rp ', //Space after $, this will not truncate the first character.
            rightAlign: true,
            autoUnmask: true,
            nullable: false,
            // unmaskAsNumber: true,
        });
        // mask to rupiah without digits after comma 'Rp 10.000'
        $('.rupiah-without-comma').inputmask("currency", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            prefix: ' Rp ', //Space after $, this will not truncate the first character.
            rightAlign: true,
            autoUnmask: true,
            nullable: false,
            // unmaskAsNumber: true,
        });


        // mask money with left-align
        $('.rupiah-left').inputmask("currency", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            prefix: ' Rp ', //Space after $, this will not truncate the first character.
            rightAlign: false,
            autoUnmask: true,
            nullable: false,
            // unmaskAsNumber: true,
        });

        // mask digits
        $('.digits').inputmask("currency", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            prefix: '', //Space after $, this will not truncate the first character.
            rightAlign: true,
            autoUnmask: true,
            nullable: false,
            // unmaskAsNumber: true,
        });

        //mask digits
        $('.digits-left').inputmask("currency", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            prefix: '', //Space after $, this will not truncate the first character.
            rightAlign: false,
            autoUnmask: true,
            nullable: false,
            // unmaskAsNumber: true,
        });

        // mask rekening
        $('.rek').inputmask("999 999 999 999 999 999 999", {
            autoUnmask: true,
            placeholder: ""
        });

        // mask telp-number
        $('.hp').inputmask("9999 9999 9999 9", {
            autoUnmask: true,
            placeholder: ""
        });

        // mask nip
        $('.nip').inputmask("99999999 999999 9 999", {
            autoUnmask: true,
            placeholder: ""
        });

        // mask nik
        $('.nik').inputmask("999999 999999 9999", {
            autoUnmask: true,
            placeholder: ""
        });

        // mask npwp
        $('.npwp').inputmask("99.999.999.9-999.999", {
            autoUnmask: true,
            placeholder: ""
        });

        // mask email
        $('.email').inputmask({alias: "email"});
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        jconfirm.defaults = {
            theme: 'light',
            animation: 'fadeIn',
            closeAnimation: 'fadeOut'
        };

        $.toast.options = {
            showHideTransition: 'fade', // fade, slide or plain
            allowToastClose: true, // Boolean value true or false
            hideAfter: 3000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
            stack: 8, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
            position: 'top-right', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values

            // bgColor: '#444444',  // Background color of the toast
            // textColor: '#eeeeee',  // Text color of the toast
            textAlign: 'left',  // Text alignment i.e. left, right or center
            loader: true,  // Whether to show loader or not. True by default
            loaderBg: '#9EC600',  // Background color of the toast loader
            beforeShow: function () {
            }, // will be triggered before the toast is shown
            afterShown: function () {
            }, // will be triggered after the toat has been shown
            beforeHide: function () {
            }, // will be triggered before the toast gets hidden
            afterHidden: function () {
            }  // will be triggered after the toast has been hidden
        };

        var coeg = ['Good Day, Sir', 'Haii', 'Welcome Back', 'Aye', 'Bash Besh Bosh', 'Boooom!! did I surprise you?', '...', 'High Five'];

        var random = Math.floor(Math.random() * coeg.length);

        // $.toast(coeg[random]);
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        // $(function () {
          $('[data-toggle="tooltip"]').tooltip();
        // });
        // custom function .ignore()
        $.fn.ignore = function (sel) {
            return this.clone().find(sel || ">*").remove().end();
        };
        // end custom function

        $cancel_search = $('#btn-reset');
        $btn_search_menu = $('#btn-search-menu');
        $search_fld = $('#filterInput');
        $filter = $search_fld.val().toUpperCase();
        $ul = $('#sidebar-menu');
        $li = $ul.children('li');

        // $('#wid-id-0 .widget-body').html($('#sidebar ul > li').parents('li').text() + '<br>')
        $('#sidebar ul > li > a').each(function () {
            $(this).prepend('<span class="d-none"> ' + $(this).parents('li').find('.menu-title').text() + '</span>');
        });
        $('#sidebar ul > li:has(ul) > a').each(function () {
            $(this).prepend('<span class="d-none d-sm-none"> ' + $(this).parent('li').children().ignore('span').text() + '</span>');
        });
        $('#sidebar ul > li > ul > li > a').each(function () {
            $(this).prepend('<span class="d-none d-xs-none"> ' + $(this).parent().parent().parent().find('.menu-title').text() + '</span>');
        });

        $search_fld.on('keyup focus blur resize', function () {

            if ($(this).val().length != 0) {
                // alert('a');
                $('#btn-reset').removeClass('d-none');
            } else {
                $('#btn-reset').addClass('d-none');
            }

            var input, filter, ul, li, a, i;
            input = document.getElementById("filterInput");
            filter = input.value.toUpperCase();
            ul = document.getElementById("sidebar-menu");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";

                }

            }
        });

        $cancel_search.on('click', function () {
            $search_fld.val(null);
            $search_fld.focus();
        });


        $btn_search_menu.on('click', function () {
            $search_fld.focus();
        });


    });
</script>

<!-- PUSHER function -->
<script type="text/javascript">
    const menuThree = document.querySelector('.menuThree');

    var localStorage_menuThree;


    $('.menuThree').addClass('localStorage_menuThree');

    function addClassFunThree() {
        localStorage_menuThree = localStorage.getItem('storage-menuthree-boys');

        (localStorage_menuThree) ? (localStorage.removeItem('storage-menuthree-boys')) : (localStorage.setItem('storage-menuthree-boys', 'clickMenuThree'));

        $('#sidebar-collapse-btn').addClass(localStorage_menuThree);

        this.classList.toggle("clickMenuThree");
    }

    localStorage_menuThree = localStorage.getItem('storage-menuthree-boys');
    if (localStorage_menuThree) {
        $("#sidebar-collapse-btn").addClass(localStorage_menuThree);
    }

    menuThree.addEventListener('click', addClassFunThree);

    // PUSHER
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
    // if this not shown-up, try to use 'artisan config:clear'
    var p_key = "{{ env('PUSHER_APP_KEY') }}";
    var p_cluster = "{{ env('PUSHER_APP_CLUSTER') }}";

    @if(Auth::check())
        <?php $sidebar = App\Http\Controllers\AksesUser::aksesSidebar() ?>
        @if($sidebar[44]->ua_read == 'Y')
            var pusher = new Pusher(p_key, {
                cluster: p_cluster,
                forceTLS: true
            });

            var channelOto = pusher.subscribe('channel-otorisasi');
            channelOto.bind('event-otorisasi', function(data) {
                otorisasi(data.name);
            });

            // var channelNotif = pusher.subscribe('channel-notifikasi');
            // channelNotif.bind('event-notifikasi', function(data) {
            //     notifikasi(data.name, data.qty, data.link);
            // });

            // get list 'notifikasi - otorisasi'
            $.ajax({
                type: 'get',
                dataType: 'json',
                url: baseUrl + '/gettmpoto',
                success : function(response){
                    console.log('oto: '+ response);
                    if (response.length != 0) {
                        for (var i = 0; i < response.length; i++) {
                            if (parseInt(response[i].n_qty) != 0) {
                                otorisasi(response[i].n_name, 0, response[i].n_link);
                            }
                        }
                    }
                }
            });

            // // get list 'notifikasi - notifikasi'
            // $.ajax({
            //     type: 'get',
            //     dataType: 'json',
            //     url: baseUrl + '/gettmpnotif',
            //     success : function(response){
            //         if (response.length != 0) {
            //             for (var i = 0; i < response.length; i++) {
            //                 if (parseInt(response[i].n_qty) != 0) {
            //                     notifikasi(response[i].n_name, 0, response[i].n_link);
            //                 }
            //             }
            //         }
            //     }
            // });
        @endif
    @endif
    // validate and update 'notif - otorisasi' (create if is_null)
    function otorisasi(name) {
        var html = "";
        $.ajax({
            type: 'get',
            data: {
                name
            },
            dataType: 'json',
            url: baseUrl + '/getoto',
            beforeSend: function() {
                loadingShow();
            },
            success : function(response){
                if (response.count == 0) {
                    html = '<center><li>'
                    +'<a href="#" class="notification-item">'
                    +'<div class="body-col">'
                    +'<p>'
                    +      '<span class="accent">Tidak ada data</span>'
                    +'</p>'
                    +'</div>'
                    +'</a>'
                    '</li></center>';
                }
                else {
                    for (var i = 0; i < response.data.length; i++) {
                        html += '<li>'
                        +'<a href="'+response.data[i].link+'" class="notification-item">'
                        +'<div class="body-col">'
                        +'<p>'
                        +      '<span class="accent"> ' + response.data[i].name + ' </span> ' + response.data[i].isi + ''
                        +      '<span class="accent"> ' + response.data[i].count + ' (' + response.data[i].date +') </span> . </p>'
                        +'</div>'
                        +'</a>'
                        '</li>';
                    }
                }
                $('#showotorisasi').html(html);
                $('#counteroto').text(response.count);
            },
            error: function (err) {
                alert('Koneksi internet tidak stabil, notifikasi tidak diperbaharui !');
            },
            complete: function (){
                loadingHide();
            }
        });
    }

    // // validate and update 'notif - notifikasi' (create if is_null)
    // function notifikasi(name, qty, link) {
    //     // alert(link);
    //     var html = "";
    //     $.ajax({
    //         type: 'get',
    //         data: {
    //             name,
    //             qty,
    //             link
    //         },
    //         dataType: 'json',
    //         url: baseUrl + '/getnotif',
    //         success : function(response){
    //             if (response.count == 0) {
    //                 html = '<center><li>'
    //                 +'<a href="#" class="notification-item">'
    //                 +'<div class="body-col">'
    //                 +'<p>'
    //                 +      '<span class="accent">Tidak ada data</span>'
    //                 +'</p>'
    //                 +'</div>'
    //                 +'</a>'
    //                 '</li></center>';
    //             } else {
    //                 for (var i = 0; i < response.data.length; i++) {
    //                     html += '<li>'
    //                     +'<a href="'+response.data[i].link+'" class="notification-item">'
    //                     +'<div class="body-col">'
    //                     +'<p>'
    //                     +      '<span class="accent"> '+response.data[i].name+' </span> '+response.data[i].isi+''
    //                     +      '<span class="accent"> '+response.data[i].count + ', ' + response.data[i].date+' </span> . </p>'
    //                     +'</div>'
    //                     +'</a>'
    //                     '</li>';
    //                 }
    //             }
    //             $('#shownotifikasi').html(html);
    //             $('#counternotif').text(response.count);
    //         }
    //     });
    // }
</script>


<script type="text/javascript">
    $(document).ready(function(){

        $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

            localStorage.setItem('activeTab', $(e.target).attr('href'));

        });

        var activeTab = localStorage.getItem('activeTab');

        if(activeTab){

            $('#Tabzs a[href="' + activeTab + '"]').tab('show');

        }

    });

</script>
<script>
        function search() {
            var search = $('#filterInput');
            search.val('');
            search.focus();
        }

        function hideMenu() {
            document.getElementById("sidebar-collapse-btn").click();
        }

        function easyCreate(){
            document.getElementById("e-create").click();
        }

        Mousetrap.bind ({
            '/': search,
            'ctrl+shift+h': hideMenu,
            'f1' : easyCreate
        });
</script>
