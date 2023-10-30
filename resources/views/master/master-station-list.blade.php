@include('header')
<div class="page-content-wrapper">
    @include('flash')

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Masters</li>
                    <li class="breadcrumb-item active">Station Master</li>
                </ol>
            </nav>
        </div>
        <div class="content-viewport">
            <div class="row">
                <div class="col-lg-12">
                    <div class="grid">
                        <p class="grid-header">Station Master</p>
                        <div class="item-wrapper">
                            <div class="text-left pl-3 pb-2 clearfix">
                                <a href="{{url('master-station/add')}}" class="btn btn-sm btn-primary btn-icon"><i class="mdi mdi-plus"></i>Add</a>
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
                                            <?php if(in_array('name', $mth)){ ?>
                                            <th>Name</th>
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
                                            <?php if(in_array('name', $mth)){ ?>
                                            <td><?php echo $d['name']; ?></td>
                                            <?php } ?>                       
                                            <?php if(in_array('edit', $mth)){ ?>
                                            <td><a href="{{ url('master-station/edit/'.enc(1,$d['id'])) }}"><i class="mdi mdi-border-color"></i></a></td>
                                            <?php } ?>
                                            <?php if(in_array('delete', $mth)){ ?>
                                            <td><a href="{{url('master-station/delete') }}/{{enc(1,$d['id'])}}" onClick="return confirm('Are you sure to delete?')"><i class="mdi mdi-delete"></i></a></td>
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