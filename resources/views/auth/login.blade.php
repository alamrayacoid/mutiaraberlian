<!DOCTYPE html>
<html class="no-js" lang="en">

@include('layouts._head')

<body height="100px" width="100px">
<style>
    body, html {
        height: 100%;
    }

    .falling-leaves {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0px;
        width: 100%;
        height: 766px;
        max-width: 100%;
        max-height: 100%;
    / / image is only 880 x880 transform: translate(- 50 %, 0);
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
                        <img src="{{asset('assets/img/mutiaraberlian.svg')}}" height="45px" width="45px"
                             class="login-logo">
                        <!-- <span class="l l1"></span>
                        <span class="l l2"></span>
                        <span class="l l3"></span>
                        <span class="l l4"></span>
                        <span class="l l5"></span> -->
                    </div>
                    Mutiara Berlian
                </h1>
            </header>
            <div class="auth-content">
                @if (Session::get('status') == 'gagal')
                    <small style="color: red">{{ Session::get('message') }}</small>
                @elseif ($message = Session::get('gagal'))
                    <small style="color: red">Kombinasi username dan password salah</small>
                @endif
                <form method="POST" action="{{ url('auth') }}">
                    {{ csrf_field() }}
                    <div class="form-group wrap-input validate-input has-feedback" >
                        <label for="email">Username</label>
                        <input type="text" class="form-control login-input" name="username" id="username" required autofocus="">
                        <span class="focus-input"></span>
                    </div>
                    <div class="wrap-input validate-input has-feedback{{ $errors->has('password') ? 'has-error' : '' }}" data-validate="Enter password">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <p>{{ $errors->first('password') }}</p>
                            </span>
                        @endif
                        <label for="password">Password</label>
                        <input type="password" class="form-control login-input" name="password" id="password" required>
                        <span class="focus-input"></span>
                    </div>
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

<script type="text/javascript">
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

    //Dinamic Background
    setInterval(function () {
        dinamicbackground();
    }, 3000);

    dinamicbackground();

    function dinamicbackground(){
      var today = new Date();
      var curHr = today.getHours();
      if (curHr < 12) {
        document.getElementById("scene").style.background = "url(assets/img/forestback.svg) no-repeat center center";
      } else if (curHr < 18) {
        document.getElementById("scene").style.background = "url(assets/img/forestback1.svg) no-repeat center center";
      } else {
        document.getElementById("scene").style.background = "url(assets/img/forestback2.svg) no-repeat center center";
      }
    }

</script>

{{--
<!-- <script type="text/javascript">
    $(document).ready(function() {
        console.log("{!! Session::get('status') !!}");
        console.log("{!! Session::get('message') !!}");
    })
</script> -->
--}}
</body>
</html>
