--2015 Mar 06---
	--db structure updated for business_seasons and destinations (get it from tours_structure)--
------------------------------------------------------------------------
--2015 Mar 11---
ALTER TABLE `destinations` ADD `status_id` INT(11) NOT NULL AFTER `seasons`;



--2015 Mar 18---

ALTER TABLE `trips`  ADD `pick_up_lat` DOUBLE NOT NULL AFTER `pick_up`,  ADD `pick_up_lng` DOUBLE NOT NULL AFTER `pick_up_lat`;
ALTER TABLE `trips`  ADD `drop_lat` DOUBLE NOT NULL AFTER `drop`,  ADD `drop_lng` DOUBLE NOT NULL AFTER `drop_lat`

ALTER TABLE `trips` CHANGE `pick_up` `pick_up_location` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `trips` CHANGE `drop` `drop_location` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `trips` CHANGE `booking-time` `booking_time` TIME NOT NULL;