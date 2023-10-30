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
                    <li class="breadcrumb-item">Admin Master</li>
                    <li class="breadcrumb-item active">{{ (isset($id)) ? 'Edit' : 'Create' }} Admin</li>
                </ol>
            </nav>
        </div>
        <div class="content-viewport">
            <form action="{{ url('admin/save') }}" method="post">
                @csrf
                @if(isset($id))
                <input type="hidden" name="id" value="{{ $id }}">
                @endif
                <div class="grid">
                    <p class="grid-header">Create New Admin</p>
                    <div class="grid-body">
                        <div class="item-wrapper">
                            <div class="row">
                                <div class="col-lg-4"> 
                                    <div class="form-group"><label for="fpage">First Page<i>*</i></label>
                                        <select required class="form-control select2" data-value="{{ (isset($result)) ? $result[0]['firstpage'] : '' }}" id="fpage" name="fpage" data-hidden="First Page">
                                            <option></option>
                                            <?php
                                            foreach($viewpages as $g) {
                                                ?>
                                            <option value="<?php echo $g['url']; ?>" data-value="<?php echo $g['id']; ?>"><?php echo $g['name']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group"><label>Name<i>*</i></label> <input type="text" value="{{ (isset($result)) ? $result[0]['name'] : '' }}" class="form-control" placeholder="Enter Name" name="fname" required>
                                    </div>

                                    <div class="form-group"><label>Email<i>*</i></label> <input type="email" value="{{ (isset($result)) ? $result[0]['email'] : '' }}" class="form-control" placeholder="Enter Email" name="email" required>
                                    </div>

                                    <div class="form-group"><label for="name">Username<i>*</i></label> <input type="text" value="{{ (isset($result)) ? $result[0]['username'] : '' }}" class="form-control" id="name" placeholder="Enter Name" name="name" required>
                                    </div>

                                    
                                    <div class="form-group"><label for="password">Password<i>*</i></label> <input type="text" class="form-control" id="password" placeholder="Enter Password" name="password" value="{{ (isset($result)) ? $result[0]['password'] : '' }}" required>
                                    </div>


<!--
                                    <div class="form-group"><label for="digit_password">4 Digit Password (Lock Screen)<i>*</i></label> <input type="number" class="form-control" id="digit_password" min="1111" maxlength="4" max="9999" placeholder="Enter 4 Digit Password (Lock Screen)" name="digit_password" value="{{ (isset($result)) ? $result[0]['digit_password'] : '' }}" required>
                                    </div>
-->


                                </div>
                                <div class="col-lg-8">
                                    <div class="hidnelist">
                                        <div class="table-responsive" style="overflow: auto;max-height: 700px;">
                                            <table class="table search">
                                                <thead>
                                                    <tr>
                                                        <th>Page Permission</th>
                                                        <th class="text-center">Fields</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                            foreach($viewpages as $g) {
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <div class="custom-control custom-switch">
                                                                <input value="<?php echo $g['id']; ?>" <?php if($g['id'] == 2 || isset($permission[$g['id']])){ echo "checked"; } ?> type="checkbox" name="per[]" class="custom-control-input" id="csgo<?php echo $g['id']; ?>">
                                                                <label class="custom-control-label" for="csgo<?php echo $g['id']; ?>">&nbsp;<?php echo $g['name']; ?></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                        <?php
                                                        $isch = '';
                                                        if(isset($permission[$g['id']])){
                                                            $chkf = explode(',',$permission[$g['id']][0]['fields']);
                                                        }
                                                        if($g['fields']){
                                                        $fileds = explode(',', $g['fields']);
                                                        foreach ($fileds as $kig => $fi){
                                                            $checked1 = "";
                                                            
                                                            ?>
                                                            <div class="custom-control custom-switch">
                                                                <input value="<?php echo $fi; ?>" <?php echo $checked1; ?> type="checkbox" name="field[<?php echo $g['id']; ?>][]" class="custom-control-input" <?php if(isset($permission[$g['id']]) && isset($chkf) && in_array($fi,$chkf)){echo 'checked';} ?> id="csgox<?php echo $g['id'].$kig; ?>">
                                                                <label class="custom-control-label" for="csgox<?php echo $g['id'].$kig; ?>">&nbsp;<?php echo ucwords($fi); ?></label>
                                                            </div>
                                                            <?php
                                                        }
                                                    }else{
                                                        echo '-';
                                                    }
                                                    ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                            }
                                            ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <br>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-sm btn-primary btn-icon"><i class="mdi mdi-check"></i>Submit</button>
                                    <a href="{{url('admin')}}" class="btn btn-sm btn-danger btn-icon"><i class="mdi mdi-close"></i>Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on("change","#fpage",function() {
        var $v = $("#fpage option:selected").data('value');
        $("#csgo" + $v).prop('checked', true).change();
    });
    
    $(document).on("change","#fpage55",function() {
        var str = $(this).val();
        var res = str.toLowerCase();
        res = res.replace(/\s/g, '');
        $("input#name").val(res);
    });
    
    $(document).on("change",'input[name="per[]"]',function() {
        var $v = $(this).val();
        if($(this).prop('checked') == true){
            $('.custom-control-input[name="field['+$v+'][]"]').prop('checked',true);
        }else{
            $('.custom-control-input[name="field['+$v+'][]"]').prop('checked',false);
        }
    });
</script>
@include('footer')