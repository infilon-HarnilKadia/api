@include('header')
<div class="page-content-wrapper">
    @include('flash')

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Orders</li>
                    <li class="breadcrumb-item active">Booking Order</li>
                </ol>
            </nav>
        </div>
        <div class="content-viewport">
            <div class="row">
                <div class="col-lg-12">
                    <div class="grid">
                        <p class="grid-header">Booking Order</p>
                        <div class="item-wrapper">
                            <div class="text-left pl-3 pb-2 clearfix">
                                <a href="{{url('booking-order/add')}}" class="btn btn-sm btn-primary btn-icon"><i class="mdi mdi-plus"></i>Add</a>
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
                                'booking_order.b_date' => 'Date ',
                                'booking_order.name' => 'DN',
                                'a2.name' => 'Customer',
                                'booking_order.truck_no' => 'Truck Number',
                                'a3.name' => 'Trailer Number',
                                'a4.name' => 'Destination',
                                'booking_order.invoice' => 'Invoice Number',
                                'booking_order.i_date' => 'Invoice Date',
                                'booking_order.id' => 'Invoice Date'
                            ));
                            ?>
                            <div class="data-responsive">
                                <table class="table search" id="gmm-ajax-table" data-url="{{url('booking-order/ajax-data')}}" data-order="[[ 1, &quot;desc&quot; ]]">
                                    <thead>
                                        <tr>
                                            <th data-name="b_date">Date</th>
                                            <th data-name="name">DN</th>
                                            <th data-name="customer">Customer</th>
                                            <th data-name="truck_no">Truck Number</th>
                                            <th data-name="trailer">Trailer Number</th>
                                            <th data-name="destination">Destination</th>
                                            <th data-name="invoice">Invoice Number</th>
                                            <th data-name="i_date">Invoice Date</th>
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