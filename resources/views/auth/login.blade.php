<!DOCTYPE html>
<html class="no-js" lang="en">

@include('layouts._head')

<body>
<style>
    body, html {
        height: 100%;
    }

    .falling-leaves {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 3px;
        width: 100%;
        height: 766px;
        max-width: 100%;
        max-height: 100%;
    / / image is only 880 x880 transform: translate(- 50 %, 0);
        background: url(assets/img/forestback.svg) no-repeat center center;
        background-size: cover;
        overflow: hidden;
    }

    .leaf-scene {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 100%;
        transform-style: preserve-3d;

    }

    p {
        position: absolute;
        top: 0;
        left: 0;
        width: 20px;
        height: 20px;
        background: url(assets/img/leaf.png) no-repeat;
        background-size: 100%;
        transform-style: preserve-3d;
        backface-visibility: visible;
    }
</style>
<div class="auth">
    <div class="falling-leaves" id="scene"></div>
    <div class="auth-container">
        <div class="card login-section animated fadeIn">
            <header class="auth-header">
                <h1 class="auth-title">
                    <div class="logo">
                        <img src="{{asset('assets/img/cv-mutiaraberlian-icon.png')}}" height="45px" width="45px"
                             class="login-logo">
                        <!-- <span class="l l1"></span>
                        <span class="l l2"></span>
                        <span class="l l3"></span>
                        <span class="l l4"></span>
                        <span class="l l5"></span> -->
                    </div class="login-text">
                    Mutiara Berlian
                </h1>
            </header>
            <div class="auth-content">
                <form method="POST" action="{{ url('auth') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="email">Username</label>
                        <input type="text" class="form-control login-input" name="username" id="username" required
                               autofocus="">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control login-input" name="password" id="password" required>
                    </div>
                    <div class="form-group">
                        <label for="remember">
                            <input class="checkbox check-remember" id="remember" name="remember" type="checkbox">
                            <span>Remember me</span>
                        </label>
                        <!-- <a href="reset.html" class="forgot-btn pull-right">Forgot password?</a> -->
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary btn-login">Login
                        </button>
                    </div>
                    <!-- <div class="form-group">
                        <p class="text-muted text-center">Do not have an account?
                            <a href="signup.html">Sign Up!</a>
                        </p>
                    </div> -->
                </form>
            </div>
        </div>
        <!-- <div class="text-center">
            <a href="index.html" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Back to dashboard </a>
        </div> -->
    </div>
</div>
@include('layouts._script')
<script type="text/javascript">

</script>
</body>
</html>
