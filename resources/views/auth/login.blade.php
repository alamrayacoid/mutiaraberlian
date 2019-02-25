<!DOCTYPE html>
<html class="no-js" lang="en">

    @include('layouts._head')

<body>
    <div class="auth">
            <div class="auth-container">
                <div class="card login-section animated fadeIn">
                    <header class="auth-header">
                        <h1 class="auth-title">
                          <div class="logo">
                          <img src="{{asset('assets/img/cv-mutiaraberlian-icon.png')}}" height="45px" width="45px" class="login-logo">
                              <!-- <span class="l l1"></span>
                              <span class="l l2"></span>
                              <span class="l l3"></span>
                              <span class="l l4"></span>
                              <span class="l l5"></span> -->
                          </div class="login-text">Mutiara Berlian</h1>
                    </header>
                    <div class="auth-content">
                        <form method="POST" action="{{ url('login') }}">
                        {{ csrf_field() }}
                            <div class="form-group">
                                <label for="email">Username</label>
                                <input type="text" class="form-control login-input" name="email" id="email" placeholder="Username" required autofocus=""> </div>
                                <br>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control login-input" name="password" id="password" placeholder="Password" required> </div>
                                <br>
                            <div class="form-group">
                                <label for="remember">
                                    <input class="checkbox check-remember" id="remember" name="remember" type="checkbox">
                                    <span>Remember me</span>
                                </label>
                                <!-- <a href="reset.html" class="forgot-btn pull-right">Forgot password?</a> -->
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary btn-login">Login</button>
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
</body>
</html>