@include('header')
<div class="page-content-wrapper">
    @include('flash')
    <style>
        td.ipino {
            text-align: center;
        }

        .flag-icon {
            margin-right: 4px;
        }
    </style>

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item ">Admin Master</li>
                    <li class="breadcrumb-item active">Admin List</li>
                </ol>
            </nav>
        </div>
        <div class="content-viewport">
            <div class="row">
                <div class="col-lg-12">
                    <div class="grid">
                        <p class="grid-header">List of Admin</p>
                        <div class="item-wrapper">
                            <div class="text-left pl-3 pb-2 clearfix">
                                <div class="settings-btn">
                                    <a href="#" class="btn setting btn-icon btn-success btn-sm"><i class="mdi mdi-settings"></i></a>
                                    <div class="settings">
                                    <?php
                                    $mth = explode(',' ,$th);
                                    get_setting($classname, $mth, 1);
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <div class="data-responsive">
                                <table class="table search" id="gmm-table">
                                    <thead>
                                        <tr>
                                            <?php
                                        $mth = explode(',' ,$th);
                                        ?>
                                            <?php if(in_array('name', $mth)){ ?>
                                            <th>Name</th>
                                            <?php } ?>
                                            <?php if(in_array('username', $mth)){ ?>
                                            <th>Username</th>
                                            <?php } ?>
                                            <?php if(in_array('password', $mth)){ ?>
                                            <th>Password</th>
                                            <?php } ?>
                                            <?php if(in_array('last login', $mth)){ ?>
                                            <th>Last Login</th>
                                            <?php } ?>
                                            <?php if(in_array('location', $mth)){ ?>
                                            <th class="text-center">Location</th>
                                            <?php } ?>
                                            <?php if(in_array('system', $mth)){ ?>
                                            <th>System</th>
                                            <?php } ?>
                                            <?php if(in_array('lock', $mth)){ ?>
                                            <th class="text-center">Lock</th>
                                            <?php } ?>
                                            <?php if(in_array('edit', $mth)){ ?>
                                            <th class="text-center">Edit</th>
                                            <?php } ?>
                                            <?php if(in_array('delete', $mth)){ ?>
                                            <th class="text-center">Delete</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if(count($users)) {
                                        foreach ($users as $r) {
                                            $r['password'] = 'xx';
                                            ?>
                                        <tr>
                                            <?php if(in_array('name', $mth)){ ?>
                                            <td><?php echo $r['fname']; ?></td>
                                            <?php } ?>
                                            <?php if(in_array('username', $mth)){ ?>
                                            <td><?php echo $r['username']; ?></td>
                                            <?php } ?>
                                            <?php if(in_array('password', $mth)){ ?>
                                            <td><?php echo $r['password']; ?></td>
                                            <?php } ?>
                                            <?php if(in_array('last login', $mth)){ ?>
                                            <td><?php echo datefor($r['l_date']); ?></td>
                                            <?php } ?>
                                            <?php if(in_array('location', $mth)){ ?>
                                            <td class="ipino" data-ip="<?php echo $r['ip']; ?>">...</td>
                                            <?php } ?>
                                            <?php if(in_array('system', $mth)){ ?>
                                            <td><?php echo $r['osname']; ?>, <?php echo $r['name']; ?></td>
                                            <?php } ?><?php if(in_array('lock', $mth)){ ?><td class="lock"><a href="{{ url('admin/lock/'.$r['status'].'-'.enc(1, $r['id'])) }}"><i class="mdi mdi-lock<?php echo ($r['status'] == 2) ? "" : "-open-outline"; ?>"></i></a></td><?php } ?>
                                            <?php if(in_array('edit', $mth)){ ?>
                                            <td><a href="{{ url('admin/edit/'.$r['id']) }}"><i class="mdi mdi-border-color"></i></a></td>
                                            <?php } ?>
                                            <?php if(in_array('delete', $mth)){ ?>
                                            <td class="delete"><a href="{{ url('admin/delete/'.$r['id']) }}"><i class="mdi mdi-delete"></i></a></td>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="{{ url('assets/vendors/iconfonts/flag-icon-css/css/flag-icon.css')}}">
<script>
    setTimeout(function() {
        $("td.ipino").each(function() {
            var $thisnew = $(this);
            $ip = $thisnew.data('ip');
            if ($ip != "") {
                $thisnew.html('Wait...');
                $.get("http://ip-api.com/json/" + $ip, function(response) {
                    if (response.status != "fail") {
                        $thisnew.html("<p><i class='flag-icon flag-icon-" + response.countryCode.toLowerCase() + "'></i> " + response.country + "</p>");
                    } else {
                        $thisnew.html("...");
                    }
                })
            }
        });
    }, 2000);
</script>
<script>
    $(".t-header .t-header-content-wrapper .t-header-search-box").css('display', 'flex');
</script>
@include('footer')