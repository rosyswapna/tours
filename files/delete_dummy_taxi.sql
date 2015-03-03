--Truncate Fa tables transactions
TRUNCATE `1_audit_trail`;
TRUNCATE `1_bank_trans`;
TRUNCATE `1_comments`;
TRUNCATE `1_debtor_trans`;
TRUNCATE `1_debtor_trans_details`;
TRUNCATE `1_gl_trans`;
TRUNCATE `1_purch_orders`;
TRUNCATE `1_purch_order_details`;
TRUNCATE `1_refs`;
TRUNCATE `1_sales_orders`;
TRUNCATE `1_sales_order_details`;
TRUNCATE TABLE `1_stock_moves`;
TRUNCATE `1_supp_trans`;
TRUNCATE `1_trans_tax_details`;
TRUNCATE `1_voided`;
TRUNCATE TABLE `1_grn_items`;
TRUNCATE TABLE `1_grn_batch`;
TRUNCATE TABLE `1_purch_data`;
TRUNCATE TABLE `1_supp_allocations`;
TRUNCATE TABLE `1_supp_invoice_items`;

--Truncate dummy customers

TRUNCATE `1_debtor_master`;
TRUNCATE `1_cust_branch`;

TRUNCATE `customers`;
---------------------------

--Truncate dummy suppliers

TRUNCATE `1_suppliers`;

TRUNCATE `drivers`;
TRUNCATE `vehicle_drivers`;
TRUNCATE `vehicle_devices`;

TRUNCATE `vehicle_owners`;
------------------------------

--Truncate taxi test trip data
TRUNCATE TABLE `trips`;
TRUNCATE TABLE `trip_vouchers`;

------------------------------





