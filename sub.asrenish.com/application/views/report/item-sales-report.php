<!-- ============================================================== -->
<!-- Item Sales Report -->
<!-- ============================================================== -->
<div class="wrapper">
    <div class="container">
        <!-- Filters Row -->
        <div class="row">
            <div class="col-3">
                <input class="form-control" type="text" id="itemCodeSearch" placeholder="Search by item code or name...">
            </div>
            <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
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
                <button type="button" id="btnFilter" class="btn btn-primary waves-effect waves-light" title="Item summary report">
                    <i class="fa fa-search"></i>
                </button>
            </div>
            <div class="col-2">
                <button type="button" id="btnReceipts" class="btn btn-info waves-effect waves-light" title="Find sales receipts by item code">
                    <i class="fa fa-file-text-o"></i> Find Receipts
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
                    <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-bar-chart"></i> Item Sales Summary</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Total Items Sold: <span class="text-primary" id="summaryTotalItems">0</span></h5>
                        </div>
                        <div class="col-md-4">
                            <h5>Total Quantity: <span class="text-info" id="summaryTotalQty">0</span></h5>
                        </div>
                        <div class="col-md-4">
                            <h5>Total Revenue: <span class="text-success" id="summaryTotalRevenue">0.00</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Receipts by Item Code (Item 17) -->
        <div class="row" id="receiptsRow" style="display:none;">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-file-text-o"></i> Sales Receipts containing <span id="receiptItemLabel" class="text-primary"></span></h4>
                    <table class="table table-striped table-bordered" id="receiptsTable" width="100%">
                        <thead>
                            <tr>
                                <th>Receipt #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th style="text-align:right;">Qty</th>
                                <th style="text-align:right;">Line Total</th>
                                <th style="text-align:right;">Bill Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="receiptsBody"></tbody>
                    </table>
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
                            <h5>Total Revenue: <span class="text-success" id="totalRevenue">0.00</span></h5>
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
        var itemCode = $('#itemCodeSearch').val().trim();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url()?>Reports/getItemFullReportData',
            data: { from: from, to: to, item_code: itemCode },
            dataType: 'json',
            success: function(data){
                if(!data || data.length === 0){
                    try{ $('#datatable-buttons').DataTable().destroy(); }catch(e){}
                    $('#datatable-buttons').html('<thead><tr><th>No item data found for the selected criteria</th></tr></thead>');
                    $('#totalRevenue').text("0.00");
                    $('#summaryCards').hide();
                    return;
                }

                var totalSoldQty = 0;
                var totalRevenue = 0;
                var totalGrnQty = 0;
                var totalGrnCost = 0;

                var tableHTML = '<thead><tr>' +
                    '<th>#</th>' +
                    '<th>Item Code</th>' +
                    '<th>Item Name</th>' +
                    '<th>Category</th>' +
                    '<th style="text-align:right;">Sales</th>' +
                    '<th style="text-align:right;">Qty Sold</th>' +
                    '<th style="text-align:right;">Revenue (LKR)</th>' +
                    '<th style="text-align:right;">GRNs</th>' +
                    '<th style="text-align:right;">Qty Purchased</th>' +
                    '<th style="text-align:right;">GRN Cost (LKR)</th>' +
                    '</tr></thead><tbody>';

                for(var i = 0; i < data.length; i++){
                    var row = data[i];
                    var soldQty = parseFloat(row.sold_qty) || 0;
                    var rev = parseFloat(row.sale_revenue) || 0;
                    var grnQty = parseFloat(row.grn_qty) || 0;
                    var grnCost = parseFloat(row.grn_cost) || 0;
                    totalSoldQty += soldQty;
                    totalRevenue += rev;
                    totalGrnQty += grnQty;
                    totalGrnCost += grnCost;

                    tableHTML += '<tr>' +
                        '<td>' + (i+1) + '</td>' +
                        '<td>' + (row.itm_code || '') + '</td>' +
                        '<td>' + (row.itm_name || '') + '</td>' +
                        '<td>' + (row.cat_name || 'N/A') + '</td>' +
                        '<td style="text-align:right;">' + (row.num_sales || 0) + '</td>' +
                        '<td style="text-align:right;">' + soldQty.toFixed(2) + '</td>' +
                        '<td style="text-align:right;">' + rev.toFixed(2) + '</td>' +
                        '<td style="text-align:right;">' + (row.num_grns || 0) + '</td>' +
                        '<td style="text-align:right;">' + grnQty.toFixed(2) + '</td>' +
                        '<td style="text-align:right;">' + grnCost.toFixed(2) + '</td>' +
                        '</tr>';
                }
                tableHTML += '</tbody>';

                try{ $('#datatable-buttons').DataTable().destroy(); }catch(e){}
                $('#datatable-buttons').html(tableHTML);
                $('#datatable-buttons').DataTable({
                    buttons: ['copy', 'excel', 'pdf'],
                    order: [[6, 'desc']]
                });

                $('#totalRevenue').text(totalRevenue.toFixed(2));
                $('#summaryTotalItems').text(data.length);
                $('#summaryTotalQty').text(totalSoldQty.toFixed(2));
                $('#summaryTotalRevenue').text('LKR ' + totalRevenue.toFixed(2));
                $('#summaryCards').show();
            },
            error: function(){
                alert('Failed to fetch item report data.');
            }
        });
    }

    // Filter button
    $('#btnFilter').click(function(){
        loadReport();
    });

    // Item 17: find all sales receipts containing the entered item code
    function loadReceipts(){
        var itemCode = $('#itemCodeSearch').val().trim();
        if(!itemCode){ alert('Enter an item code (or name) to find receipts.'); return; }
        var from = $('#datepicFrom').val();
        var to = $('#datepicTo').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url()?>Reports/getSalesReceiptsByItem',
            data: { item_code: itemCode, from: from, to: to },
            dataType: 'json',
            success: function(data){
                try{ $('#receiptsTable').DataTable().destroy(); }catch(e){}
                $('#receiptItemLabel').text('"' + itemCode + '"');
                var html = '';
                if(!data || data.length === 0){
                    html = '<tr><td colspan="7" class="text-center text-muted">No sales receipts found for this item.</td></tr>';
                } else {
                    for(var i=0;i<data.length;i++){
                        var r = data[i];
                        html += '<tr>' +
                            '<td>#' + r.sale_id + '</td>' +
                            '<td>' + (r.sale_date || '') + '</td>' +
                            '<td>' + (r.cus_name || '-') + ' <br><small class="text-muted">' + (r.itm_code||'') + ' - ' + (r.itm_name||'') + '</small></td>' +
                            '<td style="text-align:right;">' + parseFloat(r.saleitem_quantity||0).toFixed(2) + '</td>' +
                            '<td style="text-align:right;">' + parseFloat(r.saleitem_total||0).toFixed(2) + '</td>' +
                            '<td style="text-align:right;">' + parseFloat(r.sale_grandtotal||0).toFixed(2) + '</td>' +
                            '<td><a href="<?php echo base_url();?>Sales/print_inv/' + r.sale_id + '" target="_blank" class="btn btn-sm btn-outline-dark" title="View / Print bill"><i class="fa fa-eye"></i> View / Print</a></td>' +
                            '</tr>';
                    }
                }
                $('#receiptsBody').html(html);
                $('#receiptsRow').show();
                if(data && data.length){ $('#receiptsTable').DataTable({ order: [[0,'desc']], destroy: true }); }
            },
            error: function(){ alert('Failed to fetch sales receipts.'); }
        });
    }
    $('#btnReceipts').click(loadReceipts);

    // Reset
    $('#reset').click(function(){
        try{ $('#datatable-buttons').DataTable().destroy(); }catch(e){}
        $('#datatable-buttons').html('<thead><tr><th>Select date range and click Search to view report</th></tr></thead>');
        $('#datepicFrom').val('');
        $('#datepicTo').val('');
        $('#itemCodeSearch').val('');
        $('#totalRevenue').text("0.00");
        $('#summaryCards').hide();
        $('#receiptsRow').hide();
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
    var title = 'Item Report (Sales & GRN)';
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

    var totalRev = document.getElementById('totalRevenue').textContent;
    doc.setFontSize(10);
    doc.text('Total Revenue: LKR ' + totalRev, 14, doc.lastAutoTable.finalY + 10);

    doc.save('Item_Report.pdf');
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
