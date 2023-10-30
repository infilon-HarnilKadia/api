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
                                    </div>
                                    <div class="row loc-table active">

                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="form-group"><label>Location<i>*</i></label> <input
                                                            type="text" class="form-control"
                                                            placeholder="Enter Invoice Number" name="location[]"
                                                            value="<?php echo isset($data['i_num']) ? $data['i_num'] : ""; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group"><label>Description<i>*</i></label> <input
                                                        type="text" class="form-control"
                                                        placeholder="Enter Invoice Number" name="location[]"
                                                        value="<?php echo isset($data['i_num']) ? $data['i_num'] : ""; ?>" required>
                                                </div>
                                                </div>
                                                <div class="col-lg-3"></div>
                                                <div class="col-lg-3"></div>
                                            </div>
                                        </div>
                                    </div>
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
    $(document).on("change", "select[name='name']", function() {
        var val = $(this).val();
        $.ajax({
            url: "{{ url('daily-location/fetch') }}",
            type: "POST",
            data: {
                val: val,
                _token: $("meta[name='csrf-token']").attr("content")
            },
            success: function(response) {
                $(".loc-table").removeClass("active");
                $(".loc-table").html(response);
            }
        })
    })
</script>
@include('footer')
