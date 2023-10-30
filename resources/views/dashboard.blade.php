@include('header')
<style>
    #current-circle-progresses {
        position: relative;
    }

    #current-circle-progresses canvas {
        margin: auto;
        display: block;
    }

    #chartjs-tooltip {
        z-index: 999999;
        font-family: inherit !important;
    }

    .wrapper.mt-2 h3 {
        border-bottom: 2px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 10px;
        margin-left: -25px;
        margin-right: -25px;
        padding-left: 25px;
    }

    .wrapper.mt-2 small {
        display: block;
        text-align: center;
        margin-bottom: 7px;
    }

    #current-circle-progresses .circle-progress-value {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        margin: auto;
        text-align: center;
    }

    span.animated-count1 {
        color: #777;
    }

    div#chartjs-tooltip * {
        white-space: nowrap !important;
        font-family: inherit !important;
    }
</style>
<div class="page-content-wrapper">
    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Overview</li>
                </ol>
            </nav>
        </div>
        <div class="content-viewport">
            <h1 style="color:#888888" class="mb-5">Welcome to {{ env('PROJECT_NAME') }} - Moving With Care Everywhere</h1>
            <div class="row changedcvs d-none hidden dnone">
                <div class="col-md-3 equel-grid">
                    <div class="grid">
                        <a href="#" class="grid-body text-center">
                            <p class="card-title">Pending Calls</p>
                            <div class=" component-flat mx-auto">
                                <i class="mdi mdi-phone "></i>
                            </div>
                            <h2 class="font-weight-medium"><span class="animated-count1">11</span></h2>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 equel-grid">
                    <div class="grid">
                        <a href="#" class="grid-body text-center">
                            <p class="card-title">Assigned Calls</p>
                            <div class=" component-flat mx-auto">
                                <i class="mdi mdi-phone-forward "></i>
                            </div>
                            <h2 class="font-weight-medium"><span class="animated-count1">11</span></h2>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 equel-grid">
                    <div class="grid">
                        <a href="#" class="grid-body text-center">
                            <p class="card-title">Completed Calls</p>
                            <div class=" component-flat mx-auto">
                                <i class="mdi mdi-phone-hangup "></i>
                            </div>
                            <h2 class="font-weight-medium"><span class="animated-count1">11</span></h2>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 equel-grid">
                    <div class="grid">
                        <a href="#" class="grid-body text-center">
                            <p class="card-title">Total Calls</p>
                            <div class=" component-flat mx-auto">
                                <i class="mdi mdi-phone-in-talk "></i>
                            </div>
                            <h2 class="font-weight-medium"><span class="animated-count1">11</span></h2>
                        </a>
                    </div>
                </div>
            </div>
            <style>
                .changedcvs .component-flat.mx-auto i {
                    font-size: 35px;
                    color: #3386C7;
                    margin: 14px 0 10px;
                    display: block;
                }
            </style>

        </div>
    </div>
</div>
@include('footer')