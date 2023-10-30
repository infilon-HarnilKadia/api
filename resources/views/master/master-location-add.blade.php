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
                    <li class="breadcrumb-item"><a href="{{ url('master-location') }}">Location Master</a></li>
                    <li class="breadcrumb-item active"><?php echo isset($result)?'Edit':'Add'; ?></li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="content-viewport">
                    <form action="{{ url('master-location/save') }}" method="post">
                        @csrf
                        @if(isset($id))
                        <input type="hidden" name="id" value="{{$id}}">
                        @endif
                        <div class="grid">
                            <p class="grid-header"><?php echo isset($result)?'Edit':'Add'; ?> Location</p>
                            <div class="grid-body">
                                <div class="item-wrapper">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Location<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Location" name="name" value="<?php echo isset($result['name'])?$result['name']:''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Country<i>*</i></label>
                                                <select class="form-control select2" data-hidden="Select Country" name="country" data-value="<?php echo isset($result['country'])?$result['country']:''; ?>" required>
                                                    <option></option>
                                                    <?php foreach($country as $c){ ?>
                                                    <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Kilometers From Dar<i>*</i></label> <input type="number" class="form-control" placeholder="Enter Kilometers From Dar" name="km" value="<?php echo isset($result['km'])?$result['km']:''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary btn-icon"><i class="mdi mdi-check"></i><?php echo isset($result)?'Update':'Add'; ?></button>
                                    <a href="{{url('master-location')}}" class="btn btn-sm btn-danger btn-icon"><i class="mdi mdi-close"></i>Cancel</a>
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