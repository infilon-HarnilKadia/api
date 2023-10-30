@include('header')
<div class="page-content-wrapper">
    @include('flash')

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Daily Location</li>
                    <li class="breadcrumb-item active">Daily Location</li>
                </ol>
            </nav>
        </div>
        <div class="content-viewport">
            <div class="row">
                <div class="col-lg-12">
                    <div class="grid">
                        <p class="grid-header">Daily Location</p>
                        <div class="item-wrapper">
                            <div class="text-left pl-3 pb-2 clearfix">
                                <a href="{{ url('daily-location/add') }}" class="btn btn-sm btn-primary btn-icon"><i
                                        class="mdi mdi-plus"></i>Add Location</a>
                                <a href="#." class="btn btn-success searchby btn-icon btn-sm"><i
                                        class="mdi mdi-file-search"></i>Search</a>
                                <div class="settings-btn">
                                    <a href="#" class="btn setting btn-icon btn-success btn-sm"><i
                                            class="mdi mdi-settings"></i></a>
                                    <div class="settings">
                                        {{-- <?php
                                        $mth = explode(',', $th);
                                        get_setting($classname, $mth, 1);
                                        ?> --}}
                                    </div>
                                </div>
                            </div>
                            <?php
                            exl_search([
                                'account.name' => 'DN',
                                'a2.name' => 'Customer',
                                'a2.loading_date' => 'Loading Date',
                                'a2.weight' => 'Weight',
                                'account.rate' => 'Rate',
                                'account.i_num' => 'Invoice Number',
                                'account.invoice_date' => 'Invoice Date',
                            ]);
                            ?>
                            <div class="data-responsive">
                                <table class="table search" id="gmm-ajax-table"
                                    data-url="{{ url('accounts/ajax-data') }}" data-order="[[ 1, &quot;desc&quot; ]]">
                                    <thead>

                                        <tr>
                                            <th data-name="name">DN</th>
                                            <th data-name="t_value">Customer</th>
                                            <th data-name="narration">Loading Date</th>
                                            <th data-name="r_num">Weight</th>
                                            <th data-name="rate">Rate</th>
                                            <th data-name="i_num">Invoice Number</th>
                                            <th data-name="invoice_date">Invoice Date</th>
                                            <th class="text-center">Invoice</th>
                                            <th class="text-center">Forma Invoice</th>
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
