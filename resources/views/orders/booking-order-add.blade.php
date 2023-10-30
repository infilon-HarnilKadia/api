@include('header')
<div class="page-content-wrapper">
    <style>
        .theme{
            color: #21437F
        }
    </style>

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Orders</li>
                    <li class="breadcrumb-item"><a href="{{ url('booking-order') }}">Booking Order</a></li>
                    <li class="breadcrumb-item active"><?php echo isset($result)?'Edit':'Add'; ?></li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="content-viewport">
                    <form action="{{ url('booking-order/save') }}" method="post">
                        @csrf
                        @if(isset($id))
                        <input type="hidden" name="id" value="{{$id}}">
                        @endif
                        <div class="grid">
                            <p class="grid-header"><?php echo isset($result)?'Edit':'Add'; ?> Booking Order</p>
                            <div class="grid-body">
                                <div class="item-wrapper">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Date<i>*</i></label> <input type="text" class="form-control simpledate" placeholder="Select Date" name="b_date" value="<?php echo isset($result['b_date'])?dateind($result['b_date']):''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Delivery Note<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Delivery Note" value="<?php echo isset($result['name'])?$result['name'] : $dn; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Customer<i>*</i></label>
                                                <select class="form-control select2" name="customer" data-hidden="Select Customer" data-value="<?php echo isset($result['customer'])?$result['customer']:''; ?>">
                                                    <option></option>
                                                    <?php foreach($customer as $c){ ?>
                                                    <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Loading Date<i>*</i></label> <input type="text" class="form-control simpledate" placeholder="Select Loading Date" name="loading_date" value="<?php echo isset($result['loading_date'])?dateind($result['loading_date']):''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Invoice Number</label> <input type="text" class="form-control" placeholder="Enter Invoice Number" value="<?php echo isset($result['invoice'])?$result['invoice'] :''; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Invoice Date</label> <input type="text" class="form-control" placeholder="Select Invoice Date" name="i_date" value="<?php echo isset($result['i_date'])?dateind($result['i_date']):''; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Truck<i>*</i></label>
                                                <select class="form-control select2" name="truck" data-hidden="Select Truck" data-value="<?php echo isset($result['truck'])?$result['truck']:''; ?>" required>
                                                    <option></option>
                                                    <?php foreach($truck as $t){ ?>
                                                    <option value="<?php echo $t['id']; ?>" data-number="<?php echo $t['number']; ?>"><?php echo $t['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Truck Number<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Truck Number" name="truck_no" value="<?php echo isset($result['truck_no'])?$result['truck_no']:''; ?>" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Trailer Number<i>*</i></label>
                                                <select class="form-control select2" name="trailer" data-hidden="Select Trailer Number" data-value="<?php echo isset($result['trailer'])?$result['trailer']:''; ?>" required>
                                                    <option></option>
                                                    <?php foreach($trailer as $t){ ?>
                                                    <option value="<?php echo $t['id']; ?>"><?php echo $t['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Loading Point<i>*</i></label>
                                                <select class="form-control select2" name="loading" data-hidden="Select Loading Point" data-value="<?php echo isset($result['loading'])?$result['loading']:''; ?>" required>
                                                    <option></option>
                                                    <?php foreach($loading as $l){ ?>
                                                    <option value="<?php echo $l['id']; ?>"><?php echo $l['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Destination<i>*</i></label>
                                                <select class="form-control select2" name="destination" data-hidden="Select Destination" data-value="<?php echo isset($result['destination'])?$result['destination']:''; ?>" required>
                                                    <option></option>
                                                    <?php foreach($location as $l){ ?>
                                                    <option value="<?php echo $l['id']; ?>"><?php echo $l['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Return From<i>*</i></label>
                                                <select class="form-control select2" name="return" data-hidden="Select Return From" data-value="<?php echo isset($result['return'])?$result['return']:''; ?>" required>
                                                    <option></option>
                                                    <?php foreach($location as $l){ ?>
                                                    <option value="<?php echo $l['id']; ?>"><?php echo $l['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Container Number<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Container Number" name="container" value="<?php echo isset($result['container'])?$result['container']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Description<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Description" name="description" value="<?php echo isset($result['description'])?$result['description']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Weight<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Weight" name="weight" value="<?php echo isset($result['weight'])?$result['weight']:''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Driver Name<i>*</i></label>
                                                <select class="form-control select2" name="driver" data-hidden="Select Driver" data-value="<?php echo isset($result['driver'])?$result['driver']:''; ?>" required>
                                                    <option></option>
                                                    <?php foreach($driver as $t){ ?>
                                                    <option value="<?php echo $t['id']; ?>" data-cell="<?php echo $t['cell']; ?>" data-licence="<?php echo $t['licence']; ?>"><?php echo $t['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Driver Cell Number<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Driver Cell Number" name="driver_cell" value="<?php echo isset($result['driver_cell'])?$result['driver_cell']:''; ?>" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Driver Licence<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Driver Licence" name="driver_lic" value="<?php echo isset($result['driver_lic'])?$result['driver_lic']:''; ?>" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Remarks</label> <input type="text" class="form-control" placeholder="Enter Remarks" name="remarks" value="<?php echo isset($result['remarks'])?$result['remarks']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Narration<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Narration" name="narration" value="<?php echo isset($result['narration'])?$result['narration']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Others</label> <input type="text" class="form-control" placeholder="Enter Others" name="others" value="<?php echo isset($result['others'])?$result['others']:''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="mb-3 theme">Expenses</h6>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Buza Dar Lts</label> <input type="text" class="form-control" placeholder="Enter Buza Dar Lts" name="extra[buza_dar]" readonly value="<?php echo isset($result['extra'][0]['buza_dar']) ? $result['extra'][0]['buza_dar']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Dar Cash Lts</label> <input type="text" class="form-control" placeholder="Enter Dar Cash Lts" name="extra[dar_cash]" readonly value="<?php echo isset($result['extra'][0]['dar_cash']) ? $result['extra'][0]['dar_cash']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Kongowe Lts</label> <input type="text" class="form-control" placeholder="Enter Kongowe Lts" name="extra[kongowe_lts]" readonly value="<?php echo isset($result['extra'][0]['kongowe_lts']) ? $result['extra'][0]['kongowe_lts']:''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Tunduma Going >>> Lts</label> <input type="text" class="form-control" placeholder="Enter Tunduma Going >>> Lts" name="extra[going_lts]" readonly value="<?php echo isset($result['extra'][0]['going_lts']) ? $result['extra'][0]['going_lts']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Zambia Cash Lts</label> <input type="text" class="form-control" placeholder="Enter Zambia Cash Lts" name="extra[zambia_cash]" readonly value="<?php echo isset($result['extra'][0]['zambia_cash']) ? $result['extra'][0]['zambia_cash']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Drc Cash Lts</label> <input type="text" class="form-control" placeholder="Enter Drc Cash Lts" name="extra[drc_lts]" readonly value="<?php echo isset($result['extra'][0]['drc_lts']) ? $result['extra'][0]['drc_lts']:''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Tunduma Return <<< Lts</label> <input type="text" class="form-control" placeholder="Enter Tunduma Returb <<< Lts" name="extra[return_lts]" readonly value="<?php echo isset($result['extra'][0]['return_lts']) ? $result['extra'][0]['return_lts']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Additional Fuel Lts</label> <input type="text" class="form-control" placeholder="Enter Additional Fuel Lts" name="extra[add_fuel]" readonly value="<?php echo isset($result['extra'][0]['add_fuel']) ? $result['extra'][0]['add_fuel']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Extra Fuel Lts</label> <input type="text" class="form-control" placeholder="Enter Extra Fuel Lts" name="extra[extra_fuel]" readonly value="<?php echo isset($result['extra'][0]['extra_fuel']) ? $result['extra'][0]['extra_fuel']:''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Total Fuel Lts</label> <input type="text" class="form-control" placeholder="Enter Total Fuel Lts" name="extra[total_fuel]" readonly value="<?php echo isset($result['extra'][0]['total_fuel']) ? $result['extra'][0]['total_fuel']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Distance Kilometers</label> <input type="text" class="form-control" placeholder="Enter Distance Kilometers" name="extra[distance]" readonly value="<?php echo isset($result['extra'][0]['distance']) ? $result['extra'][0]['distance']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Fuel Shortage</label> <input type="text" class="form-control" placeholder="Enter Fuel Shortage" name="extra[shortage]" readonly value="<?php echo isset($result['extra'][0]['shortage']) ? $result['extra'][0]['shortage']:''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Truck Expenses</label> <input type="text" class="form-control" placeholder="Enter Truck Expenses" name="extra[truck_exp]" readonly value="<?php echo isset($result['extra'][0]['truck_exp']) ? $result['extra'][0]['truck_exp']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Extra Driver Expenses</label> <input type="text" class="form-control" placeholder="Enter Extra Driver Expenses" name="extra[driver_exp]" readonly value="<?php echo isset($result['extra'][0]['driver_exp']) ? $result['extra'][0]['driver_exp']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Container Offloading</label> <input type="text" class="form-control" placeholder="Enter Container Offloading" name="extra[offloading]" readonly value="<?php echo isset($result['extra'][0]['offloading']) ? $result['extra'][0]['offloading']:''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Maintenance Expenses</label> <input type="text" class="form-control" placeholder="Enter Maintenance Expenses" name="extra[main_exp]" readonly value="<?php echo isset($result['extra'][0]['main_exp']) ? $result['extra'][0]['main_exp']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Spares Parts Costs</label> <input type="text" class="form-control" placeholder="Enter Spares Parts Costs" name="extra[spare_exp]" readonly value="<?php echo isset($result['extra'][0]['spare_exp']) ? $result['extra'][0]['spare_exp']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Miscellaneous Costs</label> <input type="text" class="form-control" placeholder="Enter Miscellaneous Costs" name="extra[miscellaneous]" readonly value="<?php echo isset($result['extra'][0]['miscellaneous']) ? $result['extra'][0]['miscellaneous']:''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Additional Charges</label> <input type="text" class="form-control" placeholder="Enter Additional Charges" name="extra[additional]" readonly value="<?php echo isset($result['extra'][0]['additional']) ? $result['extra'][0]['additional']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Total Shortages</label> <input type="text" class="form-control" placeholder="Enter Total Shortages" name="extra[total]" readonly value="<?php echo isset($result['extra'][0]['total']) ? $result['extra'][0]['total']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Remarks</label> <input type="text" class="form-control" placeholder="Enter Remarks" name="extra[remarks]" readonly value="<?php echo isset($result['extra'][0]['remarks']) ? $result['extra'][0]['remarks']:''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="mb-3 theme">Others</h6>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Notes</label> <input type="text" class="form-control" placeholder="Enter Notes" name="notes" value="<?php echo isset($result['notes'])?$result['notes']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Notes 1</label> <input type="text" class="form-control" placeholder="Enter Notes" name="notes_1" value="<?php echo isset($result['notes_1'])?$result['notes_1']:''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Notes 2</label> <input type="text" class="form-control" placeholder="Enter Notes" name="notes_2" value="<?php echo isset($result['notes_2'])?$result['notes_2']:''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary btn-icon"><i class="mdi mdi-check"></i><?php echo isset($result)?'Update':'Add'; ?></button>
                                    <a href="{{url('booking-order')}}" class="btn btn-sm btn-danger btn-icon"><i class="mdi mdi-close"></i>Cancel</a>
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
$(document).on('change','select[name="truck"]',function(){
    var response = $('select[name="truck"] option[value="' + $(this).val() + '"]').attr('data-number');
    $('input[name="truck_no"]').val(response);
});
$(document).on('change','select[name="driver"]',function(){
    var response_cell = $('select[name="driver"] option[value="' + $(this).val() + '"]').attr('data-cell');
    $('input[name="driver_cell"]').val(response_cell);
    var response_lic = $('select[name="driver"] option[value="' + $(this).val() + '"]').attr('data-licence');
    $('input[name="driver_lic"]').val(response_lic);
});
</script>