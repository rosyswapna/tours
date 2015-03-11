--2015 Mar 06---
	--db structure updated for business_seasons and destinations (get it from tours_structure)--
------------------------------------------------------------------------
--2015 Mar 11---
ALTER TABLE `destinations` ADD `status_id` INT(11) NOT NULL AFTER `seasons`;