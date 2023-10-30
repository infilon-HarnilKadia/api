@include('header')
<div class="page-content-wrapper">
    <style>
        td.ipino {
            text-align: center;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>

    <div class="page-content-wrapper-inner">
        <div class="viewport-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb has-arrow">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Masters</li>
                    <li class="breadcrumb-item"><a href="{{ url('master-driver') }}">Driver Master</a></li>
                    <li class="breadcrumb-item active"><?php echo isset($result)?'Edit':'Add'; ?></li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="content-viewport">
                    <form action="{{ url('master-driver/save') }}" method="post">
                        @csrf
                        @if(isset($id))
                        <input type="hidden" name="id" value="{{$id}}">
                        @endif
                        <div class="grid">
                            <p class="grid-header"><?php echo isset($result)?'Edit':'Add'; ?> Driver</p>
                            <div class="grid-body">
                                <div class="item-wrapper">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Driver Name<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Driver Name" name="name" value="<?php echo isset($result['name'])?$result['name']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Driver Telephone Number<i>*</i></label> <input type="number" class="form-control" placeholder="Enter Driver Telephone Number" name="number" value="<?php echo isset($result['number'])?$result['number']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Driver Cell Number<i>*</i></label> <input type="number" class="form-control" placeholder="Enter Driver Cell Number" name="cell" value="<?php echo isset($result['cell'])?$result['cell']:''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>National Id Number(NIDA)<i>*</i></label> <input type="text" class="form-control" placeholder="Enter National Id Number(NIDA)" name="nida" value="<?php echo isset($result['nida'])?$result['nida']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Driving Licence Number<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Driving Licence Number" name="licence" value="<?php echo isset($result['licence'])?$result['licence']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Driving Licence Expiry Date<i>*</i></label> <input type="text" class="form-control simpledate" placeholder="Enter Driving Licence Expiry Date" name="licence_date" value="<?php echo isset($result['licence_date'])?$result['licence_date']:''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Passport Number<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Passport Number" name="passport" value="<?php echo isset($result['passport'])?$result['passport']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Passport Expiry Date<i>*</i></label> <input type="text" class="form-control simpledate" placeholder="Enter Passport Expiry Date" name="passport_date" value="<?php echo isset($result['passport_date'])?$result['passport_date']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>TIN<i>*</i></label> <input type="text" class="form-control" placeholder="Enter TIN" name="tin" value="<?php echo isset($result['tin'])?$result['tin']:''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary btn-icon"><i class="mdi mdi-check"></i><?php echo isset($result)?'Update':'Add'; ?></button>
                                    <a href="{{url('master-driver')}}" class="btn btn-sm btn-danger btn-icon"><i class="mdi mdi-close"></i>Cancel</a>
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