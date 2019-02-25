<!DOCTYPE html>
<html class="no-js" lang="en">

    @include('layouts._head')

<body>
    <div class="auth">
            <div class="auth-container">
                <div class="card">
                    <header class="auth-header">
                        <h1 class="auth-title">
                          <div class="logo">
                              <span class="l l1"></span>
                              <span class="l l2"></span>
                              <span class="l l3"></span>
                              <span class="l l4"></span>
                              <span class="l l5"></span>
                          </div class="login-text">Mutiara Berlian</h1>
                    </header>
                    <div class="auth-content">
                        <p class="text-center">LOGIN TO CONTINUE</p>
                        <form method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username">Username</label>
                                <input type="text" class="form-control underlined" name="username" id="username" value="{{old('username')}}" required autofocus="">
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif 
                            </div>
                            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name">Name</label>
                                <input type="text" class="form-control underlined" name="name" id="name" value="{{old('name')}}" required autofocus="">
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif 
                            </div>
                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email">E-mail</label>
                                <input type="text" class="form-control underlined" name="email" id="email" value="{{old('email')}}" required autofocus="">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif 
                            </div>
                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password">Password</label>
                                <input type="password" class="form-control underlined" name="password" id="password" value="{{old('password')}}" required autofocus="">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif 
                            </div>
                            <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="password_confirmation">Password Confirm</label>
                                <input type="password" class="form-control underlined" name="password_confirmation" id="password_confirmation" value="{{old('password_confirmation')}}" required autofocus="">
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif 
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary">Register</button>
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