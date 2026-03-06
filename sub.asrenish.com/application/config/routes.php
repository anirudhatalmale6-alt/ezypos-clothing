<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['register'] = 'Register';
$route['login'] = 'Userauthentication/index/login';

//$route['cus-return'] = 'returns//'; our-cheque 
$route['cus-payment'] = 'payments/CusPaymentGET/Payment-Customer';
$route['sup-payment'] = 'payments/SupPaymentGET/Payment-Supplier';

$route['show-all-grn'] = 'grns/showAllGRN/All-GRNs';

$route['our-cheque'] = 'SupplierPayment/ourCheque/our-cheque';
$route['cus-cheque'] = 'CustomerPayment/cusCheque/cus-cheque';

$route['stock-log'] = 'stocks/showAllStocklog/stocklog';
$route['stock-supplier'] ='stocks/showAllSupplierStock/supplier-stock';
$route['show-stock'] = 'stocks/showStocks/show-stock';
$route['show-stock-list'] = 'stocks/showStocks_list/show-stock-list';


$route['show-stocklog'] = 'stocks/showAllStocklog/stocklog';
$route['sales-report'] = 'reports/sales_report/salesreport';
$route['monthly-sales-report'] = 'reports/monthly_sales_report/sales-report-monthly';
$route['purchase-report'] = 'reports/purchase_report/purchase-report';
$route['expense-report'] = 'reports/expense_report/expense-report';
$route['today-summary'] = 'reports/today_summary/today-summary';
$route['profit-loss-report'] = 'reports/profit_loss_report/profit-loss-report';
$route['backup'] = 'reports/backup/db_backup';

$route['add-bankacc'] = 'banks/createbankGet/bank-account';
$route['add-expense'] = 'expenses/addExpense/addexpense';
$route['add-sale'] = 'sales/addSaleGET/addsale';
$route['add-grn'] = 'grns/addGrnGET/addgrn';
$route['add-production'] = 'production/addProductionGET/addproduction';
$route['show-all-productions'] = 'production/showAllProductions/All-Productions';

$route['subcat-view'] = 'categories/editSubCat/showsubcategories';

$route['add-staff'] = 'staffs/addStaffGET/addstaff';
$route['add-store'] = 'stores/addStoreGET/addstore';
$route['add-tax'] = 'taxs/addTaxGET/addtax';
$route['add-supplier'] = 'suppliers/addsupplierGET/addsupplier';
$route['add-customer'] = 'customers/addCustomerGET/addcustomer';
$route['add-category'] = 'categories/addCategoryGET/Category';
$route['add-item'] = 'items/addItemGET/additem';
$route['index'] = 'home/view';

$route['testpage'] = 'home/test/testpage';

$route['default_controller'] = 'home/view';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['add-category-b'] = 'categories/addCategoryGETbackup/addcategory_Backup';

$route['retail-pos'] = 'RetailPosController/index';

$route['store_items'] = 'StoreItems/storeItem/store_items';

$route['warehouse'] = 'StoreItems/warehouse/warehouse';

$route['payment-methods'] = 'sales/paymentMethods';








