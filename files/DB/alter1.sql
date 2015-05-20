
--------14 may 2015------------server updated
ALTER TABLE `trip_vouchers` CHANGE `voucher_number` `voucher_no` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;



------18 may 2015---------------server updated
ALTER TABLE `trip_voucher_vehicles`  ADD `no_of_days` INT(11) NOT NULL AFTER `end_time`;

INSERT INTO `1_stock_master` (`stock_id`, `category_id`, `tax_type_id`, `description`, `long_description`, `units`, `mb_flag`, `sales_account`, `cogs_account`, `inventory_account`, `adjustment_account`, `assembly_account`, `dimension_id`, `dimension2_id`, `actual_cost`, `last_cost`, `material_cost`, `labour_cost`, `overhead_cost`, `inactive`, `no_sale`, `editable`) VALUES
('104', 1, 1, 'Accommodation', 'Trip accommodation', 'days', 'D', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('105', 1, 1, 'Trip Service', 'trip service', 'hr', 'D', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);


INSERT INTO `1_item_codes` (`id`, `item_code`, `stock_id`, `description`, `category_id`, `quantity`, `is_foreign`, `inactive`) VALUES
(5, '104', '104', 'Accommodation', 1, 1, 0, 0),
(6, '105', '105', 'Trip Service', 1, 1, 0, 0);


ALTER TABLE `1_debtor_trans_details`  ADD `voucher_itinerary_table` VARCHAR(50) NOT NULL AFTER `trip_voucher`,  ADD `voucher_itinerary_id` INT(11) NOT NULL AFTER `voucher_itinerary_table`,  ADD INDEX (`voucher_itinerary_id`);


ALTER TABLE `trip_voucher_accommodation`  ADD `trans_detail_id` INT(11) NOT NULL AFTER `narration`,  ADD INDEX (`trans_detail_id`);
ALTER TABLE `trip_voucher_vehicles`  ADD `trans_detail_id` INT(11) NOT NULL AFTER `narration`,  ADD INDEX (`trans_detail_id`);
ALTER TABLE `trip_voucher_services`  ADD `trans_detail_id` INT(11) NOT NULL AFTER `narration`,  ADD INDEX (`trans_detail_id`);

ALTER TABLE `trip_vouchers` ADD `delivery_no` INT( 11 ) NOT NULL AFTER `trip_ending_time` ,
ADD `invoice_no` INT( 11 ) NOT NULL AFTER `delivery_no` ,
ADD INDEX ( `delivery_no` , `invoice_no` ) ;

