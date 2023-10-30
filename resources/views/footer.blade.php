    </div>
    <footer>
        <div class="row">
            <div class="col-sm-12"><small><?= date('l - d M Y'); ?></small><small class="d-block mt-1">© <?= date('Y'); ?> {{ env('PROJECT_NAME') }}. All rights reserved</small> </div>
        </div>
    </footer>
    <div class="modal fade" tabindex="-1" role="dialog" id="attachments">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attachments</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">...</div>
            </div>
        </div>
    </div>
    <img src="{{ url('assets/images/load.svg') }}" class="ajax-loader" style="display: none;">
    <script src="{{ url('assets/vendors/js/vendor.addons.js') }}"></script>
    <script src="{{ url('assets/js/script.js') }}"></script>
    <script src="{{ url('assets/vendors/chartjs/Chart.min.js') }}"></script>
    <script src="{{ url('assets/js/bootstrap-datetimepicker.js') }}"></script>
    <script src="{{ url('assets/js/custom.js') }}"></script>
    <script>
        <?php if (isset($classname)) { ?>
            $("body").addClass('{{ $classname }}').attr('data-page', '{{ $classname }}');
        <?php } ?>
        $(".t-header .navbar-dropdown .dropdown-body").html('<div class="text-center"> <div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
        setTimeout(function() {
            notification();
        }, 2000);
        setTimeout(function() {
            $('.alert-block').slideUp();
        }, 4000);
        // setInterval(function() {
        // notification();
        // }, 10000);
        $(window).bind('load', function() {
            setTimeout(() => {
                $(".link-title").each(function() {
                    $(this).closest('a').attr('title', $(this).text()).tooltip();
                });
            }, 100);
        });
    </script>
    <style>
        .select2-container .select2-selection.select2-selection--single,
        .form-control {
            border-color: #999 !important;
        }

        .select2-container--disabled .select2-selection__rendered {
            cursor: not-allowed;
            background: #eee !important;
        }

        i.notice-v {
            color: #f00;
            font-size: 12px;
            line-height: normal;
            font-style: normal;
            font-weight: 500;
            display: block;
            letter-spacing: 0.3px;
            margin-top: 2px;
        }

        .user_name {
            line-height: normal !important;
        }

        .form-group small {
            display: block;
            color: #000000;
            font-weight: 500;
            padding: 4px 0
        }
    </style>

    </body>

    </html>