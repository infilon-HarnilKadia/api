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
                    <li class="breadcrumb-item active">Shortfall Form</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="content-viewport">
                    <form action="{{ url('shortfall-form/save') }}" method="post">
                        @csrf
                        <div class="grid">
                            <p class="grid-header">Shortfall Form</p>
                            <div class="grid-body">
                                <div class="item-wrapper">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>SF Number<i>*</i></label>
                                                <select class="form-control select2" name="sf" data-hidden="Select SF Number" required>
                                                    <option></option>
                                                    <?php foreach($dn as $t){ ?>
                                                    <option value="<?php echo $t['id']; ?>"><?php echo $t['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>TAREHE<i>*</i></label> <input type="text" class="form-control simpledate" placeholder="TAREHE" name="tarehe" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group"><label>JINA LA DEREVA<i>*</i></label> <input type="text" class="form-control" placeholder="JINA LA DEREVA" name="jina_la_dereva" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group"><label>NAENDESHA TRUCK NAME.<i>*</i></label> <input type="text" class="form-control" placeholder="NAENDESHA TRUCK NAME." name="truck" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group"><label>NAENDESHA TRUCK NO.<i>*</i></label> <input type="text" class="form-control" placeholder="NAENDESHA TRUCK NO." name="truck_no" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group"><label>Trailer Number<i>*</i></label> <input type="text" class="form-control" placeholder="Enter Trailer Number" name="trailer_no" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>NIMEPATA SHORT YA LITA<i>*</i></label> <input type="text" class="form-control" placeholder="NIMEPATA SHORT YA LITA" name="nimepata_short_ya_lita" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>NIMEPATA SHORT YA Tsh<i>*</i></label> <input type="text" class="form-control" placeholder="NIMEPATA SHORT YA Tsh" name="nimepata_short_ya_tsh" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>TAREHE YA KUPAKIA MZIGO<i>*</i></label> <input type="text" class="form-control" placeholder="TAREHE YA KUPAKIA MZIGO" name="loading_date" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>MZIGO ULIPOPAKILIWA<i>*</i></label> <input type="text" class="form-control" placeholder="MZIGO ULIPOPAKILIWA" name="mzigo_ulipopakiliwa" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>KWENDA<i>*</i></label> <input type="text" class="form-control" placeholder="KWENDA" name="kwenda" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>MAELEZO YA SHOTI<i>*</i></label> <input type="text" class="form-control" placeholder="MAELEZO YA SHOTI" name="maelezo_ya_shoti" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>SAHIHI YA DEREVA<i>*</i></label> <input type="text" class="form-control" placeholder="SAHIHI YA DEREVA" name="sahihi_ya_dereva" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>NYARAKA<i>*</i></label> <input type="text" class="form-control" placeholder="NYARAKA" name="nyaraka" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>ALAMA YA KIDOLE GUMBA<i>*</i></label> <input type="text" class="form-control" placeholder="ALAMA YA KIDOLE GUMBA" name="alama_ya_kidole_gumba" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary btn-icon"><i class="mdi mdi-file-pdf"></i>Print</button>
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
$(document).on('change','select[name="sf"]',function(){
    var q = $(this).val();
    if(q){
        spin(1);
        $.ajax({
            type: 'POST',
            url: base_url + 'shortfall-form/data',
            data: {
                q: q,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('input[name="tarehe"]').val(data.b_date).change();
                $('input[name="kwenda"]').val(data.destination);
                $('input[name="jina_la_dereva"]').val(data.driver);
                $('input[name="mzigo_ulipopakiliwa"]').val(data.loading);
                $('input[name="loading_date"]').val(data.loading_date);
                $('input[name="trailer_no"]').val(data.trailer);
                $('input[name="truck_no"]').val(data.truck_no);
                $('input[name="truck"]').val(data.truck);
                spin();
            },
            error: function () {
            }
        });
    }
});
</script>