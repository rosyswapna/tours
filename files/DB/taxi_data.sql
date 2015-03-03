-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 21, 2015 at 05:05 AM
-- Server version: 5.5.37-0ubuntu0.13.10.1
-- PHP Version: 5.5.3-1ubuntu2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `test`
--

--
-- Dumping data for table `bank_account_types`
--

INSERT INTO `bank_account_types` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Current', 'Current', NULL, 1, 5, '2014-09-09 06:32:49', '0000-00-00 00:00:00'),
(2, 'Savings', 'Savings', NULL, 1, 5, '2014-09-09 06:33:07', '0000-00-00 00:00:00'),
(3, 'NRI', 'NRI', NULL, 1, 5, '2014-09-09 06:33:16', '0000-00-00 00:00:00');

--
-- Dumping data for table `booking_sources`
--

INSERT INTO `booking_sources` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Call', 'Call', NULL, 1, 5, '2014-09-09 06:39:01', '0000-00-00 00:00:00'),
(2, 'References', 'References', NULL, 1, 5, '2014-09-09 06:39:19', '0000-00-00 00:00:00'),
(3, 'JustDial', 'JustDial', NULL, 1, 5, '2014-09-09 06:39:35', '0000-00-00 00:00:00'),
(4, 'App', 'App', NULL, 1, 5, '2014-09-09 06:39:47', '2014-09-09 06:39:54');

--
-- Dumping data for table `customer_registration_types`
--

INSERT INTO `customer_registration_types` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'PhoneCall', 'PhoneCall', NULL, 1, 5, '2014-09-09 06:31:26', '0000-00-00 00:00:00'),
(2, 'App', 'App', NULL, 1, 5, '2014-09-09 06:31:40', '0000-00-00 00:00:00');

--
-- Dumping data for table `customer_types`
--

INSERT INTO `customer_types` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'WalkIn', 'WalkIn', NULL, 1, 5, '2014-09-09 06:29:57', '0000-00-00 00:00:00'),
(2, 'Cash', 'Cash', NULL, 1, 5, '2014-09-09 06:30:16', '0000-00-00 00:00:00'),
(3, 'Credit', 'Credit', NULL, 1, 5, '2014-09-09 06:30:27', '0000-00-00 00:00:00');

--
-- Dumping data for table `driver_type`
--

INSERT INTO `driver_type` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Contract', 'Contract', NULL, 1, 5, '2014-09-09 06:28:26', '0000-00-00 00:00:00'),
(2, 'Permanent', 'Permanent', NULL, 1, 5, '2014-09-09 06:28:41', '0000-00-00 00:00:00'),
(3, 'PartTime', 'PartTime', NULL, 1, 5, '2014-09-09 06:29:00', '0000-00-00 00:00:00');

--
-- Dumping data for table `id_proof_types`
--

INSERT INTO `id_proof_types` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'DrivinggLicense', 'DrivingLicense', NULL, 1, 5, '2014-09-09 06:33:50', '0000-00-00 00:00:00'),
(2, 'VotersID', 'VotersID', NULL, 1, 5, '2014-09-09 06:34:04', '0000-00-00 00:00:00');

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'English', 'English', NULL, 1, 5, '2014-09-09 06:27:15', '0000-00-00 00:00:00'),
(2, 'Malayalam', 'Malayalam', NULL, 1, 5, '2014-09-09 06:27:25', '0000-00-00 00:00:00'),
(3, 'Hindi', 'Hindi', NULL, 1, 5, '2014-09-09 06:27:37', '0000-00-00 00:00:00');

--
-- Dumping data for table `language_proficiency`
--

INSERT INTO `language_proficiency` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Read', 'Read', NULL, 1, 5, '2014-09-09 06:27:49', '0000-00-00 00:00:00'),
(2, 'Write', 'Write', NULL, 1, 5, '2014-09-09 06:27:59', '0000-00-00 00:00:00'),
(3, 'Speak', 'Speak', NULL, 1, 5, '2014-09-09 06:28:09', '0000-00-00 00:00:00');

--
-- Dumping data for table `marital_statuses`
--

INSERT INTO `marital_statuses` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Married', 'Married', NULL, 1, 5, '2014-09-09 06:31:59', '0000-00-00 00:00:00'),
(2, 'Single', 'Single', NULL, 1, 5, '2014-09-09 06:32:10', '0000-00-00 00:00:00'),
(3, 'Divorcee', 'Divorcee', NULL, 1, 5, '2014-09-09 06:32:33', '0000-00-00 00:00:00');

--
-- Dumping data for table `payment_type`
--

INSERT INTO `payment_type` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Cash', 'Cash', NULL, 1, 5, '2014-09-09 06:29:13', '0000-00-00 00:00:00'),
(2, 'Credit', 'Credit', NULL, 1, 5, '2014-09-09 06:29:34', '0000-00-00 00:00:00');

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `name`, `description`) VALUES
(1, 'Active', 'Active'),
(2, 'Inactive', 'Inactive');


--
-- Dumping data for table `trip_models`
--

INSERT INTO `trip_models` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Local', 'Local', NULL, 1, 5, '2014-09-09 06:34:18', '0000-00-00 00:00:00'),
(2, 'OutStations', 'OutStations', NULL, 1, 5, '2014-09-09 06:34:32', '0000-00-00 00:00:00'),
(3, 'RentACar', 'RentACar', NULL, 1, 5, '2014-09-09 06:34:56', '0000-00-00 00:00:00'),
(4, 'TALCTaxi', 'TALCTaxi', NULL, 1, 5, '2014-09-09 06:35:27', '0000-00-00 00:00:00'),
(5, 'AirportPickup', 'AirportPickup', NULL, 1, 5, '2014-09-09 06:35:57', '0000-00-00 00:00:00');

--
-- Dumping data for table `trip_statuses`
--

INSERT INTO `trip_statuses` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Pending', 'Pending', NULL, 1, 5, '2014-09-09 06:36:20', '2014-09-09 06:36:31'),
(2, 'Confirmed', 'Confirmed', NULL, 1, 5, '2014-09-09 06:36:51', '0000-00-00 00:00:00'),
(3, 'Canceled', 'Canceled', NULL, 1, 5, '2014-09-09 06:37:07', '0000-00-00 00:00:00'),
(4, 'CustomerCanceled', 'CustomerCanceled', NULL, 1, 5, '2014-09-09 06:37:25', '0000-00-00 00:00:00'),
(5, 'OnTrip', 'OnTrip', NULL, 1, 5, '2014-09-09 06:37:43', '0000-00-00 00:00:00'),
(6, 'TripCompleted', 'TripCompleted', NULL, 1, 5, '2014-09-09 06:37:59', '0000-00-00 00:00:00'),
(7, 'TripPayed', 'TripPayed', NULL, 1, 5, '2014-09-09 06:38:17', '2014-09-09 06:38:39'),
(8, 'BillGenerated', 'BillGenerated', NULL, 1, 5, '2014-09-29 07:37:59', '0000-00-00 00:00:00');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `email`, `phone`, `address`, `occupation`, `user_status_id`, `password_token`, `user_type_id`, `organisation_id`, `organisation_admin_id`, `fa_account`, `created`, `updated`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'System', 'Administrator', 'admin@acube.local', NULL, NULL, NULL, 1, NULL, 1, -1, NULL, 0, '2014-08-10 18:30:00', '0000-00-00 00:00:00');

--
-- Dumping data for table `user_statuses`
--

INSERT INTO `user_statuses` (`id`, `name`, `description`) VALUES
(1, 'Active', 'Active'),
(2, 'Suspended', 'Suspended'),
(3, 'Disabled', 'Disabled');

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `name`, `description`) VALUES
(1, 'System Administrator', 'System Administrator'),
(2, 'Organisation Administrator', 'Organisation Administrator'),
(3, 'Front Desk', 'Front Desk'),
(4, 'Customer', 'Customer'),
(5, 'Driver', 'Driver'),
(6, 'Vehicle Owner', 'Vehicle Owner');


--
-- Dumping data for table `vehicle_ac_types`
--

INSERT INTO `vehicle_ac_types` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'AC', 'AC', NULL, 1, 5, '2014-09-09 06:21:20', '0000-00-00 00:00:00'),
(2, 'NonAC', 'NonAc', NULL, 1, 5, '2014-09-09 06:21:32', '0000-00-00 00:00:00');

--
-- Dumping data for table `vehicle_beacon_light_options`
--

INSERT INTO `vehicle_beacon_light_options` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Red', 'Red', NULL, 1, 5, '2014-09-09 06:23:08', '0000-00-00 00:00:00'),
(2, 'Blue', 'Blue', NULL, 1, 5, '2014-09-09 06:23:16', '0000-00-00 00:00:00');

--
-- Dumping data for table `vehicle_driver_bata_percentages`
--

INSERT INTO `vehicle_driver_bata_percentages` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, '17percent', '17percent', NULL, 1, 5, '2014-09-09 06:24:25', '0000-00-00 00:00:00'),
(2, '20percent', '20percent', NULL, 1, 5, '2014-09-09 06:24:39', '0000-00-00 00:00:00');

--
-- Dumping data for table `vehicle_fuel_types`
--

INSERT INTO `vehicle_fuel_types` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Petrol', 'Petrol', NULL, 1, 5, '2014-09-09 06:21:47', '0000-00-00 00:00:00'),
(2, 'Diesel', 'Diesel', NULL, 1, 5, '2014-09-09 06:21:59', '0000-00-00 00:00:00'),
(3, 'CNG', 'CNG', NULL, 1, 5, '2014-09-09 06:22:08', '0000-00-00 00:00:00');

--
-- Dumping data for table `vehicle_makes`
--

INSERT INTO `vehicle_makes` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'TATAindica', 'TATA', NULL, 1, 5, '2014-09-09 06:23:33', '2014-09-25 06:05:48'),
(2, 'Toyota', 'Toyota', NULL, 1, 5, '2014-09-09 06:23:45', '0000-00-00 00:00:00'),
(3, 'Fiat', 'Fiat', NULL, 1, 5, '2014-09-09 06:23:56', '0000-00-00 00:00:00'),
(4, 'tatasumo', 'tatasumo', NULL, 1, 5, '2014-09-25 06:06:55', '0000-00-00 00:00:00');

--
-- Dumping data for table `vehicle_models`
--

INSERT INTO `vehicle_models` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Indica', 'Indica', NULL, 1, 4, '2015-01-21 06:31:12', '2015-01-21 06:34:43'),
(2, 'Indigo', 'Indigo', NULL, 1, 4, '2015-01-21 06:36:22', '0000-00-00 00:00:00'),
(3, 'Verito', 'Verito', NULL, 1, 4, '2015-01-21 06:37:21', '0000-00-00 00:00:00'),
(4, 'Xylo', 'Xylo', NULL, 1, 4, '2015-01-21 06:37:37', '0000-00-00 00:00:00'),
(5, 'Innova', 'Innova', NULL, 1, 4, '2015-01-21 06:37:55', '0000-00-00 00:00:00'),
(6, 'Liva', 'Etios Liva', NULL, 1, 19, '2015-01-31 07:30:18', '0000-00-00 00:00:00'),
(7, 'Logan', 'Logan', NULL, 1, 19, '2015-01-31 07:32:13', '0000-00-00 00:00:00'),
(8, 'Ertiga', 'Ertiga', NULL, 1, 19, '2015-01-31 07:34:01', '2015-01-31 07:34:36'),
(9, 'Swift', 'Swift', NULL, 1, 6, '2015-01-31 09:38:38', '0000-00-00 00:00:00'),
(10, 'Dezire', 'Dezire', NULL, 1, 6, '2015-01-31 09:38:51', '0000-00-00 00:00:00');

--
-- Dumping data for table `vehicle_ownership_types`
--

INSERT INTO `vehicle_ownership_types` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Owned', 'Owned', NULL, 1, 5, '2014-09-09 06:19:29', '0000-00-00 00:00:00'),
(2, 'Rented', 'Rented', NULL, 1, 5, '2014-09-09 06:19:45', '0000-00-00 00:00:00'),
(3, 'Attached', 'Attached', NULL, 1, 5, '2014-09-09 06:20:04', '0000-00-00 00:00:00');

--
-- Dumping data for table `vehicle_permit_types`
--

INSERT INTO `vehicle_permit_types` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'AllKerala', 'AllKerala', NULL, 1, 5, '2014-09-09 06:26:11', '0000-00-00 00:00:00'),
(2, 'AllIndia', 'AllIndia', NULL, 1, 5, '2014-09-09 06:26:25', '0000-00-00 00:00:00');

--
-- Dumping data for table `vehicle_seating_capacity`
--

INSERT INTO `vehicle_seating_capacity` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, '4seated', '4seated', NULL, 1, 5, '2014-09-09 06:22:33', '0000-00-00 00:00:00'),
(2, '5seated', '5seated', NULL, 1, 5, '2014-09-09 06:22:44', '0000-00-00 00:00:00'),
(3, '6seated', '6seated', NULL, 1, 5, '2014-09-09 06:22:55', '0000-00-00 00:00:00');

--
-- Dumping data for table `vehicle_types`
--

INSERT INTO `vehicle_types` (`id`, `name`, `description`, `value`, `organisation_id`, `user_id`, `created`, `updated`) VALUES
(1, 'Sedan', 'Sedan', NULL, 1, 5, '2014-09-09 06:20:26', '0000-00-00 00:00:00'),
(2, 'Hatchback', 'Hatchback', NULL, 1, 5, '2014-09-09 06:20:43', '0000-00-00 00:00:00'),
(3, 'SUV', 'SUV', NULL, 1, 5, '2014-09-09 06:20:55', '0000-00-00 00:00:00'),
(4, 'Traveler', 'Traveler', NULL, 1, 5, '2014-09-09 06:21:06', '0000-00-00 00:00:00');

