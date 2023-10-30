@include('header')
<div class="page-content-wrapper">
    @include('flash')

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Orders</li>
                    <li class="breadcrumb-item active">Delivery Note</li>
                </ol>
            </nav>
        </div>
        <div class="content-viewport">
            <div class="row">
                <div class="col-lg-12">
                    <div class="grid">
                        <p class="grid-header">Delivery Note</p>
                        <div class="item-wrapper">
                            <div class="text-left pl-3 pb-2 clearfix">
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
                                'booking_order.name' => 'DN',
                                'booking_order.b_date' => 'Date ',
                                'a2.name' => 'Customer',
                                'a3.name' => 'Loading From',
                                'a4.name' => 'Destination',
                                'booking_order.description' => 'Description',
                                'booking_order.weight' => 'Weight'
                            ));
                            ?>
                            <div class="data-responsive">
                                <table class="table search" id="gmm-ajax-table" data-url="{{url('delivery-note/ajax-data')}}">
                                    <thead>
                                        <tr>
                                            <th data-name="name">DN</th>
                                            <th data-name="b_date">Date</th>
                                            <th data-name="customer">Customer</th>
                                            <th data-name="loading">Loading From</th>
                                            <th data-name="destination">Destination</th>
                                            <th data-name="description">Description</th>
                                            <th data-name="weight">Weight</th>
                                            <th class="text-center">Print/PDF</th>
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