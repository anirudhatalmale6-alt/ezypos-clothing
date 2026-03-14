<div class="wrapper">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Summary Cards -->
            <div class="row m-b-20" id="summaryCards"></div>

            <!-- Sales Report -->
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-gift"></i> Voucher Sales & Redemption Report</h4>
                <div class="row m-b-15">
                    <div class="col-md-3">
                        <label>From</label>
                        <input type="date" class="form-control" id="rpt_from">
                    </div>
                    <div class="col-md-3">
                        <label>To</label>
                        <input type="date" class="form-control" id="rpt_to">
                    </div>
                    <div class="col-md-3" style="margin-top:24px;">
                        <button class="btn btn-primary" id="btn_filter"><i class="fa fa-search"></i> Filter</button>
                        <button class="btn btn-secondary" id="btn_clear"><i class="fa fa-refresh"></i> All</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-striped" id="salesReportTable">
                        <thead class="bg-light">
                            <tr>
                                <th>Card Number</th>
                                <th>Category</th>
                                <th>Original Value</th>
                                <th>Redeemed</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Sold Date</th>
                            </tr>
                        </thead>
                        <tbody id="salesReportBody"></tbody>
                    </table>
                </div>
            </div>

            <!-- Outstanding Vouchers -->
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-exclamation-circle"></i> Outstanding Vouchers (Sold but not fully redeemed)</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="outstandingTable">
                        <thead class="bg-light">
                            <tr>
                                <th>Card Number</th>
                                <th>Category</th>
                                <th>Original Value</th>
                                <th>Remaining</th>
                                <th>Sold Date</th>
                                <th>Expiry</th>
                            </tr>
                        </thead>
                        <tbody id="outstandingBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';

$(document).ready(function() {
    loadSummary();
    loadSalesReport();
    loadOutstanding();

    $('#btn_filter').click(function() {
        loadSalesReport($('#rpt_from').val(), $('#rpt_to').val());
    });
    $('#btn_clear').click(function() {
        $('#rpt_from, #rpt_to').val('');
        loadSalesReport();
    });
});

function loadSummary() {
    $.post(BASE_URL + 'GiftVoucher/getCategorySummary', function(res) {
        var data = JSON.parse(res);
        var html = '';
        var totalSold = 0, totalRedeemed = 0, totalOutstanding = 0;
        for (var i = 0; i < data.length; i++) {
            var c = data[i];
            totalSold += parseInt(c.sold_cards || 0) + parseInt(c.redeemed_cards || 0);
            totalRedeemed += parseInt(c.redeemed_cards || 0);
            totalOutstanding += parseInt(c.sold_cards || 0);
        }
        html += '<div class="col-md-3"><div class="card-box" style="background:#e3f2fd;"><h5>Total Sold</h5><h3>' + totalSold + '</h3></div></div>';
        html += '<div class="col-md-3"><div class="card-box" style="background:#e8f5e9;"><h5>Redeemed</h5><h3>' + totalRedeemed + '</h3></div></div>';
        html += '<div class="col-md-3"><div class="card-box" style="background:#fff3e0;"><h5>Outstanding</h5><h3>' + totalOutstanding + '</h3></div></div>';
        html += '<div class="col-md-3"><div class="card-box" style="background:#fce4ec;"><h5>Categories</h5><h3>' + data.length + '</h3></div></div>';
        $('#summaryCards').html(html);
    });
}

function loadSalesReport(from, to) {
    var postData = {};
    if (from) postData.from = from;
    if (to) postData.to = to;
    $.post(BASE_URL + 'GiftVoucher/getSalesReport', postData, function(res) {
        var data = JSON.parse(res);
        var html = '';
        for (var i = 0; i < data.length; i++) {
            var d = data[i];
            var statusClass = 'secondary';
            if (d.gc_status == 'Sold') statusClass = 'primary';
            else if (d.gc_status == 'Redeemed') statusClass = 'success';
            else if (d.gc_status == 'Expired') statusClass = 'danger';

            html += '<tr>';
            html += '<td><strong>' + d.gc_card_number + '</strong></td>';
            html += '<td>' + d.vcat_name + '</td>';
            html += '<td>LKR ' + parseFloat(d.gc_original_value).toFixed(2) + '</td>';
            html += '<td>LKR ' + parseFloat(d.total_redeemed).toFixed(2) + '</td>';
            html += '<td>LKR ' + parseFloat(d.gc_remaining_value).toFixed(2) + '</td>';
            html += '<td><span class="badge badge-' + statusClass + '">' + d.gc_status + '</span></td>';
            html += '<td>' + (d.gc_sold_date ? d.gc_sold_date.substring(0, 10) : '-') + '</td>';
            html += '</tr>';
        }
        $('#salesReportBody').html(html);
        if ($.fn.DataTable.isDataTable('#salesReportTable')) {
            $('#salesReportTable').DataTable().destroy();
        }
        $('#salesReportTable').DataTable({ order: [[6, 'desc']], pageLength: 25 });
    });
}

function loadOutstanding() {
    $.post(BASE_URL + 'GiftVoucher/getOutstandingReport', function(res) {
        var data = JSON.parse(res);
        var html = '';
        for (var i = 0; i < data.length; i++) {
            var d = data[i];
            html += '<tr>';
            html += '<td><strong>' + d.gc_card_number + '</strong></td>';
            html += '<td>' + d.vcat_name + '</td>';
            html += '<td>LKR ' + parseFloat(d.gc_original_value).toFixed(2) + '</td>';
            html += '<td><strong style="color:#e65100;">LKR ' + parseFloat(d.gc_remaining_value).toFixed(2) + '</strong></td>';
            html += '<td>' + (d.gc_sold_date ? d.gc_sold_date.substring(0, 10) : '-') + '</td>';
            html += '<td>' + (d.gc_expiry_date || 'Never') + '</td>';
            html += '</tr>';
        }
        $('#outstandingBody').html(html);
    });
}
</script>
