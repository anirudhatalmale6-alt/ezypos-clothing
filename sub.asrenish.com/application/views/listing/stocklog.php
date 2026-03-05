        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">
                <div class="row">                    
                        <div class="button-list col-6 col-xl-5 col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <button id="grnBtn" type="button" class="btn btn-outline-primary waves-effect waves-light">GRN</button>
                            <button id="saleBtn"  type="button" class="btn btn-outline-primary waves-effect waves-light">Sales</button>
                            <button id="supRtrnBtn"  type="button" class="btn btn-outline-primary waves-effect waves-light">Supplier Return</button>
                            <button id="cusRtrnBtn"  type="button" class="btn btn-outline-primary waves-effect waves-light">Customer Return</button>
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
                            <button type="reset" id="reset" class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-refresh"></i></button>
                        </div>                   
                </div>
                 <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive" id="table_div" > 
                                <div class="d-flex">
                                    <!-- Copy Button -->
                                    <button id="copyToClipboard" 
                                            class="btn text-white btn-hover-custom"
                                            style="background-color: #868E96; border-color: #868E96; border-top-left-radius: 5px; border-bottom-left-radius: 5px; border-top-right-radius: 0; border-bottom-right-radius: 0; outline: none; margin-bottom: 10px;margin-left: 15px;"
                                            onclick="this.blur();">
                                        Copy
                                    </button>
                                    <!-- PDF Button -->
                                    <button id="exportPDF" 
                                            class="btn text-white btn-hover-custom"
                                            style="background-color: #868E96; border-color: #868E96; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-top-left-radius: 0; border-bottom-left-radius: 0; outline: none; margin-bottom: 10px;"
                                            onclick="this.blur();">
                                        PDF
                                    </button>

                                </div>
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                          
                                                                          
                             </tbody>
                            </table>
                        </div>
                    </div>                 
                </div>

            </div> <!-- container -->


            <script>
    var stock1, stock2, stock3, stock4; // Variables to store loaded data
    var currentData = []; // Store currently loaded data to filter

    $(function () {
        // Initialize Datepickers
        $(".datepic").datepicker({
            dateFormat: "yy-mm-dd",
        });

        // Initialize DataTable without any data
        var table = $('#datatable-buttons').DataTable({
            responsive: true,
            autoWidth: false,
            order: [],
            buttons: ['copy', 'excel', 'pdf'],
            data: [],
        });

        // Function to Render Table
        function renderTable(data, columns) {
            table.destroy();
            $('#datatable-buttons').empty(); // Clear the table DOM
            table = $('#datatable-buttons').DataTable({
                responsive: true,
                autoWidth: false,
                data: data,
                columns: columns,
                order: [],
                buttons: ['copy', 'excel', 'pdf']
            });
        }

       // Function to Filter Data by Date
function filterTableByDate(fromDate, toDate, dateColumnIndex) {
    var filteredData = currentData.filter(function (row) {
        var rowDate = new Date(row[dateColumnIndex]); // Use the provided column index
        return rowDate >= new Date(fromDate) && rowDate <= new Date(toDate);
    });
    renderTable(filteredData, table.columns().header().map((col) => ({ title: $(col).html() })));
}

// Date Picker Change Event
$(".datepic").on("change", function () {
    var fromDate = $('#datepicFrom').val() + " 00:00:00"; // Add start time
    var toDate = $('#datepicTo').val() + " 23:59:59"; // Add end time

    if (fromDate && toDate) {
        let dateColumnIndex;
        // Determine the index of the date column based on the current button
        if ($("#grnBtn").hasClass("active")) dateColumnIndex = 4;
        else if ($("#saleBtn").hasClass("active")) dateColumnIndex = 3;
        else if ($("#cusRtrnBtn").hasClass("active")) dateColumnIndex = 3;
        else if ($("#supRtrnBtn").hasClass("active")) dateColumnIndex = 3;

        if (dateColumnIndex !== undefined) {
            filterTableByDate(fromDate, toDate, dateColumnIndex);
        } else {
            alert("No data loaded to filter!");
        }
    }
});

// Add 'active' class to buttons when clicked
$(".button-list button").click(function () {
    $(".button-list button").removeClass("active");
    $(this).addClass("active");
});


        // GRN Report Button
        $("#grnBtn").click(function () {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url()?>Stocks/getGRNReport',
                dataType: 'json',
                success: function (data) {
                    currentData = data.map((item, index) => [
                        index + 1, item.grn_id, item.grn_code, item.sup_name,
                        item.grn_date, item.grn_subtotal, item.grn_discount, item.grn_grandtotal
                    ]);

                    var columns = [
                        { title: "#" },
                        { title: "GRN ID" },
                        { title: "GRN Code" },
                        { title: "Supplier Name" },
                        { title: "Date" },
                        { title: "Sub Total" },
                        { title: "Discount" },
                        { title: "Grand Total" }
                    ];
                    renderTable(currentData, columns);
                },
                error: function () {
                    alert('Error fetching GRN data');
                }
            });
        });

        // Sales Report Button
        $("#saleBtn").click(function () {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url()?>Stocks/getSaleReport',
                dataType: 'json',
                success: function (data) {
                    currentData = data.map((item, index) => [
                        index + 1, item.sale_id, item.cus_name, item.sale_createdat,
                        item.sale_subtotal, item.sale_discount, item.sale_grandtotal
                    ]);

                    var columns = [
                        { title: "#" },
                        { title: "Code" },
                        { title: "Customer Name" },
                        { title: "Date" },
                        { title: "Sub Total" },
                        { title: "Discount" },
                        { title: "Grand Total" }
                    ];
                    renderTable(currentData, columns);
                },
                error: function () {
                    alert('Error fetching Sales data');
                }
            });
        });

        // Customer Return Button
        $("#cusRtrnBtn").click(function () {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url()?>Stocks/getCustomerReturn',
                dataType: 'json',
                success: function (data) {
                    currentData = data.map((item, index) => [
                        index + 1, item.itm_code, item.itm_name, item.cusrtrn_createdat,
                        item.stocklog_qty, item.cus_name
                    ]);

                    var columns = [
                        { title: "#" },
                        { title: "Code" },
                        { title: "Name" },
                        { title: "Date" },
                        { title: "Qty" },
                        { title: "Customer" }
                    ];
                    renderTable(currentData, columns);
                },
                error: function () {
                    alert('Error fetching Customer Return data');
                }
            });
        });

        // Supplier Return Button
        $("#supRtrnBtn").click(function () {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url()?>Stocks/getSupplierReturn',
                dataType: 'json',
                success: function (data) {
                    currentData = data.map((item, index) => [
                        index + 1, item.itm_code, item.itm_name, item.suprtrn_createdat,
                        item.stocklog_qty, item.sup_name
                    ]);

                    var columns = [
                        { title: "#" },
                        { title: "Code" },
                        { title: "Name" },
                        { title: "Date" },
                        { title: "Qty" },
                        { title: "Supplier" }
                    ];
                    renderTable(currentData, columns);
                },
                error: function () {
                    alert('Error fetching Supplier Return data');
                }
            });
        });

        // Reset Button
        $("#reset").click(function () {
            $(".datepic").val("");
            if (currentData.length > 0) {
                table.clear().rows.add(currentData).draw();
            } else {
                table.clear().draw();
            }
        });
    });




    // Handle PDF Export
document.getElementById('exportPDF').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Fetch Table Headers
    const headers = [];
    $('#datatable-buttons thead tr th').each(function () {
        headers.push($(this).text().trim());
    });

    // Fetch Table Data
    const rows = [];
    $('#datatable-buttons tbody tr').each(function () {
        const row = [];
        $(this).find('td').each(function () {
            row.push($(this).text().trim());
        });
        rows.push(row);
    });

    // Generate PDF
    if (rows.length > 0) {
        doc.autoTable({
            head: [headers],
            body: rows,
            startY: 20,
            styles: { fontSize: 10 },
            headStyles: { fillColor: [45, 65, 84] } // Color for #2d4154
        });

        doc.text('Report', doc.internal.pageSize.getWidth() / 2, 10, { align: 'center' });
        doc.save('Report.pdf');
    } else {
        alert('No data to export!');
    }
});

// Handle Copy to Clipboard
document.getElementById('copyToClipboard').addEventListener('click', function () {
    const headers = [];
    const rows = [];

    // Fetch Table Headers
    $('#datatable-buttons thead tr th').each(function () {
        headers.push($(this).text().trim());
    });

    // Fetch Table Data
    $('#datatable-buttons tbody tr').each(function () {
        const row = [];
        $(this).find('td').each(function () {
            row.push($(this).text().trim());
        });
        rows.push(row);
    });

    // Combine headers and rows into a string
    let clipboardData = headers.join('\t') + '\n';
    rows.forEach(row => {
        clipboardData += row.join('\t') + '\n';
    });

    // Copy to Clipboard
    navigator.clipboard.writeText(clipboardData).then(() => {
        alert('Table data copied to clipboard!');
    }).catch(err => {
        alert('Failed to copy data: ' + err);
    });
});

// Add hover functionality for buttons
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.27/jspdf.plugin.autotable.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>



  