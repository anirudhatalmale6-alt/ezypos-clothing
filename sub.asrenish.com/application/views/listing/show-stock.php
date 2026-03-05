        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">
                 
                <div class="row">
                    
                    
                    <div class="col-12">
                        
                    
                    
                    
                        <div class="card-box table-responsive"> 
                        
                              <!-- Dropdown for Stores -->
                    <select class="form-select p-2" id="storeSelect" style="width: 200px; min-width: 200px; margin-bottom:15px;">
                        <option selected disabled>Loading warehouses...</option>
                    </select>
                    
                            <!-- Default Totals Row -->
                            <div id="defaultTotalsRow" class="row" style="text-align: left; font-size: large; font-weight: bold; background: gray;">
                                <div class="col-md-2">TOTAL SELLING VALUE :</div>
                                <div class="col-md-4" id="id_total_selling"></div>
                                <div class="col-md-2">TOTAL GRN VALUE :</div>
                                <div class="col-md-4" id="id_total_grn"></div>
                            </div>
                            
                            <!-- Warehouse-specific Totals Row (Initially hidden) -->
                            <div id="warehouseTotalsRow" class="row" style="text-align: left; font-size: large; font-weight: bold; background: gray; display: none;">
                                <div class="col-md-2">TOTAL SELLING VALUE :</div>
                                <div class="col-md-4" id="newid_total_selling"></div>
                                <div class="col-md-2">TOTAL GRN VALUE :</div>
                                <div class="col-md-4" id="newid_total_grn"></div>
                            </div>
                            <br>
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Warehouse(s)</th> <!-- NEW COLUMN -->
                                    <th>Stock Qty</th>
                                    <th>Selling Value</th>
                                    <th>GRN Value</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyID">                                          
                                </tbody>
                            </table>
                        </div>
                    </div>                 
                </div>
            </div> <!-- container -->


<script>
    $(function () {
        function showAllStock() {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url() ?>stocks/showAllStock',
                async: false,
                dataType: 'json',
                success: function (data) {
                    var rows = '';
                    var total_grn = 0;
                    var total_selling = 0;

                    for (let i = 0; i < data.length; i++) {
                        let reorderQty = parseFloat(data[i].itm_reorderlevel);
                        let stckQty = parseFloat(data[i].stock_qty);
                        let grnValue = parseFloat(data[i].grnValue).toFixed(2);
                        let sellingValue = parseFloat(data[i].sellingValue).toFixed(2);

                        if (isNaN(grnValue)) {
                            grnValue = "No GRN";
                        } else {
                            total_grn += parseFloat(data[i].grnValue);
                        }

                        if (isNaN(sellingValue)) {
                            sellingValue = "No GRN";
                        } else {
                            total_selling += parseFloat(data[i].sellingValue);
                        }

                        let alertColor = '';
                        if (stckQty >= reorderQty) {
                            alertColor = 'style="background-color:green"';
                        } else if (stckQty > 0 && stckQty < reorderQty) {
                            alertColor = 'style="background-color:orange"';
                        } else {
                            alertColor = 'style="background-color:red"';
                        }

                        rows += '<tr>' +
                            '<td ' + alertColor + '>' + data[i].itm_id + '</td>' +
                            '<td>' + data[i].itm_code + '</td>' +
                            '<td>' + data[i].itm_name + '</td>' +
                            '<td>' + (data[i].warehouse_names ?? 'Not Assigned') + '</td>' +
                            '<td style="text-align: right;">' + stckQty.toFixed(2) + " " + data[i].itm_uom + '</td>' +
                            '<td style="text-align: right;">' + sellingValue + '</td>' +
                            '<td style="text-align: right;">' + grnValue + '</td>' +
                            '</tr>';
                    }

                    $('#id_total_grn').html(total_grn.toFixed(2));
                    $('#id_total_selling').html(total_selling.toFixed(2));
                    $('#tbodyID').html(rows);
                },
                error: function () {
                    alert('error data collection');
                }
            });
        }

        showAllStock();

        // DataTable buttons
        var table = $('#datatable-buttons').DataTable({
            buttons: ['copy', 'excel', 'pdf']
        });
        table.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

        // Load store list into dropdown
        $.ajax({
            url: "<?= base_url('StoreItems/getStores') ?>",
            type: "GET",
            dataType: "json",
            success: function (data) {
                let dropdown = $("#storeSelect");
                dropdown.empty();
                dropdown.append('<option selected disabled>Select Warehouses</option>');
                $.each(data, function (key, entry) {
                    dropdown.append($('<option></option>').attr('value', entry.wh_id).text(entry.wh_name));
                });
            },
            error: function () {
                alert("Failed to load stores.");
            }
        });

        // On warehouse change - get GRN and Selling Values
        $('#storeSelect').on('change', function () {
            let selectedWarehouseId = $(this).val();
            
            $.ajax({
                url: "<?= base_url('StoreItems/getWarehouseData') ?>",
                type: "POST",
                data: { warehouse_id: selectedWarehouseId },
                dataType: "json",
                success: function (response) {
                    console.log(response);
        
                    if (response.total_selling_value !== undefined && response.total_grn_value !== undefined) {
                        let sellingValue = parseFloat(response.total_selling_value).toFixed(2);
                        let grnValue = parseFloat(response.total_grn_value).toFixed(2);
        
                        // Set new values
                        $('#newid_total_selling').html(sellingValue);
                        $('#newid_total_grn').html(grnValue);
        
                        // Hide default totals row
                        $('#defaultTotalsRow').hide();
        
                        // Show warehouse-specific totals row
                        $('#warehouseTotalsRow').show();
                    } else {
                        $('#newid_total_selling').html("N/A");
                        $('#newid_total_grn').html("N/A");
                    }
                },
                error: function () {
                    alert("Failed to fetch warehouse-specific data.");
                }
            });
        
            filterTable(); // Call your existing filter function
        });

        // Filter table rows by selected warehouse
        function filterTable() {
            let selectedStore = $('#storeSelect').val();
            $('#table2 tbody tr').each(function () {
                let warehouseName = $(this).find('td:nth-child(4)').text().trim();
                let storeMatch = selectedStore ? warehouseName === $('#storeSelect option:selected').text() : true;
                $(this).toggle(storeMatch);
            });
        }

        // Refresh button
        $(document).on('click', '#refreshButton', function () {
            location.reload();
        });
        
        
        function showWarehouseStock(warehouse_id) {
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url() ?>stocks/getStockByWarehouse',
            data: { warehouse_id: warehouse_id },
            dataType: 'json',
            success: function (data) {
                var rows = '';
    
                for (let i = 0; i < data.length; i++) {
                    let reorderQty = parseFloat(data[i].itm_reorderlevel);
                    let stckQty = parseFloat(data[i].stock_qty);
                    let grnValue = parseFloat(data[i].grnValue).toFixed(2);
                    let sellingValue = parseFloat(data[i].sellingValue).toFixed(2);
    
                    let alertColor = '';
                    if (stckQty >= reorderQty) {
                        alertColor = 'style="background-color:green"';
                    } else if (stckQty > 0 && stckQty < reorderQty) {
                        alertColor = 'style="background-color:orange"';
                    } else {
                        alertColor = 'style="background-color:red"';
                    }
    
                    rows += '<tr>' +
                        '<td ' + alertColor + '>' + data[i].itm_id + '</td>' +
                        '<td>' + data[i].itm_code + '</td>' +
                        '<td>' + data[i].itm_name + '</td>' +
                        '<td>' + (data[i].warehouse_names ?? 'Not Assigned') + '</td>' +
                        '<td style="text-align: right;">' + stckQty.toFixed(2) + " " + data[i].itm_uom + '</td>' +
                        '<td style="text-align: right;">' + sellingValue + '</td>' +
                        '<td style="text-align: right;">' + grnValue + '</td>' +
                        '</tr>';
                }
    
                $('#tbodyID').html(rows);
            },
            error: function () {
                alert('Failed to fetch warehouse-specific stock');
            }
        });
    }

        
    });
</script>

