@include('header')
<div class="page-content-wrapper">
    <style>
        .theme {
            color: #21437F
        }
    </style>

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Border</li>
                    <li class="breadcrumb-item"><a href="{{ url('broders-border-detail') }}">Border Details</a></li>
                    <li class="breadcrumb-item active"><?php echo isset($result) ? 'Edit' : 'Add'; ?></li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="content-viewport">
                    <form action="{{ url('borders-border-details/save') }}" method="post">
                        @csrf
                        @if (isset($id))
                            <input type="hidden" name="id" value="{{ $id }}">
                        @endif
                        <div class="grid">
                            <p class="grid-header"><?php echo isset($result) ? 'Edit' : 'Add'; ?> Border Details</p>
                            <div class="grid-body">
                                <div class="item-wrapper">
                                    <div class="row">

                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Delivery Note<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Delivery Note" value="<?php echo isset($result['name']) ? $result['name'] : $dn; ?>"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Customer<i>*</i></label>
                                                <select class="form-control select2" name="customer"
                                                    data-hidden="Select Customer" data-value="<?php echo isset($result['customer']) ? $result['customer'] : ''; ?>">
                                                    <option></option>
                                                    <?php foreach($customer as $c){ ?>
                                                    <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Loading Date<i>*</i></label> <input
                                                    type="text" class="form-control simpledate"
                                                    placeholder="Select Loading Date" name="loading_date"
                                                    value="<?php echo isset($result['loading_date']) ? dateind($result['loading_date']) : ''; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Truck<i>*</i></label>
                                                <select class="form-control select2" name="truck"
                                                    data-hidden="Select Truck" data-value="<?php echo isset($result['truck']) ? $result['truck'] : ''; ?>"
                                                    required>
                                                    <option></option>
                                                    <?php foreach($truck as $t){ ?>
                                                    <option value="<?php echo $t['id']; ?>"
                                                        data-number="<?php echo $t['number']; ?>"><?php echo $t['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Truck Number<i>*</i></label> <input
                                                    type="text" class="form-control" placeholder="Enter Truck Number"
                                                    name="truck_no" value="<?php echo isset($result['truck_no']) ? $result['truck_no'] : ''; ?>" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Trailer Number<i>*</i></label>
                                                <select class="form-control select2" name="trailer"
                                                    data-hidden="Select Trailer Number"
                                                    data-value="<?php echo isset($result['trailer']) ? $result['trailer'] : ''; ?>" required>
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
                                            <div class="form-group"><label>Driver Name<i>*</i></label>
                                                <select class="form-control select2" name="driver"
                                                    data-hidden="Select Driver" data-value="<?php echo isset($result['driver']) ? $result['driver'] : ''; ?>"
                                                    required>
                                                    <option></option>
                                                    <?php foreach($driver as $t){ ?>
                                                    <option value="<?php echo $t['id']; ?>"
                                                        data-cell="<?php echo $t['cell']; ?>"
                                                        data-licence="<?php echo $t['licence']; ?>"><?php echo $t['name']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Container Number<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Container Number" name="container"
                                                    value="<?php echo isset($result['container']) ? $result['container'] : ''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Description<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Description" name="description"
                                                    value="<?php echo isset($result['description']) ? $result['description'] : ''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Loading Point<i>*</i></label>
                                                <select class="form-control select2" name="loading"
                                                    data-hidden="Select Loading Point" data-value="<?php echo isset($result['loading']) ? $result['loading'] : ''; ?>"
                                                    required>
                                                    <option></option>
                                                    <?php foreach($loading as $l){ ?>
                                                    <option value="<?php echo $l['id']; ?>"><?php echo $l['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Destination<i>*</i></label>
                                                <select class="form-control select2" name="destination"
                                                    data-hidden="Select Destination" data-value="<?php echo isset($result['destination']) ? $result['destination'] : ''; ?>"
                                                    required>
                                                    <option></option>
                                                    <?php foreach($location as $l){ ?>
                                                    <option value="<?php echo $l['id']; ?>"><?php echo $l['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Return From<i>*</i></label>
                                                <select class="form-control select2" name="return"
                                                    data-hidden="Select Return From" data-value="<?php echo isset($result['return']) ? $result['return'] : ''; ?>"
                                                    required>
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
                                            <div class="form-group"><label>Weight<i>*</i></label> <input
                                                    type="text" class="form-control" placeholder="Enter Weight"
                                                    name="weight" value="<?php echo isset($result['weight']) ? $result['weight'] : ''; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    {{-- <h6 class="mb-3 theme">Expenses</h6> --}}
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>PVR ZMB<i>*</i></label> <input type="text"
                                                    class="form-control" placeholder="Enter PVR ZMB"
                                                    >
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Road Tolls Zmb<i>*</i></label> <input type="text"
                                                    class="form-control" placeholder="Enter Road Tolls Zmb"
                                                   >
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Road Permit Truck<i>*</i></label> <input type="text"
                                                    class="form-control" placeholder="Enter Road Permit Truck"
                                                    >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Road Permit Tralier<i>*</i></label> <input type="text"
                                                    class="form-control" placeholder="Enter Road Permit Tralier"
                                                    >
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Carbon Tax<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Carbon Tax">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Council Tax<i>*</i></label> <input type="text"
                                                    class="form-control" placeholder="Enter Council Tax"
                                                   >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Toll Gates<i>*</i></label> <input
                                                            type="text" class="form-control"
                                                            placeholder="Enter Toll Gates"
                                                           >
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Bond Zmb<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Bond Zmb">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Misc Zmb<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Misc Zmb">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Border Charges<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Border Charges">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Visa Zmb<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                   >
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Driver Expense for Drc<i>*</i></label> <input type="text"
                                                    class="form-control" placeholder="Enter Driver Expense for Drc"
                                                    >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Insurance<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Insurance">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Parking Zmb<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Parking Zmb">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Chemical Permit<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Chemical Permit">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Total Zmb<i>*</i></label> <input
                                                type="text" class="form-control"
                                                placeholder="Enter Total Zmb">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Remarks<i>*</i></label> <input
                                                type="text" class="form-control"
                                                placeholder="Enter Remarks"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Notes<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Notes" >
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <button type="submit" class="btn btn-sm btn-primary btn-icon"><i
                                            class="mdi mdi-check"></i>Update</button>
                                    <a href="{{ url('borders-border-details') }}" class="btn btn-sm btn-danger btn-icon"><i
                                            class="mdi mdi-close"></i>Cancel</a>
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
    $(document).on('change', 'select[name="truck"]', function() {
        var response = $('select[name="truck"] option[value="' + $(this).val() + '"]').attr('data-number');
        $('input[name="truck_no"]').val(response);
    });
    $(document).on('change', 'select[name="driver"]', function() {
        var response_cell = $('select[name="driver"] option[value="' + $(this).val() + '"]').attr('data-cell');
        $('input[name="driver_cell"]').val(response_cell);
        var response_lic = $('select[name="driver"] option[value="' + $(this).val() + '"]').attr(
            'data-licence');
        $('input[name="driver_lic"]').val(response_lic);
    });
</script>
