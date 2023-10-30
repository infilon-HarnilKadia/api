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
                    <li class="breadcrumb-item"><a href="{{ url('master-trailer') }}">Trailer Master</a></li>
                    <li class="breadcrumb-item active"><?php echo isset($result)?'Edit':'Add'; ?></li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="content-viewport">
                    <form action="{{ url('master-trailer/save') }}" method="post">
                        @csrf
                        @if(isset($id))
                        <input type="hidden" name="id" value="{{$id}}">
                        @endif
                        <div class="grid">
                            <p class="grid-header"><?php echo isset($result)?'Edit':'Add'; ?> Trailer</p>
                            <div class="grid-body">
                                <div class="item-wrapper">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Trailer Number<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Trailer Number" name="name" value="<?php echo isset($result['name'])?$result['name']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Model of Trailer<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Model of Trailer" name="model" value="<?php echo isset($result['model'])?$result['model']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Model Year<i>*</i></label> <input type="number" class="form-control" placeholder="Enter Model Year" name="year" value="<?php echo isset($result['year'])?$result['year']:''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Axles<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Axles" name="axles" value="<?php echo isset($result['axles'])?$result['axles']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Tyres Type<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Tyres Type" name="tyre" value="<?php echo isset($result['tyre'])?$result['tyre']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Trailer Type<i>*</i></label>
                                                <select class="form-control select2" name="trailer" data-hidden="Select Trailer Type" data-value="<?php echo isset($result['trailer'])?$result['trailer']:''; ?>" required>
                                                    <option></option>
                                                    <option>Flatbed</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Gross Weight<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Gross Weight" name="weight" value="<?php echo isset($result['weight'])?$result['weight']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>GVM<i>*</i></label> <input type="text" class="form-control" placeholder="Enter GVM" name="gvm" value="<?php echo isset($result['gvm'])?$result['gvm']:''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary btn-icon"><i class="mdi mdi-check"></i><?php echo isset($result)?'Update':'Add'; ?></button>
                                    <a href="{{url('master-trailer')}}" class="btn btn-sm btn-danger btn-icon"><i class="mdi mdi-close"></i>Cancel</a>
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