<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->

<div class="container mt-7" style="margin-top: 150px;">
<div class="d-flex align-items-center justify-content-between w-100 flex-nowrap">
    <!-- Dropdown for Stores -->
    <select class="form-select p-2" id="storeSelect" style="width: 200px; min-width: 200px;">
        <option selected disabled>Loading warehouses...</option>
    </select>
    <button id="refreshButton" class="btn btn-outline-danger waves-effect waves-light mr-5">
                                        <i class="fa fa-refresh"></i>
                                    </button>

    <!-- Date Pickers -->
     <lable class ="ml-3">From*</lable>
    <input type="date" class="form-control" id="fromDate" style="width: 180px; min-width: 140px;">
    <lable>To*</lable>
    <input type="date" class="form-control" id="toDate" style="width: 180px; min-width: 140px;">

    
    <div class="ms-auto"></div>

    
    <div class="d-flex gap-3">
        <button class="btn btn-primary px-3 mr-5" id="assignButton">
            <i class="fa fa-plus"></i> Assign New Items
        </button>
        <button class="btn btn-primary px-3" id="addWarehouse">
            <i class="fa fa-plus"></i> Warehouse Details
        </button>
    </div>
</div>

 <!-- Warehouse Table (Initially Hidden) -->
<div id="warehouseTableContainer" class="mt-4 p-4 border rounded" style="display: none;">
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="m-0">Warehouse Details</h4>
        
        <button class="btn btn-primary" id="addWarehouseBtn">
            <i class="fa fa-plus"></i> Add Warehouse
        </button>
    </div>
    <table class="table table-bordered mt-3" id="warehouseTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address Line 1</th>
                <th>Address Line 2</th>
                <th>Telephone</th>
                <th>Mobile</th>
                <th>Mobile 2</th>
                <th>Fax</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Warehouse data will be loaded dynamically -->
        </tbody>
    </table>
</div>


 <!-- Warehouse Creation Popup -->
<div class="modal fade" id="warehouseModal" tabindex="-1" aria-labelledby="warehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">  
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="warehouseModalLabel">Create Warehouse</h4>
                <!-- Custom Close Button with "X" -->
                <button type="button" class="btn btn-close-custom" data-dismiss="modal" aria-label="Close">✖</button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-10 col-sm-12">  
                        <div class="card-box p-4">  
                            <h1 class="header-title m-t-0 m-b-30 text-center">Add Warehouse Details</h1>
                            <form id="warehouseForm">
                                <div class="form-group row">
                                    <label for="wh_name" class="col-2 col-form-label">Name <span class="text-danger">*</span></label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" placeholder="Enter Name" name="wh_name" id="wh_name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="wh_address" class="col-2 col-form-label">Address Line1 <span class="text-danger">*</span></label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" placeholder="Enter Address Line 1" name="wh_address" id="wh_address" required>
                                    </div>
                                </div>    
                                <div class="form-group row">
                                    <label for="wh_address2" class="col-2 col-form-label">Address Line2 <span class="text-danger">*</span></label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" placeholder="Enter Address Line 2" name="wh_address2" id="wh_address2" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="wh_tel" class="col-2 col-form-label">Telephone <span class="text-danger">*</span></label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" placeholder="Enter Telephone No" name="wh_tel" id="wh_tel" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="wh_mobile" class="col-2 col-form-label">Mobile 1 <span class="text-danger">*</span></label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" placeholder="Enter Mobile No" name="wh_mobile" id="wh_mobile" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="wh_mobile2" class="col-2 col-form-label">Mobile 2</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" placeholder="Enter Mobile No" name="wh_mobile2" id="wh_mobile2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="wh_fax" class="col-2 col-form-label">Fax</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" placeholder="Enter Fax" name="wh_fax" id="wh_fax">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="wh_email" class="col-2 col-form-label">Email</label>
                                    <div class="col-10">
                                        <input class="form-control" type="email" placeholder="Enter Email" name="wh_email" id="wh_email">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-3 gap-6">
                                    <button type="submit" class="btn btn-primary waves-effect px-4 mr-2" ><i class="fa fa-plus"></i> Add Warehouse</button>
                                    <button type="reset" class="btn btn-secondary waves-effect px-4"><i class="fa fa-refresh"></i> Reset</button>
                                </div>
                            </form>                     
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



        <div id="table1Container" class="mt-4 p-4 border rounded" style="display: none;">
        <h4>Search GRN ID</h4>
        <div class="row mb-3">
            <div class="col-md-10">
                <input type="text" class="form-control" id="searchGrnID" placeholder="Enter GRN ID">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" id="searchButton">Search</button>
            </div>
        </div>
        
        <table class="table table-bordered" id="table1">
            <thead>
                <tr>
                    <th>GRN ID</th>
                    <th>Item Name</th>
                    <th>GRN Quantity</th>
                    <th>Current Quantity</th>
                    <th>Warehouse</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="table1Body">
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <button class="btn btn-success" id="saveButton">Save</button>
        </div>
    </div>

        <!-- Table 2 (Data from Database) -->
    <div class="mt-4 p-4 border rounded">
        <table class="table table-bordered" id="table2">
            <thead>
                <tr>
                    <th>GRN ID</th>
                    <th>Item Name</th>
                    <th>GRN Quantity</th>
                    <th>Current Quantity</th>
                    <th>Warehouse</th>
                    <th>Date & Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table rows will be loaded dynamically using AJAX -->
            </tbody>
        </table>
    </div>
    </div>

    <!-- Edit Warehouse Modal -->
<div class="modal fade" id="editWarehouseModal" tabindex="-1" aria-labelledby="editWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editWarehouseModalLabel">Update Warehouse</h4>
                <button type="button" class="btn btn-close-custom" data-dismiss="modal" aria-label="Close">✖</button>
            </div>
            <div class="modal-body">
                <form id="updateWarehouseForm">
                    <div class="mb-3">
                        <label for="editGrnId" class="form-label">GRN ID</label>
                        <input type="text" class="form-control" id="editGrnId" name="grn_id" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editItemName" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="editItemName" name="item_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editGrnQty" class="form-label">GRN Quantity</label>
                        <input type="text" class="form-control" id="editGrnQty" name="grn_quantity" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editCurrentQty" class="form-label">Current Quantity</label>
                        <input type="text" class="form-control" id="editCurrentQty" name="current_quantity" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editWarehouse" class="form-label">Current Warehouse</label>
                        <input type="text" class="form-control" id="editWarehouse" name="current_warehouse" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="newWarehouse" class="form-label">Update Warehouse</label>
                        <select class="form-form-select p-2" id="newWarehouse" name="new_warehouse">
                            <option selected disabled>-- Select New Warehouse --</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>




   

<script>
    $(document).ready(function() {
        // Fetch stores from the database
        $.ajax({
            url: "<?= base_url('StoreItems/getStores') ?>",
            type: "GET",
            dataType: "json",
            success: function(data) {
                let dropdown = $("#storeSelect");
                dropdown.empty();
                dropdown.append('<option selected disabled>Select Warehouses</option>');
                $.each(data, function(key, entry) {
                    dropdown.append($('<option></option>').attr('value', entry.wh_id).text(entry.wh_name));
                });
            },
            error: function() {
                alert("Failed to load stores.");
            }
        });

    });


    $(document).ready(function() {
    // Fetch and populate Table 2
    function loadStoreItems() {
        $.ajax({
            url: "<?= base_url('StoreItems/getStoreItems') ?>",
            type: "GET",
            dataType: "json",
            success: function(data) {
                let tableBody = $("#table2 tbody");
                tableBody.empty();

                $.each(data, function(index, item) {
                    let row = `<tr>
                        <td>${item.grn_id}</td>
                        <td>${item.item_name}</td>
                        <td>${item.grn_quantity}</td>
                        <td>${item.current_quantity}</td>
                        <td>${item.wh_name}</td>
                        <td>${item.date}</td>
                        <td>
                            <button class="btn btn-success btn-sm editWarehouseBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>

                    </tr>`;
                    tableBody.append(row);
                });
            },
            error: function() {
                alert("Failed to load store items.");
            }
        });
    }

    // Call function to load table data
    loadStoreItems();
});


$(document).ready(function() {
    function loadStoresDropdown() {
        $.ajax({
            url: "<?= base_url('StoreItems/getStores') ?>",
            type: "GET",
            dataType: "json",
            success: function(data) {
                $(".storeSelect").each(function() {
                    let dropdown = $(this);
                    dropdown.empty();
                    dropdown.append(`<option selected disabled>--Select Store--</option>`);
                    $.each(data, function(index, store) {
                        dropdown.append(`<option value='${store.wh_id}'>${store.wh_name}</option>`);
                    });
                });
            },
            error: function() {
                alert("Failed to load stores.");
            }
        });
    }

    $("#assignButton").click(function() {
        $("#table1Container").toggle();
        $("#saveButton").hide(); // Hide save button when opening the form
    });

    $("#searchButton").click(function() {
        let grnID = $("#searchGrnID").val();
        if (!grnID) {
            alert("Please enter a GRN ID.");
            return;
        }
        
        $.ajax({
            url: "<?= base_url('StoreItems/searchGRN') ?>",
            type: "POST",
            data: { grn_id: grnID },
            dataType: "json",
            success: function(response) {
                let tableBody = $("#table1Body");
                tableBody.empty();
                
                if (response.length === 0) {
                    alert("No records found.");
                    $("#saveButton").hide();
                    return;
                }
                
                $.each(response, function(index, item) {
                    let storeColumn;
                    if (item.wh_name) {
                        storeColumn = `<td>${item.wh_name}</td>`;
                    } else {
                        storeColumn = `<td>
                            <div class='text-danger fw-bold store-warning'>Please Select Store</div>
                            <select class='form-select w-75 h-50 storeSelect' data-itemid='${item.item_id}' data-grnid='${item.grn_id}'>
                                <option selected disabled>--Select Store--</option>
                            </select>
                        </td>`;
                    }
                    
                    let row = `<tr data-itemid='${item.item_id}'>
                        <td class='grn_id'>${item.grn_id}</td>
                        <td class='item_name'>${item.item_name}</td>
                        <td>${item.grn_quantity}</td>
                        <td>${item.current_quantity}</td>
                        ${storeColumn}
                        <td><button class='btn btn-danger btn-sm removeRow'>Remove</button></td>
                    </tr>`;
                    tableBody.append(row);
                });
                $("#saveButton").show();
                loadStoresDropdown();
            },
            error: function() {
                alert("Error fetching data.");
            }
        });
    });
    
    $(document).on("click", "#saveButton", function() {
        let saveData = [];
        let allSelected = true;
        
        $(".storeSelect").each(function() {
            let storeID = $(this).val();
            let grnID = $(this).data("grnid");
            let itemID = $(this).closest("tr").data("itemid");
            
            if (!storeID) {
                $(this).siblings(".store-warning").show();
                allSelected = false;
            } else {
                saveData.push({ storeitem_storeid: storeID, storeitem_itemid: itemID, storeitem_grnid: grnID });
            }
        });
        
        if (!allSelected) {
            alert("Please select a store for all rows before saving.");
            return;
        }
        
        console.log("Payload sent to server:", saveData);
        
        $.ajax({
            url: "<?= base_url('StoreItems/saveStoreItems') ?>",
            type: "POST",
            data: { storeData: saveData },
            dataType: "json",
            success: function(response) {
                swal({
                                type: 'success',
                                title: 'Data saved successfully.',
                                showConfirmButton: true,
                                }).then(function() {
                                 location.reload();
                                }); 
            },
            error: function() {
                alert("Error saving data.");
            }
        });
    });
    $(document).on("click", ".removeRow", function() {
        $(this).closest("tr").remove();
        if ($("#table1Body tr").length === 0) {
            $("#saveButton").hide();
        }
    });
});

$(document).ready(function() {
    function filterTable() {
        let selectedStore = $('#storeSelect').val();
        let fromDate = $('#fromDate').val();
        let toDate = $('#toDate').val();

        $('#table2 tbody tr').each(function() {
            let storeName = $(this).find('td:nth-child(5)').text().trim();
            let dateTime = $(this).find('td:nth-child(6)').text().trim();
            let rowDate = dateTime.split(' ')[0];

            let storeMatch = selectedStore ? storeName === $('#storeSelect option:selected').text() : true;
            let dateMatch = true;

            if (fromDate && toDate) {
                dateMatch = rowDate >= fromDate && rowDate <= toDate;
            } else if (fromDate) {
                dateMatch = rowDate >= fromDate;
            } else if (toDate) {
                dateMatch = rowDate <= toDate;
            }

            if (storeMatch && dateMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    function addSearchBar() {
        let searchBar = `<div class="d-flex flex-column mb-2">
                            <h4 class="m-0">Warehouse Items</h4>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                
                                <div class="d-flex">
                                    <button id="copyToClipboard" 
                                            class="btn text-white btn-hover-custom"
                                            style="background-color: #868E96; border-color: #868E96; 
                                                border-top-left-radius: 5px; border-bottom-left-radius: 5px; 
                                                border-top-right-radius: 0; border-bottom-right-radius: 0; 
                                                outline: none;">
                                        Copy
                                    </button>
                                    <button id="exportPDF" 
                                            class="btn text-white btn-hover-custom"
                                            style="background-color: #868E96; border-color: #868E96; 
                                                border-top-right-radius: 5px; border-bottom-right-radius: 5px; 
                                                border-top-left-radius: 0; border-bottom-left-radius: 0; 
                                                outline: none;">
                                        PDF
                                    </button>
                                </div>

                                <div class="d-flex">
                                    <lable class = "mt-2 gap-4">Search: </lable>
                                    <input type="text" id="tableSearch" class="form-control w-auto me-3 gap-4" placeholder="Search Table"> 
                                </div>
                            </div>
                        </div>`;
        $('#table2').before(searchBar);
    }

    function searchTable() {
        let searchText = $('#tableSearch').val().toLowerCase();
        $('#table2 tbody tr').each(function() {
            let rowText = $(this).text().toLowerCase();
            $(this).toggle(rowText.includes(searchText));
        });
    }

    $('#storeSelect, #fromDate, #toDate').on('change', function() {
        filterTable();
    });

    $(document).on('keyup', '#tableSearch', function() {
        searchTable();
    });

    $(document).on('click', '#refreshButton', function() {
        location.reload();
    });

    addSearchBar();
});

$(document).ready(function() {
    $("#exportPDF").click(function() {
        const { jsPDF } = window.jspdf;
        let doc = new jsPDF('p', 'mm', 'a4'); // Portrait mode, millimeters, A4 paper

        // Set Title
        let title = "Warehouse Items Report";
        let pageWidth = doc.internal.pageSize.getWidth(); // Get page width
        doc.setFontSize(16);
        doc.text(title, pageWidth / 2, 15, { align: "center" }); // Centered title

        let tableHeaders = [];
        let tableData = [];

        // Get Table Headers (Exclude Last Column - "Action")
        $("#table2 thead tr th").each(function(index) {
            if (index !== $("#table2 thead tr th").length - 1) { // Skip last column
                tableHeaders.push($(this).text().trim());
            }
        });

        // Get Table Data (Exclude Last Column - "Action")
        $("#table2 tbody tr:visible").each(function() {
            let rowData = [];
            $(this).find("td").each(function(index) {
                if (index !== $(this).parent().find("td").length - 1) { // Skip last column
                    rowData.push($(this).text().trim());
                }
            });
            tableData.push(rowData);
        });

        if (tableData.length === 0) {
            alert("No data available for export.");
            return;
        }

        // Generate Table Below Title
        doc.autoTable({
            head: [tableHeaders],
            body: tableData,
            startY: 25, // Move table down to make space for title
            margin: { top: 10 },
            styles: { fontSize: 10, cellPadding: 2 },
        });

        doc.save("store_items.pdf");
    });
});

$(document).ready(function() {
    $("#copyToClipboard").click(function() {
        let table = document.getElementById("table2"); // Select Table 2
        let text = "";
        
        // Get table headers (excluding last column "Action")
        let headers = [];
        $("#table2 thead th").each(function(index) {
            if (index !== $("#table2 thead th").length - 1) { // Skip last column
                headers.push($(this).text().trim());
            }
        });
        text += headers.join("\t") + "\n"; // Add headers

        // Get table rows (excluding last column "Action")
        $("#table2 tbody tr:visible").each(function() {
            let rowData = [];
            $(this).find("td").each(function(index) {
                if (index !== $(this).parent().find("td").length - 1) { // Skip last column
                    rowData.push($(this).text().trim());
                }
            });
            text += rowData.join("\t") + "\n";
        });

        if (text.trim().length === 0) {
            alert("No data to copy.");
            return;
        }

        // Copy to clipboard
        let tempTextArea = $("<textarea>");
        $("body").append(tempTextArea);
        tempTextArea.val(text).select();
        document.execCommand("copy");
        tempTextArea.remove();

        alert("Table data copied to clipboard!");
    });
});


$(document).ready(function () {
        // Toggle warehouse table visibility
        $("#addWarehouse").click(function () {
            $("#warehouseTableContainer").toggle();
            if ($("#warehouseTableContainer").is(":visible")) {
                loadWarehouses();
            }
        });

        // Load warehouses from database
        function loadWarehouses() {
            $.ajax({
                url: "<?= base_url('StoreItems/getWarehouses') ?>",
                type: "GET",
                dataType: "json",
                success: function (data) {
                    let tableBody = $("#warehouseTable tbody");
                    tableBody.empty();

                    $.each(data, function (index, warehouse) {
                        let row = `<tr>
                            <td>${warehouse.wh_name}</td>
                            <td>${warehouse.wh_address}</td>
                            <td>${warehouse.wh_address2}</td>
                            <td>${warehouse.wh_tel}</td>
                            <td>${warehouse.wh_mobile}</td>
                            <td>${warehouse.wh_mobile2}</td>
                            <td>${warehouse.wh_fax}</td>
                            <td>${warehouse.wh_email}</td>
                            <td>
                                <div class="d-flex gap-4"> 
                                    <button class="btn btn-warning btn-sm editWarehouse me-3" data-id="${warehouse.wh_id}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-success btn-sm saveWarehouse d-none me-3" data-id="${warehouse.wh_id}">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm deleteWarehouse ml-3" data-id="${warehouse.wh_id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>`;
                        tableBody.append(row);
                    });
                },
                error: function () {
                    alert("Failed to load warehouse data.");
                }
            });
        }

        // Delete warehouse
        $(document).on("click", ".deleteWarehouse", function () {
            let warehouseId = $(this).data("id");
            if (confirm("Are you sure you want to delete this warehouse?")) {
                $.ajax({
                    url: "<?= base_url('StoreItems/deleteWarehouse') ?>",
                    type: "POST",
                    data: { wh_id: warehouseId },
                    success: function () {
                        alert("Warehouse deleted successfully.");
                        loadWarehouses();
                    },
                    error: function () {
                        alert("Failed to delete warehouse.");
                    }
                });
            }
        });

        $(document).ready(function () {
    // Function to make row editable
    $(document).on("click", ".editWarehouse", function () {
        let row = $(this).closest("tr");

        // Convert each cell to an input field
        row.find("td:not(:last-child)").each(function () {
            let text = $(this).text().trim();
            $(this).html(`<input type="text" class="form-control form-control-sm" value="${text}">`);
        });

        // Toggle button visibility
        row.find(".editWarehouse").addClass("d-none");
        row.find(".saveWarehouse").removeClass("d-none");
    });

    // Save warehouse data
    $(document).on("click", ".saveWarehouse", function () {
        let row = $(this).closest("tr");
        let wh_id = $(this).data("id");

        let updatedData = {
            wh_id: wh_id,
            wh_name: row.find("td:nth-child(1) input").val(),
            wh_address: row.find("td:nth-child(2) input").val(),
            wh_address2: row.find("td:nth-child(3) input").val(),
            wh_tel: row.find("td:nth-child(4) input").val(),
            wh_mobile: row.find("td:nth-child(5) input").val(),
            wh_mobile2: row.find("td:nth-child(6) input").val(),
            wh_fax: row.find("td:nth-child(7) input").val(),
            wh_email: row.find("td:nth-child(8) input").val()
        };

        $.ajax({
            url: "<?= base_url('StoreItems/updateWarehouse') ?>",
            type: "POST",
            data: updatedData,
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    swal({
                                type: 'success',
                                title: 'Warehouse updated successfully.',
                                showConfirmButton: true,
                                })
                    

                    // Convert inputs back to text
                    row.find("td:not(:last-child)").each(function (index) {
                        $(this).text(Object.values(updatedData)[index + 1]);
                    });

                    // Toggle button visibility
                    row.find(".saveWarehouse").addClass("d-none");
                    row.find(".editWarehouse").removeClass("d-none");
                } else {
                    alert("Failed to update warehouse.");
                }
            },
            error: function () {
                alert("Error updating warehouse.");
            }
        });
    });
});

    });




    $(document).ready(function() {
    // Show modal when button is clicked
    $("#addWarehouseBtn").click(function() {
        $("#warehouseModal").modal("show");
    });

    // Submit form data via AJAX
    $("#warehouseForm").submit(function(event) {
        event.preventDefault();

        $.ajax({
            url: "<?= base_url('StoreItems/createWarehouse') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    alert("Warehouse added successfully!");
                    $("#warehouseModal").modal("hide");
                    $("#warehouseForm")[0].reset();
                    location.reload(); // Reload to refresh the warehouse list
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("Error saving warehouse. Please try again.");
            }
        });
    });
});




$(document).ready(function () {

// Load warehouses for dropdown
function loadWarehouses() {
    $.ajax({
        url: "<?= base_url('StoreItems/getWarehouses') ?>",
        type: "GET",
        dataType: "json",
        success: function (data) {
            let dropdown = $("#newWarehouse");
            dropdown.empty();
            dropdown.append('<option selected disabled>-- Select New Warehouse --</option>');
            $.each(data, function (index, warehouse) {
                dropdown.append(`<option value='${warehouse.wh_id}'>${warehouse.wh_name}</option>`);
            });
        },
        error: function () {
            alert("Failed to load warehouses.");
        }
    });
}

// Open Edit Warehouse Modal
$(document).on("click", ".editWarehouseBtn", function () {
    let row = $(this).closest("tr");

    // Populate modal with row data
    $("#editGrnId").val(row.find("td:nth-child(1)").text().trim());
    $("#editItemName").val(row.find("td:nth-child(2)").text().trim());
    $("#editGrnQty").val(row.find("td:nth-child(3)").text().trim());
    $("#editCurrentQty").val(row.find("td:nth-child(4)").text().trim());
    $("#editWarehouse").val(row.find("td:nth-child(5)").text().trim());

    // Load warehouse dropdown options
    loadWarehouses();

    // Show modal
    $("#editWarehouseModal").modal("show");
});

// Handle Save Changes
$("#updateWarehouseForm").submit(function (event) {
    event.preventDefault();

    let grnId = $("#editGrnId").val();
    let itemName = $("#editItemName").val();
    let newWarehouseId = $("#newWarehouse").val();

    if (!newWarehouseId) {
        alert("Please select a warehouse.");
        return;
    }

    $.ajax({
        url: "<?= base_url('StoreItems/updateStoreWarehouse') ?>",
        type: "POST",
        data: {
            grn_id: grnId,
            item_name: itemName,
            new_warehouse_id: newWarehouseId
        },
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                alert("Warehouse updated successfully.");
                $("#editWarehouseModal").modal("hide");
                location.reload();
            } else {
                alert("Failed to update warehouse.");
            }
        },
        error: function () {
            alert("Error updating warehouse.");
        }
    });
});

});


</script>
