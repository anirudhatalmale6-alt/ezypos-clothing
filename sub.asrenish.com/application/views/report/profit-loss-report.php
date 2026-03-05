<div class="wrapper">


  <!-- Date Filter Dropdown -->
  <div class="date-filter">
    <div class="date-picker-container">
      <label for="from-date">From Date</label>
      <input type="date" id="from-date" class="form-control datepic hasDatepicker" name="from-date">
    </div>
    
    <div class="date-picker-container">
      <label for="to-date">To Date</label>
      <input type="date" id="to-date" class="form-control datepic hasDatepicker" name="to-date">
    </div>
    
    <!-- Submit Button -->
    <br><br>
    <button id="apply-filters" type="button">Apply Filter</button>
  </div>

  <!-- Cards for Expenses and Sales -->
  <div class="cards">
    <!-- Total Cost Card -->
    <div class="card">
      <h3>Total Cost</h3>
      <p>Cost for the selected period</p>
      <p class="total-amount" id="total-amount"></p>
    </div>

    <!-- Total Sales Card -->
    <div class="card">
      <h3>Total Sales</h3>
      <p>Sales for the selected period</p>
      <p class="total-sale" id="total-sale"></p>
    </div>
    
    <!-- Overall Expenses Card (New Card) -->
    <div class="card">
      <h3>Total Expenses</h3>
      <p>Total expenses for the business</p>
      <p class="overall-expense" id="overall-expense"></p>
    </div>
  </div>

  <!-- Profit Title and Calculation -->
<div class="profit-wrapper">
  <!-- Gross Profit Section -->
  <div class="profit-section">
    <div class="profit-title">Gross Profit</div>
    <div class="profit">
      <span id="gross-profit"> </span>
    </div>
  </div>

  <!-- Net Profit Section -->
  <div class="profit-section">
    <div class="profit-title">Net Profit</div>
    <div class="profit">
      <span id="net-profit"> </span>
    </div>
  </div>
</div>


  <style>
  
  /* Profit Wrapper Styles */
.profit-wrapper {
  display: flex;
  justify-content: space-between;  /* Align both sections side by side */
  gap: 30px;  /* Add space between the two sections */
  margin-top: 30px;  /* Add top margin for spacing */
}

/* Individual Profit Section Styles */
.profit-section {
  flex: 1;  /* Allow each section to take equal width */
  background-color: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.profit-title {
  text-align: center;
  margin-bottom: 20px;
  font-size: 28px;
  color: #333;
}

.profit {
  text-align: center;
  font-size: 36px;
  color: #4CAF50;
}

#gross-profit, #net-profit {
  font-weight: bold;
}
    /* Global Styles */
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .wrapper {
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      width: 90%;
      max-width: 1000px;
    }

    h1 {
      text-align: center;
      font-size: 36px;
      margin-bottom: 20px;
      color: #333;
    }

    .date-filter {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      margin-bottom: 30px;
      width: 100%;
    }

    .date-picker-container {
      margin-bottom: 15px;
    }

    .date-filter label {
      font-size: 16px;
      color: #333;
      margin-bottom: 5px;
      display: block;
    }

    .date-filter input[type="date"] {
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ddd;
      font-size: 16px;
      width: 100%;
      background-color: #f9f9f9;
      cursor: pointer;
    }

    #apply-filters {
      padding: 10px 20px;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      background-color: #4CAF50;
      color: white;
      cursor: pointer;
      width: 100%;
      margin-top: 20px;
    }

    #apply-filters:hover {
      background-color: #45a049;
    }

    .cards {
      display: flex;
      justify-content: space-between;
      margin-bottom: 30px;
    }

    .card {
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 48%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .card h3 {
      font-size: 24px;
      margin-bottom: 15px;
      color: #333;
    }

    .card p {
      font-size: 18px;
      margin: 5px 0;
      color: #666;
    }

    .card .total-amount,
    .card .total-sale,
    .overall-expense {
      font-size: 32px !important;
      font-weight: bold !important;
      color: #4CAF50 !important;
    }

    .profit-title {
      text-align: center;
      margin-bottom: 20px;
      font-size: 28px;
      color: #333;
    }
    
    .profit {
      text-align: center;
      font-size: 36px;
      color: #4CAF50;
    }
  </style>
</div>
<script>
$(document).ready(function() {
    var totalExpense = 0;
    var totalSales = 0;
    var overallExpense = 0;

    // Fetch total expenses (for initial page load)
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url(); ?>reports/get_total_expenses',
        dataType: 'json',
        success: function(data) {
            totalExpense = parseFloat(data.total_expense);
            if (isNaN(totalExpense)) totalExpense = 0;
            $('.card .total-amount').text('Rs' + totalExpense.toFixed(2));
            if (totalSales !== 0) updateGrossProfit();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching expenses: ', error);
        }
    });

    // Fetch total sales (for initial page load)
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url(); ?>reports/get_total_sales',
        dataType: 'json',
        success: function(data) {
            totalSales = parseFloat(data.total_sales);
            if (isNaN(totalSales)) totalSales = 0;
            $('.card .total-sale').text('Rs' + totalSales.toFixed(2));
            if (totalExpense !== 0) updateGrossProfit();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching sales: ', error);
        }
    });

    // Fetch overall expenses (for initial page load)
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url(); ?>reports/get_overall_expenses',
        dataType: 'json',
        success: function(data) {
            overallExpense = parseFloat(data.overall_expense);
            if (isNaN(overallExpense)) overallExpense = 0;
            $('#overall-expense').text('Rs' + overallExpense.toFixed(2));
        },
        error: function(xhr, status, error) {
            console.error('Error fetching overall expenses: ', error);
        }
    });

    // Function to calculate and display Gross Profit
    function updateGrossProfit() {
        var grossProfit = totalSales - totalExpense;
        $('#gross-profit').text('Rs' + grossProfit.toFixed(2));
        updateNetProfit(grossProfit);
    }

    // Function to calculate and display Net Profit
    function updateNetProfit(grossProfit) {
        var netProfit = totalSales - (overallExpense + totalExpense);
        $('#net-profit').text('Rs' + netProfit.toFixed(2));
    }

    // When the "Apply Filter" button is clicked
    $('#apply-filters').on('click', function() {
        var fromDate = $('#from-date').val();
        var toDate = $('#to-date').val();

        // Ensure that the dates are valid
        if (fromDate && toDate) {
            // Fetch filtered data based on selected dates
            fetchFilteredData(fromDate, toDate);
        } else {
            alert('Please select both "From Date" and "To Date".');
        }
    });

    // Function to fetch data based on the selected date range
    function fetchFilteredData(fromDate, toDate) {
        // Fetch filtered total expenses
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>reports/get_total_expenses',
            data: { from_date: fromDate, to_date: toDate },
            dataType: 'json',
            success: function(data) {
                totalExpense = parseFloat(data.total_expense);
                if (isNaN(totalExpense)) totalExpense = 0;
                $('.card .total-amount').text('Rs' + totalExpense.toFixed(2));
                if (totalSales !== 0) updateGrossProfit();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching filtered expenses: ', error);
            }
        });

        // Fetch filtered total sales
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>reports/get_total_sales',
            data: { from_date: fromDate, to_date: toDate },
            dataType: 'json',
            success: function(data) {
                totalSales = parseFloat(data.total_sales);
                if (isNaN(totalSales)) totalSales = 0;
                $('.card .total-sale').text('Rs' + totalSales.toFixed(2));
                if (totalExpense !== 0) updateGrossProfit();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching filtered sales: ', error);
            }
        });

        // Fetch filtered overall expenses
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>reports/get_overall_expenses',
            data: { from_date: fromDate, to_date: toDate },
            dataType: 'json',
            success: function(data) {
                console.log("Raw response data:", data);
            
                overallExpense = parseFloat(data.overall_expense);
                console.log("Parsed overall expense:", overallExpense);
            
                if (isNaN(overallExpense)) overallExpense = 0;
                $('#overall-expense').text('Rs' + overallExpense.toFixed(2));
            },
            error: function(xhr, status, error) {
                console.error('Error fetching filtered overall expenses: ', error);
            }
        });
    }

});

</script>
