<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="wrapper">
    <div class="container">
        <!-- Filters Row -->
        <div class="row">                    
            <div class="button-list col-3 col-xl-3 col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <select name="customer_select" id="customer_select" class="form-control">
                    <option value="all">--Select Customers--</option>
                    <?php foreach ($all_customers as $customer_row) { ?>
                        <option value="<?php echo $customer_row['cus_id']; ?>">
                            <?php echo $customer_row['cus_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="button-list col-3 col-xl-2 col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <select name="store_select" id="store_select" class="form-control">
                    <option value="all">-- All Branches --</option>
                    <?php if(isset($storesForFilter) && $storesForFilter){ foreach ($storesForFilter as $st) {
                        $sid   = is_array($st) ? $st['store_id']   : $st->store_id;
                        $sname = is_array($st) ? $st['store_name'] : $st->store_name; ?>
                        <option value="<?php echo $sid; ?>"><?php echo htmlspecialchars($sname); ?></option>
                    <?php }} ?>
                </select>
            </div>
            <div class="col-lg-5 col-lg-6 col-md-10 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group row">
                            <label for="datepicFrom" class="col-3 col-form-label">From<span class="text-danger">*</span></label>
                            <div class="">
                                <input class="col-8 form-control datepic" placeholder="From.." value="" id="datepicFrom">
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group row">
                            <label for="datepicTo" class="col-3 col-form-label">To<span class="text-danger">*</span></label>
                            <div class="">
                                <input class="col-8 form-control datepic" placeholder="To.." value="" id="datepicTo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1 col-xl-1 col-lg-1">
                <button type="reset" id="reset" class="btn btn-outline-danger waves-effect waves-light">
                    <i class="fa fa-refresh"></i>
                </button>
            </div>                   
        </div>

        <!-- Total Sales Row -->
        <!-- <div class="row mb-3">
            <div class="col-12 text-right">
                <h5>Total Grand Sales: <span id="totalGrandTotal">0.00</span></h5>
            </div>
        </div> -->

<!-- Table Row -->
<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive" id="table_div">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Export to PDF Button -->
                <div class="d-flex">
                    <!-- Copy Button -->
                    <button id="copyToClipboard" 
                            class="btn text-white btn-hover-custom"
                            style="background-color: #868E96; border-color: #868E96; border-top-left-radius: 5px; border-bottom-left-radius: 5px; border-top-right-radius: 0; border-bottom-right-radius: 0; outline: none;margin-left: 15px;"
                            onclick="this.blur();">
                        Copy
                    </button>
                    <!-- PDF Button -->
                    <button id="exportPDF" 
                            class="btn text-white btn-hover-custom"
                            style="background-color: #868E96; border-color: #868E96; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-top-left-radius: 0; border-bottom-left-radius: 0; outline: none;"
                            onclick="this.blur();">
                        PDF
                    </button>
                </div>
                <!-- Total Sales -->
                <div class="text-right">
                    <h5>Total Sales: <span id="totalGrandTotal">0.00</span></h5>
                </div>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <!-- Table content dynamically generated via JS -->
            </table>
        </div>
    </div>
</div>


    </div> <!-- container -->
</div> <!-- wrapper -->



<script>
$(document).ready(function () {
    // Initially clear the table
    $('#datatable-buttons').html('<thead><tr><th>No Data Available</th></tr></thead>');

    function esc(v){
        if(v === null || typeof v === 'undefined') return '';
        return String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // One header and one row builder for both views below, so the two tables
    // can never drift apart again.
    function reportHead(){
        return `<thead>
                    <tr>
                        <th>#</th>
                        <th>Receipt No</th>
                        <th>Type</th>
                        <th>Customer Name</th>
                        <th>Date</th>
                        <th>Sub Total</th>
                        <th>Discount</th>
                        <th>Grand Total</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>`;
    }

    function reportRow(row, index){
        // The receipt number the customer was handed. Falls back to the
        // internal id only on a bill saved before per-branch numbering.
        var receipt = row.bill_no ? row.bill_no : row.sale_id;
        var kind = row.sale_kind ? row.sale_kind : 'Sale';
        var kindHtml = esc(kind);
        if(kind.indexOf('Voucher') !== -1){
            var cards = row.voucher_cards ? ' (' + esc(row.voucher_cards) + ')' : '';
            kindHtml = '<span class="badge" style="background:#e65100;color:#fff;">' + esc(kind) + '</span>'
                     + '<br><small>' + esc(parseFloat(row.voucher_total || 0).toFixed(2)) + cards + '</small>';
        }
        return `<tr>
                    <td>${index + 1}</td>
                    <td>${esc(receipt)}</td>
                    <td>${kindHtml}</td>
                    <td>${esc(row.cus_name || '-')}</td>
                    <td>${esc(row.sale_createdat)}</td>
                    <td style="text-align: right;">${row.sale_subtotal}</td>
                    <td style="text-align: right;">${row.sale_discount}</td>
                    <td style="text-align: right;">${row.sale_grandtotal}</td>
                    <td><small>${esc(row.payment_info || '-')}</small></td>
                    <td style="text-align: right;">
                        <button class="btn btn-sm btn-info" onclick="load_bill_again(${row.sale_id})">
                            <i class="fa fa-print" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>`;
    }

    // Update Total Grand Total
    function updateTotalGrandTotal(data) {
        let total = 0;
        data.forEach(row => {
            total += parseFloat(row.sale_grandtotal || 0);
        });
        $('#totalGrandTotal').text(total.toFixed(2)); // Update the total in the UI
    }

    // Fetch and display data for a customer
    $("#customer_select").change(function () {
        const customerId = $(this).val();

        // If "All Customers" or no customer is selected, clear the table and total
        if (customerId === "all" || !customerId) {
            $('#datatable-buttons').DataTable().destroy();
            $('#datatable-buttons').html('<thead><tr><th>No Data Available</th></tr></thead>');
            $('#totalGrandTotal').text("0.00");
            return;
        }

        const loadUrl = `<?php echo base_url()?>Reports/getSaleReport_user/${customerId}`;
        $.ajax({
            type: 'POST',
            url: loadUrl,
            data: { store_id: $('#store_select').val() },
            dataType: 'json',
            success: function (data) {
                if (data.length === 0) {
                    // No sales data for the selected customer
                    $('#datatable-buttons').DataTable().destroy();
                    $('#datatable-buttons').html('<thead><tr><th>No Sales Data Found</th></tr></thead>');
                    $('#totalGrandTotal').text("0.00");
                    return;
                }

                // Build table rows
                let tableHTML = reportHead() + `<tbody>`;
                data.forEach((row, index) => {
                    tableHTML += reportRow(row, index);
                });
                tableHTML += `</tbody>`;

                // Update the table
                $('#datatable-buttons').DataTable().destroy();
                $('#datatable-buttons').html(tableHTML);

                // Reinitialize DataTables
                $('#datatable-buttons').DataTable({
                    buttons: ['copy', 'excel', 'pdf']
                });

                // Update the total grand total
                updateTotalGrandTotal(data);
            },
            error: function () {
                alert('Failed to fetch sales data.');
            }
        });
    });

    // Load the date-range report. Shared by the datepicker and the branch filter.
    function loadSalesByDates() {
            const dateFrom = $('#datepicFrom').val();
            const dateTo = $('#datepicTo').val();
            if(!dateFrom || !dateTo){ return; }
            const customerId = $('#customer_select').val();
            const storeId = $('#store_select').val();
            const loadUrl = '<?php echo base_url()?>Reports/sales_log_by_dates';

            $.ajax({
                type: 'POST',
                url: loadUrl,
                data: { from: dateFrom, to: dateTo, cus_id: customerId, store_id: storeId },
                dataType: 'json',
                success: function (data) {
                    if (data.length === 0) {
                        $('#datatable-buttons').DataTable().destroy();
                        $('#datatable-buttons').html('<thead><tr><th>No Data Available</th></tr></thead>');
                        $('#totalGrandTotal').text("0.00");
                        return;
                    }

                    let tableHTML = reportHead() + `<tbody>`;
                    data.forEach((row, index) => {
                        tableHTML += reportRow(row, index);
                    });
                    tableHTML += `</tbody>`;

                    $('#datatable-buttons').DataTable().destroy();
                    $('#datatable-buttons').html(tableHTML);
                    $('#datatable-buttons').DataTable({
                        buttons: ['copy', 'excel', 'pdf']
                    });

                    // Update the total grand total
                    updateTotalGrandTotal(data);
                },
                error: function () {
                    alert('Error fetching data.');
                }
            });
    }

    $(".datepic").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function () { loadSalesByDates(); }
    });

    // Branch filter: re-run whichever view is active.
    $('#store_select').change(function () {
        const dateFrom = $('#datepicFrom').val();
        const dateTo = $('#datepicTo').val();
        if (dateFrom && dateTo) {
            loadSalesByDates();
        } else {
            $('#customer_select').trigger('change');
        }
    });

    // Reset button logic
    $('#reset').click(function () {
        $('#datatable-buttons').DataTable().destroy();
        $('#datatable-buttons').html('<thead><tr><th>No Data Available</th></tr></thead>');
        $('#customer_select').val('all');
        $('#store_select').val('all');
        $('#datepicFrom').val('');
        $('#datepicTo').val('');
        $('#totalGrandTotal').text("0.00");
    });
});

// Function to handle reloading the invoice
function load_bill_again(sale_ID) {
    const horizontal = Math.floor(window.innerWidth / 2);
    const left = horizontal - 200;
    const rurl = "<?= base_url('Sales/print_inv')?>/" + sale_ID;
    window.open(rurl, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=40,left=" + left + ",width=400,height=600");
}
</script>

<!-- Include jsPDF and jsPDF-Autotable Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.27/jspdf.plugin.autotable.min.js"></script>

<script>
document.getElementById('exportPDF').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;

    // Create a new PDF document
    const doc = new jsPDF();

    const headers = [];
    const rows = [];

    // Extract headers, excluding the "Action" column
    $('#datatable-buttons thead tr th').each(function (index) {
        // Skip the "Action" column (last column in your case)
        if (index !== $('#datatable-buttons thead tr th').length - 1) {
            headers.push($(this).text().trim());
        }
    });

    // Extract rows, excluding the "Action" column
    $('#datatable-buttons tbody tr').each(function () {
        const row = [];
        $(this).find('td').each(function (index) {
            // Skip the "Action" column (last column in your case)
            if (index !== $(this).parent().find('td').length - 1) {
                row.push($(this).text().trim());
            }
        });
        rows.push(row);
    });

    // Check if there is any data to export
    if (rows.length === 0) {
        alert('No data available to export.');
        return;
    }

    // Add title to PDF and center it
    const pageWidth = doc.internal.pageSize.getWidth(); 
    const title = 'Sales Report';
    const titleWidth = doc.getTextWidth(title); 
    const titleX = (pageWidth - titleWidth) / 2; 
    doc.setFontSize(14);
    doc.text(title, titleX, 15); 

    
    doc.autoTable({
        head: [headers], 
        body: rows,      
        startY: 25,      
        styles: { fontSize: 10 },
        headStyles: { fillColor: [45, 65, 84] }, 
    });

    // Save the PDF
    doc.save('Sales_Report.pdf');
});

</script>



<script>
document.getElementById('copyToClipboard').addEventListener('click', function () {
    const headers = [];
    const rows = [];

    // Extract headers, excluding the "Action" column
    $('#datatable-buttons thead tr th').each(function (index) {
        // Skip the "Action" column (last column in your case)
        if (index !== $('#datatable-buttons thead tr th').length - 1) {
            headers.push($(this).text().trim());
        }
    });

    // Extract rows, excluding the "Action" column
    $('#datatable-buttons tbody tr').each(function () {
        const row = [];
        $(this).find('td').each(function (index) {
            // Skip the "Action" column (last column in your case)
            if (index !== $(this).parent().find('td').length - 1) {
                row.push($(this).text().trim());
            }
        });
        rows.push(row);
    });

    // Check if there is any data to copy
    if (rows.length === 0) {
        alert('No data available to copy.');
        return;
    }

    // Format data as plain text (tab-separated)
    let clipboardData = headers.join('\t') + '\n'; // Tab-separated headers
    rows.forEach(row => {
        clipboardData += row.join('\t') + '\n'; // Tab-separated rows
    });

    // Copy data to clipboard
    navigator.clipboard.writeText(clipboardData).then(() => {
        alert('Table data copied to clipboard!');
    }).catch(err => {
        alert('Failed to copy data: ' + err);
    });
});

document.querySelectorAll('.btn-hover-custom').forEach(button => {
        button.addEventListener('mouseover', () => {
            button.style.backgroundColor = '#727b84'; 
            button.style.borderColor = '#727b84';     
        });
        button.addEventListener('mouseout', () => {
            button.style.backgroundColor = '#868E96'; 
            button.style.borderColor = '#868E96';     
        });
    });
</script>





  