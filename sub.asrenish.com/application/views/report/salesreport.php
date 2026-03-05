<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="wrapper">
    <div class="container">
        <!-- Filters Row -->
        <div class="row">                    
            <div class="button-list col-6 col-xl-5 col-lg-5 col-md-12 col-sm-12 col-xs-12">
                <select name="customer_select" id="customer_select" class="form-control">
                    <option value="all">--Select Customers--</option>
                    <?php foreach ($all_customers as $customer_row) { ?>
                        <option value="<?php echo $customer_row['cus_id']; ?>">
                            <?php echo $customer_row['cus_name']; ?>
                        </option>
                    <?php } ?>
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
                let tableHTML = `<thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Customer Name</th>
                                        <th>Date</th>
                                        <th>Sub Total</th>
                                        <th>Discount</th>
                                        <th>Grand Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                data.forEach((row, index) => {
                    tableHTML += `<tr>
                                    <td>${index + 1}</td>
                                    <td>${row.sale_id}</td>
                                    <td>${row.cus_name}</td>
                                    <td>${row.sale_createdat}</td>
                                    <td style="text-align: right;">${row.sale_subtotal}</td>
                                    <td style="text-align: right;">${row.sale_discount}</td>
                                    <td style="text-align: right;">${row.sale_grandtotal}</td>
                                    <td style="text-align: right;">
                                        <button class="btn btn-sm btn-info" onclick="load_bill_again(${row.sale_id})">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>`;
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

    // Datepicker logic (if still required)
    $(".datepic").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function () {
            const dateFrom = $('#datepicFrom').val();
            const dateTo = $('#datepicTo').val();
            const customerId = $('#customer_select').val();
            const loadUrl = '<?php echo base_url()?>Reports/sales_log_by_dates';

            $.ajax({
                type: 'POST',
                url: loadUrl,
                data: { from: dateFrom, to: dateTo, cus_id: customerId },
                dataType: 'json',
                success: function (data) {
                    if (data.length === 0) {
                        $('#datatable-buttons').DataTable().destroy();
                        $('#datatable-buttons').html('<thead><tr><th>No Data Available</th></tr></thead>');
                        $('#totalGrandTotal').text("0.00");
                        return;
                    }

                    let tableHTML = `<thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Customer Name</th>
                                            <th>Date</th>
                                            <th>Sub Total</th>
                                            <th>Discount</th>
                                            <th>Grand Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                    data.forEach((row, index) => {
                        tableHTML += `<tr>
                                        <td>${index + 1}</td>
                                        <td>${row.sale_id}</td>
                                        <td>${row.cus_name}</td>
                                        <td>${row.sale_createdat}</td>
                                        <td style="text-align: right;">${row.sale_subtotal}</td>
                                        <td style="text-align: right;">${row.sale_discount}</td>
                                        <td style="text-align: right;">${row.sale_grandtotal}</td>
                                        <td style="text-align: right;">
                                            <button class="btn btn-sm btn-info" onclick="load_bill_again(${row.sale_id})">
                                                <i class="fa fa-print" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>`;
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
    });

    // Reset button logic
    $('#reset').click(function () {
        $('#datatable-buttons').DataTable().destroy();
        $('#datatable-buttons').html('<thead><tr><th>No Data Available</th></tr></thead>');
        $('#customer_select').val('all');
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





  