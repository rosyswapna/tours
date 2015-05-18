
--------14 may 2015------------
ALTER TABLE `trip_vouchers` CHANGE `voucher_number` `voucher_no` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;



------18 may 2015---------------
ALTER TABLE `trip_voucher_vehicles`  ADD `no_of_days` INT(11) NOT NULL AFTER `end_time`;
