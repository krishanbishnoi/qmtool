<!DOCTYPE html>

<html lang="en">

<!-- begin::Head -->

<head>
    <meta charset="utf-8" />
    <title>QM Tool</title>
    <meta name="description" content="Login page example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
<script>
WebFont.load({
google: {
"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
},
active: function() {
sessionStorage.fonts = true;
}
});
</script> -->

    <!--end::Fonts -->

    <!--begin::Page Custom Styles(used by this page) -->
    {!! Html::style('assets/app/custom/login/login-v3.default.css') !!}

    <!--end::Page Custom Styles -->

    <!--begin::Global Theme Styles(used by all pages) -->
    {!! Html::style('assets/vendors/base/vendors.bundle.css') !!}


    {!! Html::style('assets/demo/default/base/style.bundle.css') !!}


    <!--begin::Layout Skins(used by all pages) -->
    {!! Html::style('assets/demo/default/skins/header/base/light.css') !!}


    {!! Html::style('assets/demo/default/skins/header/menu/light.css') !!}


    {!! Html::style('assets/demo/default/skins/brand/dark.css') !!}


    {!! Html::style('assets/demo/default/skins/aside/dark.css') !!}

    <script>
        (function() {
            var isTouch = ('ontouchstart' in window) || navigator.maxTouchPoints > 0;
            var isCoarsePointer = window.matchMedia && window.matchMedia('(pointer: coarse)').matches;
            var isSmallScreen = Math.min(window.screen.width, window.screen.height) <= 480;

            var looksLikeMobileDevice = isTouch && isCoarsePointer && isSmallScreen;

            // Cookie persists regardless of what UA/Client-Hints say on later requests
            document.cookie = "device_check=" + (looksLikeMobileDevice ? "mobile" : "desktop") +
                "; path=/; max-age=86400; SameSite=Lax";
        })();
    </script>

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{url('')}}" />
</head>

<!-- end::Head -->

<!-- begin::Body -->

<body class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v3 kt-login--signin" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url(assets/media//bg/bg-3.jpg);">
                <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                    <div class="kt-login__container">
                        <div class="kt-login__logo">
                            <a href="#">
                                <img alt="Logo" src="{{url('qdegrees_logo.png')}}" /><br /><br />
                                <img alt="Logo" src="{{url('assets/media/logos/logo_default_dark.png')}}" />
                            </a>
                        </div>
                        @if (session('success'))
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-success"> <span>{{ session('success') }}</span>
                                    <button style="float:right; padding-left: 200px;" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <i class="material-icons">X</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif


                        <div class="kt-login__signin">
                            <div class="kt-login__head">
                                <h3 class="kt-login__title">Sign In</h3>
                            </div>

                            <form class="kt-form" action="" method="post" autocomplete="off">
                                @csrf
                                <div class="input-group">
                                    <input id="user_email" class="form-control" type="text" placeholder="Email" name="email" autocomplete="off">
                                </div>
                                <div class="input-group">
                                    <input class="form-control" type="password" placeholder="Password" name="password">
                                </div>
                                <div class="row kt-login__extra">
                                    <div class="col">
                                        <label class="kt-checkbox">
                                            <input type="checkbox" name="remember"> Remember me
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col kt-align-right">
                                        <a onclick="getDiv();" id="kt_login_forgot" class="kt-login__link">Forget Password ?</a>
                                    </div>
                                </div>
                                <div class="kt-login__actions">
                                    <button id="kt_login_signin_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Sign In</button>
                                </div>
                            </form>
                        </div>
                        <div class="kt-login__signup">
                            <div class="kt-login__head">
                                <h3 class="kt-login__title">Sign Up</h3>
                                <div class="kt-login__desc">Enter your details to create your account:</div>
                            </div>
                            <form class="kt-form" action="" method="post" autocomplete="off">
                                @csrf
                                <h6>Company Detail</h6>
                                <div class="input-group">
                                    <input class="form-control" type="text" placeholder="Company Name" name="company_name">
                                </div>
                                <div class="input-group">
                                    <input class="form-control" type="email" placeholder="Contact Email" name="contact_email">
                                </div>
                                <div class="input-group">
                                    <input class="form-control" type="text" placeholder="Contact Mobile" name="contact_phone">
                                </div>
                                <br />
                                <h6>Admin Detail</h6>
                                <div class="input-group">
                                    <input class="form-control" type="text" placeholder="Full Name" name="name">
                                </div>
                                <div class="input-group">
                                    <input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off">
                                </div>
                                <div class="input-group">
                                    <input class="form-control" type="text" placeholder="Mobile" name="mobile" autocomplete="off">
                                </div>
                                <div class="input-group">
                                    <input class="form-control" type="password" placeholder="Password" name="password">
                                    <small>Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.</small>
                                </div>
                                <div class="input-group">
                                    <input class="form-control" type="password" placeholder="Confirm Password" name="password_confirmation">
                                </div>
                                <div class="row kt-login__extra">
                                    <div class="col kt-align-left">
                                        <label class="kt-checkbox">
                                            <input type="checkbox" name="agree">I Agree the <a href="#" class="kt-link kt-login__link kt-font-bold">terms and conditions</a>.
                                            <span></span>
                                        </label>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>
                                <div class="kt-login__actions">
                                    <button id="kt_login_signup_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Sign Up</button>&nbsp;&nbsp;
                                    <button id="kt_login_signup_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
                                </div>
                            </form>
                        </div>
                        <div class="kt" id="forget_div">
                            <div class="kt-login__head">
                                <h3 class="kt-login__title">Forgotten Password ?</h3>
                                <div class="kt-login__desc">Enter your email to reset your password:</div>
                            </div>
                            <form class="kt-form" action="{{url('sendPasswordResetMail')}}" method="post" autocomplete="off">
                                @csrf
                                <div class="input-group">
                                    <input class="form-control" type="email" placeholder="Email" name="email" id="kt_email" autocomplete="off">
                                </div>
                                <div class="kt-login__actions">
                                    <button type="submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Request</button>&nbsp;&nbsp;
                                    <button id="kt_login_forgot_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
                                </div>
                            </form>
                        </div>
                        <!--<div class="kt-login__verify-user">
        <div class="kt-login__head">
            <h3 class="kt-login__title">Verify Your Mobile</h3>
            <div class="kt-login__desc">Please Enter OTP</div>
        </div>
        <div class="kt-login__form">
            <form class="kt-form" action="" method="post">
                @csrf
                <div class="form-group">
                    <input class="form-control" type="email" placeholder="email" name="email" id="m_email" autocomplete="off">
                </div>
                <div class="kt-login__actions">
                    <button id="m_login_verify_user_cancel" class="btn btn-brand btn-elevate kt-login__btn-primary">Resend</button>&nbsp;&nbsp;
                </div>
            </form>
        </div>
    </div>-->


                        <div class="kt-login__verify-otp">
                            <div class="kt-login__head">
                                <h3 class="kt-login__title">Verify It's You</h3>
                                <div class="kt-login__desc">Please Enter OTP</div>
                            </div>
                            <div class="kt-login__form">
                                <form class="kt-form" action="" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <input class="form-control" type="number" placeholder="Enter Your 6 Digit Code" name="otp" autocomplete="off">
                                    </div>
                                    <div class="kt-login__actions">
                                        <button id="kt_login_otp_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="kt-login__account">
                            {{-- <span class="kt-login__account-msg">
    Don't have an account yet ?
</span>
&nbsp;&nbsp;
<a href="javascript:;" id="kt_login_signup" class="kt-login__account-link">Sign Up!</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Page -->

    <!-- begin::Global Config(global config for global JS sciprts) -->
    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#5d78ff",
                    "dark": "#282a3c",
                    "light": "#ffffff",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                    "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                }
            }
        };
    </script>

    <!-- end::Global Config -->

    <!--begin::Global Theme Bundle(used by all pages) -->
    <script src="assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
    <script src="assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>

    <!--end::Global Theme Bundle -->

    <!--begin::Page Scripts(used by this page) -->
    <script src="assets/app/custom/login/login-general.js" type="text/javascript"></script>

    <!--end::Page Scripts -->

    <!--begin::Global App Bundle(used by all pages) -->
    <script src="assets/app/bundle/app.bundle.js" type="text/javascript"></script>
    <script>
        (function() {
            var isTouch = ('ontouchstart' in window) || navigator.maxTouchPoints > 0;
            var isCoarsePointer = window.matchMedia && window.matchMedia('(pointer: coarse)').matches;
            var isSmallScreen = Math.min(window.screen.width, window.screen.height) <= 480;

            var looksLikeMobileDevice = isTouch && isCoarsePointer && isSmallScreen;

            document.cookie = "device_check=" + (looksLikeMobileDevice ? "mobile" : "desktop") +
                "; path=/; max-age=300; SameSite=Lax";

            if (looksLikeMobileDevice && !window.location.pathname.startsWith('/blocked')) {
                window.location.href = "/blocked";
            }
        })();
    </script>

    <!--end::Global App Bundle -->
</body>
<script type="text/javascript">
    $("#forget_div").hide();

    function getDiv() {
        //alert("jdsfj");
        $('#forget_div').show();
    }
</script>

<!-- end::Body -->

</html>