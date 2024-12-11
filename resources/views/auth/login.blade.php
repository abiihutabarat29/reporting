<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Administrator</title>
    <link rel="icon" href="{{ asset('assets/icon/icon.png') }}" type="image/x-icon" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/animate.min.css" rel="stylesheet" />
    <link href="assets/css/style.min.css" rel="stylesheet" />
    <link href="assets/css/style-responsive.min.css" rel="stylesheet" />
    <link href="assets/css/theme/default.css" rel="stylesheet" id="theme" />
    <!-- ================== END BASE CSS STYLE ================== -->

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="assets/plugins/pace/pace.min.js"></script>
    <!-- ================== END BASE JS ================== -->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />
</head>

<body class="pace-top bg-white">
    <div id="page-loader" class="fade in"><span class="spinner"></span></div>
    <div id="page-container" class="fade">
        <div class="login login-with-news-feed">
            <div class="news-feed">
                <div class="news-image">
                    <img src="{{ url('assets/img/bg.jpg') }}" data-id="login-cover-image" alt="" />
                </div>
                <div class="news-caption d-flex flex-col justify-center items-center">
                    <img src="{{ asset('assets/icon/icon.png') }}" class="mb-5" alt="" width="230">
                    <h3 class="text-white font-semibold">
                        E-REPORTING PKK KABUPATEN BATU BARA
                    </h3>
                </div>
            </div>
            <div class="right-content">
                <div class="login-header">
                    <div class="brand">
                        <img src="" alt="" width="70"> Administrator
                        <div class="text-sm">
                            <small>silahkan masukkan akun anda.</small>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fa fa-sign-in"></i>
                    </div>
                </div>
                <div class="login-content">
                    @php
                        use Carbon\Carbon;

                        $a = rand(1, 10);
                        $b = rand(1, 10);
                        $sum = $a + $b;

                    @endphp
                    <form method="POST" action="{{ route('login') }}" class="margin-bottom-0">
                        @csrf
                        <div class="form-group m-b-15">
                            <input type="text" name="email" id="email" type="email"
                                class="form-control
                                input-lg"
                                placeholder="Email Address" />
                            @if ($errors->has('email'))
                                <small class="text-danger">{{ $errors->first('email') }}</small>
                            @endif
                        </div>
                        <div class="form-group m-b-20">
                            <input name="password" class="form-control input-lg" id="password" type="password"
                                class="form-control input-lg inverse-mode no-border" placeholder="Password" />
                            @if ($errors->has('password'))
                                <small class="text-danger">{{ $errors->first('password') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="m-b-20">
                                <div class="row">
                                    <div class="col-md-6 m-t-10">
                                        <label for="math-captcha" class="form-label">Berapa hasil dari
                                            {{ $a }} +
                                            {{ $b }} ?</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control input-lg" id="math-captcha"
                                            name="math_captcha" placeholder="???">
                                        <input type="hidden" name="correct_sum" value="{{ $sum }}">
                                    </div>
                                </div>
                            </div>
                            @if ($errors->has('math_captcha'))
                                <small class="text-danger">{{ $errors->first('math_captcha') }}</small>
                            @endif
                        </div>
                        <div class="login-buttons">
                            <button type="submit" class="btn btn-success btn-block btn-lg">Login</button>
                        </div>
                        <hr />
                        <footer class="main-footer mt-2"
                            style="display: flex; align-items: center; justify-content: center;">
                            <small>
                                <span style="margin-right: 10px;">&copy;{{ date('Y') }} <a
                                        href="https://diskominfo.batubarakab.go.id" target="blank">Dinas Komunikasi dan
                                        Informatika Kab. Batu Bara</a> All rights reserved.</span>
                            </small>
                        </footer>
                    </form>
                </div>
            </div>
        </div>


    </div>
    <!-- end page container -->

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
    <script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
    <script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
    <!-- ================== END BASE JS ================== -->

    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="assets/js/apps.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->

    <script>
        $(document).ready(function() {
            App.init();
        });

        setTimeout(() => {
            document.querySelectorAll('.text-danger').forEach((element) => {
                element.style.display = 'none';
            });
        }, 5000);

        document.addEventListener("DOMContentLoaded", function() {
            const emailInput = document.getElementById("email");
            const passwordInput = document.getElementById("password");
            const submitButton = document.querySelector("button[type='submit']");

            function checkFormValidity() {
                const emailValue = emailInput.value.trim();
                const passwordValue = passwordInput.value.trim();
                submitButton.disabled = !(emailValue && passwordValue);
            }

            emailInput.addEventListener("input", checkFormValidity);
            passwordInput.addEventListener("input", checkFormValidity);

            checkFormValidity();
        });
    </script>
</body>

</html>
