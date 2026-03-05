<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retail POS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Header Section -->
<div class="container-fluid bg-white py-3">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Buttons on the Left -->
        <div class="d-flex">
            <button class="btn btn-dark me-2">
                <i class="fas fa-home"></i> Home
            </button>
            <button class="btn btn-primary me-2">GRN</button>
            <button class="btn btn-secondary me-2">Expenses</button>
        </div>
        
        <!-- Buttons on the Right -->
        <div class="d-flex">
            <!-- Logout Button -->
            <button class="btn btn-danger me-2">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
            <!-- Close Button -->
            <button class="btn btn-dark">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<!-- Main Content Section -->
<div class="container-fluid mt-4 flex-grow-1">
    <div class="row">
        <!-- Left Sidebar (Categories & Items) -->
        <div class="col-md-6">
            <!-- Categories Dropdown -->
            <div class="mb-3">
                <select id="categories" class="form-select">
                    <option selected>--Select Category--</option>
                    <option value="1">Category 1</option>
                    <option value="2">Category 2</option>
                </select>
            </div>

            <!-- Items Dropdown -->
            <div class="mb-3">
                <select id="items" class="form-select">
                    <option selected>--Select Item--</option>
                    <option value="1">Item 1</option>
                    <option value="2">Item 2</option>
                </select>
            </div>

            <!-- Items Display (4 items per row) -->
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3">
                <div class="col">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Item 1">
                        <div class="card-body text-center">
                            <p class="card-text">Item 1</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Item 2">
                        <div class="card-body text-center">
                            <p class="card-text">Item 2</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Item 3">
                        <div class="card-body text-center">
                            <p class="card-text">Item 3</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Item 4">
                        <div class="card-body text-center">
                            <p class="card-text">Item 4</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Item 5">
                        <div class="card-body text-center">
                            <p class="card-text">Item 5</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Item 6">
                        <div class="card-body text-center">
                            <p class="card-text">Item 6</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Item 7">
                        <div class="card-body text-center">
                            <p class="card-text">Item 7</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Item 8">
                        <div class="card-body text-center">
                            <p class="card-text">Item 8</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content (Form Section) -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm" style="border-radius: 15px;">
                <div class="mb-3">
                    <label for="customerName" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" id="customerName" placeholder="Enter customer name">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <select id="location" class="form-select">
                            <option selected>--Select Location--</option>
                            <option value="1">Location 1</option>
                            <option value="2">Location 2</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="saleDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="saleDate">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subTotal" class="form-label">Sub Total</label>
                        <input type="text" class="form-control" id="subTotal" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="discount" class="form-label">Discount</label>
                        <input type="number" class="form-control" id="discount" placeholder="Enter discount">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="grandTotal" class="form-label">Grand Total</label>
                    <input type="text" class="form-control" id="grandTotal" disabled>
                </div>
                <div class="mb-3">
                    <label for="cash" class="form-label">Cash</label>
                    <input type="number" class="form-control mb-2" id="cash">
                    
                    <!-- Cheque Checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="chequeCheck" onclick="toggleChequeForm()">
                        <label class="form-check-label" for="chequeCheck">
                            Cheque
                        </label>
                    </div>

                    <!-- Cheque Form (Initially Hidden) -->
                    <div id="chequeForm" class="mt-3" style="display: none;">
                        <div class="mb-3">
                            <label for="chequeAmount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="chequeAmount" placeholder="Enter amount">
                        </div>
                        <div class="mb-3">
                            <label for="bank" class="form-label">Bank</label>
                            <input type="text" class="form-control" id="bank" placeholder="Enter bank name">
                        </div>
                        <div class="mb-3">
                            <label for="chequeNo" class="form-label">Cheque No</label>
                            <input type="text" class="form-control" id="chequeNo" placeholder="Enter cheque number">
                        </div>
                        <div class="mb-3">
                            <label for="chequeDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="chequeDate">
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Section (Item Details) -->
            <div class="card p-4 shadow-sm mt-4" style="border-radius: 15px;">
                <h5 class="card-title">Added Item Details</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Item 1</td>
                                <td>2</td>
                                <td>$10.00</td>
                            </tr>
                            <tr>
                                <td>Item 2</td>
                                <td>1</td>
                                <td>$5.00</td>
                            </tr>
                            <tr>
                                <td>Item 3</td>
                                <td>3</td>
                                <td>$15.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Section -->
<div class="container-fluid bg-dark text-white py-3 mt-auto">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Grand Total -->
        <div class="fs-4">Grand Total: $0.00</div>
        <!-- Buttons -->
        <div>
            <button class="btn btn-light me-2">Cancel</button>
            <button class="btn btn-success">Save</button>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleChequeForm() {
        const chequeForm = document.getElementById('chequeForm');
        const isChecked = document.getElementById('chequeCheck').checked;
        chequeForm.style.display = isChecked ? 'block' : 'none';
    }
</script>
</body>
</html>