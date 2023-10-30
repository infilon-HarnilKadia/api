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
                           
                            <?php
                            exl_search(array(
                                'booking_order.b_date' => 'Date ',
                                'booking_order.name' => 'DN',
                                'a2.name' => 'Customer',
                                'a5.name' => 'Truck Name',
                                'booking_order.truck_no' => 'Truck Number',
                                'a3.name' => 'Trailer Number',
                                'booking_order.loading' => 'Loading Point',
                                'a4.name' => 'Destination',
                                'booking_order.description' => 'Description',
                            ));
                            ?>
                            <div class="data-responsive">
                                <table class="table search" id="gmm-ajax-table" data-url="{{url('borders-border-details/ajax-data')}}" data-order="[[ 1, &quot;desc&quot; ]]">
                                    <thead>
                                        <tr>
                                            <th data-name="b_date">Date</th>
                                            <th data-name="name">DN</th>
                                            <th data-name="customer">Customer</th>
                                            <th data-name="truck_name">Truck Name</th>
                                            <th data-name="truck_no">Truck Number</th>
                                            <th data-name="trailer">Trailer Number</th>
                                            <th data-name="loading_point">Loading Point</th>
                                            <th data-name="destination">Destination</th>
                                            <th data-name="description">Description</th>
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