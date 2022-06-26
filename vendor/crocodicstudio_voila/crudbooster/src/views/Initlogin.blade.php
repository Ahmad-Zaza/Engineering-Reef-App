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
    <title>{{trans("crudbooster.page_title_login")}} : {{config('setting.AppName')}}</title>
    <meta name='generator' content='{{config('setting.AppName')}}'/>
    <meta name='robots' content='{{config('setting.AppName')}}'/>
    <link rel="shortcut icon"
          href="{{ CRUDBooster::getSetting('favicon')?asset(CRUDBooster::getSetting('favicon')):asset('vendor/crudbooster/assets/logo_crudbooster.png') }}">

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->

    @if (in_array(App::getLocale(), ['ar', 'fa']))
        <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">
        <link href="{{ asset("vendor/crudbooster/assets/rtl.css")}}" rel="stylesheet" type="text/css"/>
        <link rel='stylesheet' href='{{asset("vendor/crudbooster/assets/css/main_rtl.css").'?r='.time()}}'/>
    @else
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    @endif

    <style>

        body {
            font-family: "Droid Arabic Kufi";
            overflow-x:hidden;
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
            background-size: 100% 100%;
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
            margin-right: 35%;
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
        .formContact span:hover{
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
            width: 94%;
            margin-bottom:10px;
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
            -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
            -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
        }

        .formContact .btn{
            font-family: 'Droid Arabic Kufi';
        }
        .formContact .btn:hover{
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


    </style>
</head>

<body>
<div class="behindBg">
</div>
<div class="frontImg" style="background-image:url({{asset("vendor/crudbooster/login/img/login.png")}})">
    <div class="formContact">
        <form autocomplete='off' action="{{ route('InitpostLogin') }}" method="post">
            <p class='login-box-msg'>الرجاء إدخال اسم المستخدم وكلمة المرور</p>
            @if ( Session::get('message') != '' )
                <div class='alert alert-warning'>
                    {{ Session::get('message') }}
                </div>
            @endif
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            <div class="form-group">
                <input autocomplete='off' type="text" class="form-control" id="username" name='username' value="{{ old('username') }}" required  placeholder="اسم المستخدم">
            </div>

            <div class="form-group">
                <input autocomplete='off' type="password" class="form-control" id="pwd" name='password' value="" required placeholder="كلمة المرور">
            </div>
            <button type="submit" class="btn btn-default">{{trans("crudbooster.button_sign_in")}}</button>
{{--            <a href="{{route("getForgot")}}"><span>نسيت كلمة المرور ؟</span></a>--}}

        </form>
    </div>

</div>
<p class="alt">نقابة المهندسين - ريف دمشق</p>

<!-- <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js'></script> -->
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<!-- <script src="js/bootstrap.min.js"></script> -->
<script>
    $(document).ready(function () {
        $(".behindBg").animate({ "opacity": "0.1" }, 1000);
    });

    $('body').on('click',function(){
        $(".alert").fadeOut(700);
    });
</script>
</body>

</html>