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
                    <li class="breadcrumb-item"><a href="{{ url('master-customer') }}">Customer Master</a></li>
                    <li class="breadcrumb-item active"><?php echo isset($result)?'Edit':'Add'; ?></li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="content-viewport">
                    <form action="{{ url('master-customer/save') }}" method="post">
                        @csrf
                        @if(isset($id))
                        
                        <input type="hidden" name="id" value="{{$id}}">
                        @endif
                        <div class="grid">
                            <p class="grid-header"><?php echo isset($result)?'Edit':'Add'; ?> Customer</p>
                            <div class="grid-body">
                                <div class="item-wrapper">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Name<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Name" name="name" value="<?php echo isset($result['name'])?$result['name']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Tel No.<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Tel No." name="tel" value="<?php echo isset($result['tel'])?$result['tel']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Contact No.<i>*</i></label> <input type="number" class="form-control" placeholder="Enter Contact No." name="contact" value="<?php echo isset($result['contact'])?$result['contact']:''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group"><label>TIN No.<i>*</i></label> <input type="text" class="form-control" placeholder="Enter TIN No." name="tin" value="<?php echo isset($result['tin'])?$result['tin']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group"><label>VRN No.<i>*</i></label> <input type="text" class="form-control" placeholder="Enter VRN No." name="vrn" value="<?php echo isset($result['vrn'])?$result['vrn']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group"><label>P.O. Box No.<i>*</i></label> <input type="text" class="form-control" placeholder="Enter P.O. Box No." name="po" value="<?php echo isset($result['po'])?$result['po']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group"><label>Country<i>*</i></label>
                                                <select class="form-control select2" data-hidden="Select Country" name="country" data-value="<?php echo isset($result['country'])?$result['country']:''; ?>" required>
                                                    <option></option>
                                                    <?php foreach($country as $c){ ?>
                                                    <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group"><label>Address<i>*</i></label> <textarea class="form-control" placeholder="Enter Address" name="address" required><?php echo isset($result['address'])?$result['address']:''; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary btn-icon"><i class="mdi mdi-check"></i><?php echo isset($result)?'Update':'Add'; ?></button>
                                    <a href="{{url('master-customer')}}" class="btn btn-sm btn-danger btn-icon"><i class="mdi mdi-close"></i>Cancel</a>
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