<!-- ============================================================== -->
<!-- Production & Tailoring Report -->
<!-- ============================================================== -->
<div class="wrapper">
    <div class="container">
        <!-- Filters Row -->
        <div class="row">
            <div class="button-list col-3 col-xl-3 col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <select name="report_type" id="report_type" class="form-control">
                    <option value="production">Production Orders</option>
                    <option value="tailoring">Tailoring Orders</option>
                </select>
            </div>
            <div class="button-list col-2 col-xl-2 col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <select name="status_select" id="status_select" class="form-control">
                    <option value="all">-- All Status --</option>
                </select>
            </div>
            <div class="col-lg-5 col-md-10 col-sm-12 col-xs-12">
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
                    <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-bar-chart"></i> <span id="summaryTitle">Summary</span></h4>
                    <div class="row" id="summaryContent">
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
                            <h5>Total: <span class="text-success" id="totalAmount">0.00</span></h5>
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

    var prodStatuses = {'Issued':'Issued', 'In-Progress':'In-Progress', 'Completed':'Completed', 'Cancelled':'Cancelled'};
    var tailStatuses = {'Pending':'Pending', 'In-Progress':'In-Progress', 'Completed':'Completed', 'Cancelled':'Cancelled'};

    function updateStatusDropdown(){
        var type = $('#report_type').val();
        var html = '<option value="all">-- All Status --</option>';
        if(type === 'production'){
            for(var k in prodStatuses){
                html += '<option value="'+k+'">'+prodStatuses[k]+'</option>';
            }
        } else {
            for(var k in tailStatuses){
                html += '<option value="'+k+'">'+tailStatuses[k]+'</option>';
            }
        }
        $('#status_select').html(html);
    }

    updateStatusDropdown();
    $('#report_type').change(function(){ updateStatusDropdown(); });

    function getStatusLabel(status, type){
        if(type === 'production'){
            return prodStatuses[status] || status;
        }
        return status || 'Unknown';
    }

    function getStatusBadge(status, type){
        var label = getStatusLabel(status, type);
        var cls = 'badge-secondary';
        var s = String(status);
        if(s === 'Completed') cls = 'badge-success';
        else if(s === 'In-Progress') cls = 'badge-primary';
        else if(s === 'Pending' || s === 'Issued') cls = 'badge-warning';
        else if(s === 'Cancelled') cls = 'badge-danger';
        return '<span class="badge '+cls+'">'+label+'</span>';
    }

    function loadReport(){
        var from = $('#datepicFrom').val();
        var to = $('#datepicTo').val();
        var type = $('#report_type').val();
        var status = $('#status_select').val();

        var dataUrl, summaryUrl;
        if(type === 'production'){
            dataUrl = '<?php echo base_url()?>Reports/getProductionReportData';
            summaryUrl = '<?php echo base_url()?>Reports/getProductionSummaryData';
        } else {
            dataUrl = '<?php echo base_url()?>Reports/getTailoringReportData';
            summaryUrl = '<?php echo base_url()?>Reports/getTailoringSummaryData';
        }

        // Load detail table
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: { from: from, to: to, status: status },
            dataType: 'json',
            success: function(data){
                if(!data || data.length === 0){
                    try{ $('#datatable-buttons').DataTable().destroy(); }catch(e){}
                    $('#datatable-buttons').html('<thead><tr><th>No records found for the selected criteria</th></tr></thead>');
                    $('#totalAmount').text("0.00");
                    return;
                }

                var grandTotal = 0;
                var tableHTML = '';

                if(type === 'production'){
                    tableHTML = '<thead><tr>' +
                        '<th>#</th>' +
                        '<th>Code</th>' +
                        '<th>Item</th>' +
                        '<th>Tailor/Supplier</th>' +
                        '<th>Type</th>' +
                        '<th style="text-align:right;">Qty</th>' +
                        '<th style="text-align:right;">Total Cost</th>' +
                        '<th>Date</th>' +
                        '<th>Status</th>' +
                        '</tr></thead><tbody>';

                    for(var i = 0; i < data.length; i++){
                        var row = data[i];
                        var cost = parseFloat(row.prod_total_cost) || 0;
                        grandTotal += cost;

                        tableHTML += '<tr>' +
                            '<td>' + (i+1) + '</td>' +
                            '<td>' + (row.prod_code || '') + '</td>' +
                            '<td>' + (row.itm_name || 'N/A') + '</td>' +
                            '<td>' + (row.tailor_name || 'N/A') + '</td>' +
                            '<td>' + (row.prod_type || '') + '</td>' +
                            '<td style="text-align:right;">' + (parseFloat(row.prod_quantity) || 0).toFixed(2) + '</td>' +
                            '<td style="text-align:right;">' + cost.toFixed(2) + '</td>' +
                            '<td>' + (row.prod_date || '') + '</td>' +
                            '<td>' + getStatusBadge(row.prod_status, 'production') + '</td>' +
                            '</tr>';
                    }
                } else {
                    tableHTML = '<thead><tr>' +
                        '<th>#</th>' +
                        '<th>Code</th>' +
                        '<th>Customer</th>' +
                        '<th>Store</th>' +
                        '<th style="text-align:right;">Total</th>' +
                        '<th style="text-align:right;">Paid</th>' +
                        '<th style="text-align:right;">Balance</th>' +
                        '<th>Order Date</th>' +
                        '<th>Delivery Date</th>' +
                        '<th>Status</th>' +
                        '</tr></thead><tbody>';

                    for(var i = 0; i < data.length; i++){
                        var row = data[i];
                        var total = parseFloat(row.prodsale_total) || 0;
                        grandTotal += total;

                        tableHTML += '<tr>' +
                            '<td>' + (i+1) + '</td>' +
                            '<td>' + (row.prodsale_code || '') + '</td>' +
                            '<td>' + (row.cus_name || 'N/A') + '</td>' +
                            '<td>' + (row.store_name || 'N/A') + '</td>' +
                            '<td style="text-align:right;">' + total.toFixed(2) + '</td>' +
                            '<td style="text-align:right;">' + (parseFloat(row.prodsale_paid) || 0).toFixed(2) + '</td>' +
                            '<td style="text-align:right;">' + (parseFloat(row.prodsale_balance) || 0).toFixed(2) + '</td>' +
                            '<td>' + (row.prodsale_date || '') + '</td>' +
                            '<td>' + (row.prodsale_delivery_date || '') + '</td>' +
                            '<td>' + getStatusBadge(row.prodsale_status, 'tailoring') + '</td>' +
                            '</tr>';
                    }
                }
                tableHTML += '</tbody>';

                try{ $('#datatable-buttons').DataTable().destroy(); }catch(e){}
                $('#datatable-buttons').html(tableHTML);
                $('#datatable-buttons').DataTable({
                    buttons: ['copy', 'excel', 'pdf'],
                    order: [[0, 'asc']]
                });

                $('#totalAmount').text(grandTotal.toFixed(2));
            },
            error: function(){
                alert('Failed to fetch report data.');
            }
        });

        // Load summary
        $.ajax({
            type: 'POST',
            url: summaryUrl,
            data: { from: from, to: to },
            dataType: 'json',
            success: function(data){
                if(!data || data.length === 0){
                    $('#summaryCards').hide();
                    return;
                }

                var html = '';
                var totalOrders = 0;
                var title = (type === 'production') ? 'Production Summary' : 'Tailoring Summary';
                $('#summaryTitle').text(title);

                if(type === 'production'){
                    for(var i = 0; i < data.length; i++){
                        var s = data[i];
                        var count = parseInt(s.order_count) || 0;
                        var qty = parseFloat(s.total_qty) || 0;
                        var cost = parseFloat(s.total_cost) || 0;
                        totalOrders += count;

                        html += '<div class="col-md-3 col-sm-6 mb-2">' +
                            '<div class="card" style="border-left: 4px solid #5b69bc;">' +
                            '<div class="card-body p-2">' +
                            '<h6 class="m-0">' + getStatusLabel(s.prod_status, 'production') + '</h6>' +
                            '<small class="text-muted">' + count + ' orders</small><br>' +
                            '<span class="text-info">Qty: ' + qty.toFixed(2) + '</span><br>' +
                            '<span class="text-primary"><b>LKR ' + cost.toFixed(2) + '</b></span>' +
                            '</div></div></div>';
                    }
                } else {
                    for(var i = 0; i < data.length; i++){
                        var s = data[i];
                        var count = parseInt(s.order_count) || 0;
                        var amt = parseFloat(s.total_amount) || 0;
                        totalOrders += count;

                        html += '<div class="col-md-3 col-sm-6 mb-2">' +
                            '<div class="card" style="border-left: 4px solid #5b69bc;">' +
                            '<div class="card-body p-2">' +
                            '<h6 class="m-0">' + getStatusLabel(s.prodsale_status, 'tailoring') + '</h6>' +
                            '<small class="text-muted">' + count + ' orders</small><br>' +
                            '<span class="text-primary"><b>LKR ' + amt.toFixed(2) + '</b></span>' +
                            '</div></div></div>';
                    }
                }

                html += '<div class="col-12 mt-2"><hr><h5>Total Orders: ' + totalOrders + '</h5></div>';
                $('#summaryContent').html(html);
                $('#summaryCards').show();
            }
        });
    }

    // Filter button
    $('#btnFilter').click(function(){
        loadReport();
    });

    // Reload when type or status changes (if dates set)
    $('#report_type, #status_select').change(function(){
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
        $('#report_type').val('production');
        updateStatusDropdown();
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
    var doc = new jsPDF('l');

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
    var type = document.getElementById('report_type').value;
    var title = (type === 'production') ? 'Production Report' : 'Tailoring Orders Report';
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
        styles: { fontSize: 7 },
        headStyles: { fillColor: [45, 65, 84] }
    });

    var totalAmt = document.getElementById('totalAmount').textContent;
    doc.setFontSize(10);
    doc.text('Total: LKR ' + totalAmt, 14, doc.lastAutoTable.finalY + 10);

    doc.save(title.replace(/ /g,'_') + '.pdf');
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
