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
// Production module - redesigned workflow (Item 4). Old Production controller
// kept intact but no longer linked from the menu.
$route['add-production'] = 'Manufacturing/index';
$route['show-all-productions'] = 'Manufacturing/listAll';
$route['mfg-gate-pass-print/(:num)'] = 'Manufacturing/printGatePass/$1';
// legacy routes (old production module, still reachable directly if needed)
$route['old-add-production'] = 'production/addProductionGET/addproduction';
$route['old-edit-production/(:num)'] = 'production/editProductionGET/$1';
$route['old-show-all-productions'] = 'production/showAllProductions/All-Productions';

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
$route['payment-methods-report'] = 'reports/payment_methods_report/payment-methods-report';

// Production Sale (Tailoring Orders)
$route['add-production-sale'] = 'ProductionSale/addProductionSale/addproductionsale';
$route['edit-production-sale/(:num)'] = 'ProductionSale/editProductionSale/$1';
$route['all-production-sales'] = 'ProductionSale/allProductionSales/allproductionsales';

// Delivery Companies
$route['delivery-companies'] = 'DeliveryCompany/manage';

// Gift Vouchers
$route['gift-vouchers'] = 'GiftVoucher/manage/gift-voucher-manage';
$route['gift-voucher-reports'] = 'GiftVoucher/reports/gift-voucher-reports';

// Returns & Exchanges
$route['returns'] = 'Returns/index';
$route['all-returns'] = 'Returns/listReturns';

// Stock Transfers
$route['stock-transfers'] = 'StockTransfer/index';

// New Reports
$route['cash-flow-report'] = 'reports/cash_flow_report/cash-flow-report';
$route['item-sales-report'] = 'reports/item_sales_report/item-sales-report';
$route['production-report'] = 'reports/production_report/production-report';

// Barcode API for LabelJoy
$route['barcode-api/items'] = 'BarcodeApi/items';
$route['barcode-api/item/(:num)'] = 'BarcodeApi/item/$1';
$route['barcode-api/batch'] = 'BarcodeApi/batch';
$route['barcode-api/batch-flat'] = 'BarcodeApi/batch_flat';
$route['barcode-api/categories'] = 'BarcodeApi/categories';
$route['barcode-api/stores'] = 'BarcodeApi/stores';
$route['barcode-api/info'] = 'BarcodeApi/info';
$route['barcode-api/generate-key'] = 'BarcodeApi/generate_key';
$route['barcode-api/get-key'] = 'BarcodeApi/get_key';
$route['barcode-api/settings'] = 'BarcodeApi/settings_page';

// Gate Pass
$route['print-gate-pass/(:num)'] = 'production/printGatePass/$1';

// Customer Loyalty (Point 8)
$route['loyalty-settings'] = 'Loyalty/settings';
$route['loyalty-report'] = 'Loyalty/report';

// Promotions (Point 9)
$route['promotions'] = 'Promotions/manage';






