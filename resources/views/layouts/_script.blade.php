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
<script src="{{asset('assets/datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/select2/select2.js')}}"></script>
<script src="{{asset('assets/js/jquery.maskMoney.min.js')}}"></script>
<script src="{{asset('assets/jquery-confirm/jquery-confirm.js')}}"></script>
<script src="{{asset('assets/jquery-toast/jquery.toast.js')}}"></script>
{{--<script src="{{asset('assets/jquery/jquery-3.1.0.min.js')}}"></script>--}}
<script src="{{asset('assets/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/bootstrap-datetimepicker/js/moment.js')}}"></script>
<script src="{{asset('assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{asset('assets/js/vue.js')}}"></script>
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


    // var getstoragehamb;
    // $('#sidebar-collapse-btn').click(function(){
    //   getstorage = localStorage.getItem('hamburger-collapse-storage');

    //   // console.log(getstorage);

    // (getstorage) ? (localStorage.removeItem('hamburger-collapse-storage')) : (localStorage.setItem('hamburger-collapse-storage', 'menuThree'));

    // });
    // //set sidebar ketika di refresh
    // getstorage = localStorage.getItem('hamburger-collapse-storage');
    // if (getstorage) {
    //   $('#app').addClass(getstorage);
    // }

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

    function convertToRupiah(angka) {
        var rupiah = '';
        var angkarev = angka.toString().split('').reverse().join('');
        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
        var hasil = 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
        return hasil;

    }

    $(document).ready(function () {
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
            dateFormat: "dd-mm-yy",
            enableOnReadonly: false,
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
            enableOnReadonly: false

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

        $('.select2').select2({
            theme: "bootstrap",
            dropdownAutoWidth: true,
            width: '100%'
        });

        $('.input-rupiah').maskMoney({
            thousands: ".",
            precision: 0,
            decimal: ",",
            prefix: "Rp. "
        });
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
</script>

<script>
    var LeafScene = function (el) {
        this.viewport = el;
        this.world = document.createElement('div');
        this.leaves = [];

        this.options = {
            numLeaves: 20,
            wind: {
                magnitude: 1.2,
                maxSpeed: 12,
                duration: 300,
                start: 0,
                speed: 0
            },
        };

        this.width = this.viewport.offsetWidth;
        this.height = this.viewport.offsetHeight;

        // animation helper
        this.timer = 0;

        this._resetLeaf = function (leaf) {

            // place leaf towards the top left
            leaf.x = this.width * 2 - Math.random() * this.width * 1.75;
            leaf.y = -10;
            leaf.z = Math.random() * 200;
            if (leaf.x > this.width) {
                leaf.x = this.width + 10;
                leaf.y = Math.random() * this.height / 2;
            }
            // at the start, the leaf can be anywhere
            if (this.timer == 0) {
                leaf.y = Math.random() * this.height;
            }

            // Choose axis of rotation.
            // If axis is not X, chose a random static x-rotation for greater variability
            leaf.rotation.speed = Math.random() * 10;
            var randomAxis = Math.random();
            if (randomAxis > 0.5) {
                leaf.rotation.axis = 'X';
            } else if (randomAxis > 0.25) {
                leaf.rotation.axis = 'Y';
                leaf.rotation.x = Math.random() * 180 + 90;
            } else {
                leaf.rotation.axis = 'Z';
                leaf.rotation.x = Math.random() * 360 - 180;
                // looks weird if the rotation is too fast around this axis
                leaf.rotation.speed = Math.random() * 3;
            }

            // random speed
            leaf.xSpeedVariation = Math.random() * 0.8 - 0.4;
            leaf.ySpeed = Math.random() + 1.5;

            return leaf;
        }

        this._updateLeaf = function (leaf) {
            var leafWindSpeed = this.options.wind.speed(this.timer - this.options.wind.start, leaf.y);

            var xSpeed = leafWindSpeed + leaf.xSpeedVariation;
            leaf.x -= xSpeed;
            leaf.y += leaf.ySpeed;
            leaf.rotation.value += leaf.rotation.speed;

            var t = 'translateX( ' + leaf.x + 'px ) translateY( ' + leaf.y + 'px ) translateZ( ' + leaf.z + 'px )  rotate' + leaf.rotation.axis + '( ' + leaf.rotation.value + 'deg )';
            if (leaf.rotation.axis !== 'X') {
                t += ' rotateX(' + leaf.rotation.x + 'deg)';
            }
            leaf.el.style.webkitTransform = t;
            leaf.el.style.MozTransform = t;
            leaf.el.style.oTransform = t;
            leaf.el.style.transform = t;

            // reset if out of view
            if (leaf.x < -10 || leaf.y > this.height + 10) {
                this._resetLeaf(leaf);
            }
        }

        this._updateWind = function () {
            // wind follows a sine curve: asin(b*time + c) + a
            // where a = wind magnitude as a function of leaf position, b = wind.duration, c = offset
            // wind duration should be related to wind magnitude, e.g. higher windspeed means longer gust duration

            if (this.timer === 0 || this.timer > (this.options.wind.start + this.options.wind.duration)) {

                this.options.wind.magnitude = Math.random() * this.options.wind.maxSpeed;
                this.options.wind.duration = this.options.wind.magnitude * 50 + (Math.random() * 20 - 10);
                this.options.wind.start = this.timer;

                var screenHeight = this.height;

                this.options.wind.speed = function (t, y) {
                    // should go from full wind speed at the top, to 1/2 speed at the bottom, using leaf Y
                    var a = this.magnitude / 2 * (screenHeight - 2 * y / 3) / screenHeight;
                    return a * Math.sin(2 * Math.PI / this.duration * t + (3 * Math.PI / 2)) + a;
                }
            }
        }
    }

    LeafScene.prototype.init = function () {

        for (var i = 0; i < this.options.numLeaves; i++) {
            var leaf = {
                el: document.createElement('p'),
                x: 0,
                y: 0,
                z: 0,
                rotation: {
                    axis: 'X',
                    value: 0,
                    speed: 0,
                    x: 0
                },
                xSpeedVariation: 0,
                ySpeed: 0,
                path: {
                    type: 1,
                    start: 0,

                },
                image: 1
            };
            this._resetLeaf(leaf);
            this.leaves.push(leaf);
            this.world.appendChild(leaf.el);
        }

        this.world.className = 'leaf-scene';
        this.viewport.appendChild(this.world);

        // set perspective
        this.world.style.webkitPerspective = "400px";
        this.world.style.MozPerspective = "400px";
        this.world.style.oPerspective = "400px";
        this.world.style.perspective = "400px";

        // reset window height/width on resize
        var self = this;
        window.onresize = function (event) {
            self.width = self.viewport.offsetWidth;
            self.height = self.viewport.offsetHeight;
        };
    }

    LeafScene.prototype.render = function () {
        this._updateWind();
        for (var i = 0; i < this.leaves.length; i++) {
            this._updateLeaf(this.leaves[i]);
        }

        this.timer++;

        requestAnimationFrame(this.render.bind(this));
    }

    // start up leaf scene
    var leafContainer = document.querySelector('.falling-leaves'),
        leaves = new LeafScene(leafContainer);

    leaves.init();
    leaves.render();
</script>
