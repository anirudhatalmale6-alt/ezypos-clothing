<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div id="index-page">
    <div class="wrapper">
        
        <div class="container-fluid">
            <div class="row">
                
                 <?php if ($this->session->userdata('userrole') == 1): ?>
                <!-- Sidebar Section -->
                <div class="col-md-2" id="sidebar">
                    
                    <!-- New Buttons -->
                    <div class="form-group">
                        <a href="<?php echo base_url('add-sale')?>" class="btn btn-primary waves-effect">
                            <i class="fa fa-plus"></i> Add Sales
                        </a>
                    </div>
                    <div class="form-group">
                        <a href="<?php echo base_url('add-expense')?>" class="btn btn-primary waves-effect">
                            <i class="fa fa-plus"></i> Add Expenses
                        </a>
                    </div>
                    <!-- <div class="form-group">
                        <a href="<?php echo base_url('retail-pos'); ?>" class="btn btn-primary waves-effect">
                            <i class="fa fa-shopping-cart"></i> Retail POS
                        </a>
                    </div> -->
                    
                    <!-- Admin Action Buttons -->
                    <div class="form-group">
                        <button id="btnPendingGrn" class="btn btn-primary waves-effect">
                            <i class="fa fa-plus"></i> Pending GRN
                        </button>
                    </div>
                    
                    <div class="form-group">
                        <button id="btnPendingSale" class="btn btn-primary waves-effect">
                            <i class="fa fa-plus"></i> Sale To Adjust
                        </button>
                    </div>
                    <div class="form-group">
                        <a href="<?php echo base_url('store_items')?>" class="btn btn-primary waves-effect">
                            <i class="fa fa-plus"></i> Warehouse Items
                        </a>
                    </div>

                </div>
                <?php endif; ?>
                <!-- Main Content Section -->
                <div class="col-md-10" id="main-content">
                    <!-- Welcome Message -->
                    <div id="welcome-section">
                        <h1>
                            <?php 
                            $username = $this->session->userdata('useruser');
                            echo $username ? "Welcome, $username" : "No username in session.";
                            ?>
                        </h1>
                        <?php if ($this->session->userdata('userrole') == 1): ?>
                            <h2>We are glad to have you here. Check out today's stats below!</h2>
                        <?php endif; ?>
                    </div>

                    <div>
                        <?php if ($this->session->userdata('userrole') == 1): ?>
                        <!-- Squares Section -->
                        <div id="stats-section">
                            <div class="row justify-content-center">
                                <div class="col-lg-4">
                                    <div class="card text-white bg-primary">
                                        <div class="card-body">
                                            <div class="icon-container">
                                                <img src="assets/images/SalesIcon.png" alt="Sales Icon">
                                            </div>
                                            <h3 id="sales_title">Today's Total Sales</h3>
                                            <h4 id="total_sales">Rs. 0.00</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card text-white bg-success">
                                        <div class="card-body">
                                            <div class="icon-container">
                                                <img src="assets/images/in-stock.png" alt="Stock Icon">
                                            </div>
                                            <h3>Total Stocks In Hand</h3>
                                            <select id="itemSelect">
                                                <option value="">--Select Item--</option>
                                            </select>
                                            <h4>Quantity</h4>
                                            <h4 id="itemQuantity">--PCS--</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card text-white bg-danger">
                                        <div class="card-body">
                                            <div class="icon-container">
                                                <img src="assets/images/expences.png" alt="Expenses Icon">
                                            </div>
                                            <h3>Today's Total Expenses</h3>
                                            <h4 id="total_expenses">Rs. <?= number_format($today_expenses, 2); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Table Section -->
                    <div id="table-section">
                        <div id="tableID"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <script>
    $( function(){ 
        //show all pending grn/
        $("#btnPendingGrn").click(function(e){
            e.preventDefault();
            var buttonName = "Pending GRN";  // Set button name dynamically
            $.ajax({
                    type: "Post",
                    url:"<?php echo base_url('InsufficentStockSale/showPendingGrn'); ?>",
                    async: false,
                    dataType: "json",
                    success: function (data) {
                        var rows = '';
						var i;
                        var theader= '<div class="row">'+
                                        '<div class="col-12">'+                                      
                                            '<div class="card-box table-responsive">'+
                                            '<h2 style = "margin-bottom: 50px; text-align: center;">' + buttonName + '</h2>'+  // Display the topic name here
                                                '<table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
                                                '<thead>'+
                                                    '<tr>'+
                                                        '<th style="font-size: 12px;">Sale ID</th>'+
                                                        '<th style="font-size: 12px;">Item Name</th>'+
                                                        '<th style="font-size: 12px;">Qty</th>'+  
                                                        '<th style="font-size: 12px;">GRN</th>'+                                         
                                                    '</tr>'+
                                                '</thead>';
                        var tfooter =           '</table>'+
                                            '</div>'+
                                        '</div>'+              
                                    '</div>';      

						for(i=0; i<data.length; i++){
                        rows+= '<tr>'+
                                    '<td>'+data[i].insuffi_saleid+'</td>'+
                                    '<td>'+data[i].itm_name+'</td>'+
                                    '<td>'+data[i].insuffi_newqty+'</td>'+
                                    '<td>'+
                                        '<a href="<?php echo base_url('add-grn')?>"  target="_blank" class="btn btn-sm btn-danger"><i class="fa fa-plus"></i></a>'+
                                    '</td>'+
                                '</tr>';
                        }
                        var table= theader+rows+tfooter;
							$('#tableID').html(table);

                            // Scroll to the table section after loading the data
                            <?php if ($this->session->userdata('userrole') == 1): ?> 
                                $('html, body').animate({
                                    scrollTop: $("#tableID").offset().top
                                }, 1000);// 1000ms for smooth scrolling
                            <?php endif; ?>    

                    },
                    error: function (err) {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Show pending GRN error!'
                        });
                    }
                });
        });

        // show Pending sales to adjust stock
        $("#btnPendingSale").click(function(e){ //Not in use
            e.preventDefault();
            var buttonName = "Sales to Adjust";  // Set button name dynamically
            $.ajax({
                    type: "Post",
                    url:"<?php echo base_url('InsufficentStockSale/showPendingStockAdjust'); ?>",
                    async: false,
                    dataType: "json",
                    success: function (data) {
                        var rows = '';
						var i;
                        var theader= '<div class="row">'+
                                        '<div class="col-12">'+
                                            '<div class="card-box table-responsive">'+
                                                '<h2 style = "margin-bottom: 50px; text-align: center;">' + buttonName + '</h2>'+  // Display the topic name here                                
                                                '<table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">'+
                                                '<thead>'+
                                                    '<tr>'+
                                                        '<th style="font-size: 12px;">Sale ID</th>'+ 
                                                        '<th style="font-size: 12px;">Adjust</th>'+                                        
                                                    '</tr>'+
                                                '</thead>';
                        var tfooter =           '</table>'+
                                            '</div>'+
                                        '</div>'+              
                                    '</div>';      

						for(i=0; i<data.length; i++){
                        rows+= '<tr>'+
                                    '<td>'+data[i].insuffi_saleid+'</td>'+ 
                                    '<td>'+
                                        '<a saleid="'+data[i].insuffi_saleid+'"'+
                                        'itmid="'+data[i].insuffi_itmid+'"'+
                                        'qty="'+data[i].insuffi_qty+'"'+
                                        'class="btnAdjstSale btn btn-sm btn-danger" href="javascript:;"><i class="fa fa-plus"></i></a>'+
                                    '</td>'+                               
                                '</tr>';
                        }
                        var table= theader+rows+tfooter;
	                $('#tableID').html(table);

                    // Scroll to the table section after loading the data
                    <?php if ($this->session->userdata('userrole') == 1): ?> 

                        $('html, body').animate({
                        scrollTop: $("#tableID").offset().top
                        }, 1000);  // 1000ms for smooth scrolling

                    <?php endif; ?>  

                    },
                    error: function (err) {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Error in show pending sales!'
                        });
                    }
                });
        });

        //
        $('#tableID').on('click', '.btnAdjstSale', function(){
            var saleid = $(this).attr('saleid');
            var itmid = $(this).attr('itmid');
            var qty = $(this).attr('qty');
                   //change sale vs grn for pending insufficient stock solds 
                   $.ajax({
                        type: "Post",
                        url:"<?php echo base_url('InsufficentStockSale/adjustGrnVsSale'); ?>",
                        data: {itmid:itmid,qty:qty,saleid:saleid},
                        async: false,
                        dataType: "json",
                        success: function (res) {
                           // alert(res);
                        },
                        error: function (err) {
                            swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Error in managing insufficient sold stock !'
                            });
                            console.log(err);
                        }
                    });
        });
    });

   
// JavaScript to fetch today's sales, expenses, and stock data, similar to your existing setup
$(document).ready(function () {
 // Fetch today's sales based on user role (Admin or User)
 $.ajax({
        url: '<?= base_url('sales/getTodaysSales'); ?>',  // Call the controller method to fetch sales
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data && data.total_sales !== undefined) {
                // Update the heading text dynamically based on the user role
                $('#sales_title').text(data.title);

                // Display the total sales in the card dynamically
                $('#total_sales').text("Rs. " + parseFloat(data.total_sales).toFixed(2));
            } else {
                $('#total_sales').text("Rs. 0.00");
                $('#sales_title').text("No sales data available.");
            }
        },
        error: function () {
            alert('Error fetching sales data.');
        }
    });

    // Fetch Today's Expenses
    function fetchExpenses() {
        $.ajax({
            url: "<?php echo base_url('Expenses/get_today_expenses'); ?>",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#total_expenses").text("Rs. " + parseFloat(response.total_expenses).toFixed(2));
            },
            error: function () {
                $("#total_expenses").text("Error fetching expenses.");
            }
        });
    }

    fetchExpenses(); // Call the function on page load
});

$(document).ready(function () {
    // Fetch item names for the dropdown
    $.ajax({
        type: "GET",
        url: "<?php echo base_url('stocks/fetchItemNames'); ?>", 
        dataType: "json",
        success: function (data) {
            if (data) {
                var options = '<option value="">Select Item</option>';
                $.each(data, function (index, item) {
                    options += '<option value="' + item.itm_id + '">' + item.itm_name + '</option>';
                });
                $("#itemSelect").html(options);  // Populate dropdown with items
            } else {
                $("#itemSelect").html('<option value="">No Items Available</option>');
            }
        },
        error: function () {
            alert('Error fetching item names.');
        }
    });



    // Handle item selection from dropdown
    $('#itemSelect').on('change', function () {
        var itemId = $(this).val();  // Get the selected item ID

        // Fetch the stock quantity for the selected item
        $.ajax({
            url: '<?= base_url('stocks/getItemStockQuantity'); ?>',
            type: 'POST',
            data: { item_id: itemId },
            dataType: 'json',
            success: function (response) {
                if (response && response.quantity !== undefined) {
                    $('#itemQuantity').text(response.quantity);  // Update the quantity on the page
                } else {
                    $('#itemQuantity').text('No stock data available.');
                }
            },
            error: function () {
                $('#itemQuantity').text('Error fetching stock quantity.');
            }
        });
    });
});


</script>



