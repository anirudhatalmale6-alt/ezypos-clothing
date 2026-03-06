<!-- ============================================================== -->
<!-- Payment Methods / Commission Report -->
<!-- ============================================================== -->
<div class="wrapper">
    <div class="container">
        <!-- Filters Row -->
        <div class="row">
            <div class="button-list col-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <select name="pm_select" id="pm_select" class="form-control">
                    <option value="all">-- All Payment Methods --</option>
                    <?php if(isset($payment_methods)){ foreach ($payment_methods as $pm) { ?>
                        <option value="<?php echo $pm->pm_id; ?>">
                            <?php echo $pm->pm_name; ?>
                        </option>
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
                    <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-bar-chart"></i> Commission Summary</h4>
                    <div class="row" id="summaryContent">
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Total Charged: <span class="text-primary" id="summaryTotalAmount">0.00</span></h5>
                        </div>
                        <div class="col-md-4">
                            <h5>Total Commission: <span class="text-danger" id="summaryTotalCommission">0.00</span></h5>
                        </div>
                        <div class="col-md-4">
                            <h5>Net Received: <span class="text-success" id="summaryNetReceived">0.00</span></h5>
                        </div>
                    </div>
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
                            <h5>Total Commission: <span class="text-danger" id="totalCommission">0.00</span></h5>
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

    function loadReport(){
        var from = $('#datepicFrom').val();
        var to = $('#datepicTo').val();
        var pm_id = $('#pm_select').val();

        // Load detail table
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url()?>Reports/getPaymentMethodsReportData',
            data: { from: from, to: to, pm_id: pm_id },
            dataType: 'json',
            success: function(data){
                if(!data || data.length === 0){
                    try{ $('#datatable-buttons').DataTable().destroy(); }catch(e){}
                    $('#datatable-buttons').html('<thead><tr><th>No payment method transactions found</th></tr></thead>');
                    $('#totalCommission').text("0.00");
                    $('#summaryCards').hide();
                    return;
                }

                var totalAmount = 0;
                var totalCommission = 0;

                var tableHTML = '<thead><tr>' +
                    '<th>#</th>' +
                    '<th>Sale ID</th>' +
                    '<th>Date</th>' +
                    '<th>Customer</th>' +
                    '<th>Sale Total</th>' +
                    '<th>Method</th>' +
                    '<th>Amount</th>' +
                    '<th>Commission</th>' +
                    '</tr></thead><tbody>';

                for(var i = 0; i < data.length; i++){
                    var row = data[i];
                    var amt = parseFloat(row.sp_amount) || 0;
                    var comm = parseFloat(row.sp_commission) || 0;
                    totalAmount += amt;
                    totalCommission += comm;

                    tableHTML += '<tr>' +
                        '<td>' + (i+1) + '</td>' +
                        '<td>' + row.sale_id + '</td>' +
                        '<td>' + row.sale_date + '</td>' +
                        '<td>' + row.cus_name + '</td>' +
                        '<td style="text-align:right;">' + parseFloat(row.sale_grandtotal).toFixed(2) + '</td>' +
                        '<td>' + row.pm_name + '</td>' +
                        '<td style="text-align:right;">' + amt.toFixed(2) + '</td>' +
                        '<td style="text-align:right;">' + comm.toFixed(2) + '</td>' +
                        '</tr>';
                }
                tableHTML += '</tbody>';

                try{ $('#datatable-buttons').DataTable().destroy(); }catch(e){}
                $('#datatable-buttons').html(tableHTML);
                $('#datatable-buttons').DataTable({
                    buttons: ['copy', 'excel', 'pdf'],
                    order: [[1, 'desc']]
                });

                $('#totalCommission').text(totalCommission.toFixed(2));
            },
            error: function(){
                alert('Failed to fetch report data.');
            }
        });

        // Load summary
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url()?>Reports/getPaymentMethodsSummaryData',
            data: { from: from, to: to },
            dataType: 'json',
            success: function(data){
                if(!data || data.length === 0){
                    $('#summaryCards').hide();
                    return;
                }

                var html = '';
                var grandTotal = 0;
                var grandCommission = 0;

                for(var i = 0; i < data.length; i++){
                    var s = data[i];
                    var amt = parseFloat(s.total_amount) || 0;
                    var comm = parseFloat(s.total_commission) || 0;
                    grandTotal += amt;
                    grandCommission += comm;

                    html += '<div class="col-md-3 col-sm-6 mb-2">' +
                        '<div class="card" style="border-left: 4px solid #5b69bc;">' +
                        '<div class="card-body p-2">' +
                        '<h6 class="m-0">' + s.pm_name + '</h6>' +
                        '<small class="text-muted">' + s.sale_count + ' sales</small><br>' +
                        '<span class="text-primary"><b>LKR ' + amt.toFixed(2) + '</b></span><br>' +
                        '<small class="text-danger">Commission: LKR ' + comm.toFixed(2) + '</small>' +
                        '</div></div></div>';
                }

                $('#summaryContent').html(html);
                $('#summaryTotalAmount').text('LKR ' + grandTotal.toFixed(2));
                $('#summaryTotalCommission').text('LKR ' + grandCommission.toFixed(2));
                $('#summaryNetReceived').text('LKR ' + (grandTotal - grandCommission).toFixed(2));
                $('#summaryCards').show();
            }
        });
    }

    // Filter button
    $('#btnFilter').click(function(){
        loadReport();
    });

    // Also load when payment method changes
    $('#pm_select').change(function(){
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
        $('#pm_select').val('all');
        $('#datepicFrom').val('');
        $('#datepicTo').val('');
        $('#totalCommission').text("0.00");
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
    var title = 'Commission Report';
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

    var totalComm = document.getElementById('totalCommission').textContent;
    doc.setFontSize(10);
    doc.text('Total Commission: LKR ' + totalComm, 14, doc.lastAutoTable.finalY + 10);

    doc.save('Commission_Report.pdf');
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
