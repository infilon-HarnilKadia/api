@include('header')
<div class="page-content-wrapper">
    <style>
        .theme{
            color: #21437F;
            text-align: right;
            font-weight: bold;
            margin-top: 30px
        }
        .dn-details { box-shadow: 0px 0px 10px 0px #ccc; padding: 10px; border-radius: 8px; border: 1px solid #21437f; }
    </style>

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Orders</li>
                    <li class="breadcrumb-item"><a href="{{ url('fuel-purchase-order') }}">Fuel Purchase Order</a></li>
                    <li class="breadcrumb-item active"><?php echo isset($result)?'Edit':'Add'; ?></li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="content-viewport">
                    <form action="{{ url('fuel-purchase-order/save') }}" method="post">
                        @csrf
                        @if(isset($id))
                        <input type="hidden" name="id" value="{{$id}}">
                        @endif
                        <div class="grid">
                            <p class="grid-header"><?php echo isset($result)?'Edit':'Add'; ?> Fuel Purchase Order</p>
                            <div class="grid-body">
                                <div class="item-wrapper">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Date<i>*</i></label> <input type="text" class="form-control simpledate" placeholder="Select Date" name="b_date" value="<?php echo isset($result['b_date'])?dateind($result['b_date']):date('d/m/Y'); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>FPO No.<i>*</i></label> <input type="text" class="form-control" placeholder="FPO No." value="<?php echo isset($result['name'])?$result['name'] : $fpo; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Supplied By<i>*</i></label>
                                                <select class="form-control select2" name="supplier" data-hidden="Select Supplier" data-value="<?php echo isset($result['supplier'])?$result['supplier']:''; ?>" required>
                                                    <option></option>
                                                    <?php foreach($supplier as $c){ ?>
                                                    <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>From<i>*</i></label>
                                                <select class="form-control select2" name="from" data-hidden="Select Location" data-value="<?php echo isset($result['from'])?$result['from']:''; ?>" required>
                                                    <option></option>
                                                    <?php foreach($location as $c){ ?>
                                                    <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group"><label>Delivery Note<i>*</i></label>
                                                <select class="form-control select2" name="dn" data-hidden="Select Delivery Note" data-value="<?php echo isset($result['dn'])?$result['dn']:''; ?>" required>
                                                    <option></option>
                                                    <?php foreach($dn as $c){ ?>
                                                    <option value="<?php echo $c['id']; ?>" data-truck="<?php echo $c['truck_no']; ?>" data-trailer="<?php echo $c['trailer']; ?>" data-driver="<?php echo $c['driver']; ?>"><?php echo $c['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="dn-details" style="display:none">
                                                <p data-class="truck">Truck: <span></span></p>
                                                <p data-class="trailer">Trailer: <span></span></p>
                                                <p data-class="driver">Driver: <span></span></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group"><label>Qty Lts<i>*</i></label> <input type="number" class="form-control" name="qty_lts" placeholder="Enter Qty Lts" value="<?php echo isset($result['qty_lts'])?$result['qty_lts'] : ''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group"><label>Price<i>*</i></label> <input type="number" class="form-control" name="price" placeholder="Enter Price" value="<?php echo isset($result['price'])?$result['price'] : ''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group"><label>Amount<i>*</i></label> <input type="number" class="form-control" name="amount" placeholder="Enter Amount" value="<?php echo isset($result['amount'])?$result['amount'] : ''; ?>" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="mb-3 theme">Total Amount: $<span><?php echo isset($result['amount'])?$result['amount'] : '00.00'; ?></span></h6>
                                    <button type="submit" class="btn btn-sm btn-primary btn-icon"><i class="mdi mdi-check"></i><?php echo isset($result)?'Update':'Add'; ?></button>
                                    <a href="{{url('fuel-purchase-order')}}" class="btn btn-sm btn-danger btn-icon"><i class="mdi mdi-close"></i>Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@include('footer')
<script>
$(document).ready(function(){
    $('select[name="dn"]').change();
});
$(document).on('change','select[name="dn"]',function(){
    var response_truck = $('select[name="dn"] option[value="' + $(this).val() + '"]').attr('data-truck');
    $('p[data-class="truck"] span').text(response_truck);
    var response_trailer = $('select[name="dn"] option[value="' + $(this).val() + '"]').attr('data-trailer');
    $('p[data-class="trailer"] span').text(response_trailer);
    var response_driver = $('select[name="dn"] option[value="' + $(this).val() + '"]').attr('data-driver');
    $('p[data-class="driver"] span').text(response_driver);
    $('.dn-details').show();
});
$(document).on('change keyup','input[name="qty_lts"],input[name="price"]',function(){
    var qty_lts = $('input[name="qty_lts"]').val();
    var price = $('input[name="price"]').val();
    var amount = '00.00';
    if(qty_lts && price){
        amount = parseFloat(qty_lts * price).toFixed(2);
    }
    $('input[name="amount"]').val(amount);
    $('.theme span').text(amount);
});
</script>