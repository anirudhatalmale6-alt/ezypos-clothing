<!-- ============================================================== -->
<!-- Cash Flow & Payment Method Report -->
<!-- ============================================================== -->
<div class="wrapper">
    <div class="container">
        <!-- Filters Row -->
        <div class="row">
            <div class="button-list col-2 col-xl-2 col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <select name="method_select" id="method_select" class="form-control">
                    <option value="all">-- All Payment Methods --</option>
                    <option value="cash">Cash</option>
                    <?php if(isset($paymentMethods) && $paymentMethods){ foreach ($paymentMethods as $pm) { ?>
                        <option value="<?php echo $pm->pm_id; ?>">
                            <?php echo $pm->pm_name; ?>
                        </option>
                    <?php }} ?>
                </select>
            </div>
            <div class="button-list col-2 col-xl-2 col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <select name="store_select" id="store_select" class="form-control">
                    <option value="all">-- All Branches --</option>
                    <?php if(isset($storesForFilter) && $storesForFilter){ foreach ($storesForFilter as $st) {
                        $sid   = is_array($st) ? $st['store_id']   : $st->store_id;
                        $sname = is_array($st) ? $st['store_name'] : $st->store_name; ?>
                        <option value="<?php echo $sid; ?>"><?php echo htmlspecialchars($sname); ?></option>
                    <?php }} ?>
                </select>
            </div>
            <div class="col-lg-6 col-md-10 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="datepicFrom" class="col-3 col-form-label">From<span class="text-danger">*</span></label>
                            <div class="">
                                <input class="col-8 form-control datepic" placeholder="From.." value="" id="datepicFrom">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="datepicTo" class="col-3 col-form-label">To<span class="text-danger">*</span></label>
                            <div class="">
                                <input class="col-8 form-control datepic" placeholder="To.." value="" id="datepicTo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1">
                <button type="button" id="btnFilter" class="btn btn-primary waves-effect waves-light">
                    <i class="fa fa-search"></i>
                </button>
            </div>
            <div class="col-1">
                <button type="reset" id="reset" class="btn btn-outline-danger waves-effect waves-light">
                    <i class="fa fa-refresh"></i>
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-3" id="summaryCards" style="display:none;">
            <div class="col-12">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-bar-chart"></i> Cash Flow Summary</h4>
                    <div class="row" id="summaryContent">
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Money In: <span class="text-success" id="summaryTotalIn">0.00</span></h5>
                        </div>
                        <div class="col-md-4">
                            <h5>Money Out: <span class="text-danger" id="summaryTotalOut">0.00</span></h5>
                        </div>
                        <div class="col-md-4">
                            <h5>Net Cash Flow: <span class="text-primary" id="summaryGrandTotal">0.00</span></h5>
                        </div>
                    </div>
                    <small class="text-muted">
                        Includes cash, cheque and card taken on sales; all tailoring order payments;
                        money collected when an exchange costs more than the returned items; and refunds paid out.
                    </small>
                </div>
            </div>
        </div>

        <!-- Table Row -->
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive" id="table_div">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex">
                            <button id="copyToClipboard"
                                    class="btn text-white btn-hover-custom"
                                    style="background-color: #868E96; border-color: #868E96; border-top-left-radius: 5px; border-bottom-left-radius: 5px; border-top-right-radius: 0; border-bottom-right-radius: 0; outline: none; margin-left: 15px;"
                                    onclick="this.blur();">
                                Copy
                            </button>
                            <button id="exportPDF"
                                    class="btn text-white btn-hover-custom"
                                    style="background-color: #868E96; border-color: #868E96; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-top-left-radius: 0; border-bottom-left-radius: 0; outline: none;"
                                    onclick="this.blur();">
                                PDF
                            </button>
                        </div>
                        <div class="text-right">
                            <h5>Net (In - Out): <span class="text-success" id="totalAmount">0.00</span></h5>
                        </div>
                    </div>
                    <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead><tr><th>Select date range and click Search to view report</th></tr></thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function () {

    $(".datepic").datepicker({
        dateFormat: "yy-mm-dd"
    });

    function esc(s){
        return $('<div>').text(s === null || s === undefined ? '' : s).html();
    }

    function loadReport(){
        var from = $('#datepicFrom').val();
        var to = $('#datepicTo').val();
        var method = $('#method_select').val();
        var storeId = $('#store_select').val();

        // Load detail table
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url()?>Reports/getCashFlowReportData',
            data: { from: from, to: to, method: method, store_id: storeId },
            dataType: 'json',
            success: function(data){
                if(!data || data.length === 0){
                    try{ $('#datatable-buttons').DataTable().destroy(); }catch(e){}
                    $('#datatable-buttons').html('<thead><tr><th>No cash flow transactions found</th></tr></thead>');
                    $('#totalAmount').text("0.00");
                    return;
                }

                var netTotal = 0;

                var tableHTML = '<thead><tr>' +
                    '<th>#</th>' +
                    '<th>Source</th>' +
                    '<th>Ref</th>' +
                    '<th>Date</th>' +
                    '<th>Branch</th>' +
                    '<th>Customer</th>' +
                    '<th>Payment Method</th>' +
                    '<th>Card / Cheque Ref</th>' +
                    '<th style="text-align:right;">In</th>' +
                    '<th style="text-align:right;">Out</th>' +
                    '</tr></thead><tbody>';

                for(var i = 0; i < data.length; i++){
                    var row = data[i];
                    var amt = parseFloat(row.amount) || 0;
                    var isOut = (row.direction === 'out');
                    netTotal += isOut ? -amt : amt;

                    tableHTML += '<tr>' +
                        '<td>' + (i+1) + '</td>' +
                        '<td>' + esc(row.source) + '</td>' +
                        '<td>' + esc(row.ref) + '</td>' +
                        '<td>' + esc(row.date) + '</td>' +
                        '<td>' + esc(row.store_name || '-') + '</td>' +
                        '<td>' + esc(row.customer_name || 'N/A') + '</td>' +
                        '<td>' + esc(row.method_name) + '</td>' +
                        '<td>' + esc(row.reference || '-') + '</td>' +
                        '<td style="text-align:right;color:#2e7d32;">' + (isOut ? '-' : amt.toFixed(2)) + '</td>' +
                        '<td style="text-align:right;color:#c62828;">' + (isOut ? amt.toFixed(2) : '-') + '</td>' +
                        '</tr>';
                }
                tableHTML += '</tbody>';

                try{ $('#datatable-buttons').DataTable().destroy(); }catch(e){}
                $('#datatable-buttons').html(tableHTML);
                $('#datatable-buttons').DataTable({
                    buttons: ['copy', 'excel', 'pdf'],
                    order: [[3, 'desc']]
                });

                $('#totalAmount').text(netTotal.toFixed(2));
            },
            error: function(){
                alert('Failed to fetch cash flow data.');
            }
        });

        // Load summary
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url()?>Reports/getCashFlowSummaryData',
            data: { from: from, to: to, store_id: storeId },
            dataType: 'json',
            success: function(res){
                var methods = (res && res.methods) ? res.methods : [];
                if(methods.length === 0){
                    $('#summaryCards').hide();
                    return;
                }

                var html = '';
                for(var i = 0; i < methods.length; i++){
                    var s = methods[i];
                    var net = parseFloat(s.total_amount) || 0;
                    var mIn = parseFloat(s.total_in) || 0;
                    var mOut = parseFloat(s.total_out) || 0;

                    html += '<div class="col-md-3 col-sm-6 mb-2">' +
                        '<div class="card" style="border-left: 4px solid #5b69bc;">' +
                        '<div class="card-body p-2">' +
                        '<h6 class="m-0">' + esc(s.method_name) + '</h6>' +
                        '<span class="text-primary"><b>LKR ' + net.toFixed(2) + '</b></span><br>' +
                        '<small class="text-muted">in ' + mIn.toFixed(2) +
                        (mOut > 0 ? ' &nbsp;/&nbsp; out ' + mOut.toFixed(2) : '') + '</small>' +
                        '</div></div></div>';
                }

                $('#summaryContent').html(html);
                $('#summaryTotalIn').text('LKR ' + (parseFloat(res.total_in) || 0).toFixed(2));
                $('#summaryTotalOut').text('LKR ' + (parseFloat(res.total_out) || 0).toFixed(2));
                $('#summaryGrandTotal').text('LKR ' + (parseFloat(res.net) || 0).toFixed(2));
                $('#summaryCards').show();
            }
        });
    }

    // Filter button
    $('#btnFilter').click(function(){
        loadReport();
    });

    // Also reload when the method or branch filter changes
    $('#method_select, #store_select').change(function(){
        var from = $('#datepicFrom').val();
        var to = $('#datepicTo').val();
        if(from && to){
            loadReport();
        }
    });

    // Reset
    $('#reset').click(function(){
        try{ $('#datatable-buttons').DataTable().destroy(); }catch(e){}
        $('#datatable-buttons').html('<thead><tr><th>Select date range and click Search to view report</th></tr></thead>');
        $('#method_select').val('all');
        $('#store_select').val('all');
        $('#datepicFrom').val('');
        $('#datepicTo').val('');
        $('#totalAmount').text("0.00");
        $('#summaryCards').hide();
    });
});
</script>

<!-- jsPDF Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.27/jspdf.plugin.autotable.min.js"></script>

<script>
document.getElementById('exportPDF').addEventListener('click', function () {
    var jsPDF = window.jspdf.jsPDF;
    var doc = new jsPDF();

    var headers = [];
    var rows = [];

    $('#datatable-buttons thead tr th').each(function () {
        headers.push($(this).text().trim());
    });

    $('#datatable-buttons tbody tr').each(function () {
        var row = [];
        $(this).find('td').each(function () {
            row.push($(this).text().trim());
        });
        rows.push(row);
    });

    if (rows.length === 0) {
        alert('No data available to export.');
        return;
    }

    var pageWidth = doc.internal.pageSize.getWidth();
    var title = 'Cash Flow Report';
    var titleWidth = doc.getTextWidth(title);
    doc.setFontSize(14);
    doc.text(title, (pageWidth - titleWidth) / 2, 15);

    var from = document.getElementById('datepicFrom').value;
    var to = document.getElementById('datepicTo').value;
    if(from && to){
        doc.setFontSize(10);
        doc.text('Period: ' + from + ' to ' + to, 14, 22);
    }

    doc.autoTable({
        head: [headers],
        body: rows,
        startY: from && to ? 28 : 25,
        styles: { fontSize: 8 },
        headStyles: { fillColor: [45, 65, 84] }
    });

    var totalAmt = document.getElementById('totalAmount').textContent;
    doc.setFontSize(10);
    doc.text('Total: LKR ' + totalAmt, 14, doc.lastAutoTable.finalY + 10);

    doc.save('Cash_Flow_Report.pdf');
});

document.getElementById('copyToClipboard').addEventListener('click', function () {
    var headers = [];
    var rows = [];

    $('#datatable-buttons thead tr th').each(function () {
        headers.push($(this).text().trim());
    });

    $('#datatable-buttons tbody tr').each(function () {
        var row = [];
        $(this).find('td').each(function () {
            row.push($(this).text().trim());
        });
        rows.push(row);
    });

    if (rows.length === 0) {
        alert('No data available to copy.');
        return;
    }

    var clipboardData = headers.join('\t') + '\n';
    for(var i = 0; i < rows.length; i++){
        clipboardData += rows[i].join('\t') + '\n';
    }

    navigator.clipboard.writeText(clipboardData).then(function(){
        alert('Table data copied to clipboard!');
    }).catch(function(err){
        alert('Failed to copy data: ' + err);
    });
});

document.querySelectorAll('.btn-hover-custom').forEach(function(button){
    button.addEventListener('mouseover', function(){
        button.style.backgroundColor = '#727b84';
        button.style.borderColor = '#727b84';
    });
    button.addEventListener('mouseout', function(){
        button.style.backgroundColor = '#868E96';
        button.style.borderColor = '#868E96';
    });
});
</script>
