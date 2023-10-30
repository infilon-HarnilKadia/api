@include('header')
<div class="page-content-wrapper">
    @include('flash')

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Masters</li>
                    <li class="breadcrumb-item active">Trailer Master</li>
                </ol>
            </nav>
        </div>
        <div class="content-viewport">
            <div class="row">
                <div class="col-lg-12">
                    <div class="grid">
                        <p class="grid-header">Trailer Master</p>
                        <div class="item-wrapper">
                            <div class="text-left pl-3 pb-2 clearfix">
                                <a href="{{url('master-trailer/add')}}" class="btn btn-sm btn-primary btn-icon"><i class="mdi mdi-plus"></i>Add</a>
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
                                            <?php if(in_array('sr no.', $mth)){ ?>
                                            <th>Sr No.</th>
                                            <?php } ?>
                                            <?php if(in_array('trailer number', $mth)){ ?>
                                            <th>Trailer Number</th>
                                            <?php } ?>
                                            <?php if(in_array('model of trailer', $mth)){ ?>
                                            <th>Model of Trailer</th>
                                            <?php } ?>
                                            <?php if(in_array('model year', $mth)){ ?>
                                            <th>Model Year</th>
                                            <?php } ?>
                                            <?php if(in_array('axles', $mth)){ ?>
                                            <th>Axles</th>
                                            <?php } ?>
                                            <?php if(in_array('tyres type', $mth)){ ?>
                                            <th>Tyres Type</th>
                                            <?php } ?>
                                            <?php if(in_array('trailer type', $mth)){ ?>
                                            <th>Trailer Type</th>
                                            <?php } ?>
                                            <?php if(in_array('gross weight', $mth)){ ?>
                                            <th>Gross Weight</th>
                                            <?php } ?>
                                            <?php if(in_array('gvm', $mth)){ ?>
                                            <th>GVM</th>
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
                                    if(count($data)) {
                                        foreach ($data as $d) {
                                        ?>
                                        <tr>
                                            <?php if(in_array('sr no.', $mth)){ ?>
                                            <td><?php echo $d['id']; ?></td>
                                            <?php } ?>
                                            <?php if(in_array('trailer number', $mth)){ ?>
                                            <td><?php echo $d['name']; ?></td>
                                            <?php } ?>
                                            <?php if(in_array('model of trailer', $mth)){ ?>
                                            <td><?php echo $d['model']; ?></td>
                                            <?php } ?>
                                            <?php if(in_array('model year', $mth)){ ?>
                                            <td><?php echo $d['year']; ?></td>
                                            <?php } ?>
                                            <?php if(in_array('axles', $mth)){ ?>
                                            <td><?php echo $d['axles']; ?></td>
                                            <?php } ?>
                                            <?php if(in_array('tyres type', $mth)){ ?>
                                            <td><?php echo $d['tyre']; ?></td>
                                            <?php } ?>
                                            <?php if(in_array('trailer type', $mth)){ ?>
                                            <td><?php echo $d['trailer']; ?></td>
                                            <?php } ?>
                                            <?php if(in_array('gross weight', $mth)){ ?>
                                            <td><?php echo $d['weight']; ?></td>
                                            <?php } ?>
                                            <?php if(in_array('gvm', $mth)){ ?>
                                            <td><?php echo $d['gvm']; ?></td>
                                            <?php } ?>          
                                            <?php if(in_array('edit', $mth)){ ?>
                                            <td><a href="{{ url('master-trailer/edit/'.enc(1,$d['id'])) }}"><i class="mdi mdi-border-color"></i></a></td>
                                            <?php } ?>
                                            <?php if(in_array('delete', $mth)){ ?>
                                            <td><a href="{{url('master-trailer/delete') }}/{{enc(1,$d['id'])}}" onClick="return confirm('Are you sure to delete?')"><i class="mdi mdi-delete"></i></a></td>
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
<script>
    $(".t-header .t-header-content-wrapper .t-header-search-box").css('display', 'flex');
</script>
@include('footer')