<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <!-- <link rel="icon" type="image/png" href="img/who-logo.png" /> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" class="en-style" href="css/rtl_style.css"> -->
    <meta charset="UTF-8">
    <title>{{trans("crudbooster.page_title_login")}} : {{Session::get('appname')}}</title>
    <meta name='generator' content='{{Session::get('appname')}}'/>
    <meta name='robots' content='{{Session::get('appname')}}'/>
    <link rel="shortcut icon"
          href="{{ CRUDBooster::getSetting('favicon')?asset(CRUDBooster::getSetting('favicon')):asset('vendor/crudbooster/assets/logo_crudbooster.png') }}">

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->



    <style>
        @font-face {
            font-family: "GothicRegular";
            src: url("{{asset("vendor/crudbooster/login/font/CenturyGothic.ttf")}}");
        }

        body {
            font-family: "GothicRegular";
            
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
            margin-left: 35%;
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
            display: block;
            font-size: 10px;
            margin-top: 10px;
        }

        .alt {
            position: absolute;
            bottom: 1vw;
            left: 25vw;
            text-transform: uppercase;
            color: #000000;
            font-weight: 700;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="behindBg">
    </div>
    <div class="frontImg" style="background-image:url({{asset("vendor/crudbooster/login/img/login.png")}})">
        <div class="formContact">
            <form autocomplete='off' action="{{ route('postLogin') }}" method="post">
                <p class='login-box-msg'>{{trans("crudbooster.login_message")}}</p>
                 @if ( Session::get('message') != '' )
                <div class='alert alert-warning'>
                    {{ Session::get('message') }}
                </div>
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <div class="form-group">
                    <input autocomplete='off' type="text" class="form-control" id="email" name='email' required placeholder="Email">
                </div>
                <div class="form-group">
                    <input autocomplete='off' type="password" class="form-control" id="pwd" name='password' required placeholder="Passward">
                </div>
                <button type="submit" class="btn btn-default">{{trans("crudbooster.button_sign_in")}}</button>
                <a href="{{route("getForgot")}}"><span>Forget Password?</span></a>

            </form>
        </div>

    </div>
    <p class="alt">Solutions</p>

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