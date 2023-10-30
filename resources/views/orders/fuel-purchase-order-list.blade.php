@include('header')
<div class="page-content-wrapper">
    @include('flash')

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Orders</li>
                    <li class="breadcrumb-item active">Fuel Purchase Order</li>
                </ol>
            </nav>
        </div>
        <div class="content-viewport">
            <div class="row">
                <div class="col-lg-12">
                    <div class="grid">
                        <p class="grid-header">Fuel Purchase Order</p>
                        <div class="item-wrapper">
                            <div class="text-left pl-3 pb-2 clearfix">
                                <a href="{{url('fuel-purchase-order/add')}}" class="btn btn-sm btn-primary btn-icon"><i class="mdi mdi-plus"></i>Add</a>
                                <a href="#." class="btn btn-success searchby btn-icon btn-sm"><i class="mdi mdi-file-search"></i>Search</a>
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
                            <?php
                            exl_search(array(
                                'fuel_purchase_order.name' => 'FPO No.',
                                'fuel_purchase_order.b_date' => 'Date',
                                'a2.name' => 'DN',
                                'a3.name' => 'Supplier',
                                'a4.name' => 'From',
                                'a5.name' => 'Truck',
                                'a6.name' => 'Trailer',
                                'fuel_purchase_order.qty_lts' => 'Qty Lts'
                            ));
                            ?>
                            <div class="data-responsive">
                                <table class="table search" id="gmm-ajax-table" data-url="{{url('fuel-purchase-order/ajax-data')}}" data-order="[[ 1, &quot;desc&quot; ]]">
                                    <thead>
                                        <tr>
                                            <th data-name="name">FPO No.</th>
                                            <th data-name="b_date">Date</th>
                                            <th data-name="dn">DN</th>
                                            <th data-name="supplier">Supplier</th>
                                            <th data-name="from">From</th>
                                            <th data-name="truck">Truck</th>
                                            <th data-name="trailer">Trailer</th>
                                            <th data-name="qty_lts">Qty Lts</th>
                                            <th class="text-center">Edit</th>
                                            <th class="text-center">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
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