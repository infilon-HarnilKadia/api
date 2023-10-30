@include('header')
<style>
    .showicons i {
        font-size: 35px;
        display: inline-block;
        padding: 15px;
    }
    #iconslist i:hover{
        cursor: pointer;
        opacity: .6;
        transition-duration: .4s
    }
    .showicons i span {
        font-size: 12px;
        display: block;
        position: absolute;
        font-weight: bold;
        color: green;
        font-style: normal;
    }
</style>
<div class="page-content-wrapper">
    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Icons</li>
                </ol>
            </nav>
        </div>
        <div class="content-viewport">
            <div class="row changedcvs">
                <div class="col-lg-8 showicons">
                    <div class="grid">
                        <p class="grid-header">Icons<img id="loaderimg" src="{{ url('assets/images/load.svg') }}" style="height:80%;position:absolute;right:10%;top:5%;opacity:0"></p>
                        <div class="grid-body" id="iconslist">
                            <?php foreach ($icons as $i){ ?><i title="mdi mdi-<?php echo $i; ?>" class="mdi mdi-<?php echo $i; ?>"><span></span></i><?php } ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@include('footer')
<script>
    $(".t-header .t-header-content-wrapper .t-header-search-box").css('display', 'flex');
    $('#inlineFormInputGroup').keyup(delay(function () {
        myFunction();
    }, 500));
    function myFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("inlineFormInputGroup");
        filter = input.value.toUpperCase();
        table = document.getElementById("iconslist");
        tr = table.getElementsByClassName("mdi");
        for (i = 0; i < tr.length; i++) {
            td = tr[i];
            if (td) {
                txtValue = td.className;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }
    
    $(document).on('click','.showicons i',function(){
        var thisone_ = $(this);
        $('.showicons i span').text('').hide();
        thisone_.find('span').text('Copied').show();
        copyToClipboard(thisone_.attr('title'));
        setTimeout(function(){
            thisone_.find('span').text('').hide();
        },2000);
    });
    
    function copyToClipboard(text) {
        var aux = document.createElement("input");
        aux.setAttribute("value", text);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);
    }
</script>