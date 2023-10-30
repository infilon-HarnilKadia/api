function inIframe() {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
}

function btnv(type) {
    $('.f-status').val(type);
}

function notification() {
    $.get(base_url + "notification/get-list?t=" + $.now(), function (data, status) {
        if (status == "success") {
            $(".t-header .navbar-dropdown .dropdown-body").html(data);
            if (data != "") {
                $(".t-header .notification-indicator").show();
                $(".navbar-dropdown .dropdown-footer").show();
                $(".navbar-dropdown .dropdown-header .dropdown-title-text").html('You have got a new messages.');
            } else {
                $(".navbar-dropdown .dropdown-footer").hide();
                $(".t-header .notification-indicator").hide();
                $(".navbar-dropdown .dropdown-header .dropdown-title-text").html('No any messages.');
            }
        }
    });
}

window.onbeforeunload = function () {
    $(".idex-loader").show();
    return undefined;
};

$(document).ready(function () {
    $("form").attr("autocomplete", "off");

    var pageURL = $(location).attr("href");
    $(".ctmbtn a").each(function () {
        if ($(this).attr('href') == pageURL) {
            $(this).addClass('isactive');
        }
    });

    $("form").submit(function () {
        $this = $(this);
        $this.find('button[type=submit]').html("<i class='mdi mdi-refresh mdi-spin'></i>Please wait...").attr('disabled', 'disabled');
        $this.find('input[type=submit]').attr('disabled', 'disabled');
    });

    $(".authentication form").submit(function () {
        $this = $(this);
        $this.find('button').html('Please wait...').attr('disabled', 'disabled');
        $.ajax({
            type: 'POST',
            url: base_url + 'auth/login',
            data: $this.serialize(),
            success: function () {
                window.location.href = base_url + "auth/check"
            },
            error: function () {
                $this.find('button').html("Try again...");
                setTimeout(function () {
                    $this.find('button').html("Login").removeAttr("disabled");
                }, 1500);
            }
        });
        return false;
    });

    $(".form_datetime").each(function () {
        $(this).attr('autocomplete', 'off');
        $(this).datetimepicker({
            autoclose: true,
            showMeridian: true,
            pickerPosition: 'bottom-right',
            format: 'dd/mm/yyyy HH:ii P'
        });
    });
    $(".account_datetime").datepicker({
        format:'dd/mm/yyyy',
        autoclose:true
    })
   


    $(".timerpick").each(function () {
        $(this).attr('autocomplete', 'off');
        $(this).datetimepicker({
            pickDate: false,
            minuteStep: 1,
            pickerPosition: 'bottom-right',
            format: 'dd/mm/yyyy HH:ii p',
            autoclose: true,
            showMeridian: true,
            startView: 1,
            maxView: 1,
        });
    });


    $(".select2").each(function () {
        $value = $(this).data("value");
        $clear = $(this).data("clear");
        if ($value) {
            $(this).val($value);
        }
        $search = $(this).data("hidden");
        if (!$clear) {
            $(this).select2({
                maximumInputLength: 40,
                placeholder: $search
            });
        } else {
            $(this).select2({
                maximumInputLength: 40,
                placeholder: $search,
                allowClear: true
            });
        }
    });


    if (inIframe()) {
        $('body').append('<style>::-webkit-scrollbar { width: 0; }</style>');
    }

    $("button.prints").click(function () {
        $("body").addClass('printable');
        $(this).parent().addClass('hntnshioedsa');
        $('title').html($(this).parent().data('name'));
        window.print();
    });

});

$(".alert-notification").on("click", function () {
    $(this).parent().slideUp();
});

$('#attachments').on('hidden.bs.modal', function () {
    $("#attachments .modal-body").html("closed");
});

$(document).on("click", ".attachments img", function () {
    $("h5.modal-title").html('Attachments');
    $("#attachments").modal('show');
    if ($(this).data('video')) {
        $("#attachments .modal-body").html("<div class='text-center'><a download href='" + $(this).data('video') + "' class='btn btn-sm btn-primary btn-icon'><i class='mdi mdi-arrow-down'></i> Download File</a><video autoplay controls> <source src='" + $(this).data('video') + "'></video></div>");
    } else {
        $("#attachments .modal-body").html("<div class='text-center'><a download href='" + $(this).attr('src') + "' class='btn btn-sm btn-primary btn-icon'><i class='mdi mdi-arrow-down'></i> Download File</a><img class='img-thumbnail' src='" + $(this).attr('src') + "'></div>");
    }
});
$(document).on("click", "a.locgomaps", function () {
    $("h5.modal-title").html('Location');
    $("#attachments").modal('show');
    $("#attachments .modal-body").html("<div class='text-center'><a target='_blank' href='" + $(this).attr('href') + "' class='btn btn-sm btn-primary btn-icon'><i class='mdi mdi-map-marker'></i> View Larger Map</a><iframe class='ifrae' src='" + $(this).data('link') + "'></iframe></div>");
    return false;
});

var mgscroll = 1;
var mgadditons = 1;
$(document).on("mousewheel", "#attachments img", function (e) {
    if (e.originalEvent.wheelDelta / 120 > 0) {
        mgscroll = mgscroll + 0.1;
        mgadditons = 10;
    } else {
        mgscroll = mgscroll - 1;
    }
    if (mgscroll < 1) {
        mgscroll = 1;
        mgadditons = 1;
    }
    $("#attachments img").css('transform', 'scale(' + mgscroll + ') translateY(' + mgscroll * mgadditons + '%)');
});

$(document).on("click", "td.delete a", function () {
    if (!confirm('Are you sure you want to delete?')) {
        return false;
    }
});
$(document).on("click", "td.lock a", function () {
    if (!confirm('Are you sure?')) {
        return false;
    }
});
$(document).on("click", "td.copy a, .gmm_copy", function () {
    if (!confirm('Are you sure you want to copy?')) {
        return false;
    }
});

$(document).on("click", ".navbar-dropdown .dropdown-body .dropdown-list .content-wrapper", function () {
    // alert('sd');
    return false;
});

$(document).on("click", ".t-header .dropdown-body a", function () {
    $(this).parent().slideUp(100);
    $.ajax({
        type: 'GET',
        url: base_url + 'notification/remove/' + $(this).attr('href')
    });
    return false;
});

function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

$(window).bind('load', function () {
    $(".olddata").find('.grid-header').append('<span class="mdi mdi-minus"></span>');
    $(".olddata .grid-header").click(function () {
        $(this).find('span').toggleClass('mdi-plus');
        $(this).parent().find('.grid-body').stop().slideToggle(100);
    });

    setTimeout(() => {
        $(".olddata input, .olddata textarea").each(function () {
            $(this).attr('readonly', 'readonly');
            $(this).parent().append('<div class="form-control" readonly>' + $(this).val() + ' &nbsp; </div>');
        });
    }, 1000);

    // $(".t-header .t-header-toggler").tooltip();
    setTimeout(function () {
        setInterval(function () {
            updateIndicator();
        }, 5000);
        $("form").attr('autocomplete', 'off');
        $(".form-group").each(function () {
            var attr = $(this).find('label').attr('for');
            var $number = makeid(10);
            if (typeof attr !== typeof undefined && attr !== false) { } else {
                $(this).find('label').attr('for', 'cst_' + $number);
            }
            var attr_input = $(this).find('input.form-control').attr('id');
            if (typeof attr_input !== typeof undefined && attr_input !== false) { } else {
                $(this).find('input.form-control').attr('id', 'cst_' + $number).attr("spellcheck", "false");
                $(this).find('textarea.form-control').attr('id', 'cst_' + $number).attr("spellcheck", "false");
            }
        });
        var $footer = $("footer").html();
        $("footer").remove();
        $(".page-content-wrapper").append("<footer class='footer'>" + $footer + "</footer>");

        var new_divs = "<div class='sqlrdat'>";
        new_divs += '<img src="' + base_url + 'assets/images/map.png" class="sst1">';
        new_divs += "</div>";
        $("body").append(new_divs);

        setTimeout(function () {
            $(".locgomaps").each(function () {
                $(this).find('img').attr('src', $(this).find('img').attr('data-src'));
            });
        }, 1000);

        setInterval(function () {
            $(".locgomaps").each(function () {
                $(this).find('img').attr('src', $(this).find('img').attr('data-src'));
            });
        }, 3000);

    }, 100);

    $(".t-header .navbar-dropdown .dropdown-body").html('<div class="text-center"> <div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');

    $("table.table tbody tr a").each(function () {
        $(this).closest('td').addClass('islinks');
    });


    setTimeout(function () {
        $(".alert-notification").parent().slideUp();
    }, 5000);

    $(".sidebar ul.navigation-menu li").each(function () {
        var $this1 = $(this);
        $this1.find('li').each(function () {
            var $this2 = $(this);
            $this2.find('li').each(function () {
                var $this3 = $(this);
                if ($this3.find('a').hasClass('active')) {
                    $this2.parent().prev().click();
                    setTimeout(function () {
                        $this2.find('a[data-toggle=collapse]').click();
                    }, 1000);
                }
            });
        })
    });

    $(".idex-loader").fadeOut();
});

function search(table, value) {
    value = value.toLowerCase().trim();

    $(table).filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
}


// $('.data-responsive table tbody').on('click', 'tr', function () {
//     var link = $(this).find('i.mdi-border-color').parent().attr('href');
//     var redirect = false;
//     if (link) {
//         redirect = link;
//     } else {
//         var viewlink = $(this).find('i.mdi-eye').parent().attr('href');
//         if (viewlink) {
//             redirect = viewlink;
//         }
//     }
//     if (redirect) {
//         $(".idex-loader").fadeIn(200);
//         window.location.href = redirect;
//     }
//     /*
//         if ($(this).hasClass('selected')) {
//             $(this).removeClass('selected');
//         } else {
//             $('tr.selected').removeClass('selected');
//             $(this).addClass('selected');
//         }*/
// });
$('.modal-body').on('click', 'img', function () {
    $(this).css('transform', 'scale(1)');
    if ($(this).closest('.modal-dialog').hasClass('modal-lg')) {
        $(this).closest('.modal-dialog').removeClass('modal-lg');
    } else {
        $('.modal-body img').closest('.modal-dialog').removeClass('modal-lg');
        $(this).closest('.modal-dialog').addClass('modal-lg');
    }
});


function addcenterbtn() {
    $("table#gmm-ajax-table a").each(function () {
        $(this).closest('td').addClass('islinks');
        if ($(this).find('i').hasClass('mdi-delete')) {
            $(this).closest('td').addClass('delete');
        }
        if ($(this).find('i').hasClass('mdi-content-copy')) {
            $(this).closest('td').addClass('copy');
        }
    });
}

$(document).ready(function () {
    (function () {
        var origOpen = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function () {
            this.addEventListener('load', function () {
                console.log(this.status + " - " + this.responseURL);
            });
            origOpen.apply(this, arguments);
        };
    })();

    if ($(".search_step").length) {
        var htmlsearch = '<tr class="is_search">';
        $(".search_step").each(function () {
            htmlsearch += '<th>' + $(this).html() + '</th>';
        });
        htmlsearch += '</tr>';
        $("table.table.search thead").prepend(htmlsearch);
    }

    $("form a.btn-danger i.mdi-close").parent().addClass('btn-icon');
    $.fn.dataTable.ext.errMode = 'none';
    var winheight = window.innerHeight;

    if ($('#gmm-table').length) {
        var htmlsearch = '<tr class="is_search byid">';
        $('#gmm-table thead tr th').each(function (i) {
            var names = $(this).text();
            if ($(this).hasClass('text-center')) {
                names = '';
            }
            htmlsearch += '<th>';
            if (names) {
                htmlsearch += '<label>' + names + '</label><input class="form-control" data-i="' + i + '">';
            } else {
                htmlsearch += ' ';
            }
            htmlsearch += '</th>';
        });
        htmlsearch += '</tr>';
        $("table.table.search thead").prepend(htmlsearch);
        $('<a href="#." class="btn btn-success searchby btn-icon btn-sm"><i class="mdi mdi-file-search"></i>Search</a>').insertBefore('.settings-btn');
    }

    var oTable = $('#gmm-table').DataTable({
        "bLengthChange": false,
        "scrollX": true,
        "scrollY": (winheight - 350),
        "oLanguage": {
            "sInfo": "Showing _START_ - _END_ of _TOTAL_ records",
            "sInfoEmpty": "No Found",
            "sZeroRecords": "No records found..."
        },
        "columnDefs": [{
            "targets": 'text-center',
            "orderable": false,
        }]
        // "stateSave": true
    });
    if ($('#gmm-table').length) {
        $('body').on('change keyup', "#inlineFormInputGroup", function () {
            oTable.search($(this).val()).draw();
        });
        $('a.toggle-vis').on('click', function (e) {
            e.preventDefault();
            var column = oTable.column($(this).attr('data-column'));
            column.visible(!column.visible());
        });
        if ($('div.settings').length) {
            checked_columns(oTable, 1)
        }

        $('body').on('keyup', ".dataTables_scrollHead tr.is_search.byid input", function () {
            oTable.columns().search('').draw();
            $('tr.is_search.byid input').each(function (i) {
                if ($(this).val()) {
                    oTable.columns($(this).data('i')).search($(this).val()).draw();
                }
            });
        });
        //        $('.search_step .ajax_s').keyup(function () {
        //            oTable.search($(this).val()).draw();
        //        });
    }


    $("body.reports .table-responsive").css('height', (winheight - 250));

    var ajaxTable = $('#gmm-ajax-table').DataTable({
        "bLengthChange": false,
        "processing": true,
        "serverSide": true,
        // "ajax": $('#gmm-ajax-table').data('url'),
        ajax: {
            url: $('#gmm-ajax-table').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "more": $("#more_datatbl").val() || ''
                });
            }
        },
        "scrollX": true,
        "scrollY": (winheight - 350),
        "cmd": "refresh",
        "order": [[0, "desc"]],
        "oLanguage": {
            "sInfo": "Showing _START_ - _END_ of _TOTAL_ records",
            "sInfoEmpty": "No Found",
            "sZeroRecords": "No records found...",
            "sProcessing": "<i class='mdi mdi-refresh mdi-spin'></i>Please wait..."
        },
        "columnDefs": [{
            "targets": 'text-center',
            "orderable": false,
        }],
        "pageLength": 10,
        /*
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
    <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
        fixedHeader: {
            header: true,
            headerOffset: 80
        },
        */
        /*
        "scrollY": "600px",
        "scrollCollapse": true,
        "stateSave": true,
        "stateSaveParams": function (settings, data) {
            data.search.search = "";
        },
        */
        "fnDrawCallback": function () {
            addcenterbtn();
        }
    });

    if ($('#gmm-ajax-table').length) {
        $('#inlineFormInputGroup').keyup(delay(function () {
            ajaxTable.search($(this).val()).draw();
        }, 500));
        $('a.toggle-vis').on('click', function (e) {
            e.preventDefault();
            // console.log(ajaxTable.columns([$(this).attr('data-column')]).visible());
            ajaxTable.columns([$(this).attr('data-column')]).visible(false);
        });
        if ($('div.settings').length) {
            checked_columns(ajaxTable, 1)
        }
        setInterval(function () {
            ajaxTable.ajax.reload(null, false);
        }, 20000);
        $(".t-header .t-header-toggler").click(function () {
            ajaxTable.ajax.reload(null, false);
        });
        // $("thead .ajax_s").on('change keyup', delay(function () {
        $('body').on('change keyup', "thead .ajax_s", delay(function () {
            $extra_data = {};
            $(".dataTables_scrollHead thead .ajax_s").each(function () {
                $extra_data[$(this).attr('name')] = $(this).val();
            });
            $("#more_datatbl").val(JSON.stringify($extra_data));
            ajaxTable.settings()[0].jqXHR.abort();
            ajaxTable.ajax.reload(null, false);
        }, 500));
    }
    $('div.settings .custom-switch input').change(function () {
        $(".btn-icon.btn.setting i").addClass('mdi-spin');
        if ($('#gmm-ajax-table').length) {
            checked_columns(ajaxTable);
        }
        if ($('div.settings').length) {
            checked_columns(oTable);
        }
        setTimeout(function () {
            var postarray = [];
            $('div.settings .custom-switch input').each(function () {
                if ($(this).is(':checked')) {
                    postarray.push($(this).val());
                }
            });
            var postdata = {
                page: $('body').data('page'),
                r: postarray
            };
            $.ajax({
                type: 'POST',
                url: base_url + 'notification/save',
                data: postdata,
            });
            $(".btn-icon.btn.setting i").removeClass('mdi-spin');
        }, 1000);
        addcenterbtn();
    });

    $('.ss_more select').change(function () {
        var values = $(this).find('option:selected').text();
        $("input#inlineFormInputGroup").val(values).keyup();
    });

    $('.ss_more .simpledate').change(function () {
        var values = changedate($(this).val());
        $("input#inlineFormInputGroup").val(values).keyup();
    });

    $('.search_drop').change(function () {
        $(".ss_more").hide();
        $(".ss_more.ssd" + $(this).val()).fadeIn(200);
        return false;
    });

    $('.searchby').click(function () {
        $(".ajax_s,input#more_datatbl").val('');
        $("input#inlineFormInputGroup").val('').keyup();
        // $("select.ajax_s").empty();
        $("select.ajax_s").select2({
            placeholder: " ",
        });

        // var oSettings = ajaxTable.settings();
        var winheight = window.innerHeight;
        var maxheight = 0;
        $(".dataTables_scrollHead thead tr.is_search").stop().fadeToggle(200, function () {
            if ($(this).is(':visible')) {
                $('.searchby').html('<i class="mdi mdi-close"></i>Clear');
                // oSettings.context[0].oScroll.sY = (winheight - 550);
                maxheight = (winheight - 450);
            } else {
                $('.searchby').html('<i class="mdi mdi-file-search"></i>Search');
                // oSettings.context[0].oScroll.sY = (winheight - 350);
                maxheight = (winheight - 350);
            }
            // console.log(winheight);
            // console.log(maxheight);
            $(".dataTables_scroll .dataTables_scrollBody").height(maxheight);
        });
        // ajaxTable.draw();

        // $(".searchby_div").slideToggle(200);
        /*
        $(".searchby_div").stop().slideToggle(200, function () {
            if ($('.searchby_div').is(':visible')) {
                $('.searchby_div').css('height', 'auto');
                $('.searchby').html('<i class="mdi mdi-close"></i>Clear');
            } else {
                $('.searchby').html('<i class="mdi mdi-file-search"></i>Search');
            }
        });
        */

        if ($('#gmm-table').length) {
            oTable.columns().search('').draw();
            $("tr.is_search.byid input").val('');
        }
        return false;
    });

    $('a.clone').click(function () {
        var tr = $(this).closest('tr');
        if (!tr.hasClass('iscloned')) {
            var clone = tr.clone();
            clone.addClass('iscloned');
            clone.find('td a.clone').addClass('dlpump').text('Delete').addClass('btn-danger');
            clone.find('input.time').val('');
            clone.find('span.select2.select2-container').remove();
            clone.find('select option').removeAttr('data-select2-id');
            clone.find('select option')
                .filter(function () {
                    return !this.value || $.trim(this.value).length == 0 || $.trim(this.text).length == 0;
                })
                .remove();
            clone.find('select').val('').removeAttr('data-select2-id').select2();
            clone.hide().fadeIn(100);
            tr.after(clone);
            datechoose();
        }
    });

    datechoose();
    $(".gmm_View form").removeAttr('action');
    $(".gmm_View textarea, .gmm_View input").attr('placeholder', '-');
    $(".gmm_View span.select2-selection__placeholder").html('-');

    $("select.ajax_s").each(function () {
        $this = $(this);
        $data_url = $(".searchby_div").data("link") + '/ajax-search';
        var attr = $this.attr("name");
        var vals = $this.val();
        $this.select2({
            // tags: true,
            multiple: true,
            minimumInputLength: 1,
            // minimumResultsForSearch: -1,
            ajax: {
                url: $data_url,
                dataType: "json",
                type: "POST",
                data: function (params) {
                    var queryParameters = {
                        s: params.term,
                        key: attr,
                        val: vals
                    }
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.items, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                }
            },
            templateResult: function (item) {
                if (item.loading) {
                    return item.text;
                }
                return item.text;

            },
            language: {
                searching: function (params) {
                    query = params;
                    return 'Searching...';
                },
                inputTooShort: function () {
                    return "Search here...";
                },
                errorLoading: function () {
                    return "Try again...";
                }
            }
        });
    });

    $(".search_step input").attr('autocomplete', 'new-password');
    // $(".searchby i").addClass('mdi-search-web').removeClass('mdi-file-search');
});

function delay(callback, ms) {
    var timer = 0;
    return function () {
        var context = this,
            args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}

function changedate(date) {
    return date.split("/").reverse().join("-");
}

function checked_columns(ajaxTable, times = '') {
    if (times) {
        var columnCounts = ajaxTable.columns().nodes().length;
        for (var i = 0; i < columnCounts; i++) {
            ajaxTable.columns(i).visible(false);
        }
    }
    $('div.settings .custom-switch input').each(function () {
        if ($(this).is(':checked')) {
            ajaxTable.columns([$(this).closest('.custom-switch').data('view')]).visible(true);
            $(".search_step").eq($(this).closest('.custom-switch').data('view')).show();
        } else {
            ajaxTable.columns([$(this).closest('.custom-switch').data('view')]).visible(false);
            $(".search_step").eq($(this).closest('.custom-switch').data('view')).hide();
        }
    });
}

$(document).on('click', 'a.setting', function () {
    $(".settings").stop().slideToggle(200);
    return false;
});

$(document).on('click', '.iscloned td a', function () {
    $(this).closest('tr').remove();
    return false;
});

function datechoose() {
    $(".simpledate").each(function () {
        $(this).attr('autocomplete', 'off');
        var $r = {
            beforeShow: function () {
                $(this).blur();
            },
            enableOnReadonly: !0,
            autoclose: 1,
            todayHighlight: !0,
            format: 'dd/mm/yyyy',
            templates: {
                leftArrow: '<i class="mdi mdi-chevron-left"></i>',
                rightArrow: '<i class="mdi mdi-chevron-right"></i>'
            }
        };
        if ($(this).data('min')) {
            $r.startDate = new Date($(this).data('min'));
        }
        $(this).datepicker($r);
    });
}

document.addEventListener('gesturestart', function (e) {
    e.preventDefault();
});

$(".simpledate").on('input keydown paste', function (e) {
    e.preventDefault();
});

$('.simpledate').on('focus', function () {
    $(this).trigger('blur');
});

function addedborder(html) {
    var $div = $('<div>').html(html);
    $div.find('h3').css('font-family', 'Calibri');
    $div.find('table').attr('border', '1').attr('cellspacing', '0').attr('cellpadding', '5').css('font-family', 'Calibri').css('width', '100%');
    var processedHTML = $div.html();
    return processedHTML;
}

function docfileexport(name) {
    var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
        "xmlns:w='urn:schemas-microsoft-com:office:word' " +
        "xmlns='http://www.w3.org/TR/REC-html40'>" +
        "<head><meta charset='utf-8'><title>" + name + "</title></head><body>";

    var subheader = "<center><h3>" + name + "</h3></center>";

    var footer = "</body></html>";
    var sourceHTML = addedborder(header + subheader + $(".docus_data").html() + footer);

    var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
    var fileDownload = document.createElement("a");
    document.body.appendChild(fileDownload);
    fileDownload.href = source;
    fileDownload.download = name + '.doc';
    fileDownload.click();
    document.body.removeChild(fileDownload);
}

$(window).scroll(function () {
    var scroll = ($(window).scrollTop()) / 2 + 50;
    $(".header-fixed div#hex3").css("top", "-" + scroll + "px");
});

function updateIndicator() {
    var $class = navigator.onLine ? 'online' : 'offline';
    $(".offlineerror").slideUp("normal", function () {
        $(this).remove();
    });
    if ($class == "offline") {
        var $error = '<div class="alert-notification-wrapper offlineerror bottom"> <div class="alert-notification"> <p>You\'re offline check your internet connection and try again...</p> </div> </div>';
        $(".page-content-wrapper").prepend($error);
    }
}

$(window).bind('keydown', function (event) {
    if (event.ctrlKey || event.metaKey) {
        switch (String.fromCharCode(event.which).toLowerCase()) {
            case 'f':
                event.preventDefault();
                // $("input#inlineFormInputGroup").focus();
                $(".searchby").click();
                break;
        }
    }
});

function btnv(v) {
    $("input.btn-value").val(v);
    return true;
}

var invalidChars = [
    "-",
    "+",
    "e",
];

$('input[type="number"]').on('input keydown paste', function (e) {
    if (invalidChars.includes(e.key)) {
        e.preventDefault();
    }
});

function spin(view = '') {
    if (view) {
        $("img.ajax-loader").slideDown();
    } else {
        $("img.ajax-loader").slideUp();
    }
}