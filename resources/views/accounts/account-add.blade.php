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
                    <li class="breadcrumb-item"><a href="{{ url('accounts') }}">Accounts</a></li>
                    <li class="breadcrumb-item active"><?php echo isset($data) ? 'Edit' : 'Add'; ?></li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="content-viewport">
                    <form action="{{ url('accounts/save') }}" method="post">
                        @csrf
                        @if (isset($id))
                            <input type="hidden" name="id" value="{{ $id }}">
                        @endif
                        @if (isset($type))
                            <input type="hidden" name="form_type" value="{{ $type }}">
                        @endif
                        <input type="hidden" name="id" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
                        <div class="grid">
                            <p class="grid-header"><?php echo isset($data) ? 'Edit' : 'Add'; ?> Account</p>
                            <div class="grid-body">
                                <div class="item-wrapper">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Delivery Notes<i>*</i></label>
                                                {{-- {{$result['name']}} --}}
                                                <select class="form-control select2" data-hidden="Select Delivery Note"
                                                    name="name" data-value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" required>
                                                    <option></option>
                                                    @foreach ($result as $item)
                                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="row acc-table active">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Invoice Number<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Invoice Number" name="i_num"
                                                    value="<?php echo isset($data['i_num']) ? $data['i_num'] : $num; ?>" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Invoice Date</label> <input type="text"
                                                    class="form-control account_datetime"
                                                    placeholder="Select Invoice Date" name="invoice_date"
                                                    value="<?php echo isset($data['invoice_date']) ? dateind($data['invoice_date']) : ''; ?>">

                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Unit<i>*</i></label> <input type="text"
                                                    class="form-control" placeholder="Enter Unit" name="unit"
                                                    value="<?php echo isset($data['unit']) ? $data['unit'] : ''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Rate<i>*</i></label> <input type="text"
                                                    class="form-control" placeholder="Enter Rate" name="rate"
                                                    value="<?php echo isset($data['rate']) ? $data['rate'] : ''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Total Value<i>*</i></label> <input
                                                    type="text" class="form-control" placeholder="Enter Total value"
                                                    name="t_value" value="<?php echo isset($data['t_value']) ? $data['t_value'] : ''; ?>" required>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Deal Number<i>*</i></label> <input
                                                    type="text" class="form-control" placeholder="Enter Deal Number"
                                                    name="d_num" value="<?php echo isset($data['d_num']) ? $data['d_num'] : ''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Reference Number<i>*</i></label> <input
                                                    type="text" class="form-control"
                                                    placeholder="Enter Reference Number" name="r_num"
                                                    value="<?php echo isset($data['r_num']) ? $data['r_num'] : ''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Narration</label> <input type="text"
                                                    class="form-control" placeholder="Enter Narration" name="narration"
                                                    value="<?php echo isset($data['narration']) ? $data['narration'] : ''; ?>">
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Remarks<i>*</i></label> <input
                                                    type="text" class="form-control" placeholder="Enter Remark"
                                                    name="remark" value="<?php echo isset($data['remark']) ? $data['remark'] : ''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group"><label>Forex<i>*</i></label> <input type="text"
                                                    class="form-control" placeholder="Enter Forex" name="forex"
                                                    value="<?php echo isset($data['forex']) ? $data['forex'] : ''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary btn-icon"><i
                                            class="mdi mdi-check"></i><?php echo isset($data)?"Update":"Add"; ?></button>
                                    <a href="{{ url('accounts') }}" class="btn btn-sm btn-danger btn-icon"><i
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
<script>
    if ($('select[name="name"]').data("value")) {
        $(document).ready(function() {
            var dn = $("select[name='name']").data("value");
            $.ajax({
                url: '{{ url('accounts/fetch') }}',
                type: "POST",
                data: {
                    val: dn,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // console.log(response);
                    $(".acc-table").removeClass("active");
                    $(".acc-table").html(response);
                }
            })
        })
        $(document).on("change", "select[name=name]", function() {
            var dn = $(this).val();
            $.ajax({
                url: '{{ url('accounts/fetch') }}',
                type: "POST",
                data: {
                    val: dn,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // console.log(response);
                    $(".acc-table").removeClass("active");
                    $(".acc-table").html(response);
                }
            })
        })
    } else {
        $(document).on("change", "select[name=name]", function() {
            var dn = $(this).val();
            $.ajax({
                url: '{{ url('accounts/fetch') }}',
                type: "POST",
                data: {
                    val: dn,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // console.log(response);
                    $(".acc-table").removeClass("active");
                    $(".acc-table").html(response);
                }
            })
        })
    }
</script>
@include('footer')
