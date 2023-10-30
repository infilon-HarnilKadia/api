@include('header')
<div class="page-content-wrapper">
    @include('flash')
    <style>
        .msgverify {
            border: 2px solid #3374ba;
            padding: 10px 15px;
            margin-bottom: 15px;
            background: #ffffff;
            border-radius: 5px;
        }

        .msgverify span {
            display: block;
            color: #000;
            font-weight: 500;
        }

        .msgverify .text-success {
            color: #04583b !important;
        }

        .msgverify .text-danger {
            color: #bf141b !important;
        }

        .msgverify .text-success b {
            font-weight: 400;
        }

        .msgverify p b {
            font-weight: 600;
        }

        .msgverify p {
            position: relative;
            padding-left: 20px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .msgverify p:before {
            content: "\F156";
            position: absolute;
            font: normal normal normal 24px/1 "Material Design Icons";
            font-size: 17px;
            left: 0;
            top: 4px;
        }

        .msgverify p.text-success:before {
            content: "\F12C";
        }
    </style>

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                </ol>
            </nav>
        </div>
        <div class="content-viewport">
            <div class="row">
                <div class="col-lg-4">
                    <div class="grid">
                        <p class="grid-header">Change Password</p>
                        <div class="grid-body">
                            <div class="item-wrapper">
                                <form action="<?php echo url('password'); ?>" method="post">

                                    <div class="form-group"><label for="pp1">Old Password<i>*</i></label> <input type="text" class="form-control" id="pp1" placeholder="Enter Old Password" name="old" required="">
                                    </div>

                                    <hr>


                                    <div class="msgverify">
                                        <span>New Password must contain the following:</span>
                                        <p id="letter" class="text-danger"><b>Lowercase</b> letter</p>
                                        <p id="capital" class="text-danger"><b>Capital (uppercase)</b> letter</p>
                                        <p id="number" class="text-danger"><b>Number</b></p>
                                        <p id="length" class="text-danger">Minimum <b>8 characters</b></p>
                                    </div>

                                    <div class="form-group"><label for="pp2">New Password<i>*</i></label> <input type="text" class="form-control" id="pp2" autocomplete="false" placeholder="Enter New Password" name="new" required="">
                                    </div>

                                    <div class="form-group"><label for="pp3">Repeat Password<i>*</i></label> <input type="text" class="form-control" id="pp3" placeholder="Enter Repeat Password" name="repeat" required="">
                                    </div>

                                    <button type="submit" class="btn btn-sm btn-icon btn-primary disabled" disabled><i class="mdi mdi-check"></i>Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('footer')


<script>
    $(".content-viewport input").attr('type', 'password');

    var myInput = document.getElementById("pp2");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");

    var yess1 = 0;
    var yess2 = 0;
    var yess3 = 0;
    var yess4 = 0;

    myInput.onkeyup = function() {
        yess1 = 0;
        yess2 = 0;
        yess3 = 0;
        yess4 = 0;
        // Validate lowercase letters
        var lowerCaseLetters = /[a-z]/g;
        if (myInput.value.match(lowerCaseLetters)) {
            letter.classList.remove("text-danger");
            letter.classList.add("text-success");
            yess1 = 1;
        } else {
            letter.classList.remove("text-success");
            letter.classList.add("text-danger");
            yess1 = 0;
        }

        // Validate capital letters
        var upperCaseLetters = /[A-Z]/g;
        if (myInput.value.match(upperCaseLetters)) {
            capital.classList.remove("text-danger");
            capital.classList.add("text-success");
            yess2 = 1;
        } else {
            capital.classList.remove("text-success");
            capital.classList.add("text-danger");
            yess2 = 0;
        }

        // Validate numbers
        var numbers = /[0-9]/g;
        if (myInput.value.match(numbers)) {
            number.classList.remove("text-danger");
            number.classList.add("text-success");
            yess3 = 1;
        } else {
            number.classList.remove("text-success");
            number.classList.add("text-danger");
            yess3 = 0;
        }

        // Validate length
        if (myInput.value.length >= 8) {
            length.classList.remove("text-danger");
            length.classList.add("text-success");
            yess4 = 1;
        } else {
            length.classList.remove("text-success");
            length.classList.add("text-danger");
            yess4 = 0;
        }

        if (yess1 && yess2 && yess3 && yess4) {
            $(".content-viewport .btn").removeAttr('disabled').removeClass('disabled');
        } else {
            $(".content-viewport .btn").attr('disabled', 'disabled').addClass('disabled');
        }
    }

    $("#pp2, #pp3").keyup(function() {
        if ($('#pp2').val() == $('#pp3').val()) {
            $(".content-viewport .btn").removeAttr('disabled').removeClass('disabled');
        } else {
            $(".content-viewport .btn").attr('disabled', 'disabled').addClass('disabled');
        }
    });
</script>