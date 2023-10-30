@php
    $page_per = get_user_permission('key');
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title : 'Dashboard' }} - {{ env('PROJECT_NAME') }}</title>
    <meta name="theme-color" content="#3386C7" />
    <meta name="msapplication-navbutton-color" content="#3386C7" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#3386C7" />
    <link rel="stylesheet" href="{{ url('assets/vendors/iconfonts/mdi/css/materialdesignicons.css') }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/css/vendor.addons.css') }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/iconfonts/simple-line-icon/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/custom.css') }}?{{ date('ymdH') }}">
    <script src="{{ url('assets/vendors/js/core.js') }}"></script>
    <link rel="shortcut icon" href="{{ url('assets/images/0.png') }}?t=0">
    <script>
        var base_url = "{{ url('/') }}/";
    </script>
</head>

<body class="header-fixed">
    <nav class="t-header">
        <div class="idex-loader"></div>
        <div id="hex3" class="hexagon-wrapper">
            <!--            <img src="{{ url('assets/images/samples/bg.svg') }}">-->
        </div>
        <div class="t-header-brand-wrapper">
            <a href="#.">
                <img class="logo" src="{{ url('assets/images/0.png') }}" alt="">
                <img class="logo-mini" src="{{ url('assets/images/0.png') }}" alt="">
            </a>
            <button class="t-header-toggler t-header-desk-toggler d-none d-lg-block arrow">
                <svg class="logo" viewBox="0 0 200 200">
                    <path class="top"
                        d=" M 40, 80 C 40, 80 120, 80 140, 80 C180, 80 180, 20 90, 80 C 60,100 30,120 30,120 "></path>
                    <path class="middle" d=" M 40,100 L140,100 "></path>
                    <path class="bottom"
                        d=" M 40,120 C 40,120 120,120 140,120 C180,120 180,180 90,120 C 60,100 30, 80 30, 80 "></path>
                </svg>
            </button>
        </div>
        <div class="t-header-content-wrapper">
            <div class="t-header-content">
                <button class="t-header-toggler t-header-mobile-toggler d-block d-lg-none"><i class="mdi mdi-menu"></i>
                </button>
                <form action="#" class="t-header-search-box" method="get">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="mdi mdi-magnify"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control" id="inlineFormInputGroup" autofocus
                            placeholder="Search" autocomplete="off" name="q">
                    </div>
                </form>
                <ul class="nav ml-auto">
                    <li class="nav-item dropdown"><a class="nav-link" href="#" id="messageDropdown"
                            data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-bell-outline"></i> <span
                                class="notification-indicator notification-indicator-primary notification-indicator-ripple"></span></a>
                        <div class="dropdown-menu navbar-dropdown dropdown-menu-right"
                            aria-labelledby="messageDropdown">
                            <div class="dropdown-header">
                                <h6 class="dropdown-title">Messages</h6>
                                <p class="dropdown-title-text">...</p>
                            </div>
                            <div class="dropdown-body"></div>
                            <div class="dropdown-footer"><a href="#.">View All</a></div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="page-body">
        <div class="sidebar">
            <ul class="navigation-menu">
                <li class="nav-category-divider">MAIN</li>
                @if (in_array(1, $page_per))
                    <li><a href="{{ url('dashboard') }}"><span class="link-title">Dashboard</span> <i
                                class="mdi mdi-home-outline link-icon"></i></a></li>
                @endif
                <li><a href="#admas" class="parentm collapsed" data-toggle="collapse" aria-expanded="false"><span
                            class="link-title">Masters</span> <i class="mdi mdi-truck link-icon"></i></a>
                    <ul class="navigation-submenu collapse" id="admas" style="">
                        @if (in_array(70, $page_per))
                            <li><a href="{{ url('master-truck') }}"><span class="link-title">Truck Master</span></a>
                            </li>
                        @endif
                        @if (in_array(77, $page_per))
                            <li><a href="{{ url('master-trailer') }}"><span class="link-title">Trailer
                                        Master</span></a></li>
                        @endif
                        @if (in_array(78, $page_per))
                            <li><a href="{{ url('master-customer') }}"><span class="link-title">Customer
                                        Master</span></a></li>
                        @endif
                        @if (in_array(80, $page_per))
                            <li><a href="{{ url('master-supplier') }}"><span class="link-title">Supplier
                                        Master</span></a></li>
                        @endif
                        @if (in_array(75, $page_per))
                            <li><a href="{{ url('master-driver') }}"><span class="link-title">Driver
                                        Master</span></a></li>
                        @endif
                        @if (in_array(76, $page_per))
                            <li><a href="{{ url('master-location') }}"><span class="link-title">Location
                                        Master</span></a></li>
                        @endif
                        @if (in_array(81, $page_per))
                            <li><a href="{{ url('master-loading-point') }}"><span class="link-title">Loading Point
                                        Master</span></a></li>
                        @endif
                        @if (in_array(79, $page_per))
                            <li><a href="{{ url('master-station') }}"><span class="link-title">Station
                                        Master</span></a></li>
                        @endif
                    </ul>
                </li>
                <li><a href="#ods" class="parentm collapsed" data-toggle="collapse" aria-expanded="false"><span
                            class="link-title">Orders</span> <i
                            class="mdi mdi-file-document-box-outline link-icon"></i></a>
                    <ul class="navigation-submenu collapse" id="ods" style="">
                        @if (in_array(82, $page_per))
                            <li><a href="{{ url('booking-order') }}"><span class="link-title">Booking
                                        Order</span></a></li>
                        @endif
                        @if (in_array(83, $page_per))
                            <li><a href="{{ url('delivery-note') }}"><span class="link-title">Delivery
                                        Note</span></a></li>
                        @endif
                        @if (in_array(84, $page_per))
                            <li><a href="{{ url('shortfall-form') }}"><span class="link-title">Shortfall
                                        Form</span></a></li>
                        @endif
                        @if (in_array(85, $page_per))
                            <li><a href="{{ url('fuel-purchase-order') }}"><span class="link-title">Fuel Purchase
                                        Order</span></a></li>
                        @endif
                        <li><a href="#."><span class="link-title">Fuel Chart</span></a></li>
                        <li><a href="#."><span class="link-title">Fuel Station Chart</span></a></li>
                    </ul>
                </li>
                <li><a href="#bro" class="parentm collapsed" data-toggle="collapse" aria-expanded="false"><span
                            class="link-title">Borders</span> <i class="mdi mdi-map-outline link-icon"></i></a>
                    <ul class="navigation-submenu collapse" id="bro" style="">
                        @if (in_array(86, $page_per))
                            <li><a href="{{ url('borders-border-details') }}"><span class="link-title">Border
                                        Details</span></a></li>
                        @endif
                        @if (in_array(87, $page_per))
                            <li><a href="{{ url('borders-loading-chart') }}"><span class="link-title">Loading
                                        Chart</span></a></li>
                        @endif
                        @if (in_array(88, $page_per))
                            <li><a href="{{ url('borders-customer-chart') }}"><span class="link-title">Customer
                                        Chart</span></a></li>
                        @endif
                    </ul>
                </li>
                @if (in_array(89, $page_per))
                    <li><a href="{{url('accounts')}}" class="parentm collapsed"><span class="link-title">Account</span> <i
                                class="mdi mdi-file-document-outline link-icon"></i></a>
                    </li>
                @endif
                @if (in_array(90, $page_per))
                <li><a href="{{url('daily-location')}}" class="parentm collapsed"><span class="link-title">Location</span> <i
                            class="mdi mdi-map-marker-outline link-icon"></i></a>
                </li>
            @endif
                <li class="nav-category-divider">SETTING</li>
                @if (in_array(3, $page_per))
                    <li><a href="#menu6" data-toggle="collapse" aria-expanded="false"><span
                                class="link-title">Admin Master</span> <i
                                class="mdi mdi-account-settings link-icon"></i></a>
                        <ul class="collapse navigation-submenu" id="menu6">
                            <li><a href="{{ url('admin') }}">Admin List</a></li>
                            <li><a href="{{ url('admin/add') }}">Create Admin</a></li>
                        </ul>
                    </li>
                @endif
                @if (in_array(2, $page_per))
                    <li><a href="{{ url('password') }}"><span class="link-title">Password</span> <i
                                class="mdi mdi-security link-icon"></i></a></li>
                @endif
                <li><a href="{{ url('logout') }}"><span class="link-title">Logout</span> <i
                            class="mdi mdi-exit-to-app link-icon"></i></a></li>
            </ul>

            <div class="sidebar_footer">
                <div class="user-account">
                    <div class="user-profile-item-tittle">Setting</div>
                    <a href="#." class="user-profile-item" id="hastimers" data-time="<?php echo time(); ?>"><i
                            class="mdi mdi-clock-outline"></i> ...</a>
                    <a class="user-profile-item" href="{{ url('dashboard') }}"><i class="mdi mdi-home-outline"></i>
                        Dashboard</a>
                    <a class="user-profile-item" href="{{ url('password') }}"><i class="mdi mdi-security"></i>
                        Change Password</a>
                    <a class="btn btn-primary btn-logout" href="{{ url('logout') }}">Logout</a>
                </div>
                <div class="btn-group admin-access-level">
                    <div class="avatar">
                        <img class="profile-img" src="{{ url('assets/images/profile.svg') }}" alt="">
                    </div>
                    <div class="user-type-wrapper">
                        <p class="user_name">{{ env('PROJECT_NAME') }}</p>
                        <div class="d-flex align-items-center">
                            <div class="status-indicator small rounded-indicator bg-success"></div>
                            <small class="user_access_level">Admin</small>
                        </div>
                    </div>
                    <i class="arrow mdi mdi-chevron-right"></i>
                </div>
            </div>
        </div>
        <script>
            var newtimersec = parseInt(0);
            var newtimer = parseInt($("#hastimers").data('time'));
            var checktodaystime = parseInt(0);
            var mgdate_times = '';
            $(window).bind('load', function() {
                setInterval(function() {
                    newtimersec = newtimersec + 1;
                    checktodaystime = newtimer + newtimersec;
                    mgdate_times = new Date(parseInt(checktodaystime + '000') - 1);
                    $("#hastimers").html('<i class="mdi mdi-clock-outline"></i> ' + mgdate_times
                        .toLocaleTimeString());
                }, 1000);
            });
        </script>
