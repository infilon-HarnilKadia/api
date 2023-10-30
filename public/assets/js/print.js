var tableToExcel = (function () {
    var uri = 'data:application/vnd.ms-excel;base64,'
        ,
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
        , base64 = function (s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        }
        , format = function (s, c) {
            return s.replace(/{(\w+)}/g, function (m, p) {
                return c[p];
            })
        }
    return function (table, name, filename = '') {

        /*
        var dxeer = document.getElementsByTagName('th');
        for (i = 0; i < dxeer.length; i++) {
            dxeer[i].style.fontFamily = 'Roboto, Calibri';
            dxeer[i].style.backgroundColor = 'yellow';
        }

        var dxeer = document.getElementsByTagName('td');
        for (i = 0; i < dxeer.length; i++) {
            dxeer[i].style.fontFamily = 'Roboto, Calibri';
        }
        */
        $("#response *").css('font-family', 'pfaudler, Roboto, Calibri');

        if (!table.nodeType) table = document.getElementById(table)
        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
        // window.location.href = uri + base64(format(template, ctx));

        var downloadLink;
        downloadLink = document.createElement("a");
        downloadLink.href = uri + base64(format(template, ctx))
        downloadLink.download = filename + '.xls';
        downloadLink.click();
        setTimeout(function () {
//            window.close();
        }, 1000)
    }
})();

/*
window.onload = function () {
    document.getElementById("exedata").setAttribute("border", "1");
    tableToExcel('exedata', 'download');
}
*/

$(document).on('click', "#export", function () {
    $('#response table').attr('id', 'hasmddownl___');
    tableToExcel('hasmddownl___', 'Sheet', $('title').text());
    $('#response .r').html('');
    return false;
});