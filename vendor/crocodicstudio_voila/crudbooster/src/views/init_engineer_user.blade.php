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

    <link href="{{ asset('vendor/crudbooster/assets/adminlte/font-awesome/css') }}/font-awesome.min.css"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    @if (in_array(App::getLocale(), ['ar', 'fa']))
        <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">
        <link href="{{ asset('vendor/crudbooster/assets/rtl.css') }}" rel="stylesheet" type="text/css" />
        <link rel='stylesheet' href='{{ asset('vendor/crudbooster/assets/css/main_rtl.css') . '?r=' . time() }}' />
    @endif

    <style>
        body {
            font-family: "Droid Arabic Kufi";
            overflow-x: hidden;
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
            position: absolute;
            width: 100%;
            height: 100vh;
            background-repeat: no-repeat;
            background-position: center center;
            top: 0
        }

        .formContact {
            width: 30%;
            position: relative;
            /* background: #ededed; */
            background: #fbfbfb;
            padding: 20px 30px;
            top: 27%;
            /* bottom: 27%; */
            margin-right: auto;
            margin-left: auto;
        }

        @media (max-width:1000px) {
            .formContact {
                width: 90%;
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
            margin: 0;
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
            position: absolute;
            bottom: 1vw;
            right: 25vw;
            text-transform: uppercase;
            color: #000000;
            font-weight: 700;
            font-size: 11px;
        }

        .form-control {
            display: block;
            /* width: 94%; */
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

        ul {
            list-style-type: none;
            padding-right: 0;
        }

        .form-group {
            position: relative;
        }

        .changePassword {
            position: absolute;
            left: 13px;
            z-index: 1;
            top: 31%;
            display: block;
            cursor: pointer;
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

        .flex {
            display: flex;
        }

        .flex-space-between {
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="behindBg">
    </div>
    <div class="frontImg">
        <div class="formContact">
            <form id="form" autocomplete='off'>
                <p class='login-box-msg'>الرجاء تغيير كلمة المرور الحالية</p>
                @if (Session::get('message') != '')
                    <div class='alert alert-warning'>
                        {{ Session::get('message') }}
                    </div>
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="form-group">
                    <input type="email" autocomplete='off' class="form-control" id="email" name='email'
                        value="{{ old('email') }}" required placeholder="لطفا أدخل بريدك الالكتروني"
                        oninvalid="this.setCustomValidity('الرجاء إدخال البريد الالكتروني بالشكل الصحيح')"
                        onchange="try{setCustomValidity('')}catch(e){}" oninput="setCustomValidity(' ')">
                </div>
                <div class="form-group">
                    <input type="password" autocomplete='off' class="form-control" id="password" name='password'
                        value="" required placeholder="أدخل كلمة المرور الجديدة">
                    <i class="fa fa-eye-slash changePassword"></i>
                </div>
                <div class="form-group">
                    <input type="password" autocomplete='off' class="form-control" id="password_confirmation"
                        name='password_confirmation' value="" required placeholder="أدخل كلمة المرور مرة أخرى">
                    <i class="fa fa-eye-slash changePassword"></i>
                </div>
                <div class="flex flex-space-between">

                    <button type="submit" class="btn btn-default">{{ trans('crudbooster.button_sign_in') }}</button>
                    <a href="{{ url('modules/logout') }}"
                        class="btn btn-secondary">{{ trans('crudbooster.button_cancel') }}</a>
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
            $("#form").submit(function(event) {
                event.preventDefault();
                if ($("#password").val() != $("#password_confirmation").val()) {
                    $(".login-box-msg").after(`
                        <div class='alert alert-warning'>
                            كلمات السر غير متطابقة
                        </div>
                    `);

                    setTimeout(function() {
                        $("form .alert-warning").remove();
                    }, 2500);
                } else {
                    $.ajax({
                        url: "{{ route('InitEngineerUser') }}",
                        data: $("#form").serialize(),
                        method: "POST",
                        success: function(response) {
                            window.location.href = "{{ url('modules/login') }}";
                        }
                    });
                }
            });
            
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