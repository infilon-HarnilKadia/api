<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ env('PROJECT_NAME') }}</title>
    <meta name="theme-color" content="#3386C7" />
    <meta name="msapplication-navbutton-color" content="#3386C7" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#3386C7" />
    <link rel="stylesheet" href="{{ url('') }}/assets/vendors/iconfonts/mdi/css/materialdesignicons.css">
    <link rel="stylesheet" href="{{ url('') }}/assets/vendors/css/vendor.addons.css">
    <link rel="stylesheet" href="{{ url('') }}/assets/css/style.css">
    <link rel="stylesheet" href="{{ url('') }}/assets/css/custom.css?c_<?php echo date('Ymd'); ?>">
    <link rel="shortcut icon" href="{{ url('') }}/assets/images/0.png?c">
    <style>
        .logo-section p{
            text-align: center;
            font-size: 22px;
            font-weight: 500;
            color: #6d6e71;
        }
        .col-md-8.banner-section:after{
            content: '';
            opacity: 0;
        }

        span.notis{    display: block;
            background: #3386c7;
            font-size: 15px;
            line-height: normal;
            color: #fff;
            width: 100%;
            margin-top: 10px;
            padding: 15px 20px;
            border-radius: 5px;
            font-weight: 600;
        }
        .noticebx{ margin-top: 25px; color: #3386c7; font-weight: 600; font-size: 15px; }
    </style>
</head>
<body>
<div class="authentication authentication-theme auth-style_2">
    <div class="row inner-wrapper">
        <div class="col-md-8 banner-section">
        </div>
        <div class="col-md-4 form-section">
            <?php
            if(isset($_GET['reset']) && ($_GET['reset'] == "updated")){
                echo '<span class="notis">Your password has successfully changed...</span>';
            }
            ?>
            <div id="hex3" class="hexagon-wrapper">
                <img src="{{ url('') }}/assets/images/samples/bg.svg">
            </div>
            <div class="logo-section"><a href="{{ url('') }}" class="logo"><img src="{{ url('') }}/assets/images/logo.png" alt="logo"></a><p class="d-none"><br>{{ env('PROJECT_NAME') }}</p></div>
            <form action="#" method="post"  id="hasforms" class="logins">
                @csrf
                <div class="form-group"><input name="user" type="text" class="form-control" placeholder="Username" autofocus tabindex="1"></div>
                <div class="form-group"><input name="password" type="password" class="form-control" tabindex="2"  placeholder="Password"></div>
                <div class="form-group text-right"> <a href="#" class="resetview">Reset Password?</a> </div>
                <button type="submit" class="btn btn-primary btn-sm btn-icon" tabindex="3"><i class="mdi mdi-arrow-right"></i>Login</button>
            </form>

            <form action="#" method="post" class="reset-psw" style="display: none"> <div class="form-group"><input name="user" type="text" class="form-control" placeholder="Username" autofocus tabindex="1" value=""></div> <div class="form-group text-right"> <a href="#" class="logoiview">Login?</a> </div> <button type="submit" class="btn btn-primary btn-sm btn-icon" tabindex="2"><i class="mdi mdi-arrow-right"></i>Reset Password</button> <p class="noticebx" style="display: none">Your password has been successfully requested to reset. check your registred email address.</p> </form>
            <div class="logs-sm">
            <br>
            <br>
            <br>
            <br></div>
            <div class="signup-link"><p>© <?php echo date('Y'); ?> {{ env('PROJECT_NAME') }}. All rights reserved.</p></div>
        </div>
    </div>
</div>
<script>
    var base_url = "{{ url('') }}/";
</script>
<script src="{{ url('') }}/assets/vendors/js/core.js"></script>
<script>/*
    $('.authentication-theme.auth-style_2').css('min-height', (window.innerHeight)-28);
    $(window).resize(function () {
        $('.authentication-theme.auth-style_2').css('min-height', (window.innerHeight)-28);
    });*/
</script>
<script src="{{ url('') }}/assets/vendors/js/vendor.addons.js"></script>
<script src="{{ url('') }}/assets/js/script.js"></script>
<script>
    $(document).ready(function () {
        $(".resetview").click(function () {
            $(".logins").hide();
            $(".reset-psw").fadeIn();
            return false;
        });
        $(".logoiview").click(function () {
            $(".reset-psw").hide();
            $(".logins").fadeIn();
            return false;
        });
        $("form").attr("autocomplete", "off");
        $(".authentication form.logins").submit(function () {
            $this = $(this);
            $this.find('button').html("<i class='mdi mdi-refresh mdi-spin'></i>Please wait...").attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                headers: {
                    'X-Height': window.outerHeight,
                    'X-Width': window.outerWidth
                },
                url: base_url + 'auth/login',
                data: $this.serialize(),
                success: function () {
                    $this.find('button').html("<i class='mdi mdi-check'></i>Success...");
                    window.location.href = base_url + "auth/check";
                    setInterval(function () {
                        window.location.href = base_url + "auth/check";
                    }, 2000);
                },
                error: function () {
                    $this.find('button').html("<i class='mdi mdi-close'></i>Try again...");
                    setTimeout(function () {
                        $this.find('button').html("<i class='mdi mdi-arrow-right'></i>Login").removeAttr("disabled");
                    }, 1500);
                }
            });
            return false;
        });

        $(".authentication form.reset-psw").submit(function () {
            $this = $(this);
            $this.find('button').html("<i class='mdi mdi-refresh mdi-spin'></i>Please wait...").attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: base_url + 'auth/reset-pass',
                data: $this.serialize(),
                success: function () {
                    $this.find('button').html("<i class='mdi mdi-check'></i>Success...");
                    $this.find('p').fadeIn();
                },
                error: function () {
                    $this.find('button').html("<i class='mdi mdi-close'></i>Try again...");
                    setTimeout(function () {
                        $this.find('button').html("<i class='mdi mdi-arrow-right'></i>Reset Password").removeAttr("disabled");
                    }, 1500);
                }
            });
            return false;
        });
    });
</script>
</body>
</html>