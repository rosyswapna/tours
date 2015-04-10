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


--2015 Apr 1---
ALTER TABLE `trip_destinations`  ADD `particulars` TEXT NOT NULL AFTER `destination_priority`;


--2015 Apr 9--
ALTER TABLE `trip_services` CHANGE `created` `created` TIMESTAMP NOT NULL;
ALTER TABLE `trip_services` CHANGE `updated` `updated` TIMESTAMP NOT NULL;


ALTER TABLE `trips`  ADD `pluckcard` TINYINT(1) NOT NULL AFTER `pax`,  ADD `uniform` TINYINT(1) NOT NULL AFTER `pluckcard`,  ADD `vehicle_beacon_light_option_id` INT(11) NOT NULL AFTER `uniform`,  ADD `driver_language_id` INT(11) NOT NULL AFTER `vehicle_beacon_light_option_id`,  ADD `driver_language_proficiency_id` INT(11) NOT NULL AFTER `driver_language_id`;

CREATE TABLE IF NOT EXISTS `tariff_masters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `minimum_kilometers` double NOT NULL,
  `minimum_hours` double NOT NULL,
  `organisation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `organisation_id` (`organisation_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tariffs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tariff_master_id` int(11) NOT NULL,
  `vehicle_model_id` int(11) NOT NULL,
  `vehicle_ac_type_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `rate` float NOT NULL,
  `additional_kilometer_rate` float NOT NULL,
  `additional_hour_rate` float NOT NULL,
  `driver_bata` float NOT NULL,
  `night_halt` int(11) NOT NULL,
  `organisation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `tariff_master_id` (`tariff_master_id`),
  KEY `organisation_id` (`organisation_id`),
  KEY `user_id` (`user_id`),
  KEY `vehicle_model_id` (`vehicle_model_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-------------------------------------------package tables-------------------------------
--
-- Table structure for table `packages`
--

CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `status_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `organisation_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `status_id` (`status_id`,`user_id`,`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `package_accomodation`
--

CREATE TABLE IF NOT EXISTS `package_accomodation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_itinerary_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_type_id` int(11) NOT NULL,
  `room_quantity` int(11) NOT NULL,
  `room_attributes` text NOT NULL,
  `meals_package` text NOT NULL,
  `meals_quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `organisation_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `package_itinerary_id` (`package_itinerary_id`,`hotel_id`,`room_type_id`,`user_id`,`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `package_destinations`
--

CREATE TABLE IF NOT EXISTS `package_destinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_itinerary_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `destination_priority` int(11) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `organisation_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `package_itinerary_id` (`package_itinerary_id`,`destination_id`,`user_id`,`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `package_itinerary`
--

CREATE TABLE IF NOT EXISTS `package_itinerary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `day_no` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `organisation_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`,`day_no`),
  KEY `user_id` (`user_id`,`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `package_services`
--

CREATE TABLE IF NOT EXISTS `package_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_itinerary_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `location` varchar(225) NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `organisation_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `package_itinerary_id` (`package_itinerary_id`,`service_id`,`user_id`,`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `package_vehicles`
--

CREATE TABLE IF NOT EXISTS `package_vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_itinerary_id` int(11) NOT NULL,
  `vehicle_type_id` int(11) NOT NULL,
  `vehicle_ac_type_id` int(11) NOT NULL,
  `vehicle_model_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `organisation_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `package_itinerary_id` (`package_itinerary_id`,`vehicle_type_id`,`vehicle_ac_type_id`,`vehicle_model_id`,`vehicle_id`,`driver_id`,`user_id`,`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


---Apr 10 2015---
ALTER TABLE `trip_destinations` CHANGE `particulars` `description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `package_services`  ADD `description` TEXT NOT NULL AFTER `quantity`;

RENAME TABLE `tours`.`package_accomodation`
                  TO `tours`.`package_accommodation`;

ALTER TABLE `package_services`  ADD `amount` DOUBLE NOT NULL AFTER `description`;

ALTER TABLE `package_vehicles`  ADD `tariff_id` INT(11) NOT NULL AFTER `driver_id`,  ADD INDEX (`tariff_id`) 





