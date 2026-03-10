        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive">

                            <!-- Store Filter Dropdown -->
                            <select class="form-control" id="storeFilter" style="width: 200px; min-width: 200px; margin-bottom:15px;">
                                <option value="0">-- All Stores --</option>
                                <?php if(isset($stores)){ foreach ($stores as $st) { ?>
                                    <option value="<?php echo $st->store_id; ?>"><?php echo $st->store_name; ?></option>
                                <?php }} ?>
                            </select>

                            <!-- Totals Row -->
                            <div class="row" style="text-align: left; font-size: large; font-weight: bold; background: gray;">
                                <div class="col-md-2">TOTAL SELLING VALUE :</div>
                                <div class="col-md-4" id="id_total_selling"></div>
                                <div class="col-md-2">TOTAL GRN VALUE :</div>
                                <div class="col-md-4" id="id_total_grn"></div>
                            </div>
                            <br>
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Store(s)</th>
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
        var dtTable = null;

        function showAllStock(store_id) {
            if(!store_id){ store_id = 0; }
            $.ajax({
                type: 'post',
                url: '<?php echo base_url() ?>stocks/showAllStock',
                data: { storeid: store_id },
                async: false,
                dataType: 'json',
                success: function (data) {
                    var rows = '';
                    var total_grn = 0;
                    var total_selling = 0;

                    if(data && data.length > 0){
                        for (var i = 0; i < data.length; i++) {
                            var reorderQty = parseFloat(data[i].itm_reorderlevel);
                            var stckQty = parseFloat(data[i].stock_qty);
                            var grnValue = parseFloat(data[i].grnValue);
                            var sellingValue = parseFloat(data[i].sellingValue);
                            var storeName = data[i].store_name ? data[i].store_name : 'N/A';

                            var grnDisplay = isNaN(grnValue) ? "No GRN" : grnValue.toFixed(2);
                            var sellDisplay = isNaN(sellingValue) ? "No GRN" : sellingValue.toFixed(2);

                            if (!isNaN(grnValue)) { total_grn += grnValue; }
                            if (!isNaN(sellingValue)) { total_selling += sellingValue; }

                            var alertColor = '';
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
                                '<td>' + storeName + '</td>' +
                                '<td style="text-align: right;">' + stckQty.toFixed(2) + " " + data[i].itm_uom + '</td>' +
                                '<td style="text-align: right;">' + sellDisplay + '</td>' +
                                '<td style="text-align: right;">' + grnDisplay + '</td>' +
                                '</tr>';
                        }
                    }

                    $('#id_total_grn').html(total_grn.toFixed(2));
                    $('#id_total_selling').html(total_selling.toFixed(2));

                    if(dtTable){ dtTable.destroy(); }
                    $('#tbodyID').html(rows);
                    dtTable = $('#datatable-buttons').DataTable({
                        buttons: ['copy', 'excel', 'pdf']
                    });
                    dtTable.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                error: function () {
                    alert('Error loading stock data');
                }
            });
        }

        showAllStock(0);

        // On store change - reload with filter
        $('#storeFilter').on('change', function () {
            var storeId = $(this).val();
            showAllStock(storeId);
        });
    });
</script>
