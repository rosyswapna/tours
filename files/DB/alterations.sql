--2015 Mar 06---
	--db structure updated for business_seasons and destinations (get it from tours_structure)--
------------------------------------------------------------------------
--2015 Mar 11---
ALTER TABLE `destinations` ADD `status_id` INT(11) NOT NULL AFTER `seasons`;



--2015 Mar 18---
ALTER TABLE `trips` CHANGE `from_destination_id` `pick_up` VARCHAR(250) NOT NULL;
ALTER TABLE `trips` CHANGE `to_destination_id` `drop` VARCHAR(250) NOT NULL;
