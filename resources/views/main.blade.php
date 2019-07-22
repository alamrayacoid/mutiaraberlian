<!DOCTYPE html>
<html>
@include('layouts._head')

@yield('extra_style')
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

    <div id="cover-spin"></div>

	<div class="main-wrapper">
        <div class="app" id="app">
        	@include('layouts._sidebar')

        	@yield('content')

        	@include('layouts._footer')
        </div>
    </div>

    @include('layouts._modal')

  @include('layouts._script')

  @yield('extra_script')
</body>
</html>