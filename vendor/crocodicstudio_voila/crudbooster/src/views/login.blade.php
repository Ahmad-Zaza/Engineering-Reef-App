<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <!-- <link rel="icon" type="image/png" href="img/who-logo.png" /> -->

    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" class="en-style" href="css/rtl_style.css"> -->
    <meta charset="UTF-8">
    <title>{{ trans('crudbooster.page_title_login') }} : {{ config('setting.AppName') }}</title>
    <meta name='generator' content='{{ config('setting.AppName') }}' />
    <meta name='robots' content='{{ config('setting.AppName') }}' />
    <link rel="shortcut icon"
        href="{{ CRUDBooster::getSetting('favicon') ? asset(CRUDBooster::getSetting('favicon')) : asset('vendor/crudbooster/assets/logo_crudbooster.png') }}">

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->

    @if (in_array(App::getLocale(), ['ar', 'fa']))
        <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">
        <link href="{{ asset('vendor/crudbooster/assets/rtl.css') }}" rel="stylesheet" type="text/css" />
        <link rel='stylesheet' href='{{ asset('vendor/crudbooster/assets/css/main_rtl.css') . '?r=' . time() }}' />
    @else
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    @endif

    <!-- Font Awesome Icons -->
    <link href="{{ asset('vendor/crudbooster/assets/adminlte/font-awesome/css') }}/font-awesome.min.css"
        rel="stylesheet" type="text/css" />
    <style>
        .changePassword {
            position: absolute;
            left: 33px;
            z-index: 1;
            top: 29%;
            display: block;
            cursor: pointer;
            color: #c5161c;
        }
        body {
            font-family: "Droid Arabic Kufi";
            overflow-x: hidden;
            margin: 0;
            height: 100%;
            min-height: 100vh;
        }

        * {
            box-sizing: border-box;
        }

        .behindBg {
            position: relative;
            background-size: cover;
            width: 100%;
            height: 100vh;
            background-position: center center;
            background-repeat: no-repeat;
            opacity: 0;
        }

        .frontImg {
            background-size: 95% auto;
            /* position: absolute; */
            width: 100%;
            height: 100vh;
            background-repeat: no-repeat;
            background-position: center center;
            top: 0;
            min-height: 100vh;
        }

        .formContact {
            width: 30%;
            position: relative;
            /* background: #ededed; */
            background: #fbfbfb;
            padding: 20px 30px;
            margin-right: auto;
            margin-left: auto;
        }

        @media (max-width:1000px) {
            .formContact {
                width: 90%;
            }
        }

        @media (min-width:900px) {
            .formContact {
                transform: translatey(50%);
            }
        }

        .formContact p {
            font-size: 12px;
            text-align: center;
        }

        .formContact .form-control {
            border-radius: 0
        }

        .formContact button {
            display: block;
            margin: auto;
            background: #c5161c;
            padding: 3px 10px;
            border: none;
            border-radius: 0;
            color: #ffffff;
        }

        .formContact span {
            color: #c5161c;
            text-align: center;
            display: inline-block;
            font-size: 12px;
            margin-top: 10px;
        }

        .formContact span:hover {
            opacity: 0.7;
        }

        .alt {
            /* position: absolute; */
            bottom: 1vw;
            right: 25vw;
            text-transform: uppercase;
            color: #000000;
            font-weight: 700;
            font-size: 11px;
        }
        .form-group{
            position: relative;
        }
        .form-control {
            display: block;
            width: 94%;
            margin-bottom: 10px;
            padding: 6px 12px;
            font-family: 'Droid Arabic Kufi';
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
            box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
            -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        }

        .formContact .btn {
            font-family: 'Droid Arabic Kufi';
        }

        .formContact .btn:hover {
            cursor: pointer;
            opacity: 0.7;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-warning {
            color: #8a6d3b;
            background-color: #fcf8e3;
            border-color: #faebcc;
        }

        .login-box-msg {
            color: #c5161c;
        }

        .frontImg {
            background: {{ CRUDBooster::getSetting('login_background_color') ?: '#dddddd' }} url('{{ CRUDBooster::getSetting('login_background_image') ? asset(CRUDBooster::getSetting('login_background_image')) : asset('vendor/crudbooster/assets/bg_blur3.jpg') }}');
            color: {{ CRUDBooster::getSetting('login_font_color') ?: '#ffffff' }} !important;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            height: 100%;
        }

    </style>
</head>

<body>
    <div class="frontImg">
        <div class="formContact">
            <form autocomplete='off' action="{{ route('postLogin') }}" method="post">
                <p class='login-box-msg'>{{ trans('crudbooster.login_message') }} لتطبيق نقابة المهندسين - ريف دمشق
                </p>
                @if (Session::get('message') != '')
                    <div class='alert alert-warning'>
                        {{ Session::get('message') }}
                    </div>
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="form-group">
                    <input autocomplete='off' type="text" class="form-control" id="username" name='username'
                        value="{{ old('username') }}" required placeholder="الرقم النقابي">
                </div>

                <div class="form-group relative">
                    <input autocomplete='off' type="password" class="form-control" id="pwd" name='password' value=""
                        required placeholder="كلمة المرور">
                    <i class="fa fa-eye-slash changePassword"></i>
                </div>
                <button type="submit" class="btn btn-default">{{ trans('crudbooster.button_sign_in') }}</button>
                <a href="{{ route('getForgot') }}"><span>نسيت كلمة المرور ؟</span></a>

                <div class="pull-left">
                    <a href="{{url('support_file.pdf')}}" target="_blank" style="text-decoration:none;">
                        <span class="fa fa-download text-success">
                            <span style="color: black;">كتاب إرشادي للدخول إلى التطبيق</span>
                        </span>
                    </a>
                    &nbsp;
                    &nbsp;
                    <a href="https://api.whatsapp.com/send/?phone=963991060638&text&app_absent=0" target="_blank">
                        <span class="fa fa-whatsapp text-success">
                            <span style="color: black;">للدعم الفني</span>
                        </span>
                    </a>
                </div>

            </form>
        </div>

    </div>

    <!-- <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js'></script> -->
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <!-- <script src="js/bootstrap.min.js"></script> -->
    <script>
        $(document).ready(function() {
            $(".behindBg").animate({
                "opacity": "0.1"
            }, 1000);

            $(".changePassword").click(function() {
                let $password = $(this).closest(".form-group").find("input");
                if ($(this).hasClass("fa-eye")) {
                    $(this).removeClass("fa-eye").addClass("fa-eye-slash");
                    $password.attr("type", "password");
                } else {
                    $(this).removeClass("fa-eye-slash").addClass("fa-eye");
                    $password.attr("type", "text");
                }
            })
        });

        $('body').on('click', function() {
            $(".alert").fadeOut(700);
        });
    </script>
</body>

</html>
