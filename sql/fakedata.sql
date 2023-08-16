# queries to setup testing
#Update proposal_details set proposal_id = 9124 where proposal_id = 5878;
#Update proposal_media set proposal_id = 9124 where proposal_id = 57;
#Update proposal_detail_additional_costs set proposal_detail_id =15516 where proposal_detail_id =15865;

INSERT INTO `proposal_notes` (`id`, `reminder`, `reminder_date`, `proposal_id`, `created_by`, `note`, `created_at`, `updated_at`) VALUES (NULL, '0', '2022-09-14', '9124', '10', 'My Note here', '2022-09-14 17:22:40', NULL); 
INSERT INTO `proposal_notes` (`id`, `reminder`, `reminder_date`, `proposal_id`, `created_by`, `note`, `created_at`, `updated_at`) VALUES (NULL, '0', '2022-09-14', '9124', '10', 'My Other Note here', '2022-09-14 17:22:40', NULL); 

INSERT INTO `leads` (`id`, `contact_type_id`, `first_name`, `last_name`, `email`, `phone`, `address1`, `address2`, `city`, `postal_code`, `state`, `county`, `created_by`, `status_id`, `assigned_to`, `worked_before`, `worked_before_description`, `previous_assigned_to`, `type_of_work_needed`, `lead_source`, `how_related`, `onsite`, `best_days`, `created_at`, `updated_at`) VALUES (NULL, 18,'Michael', 'Trachtenberg', 'mike.trachtenberg@gmail.com', '7866767777', '123 Street', NULL, 'Weston', '33326', 'FL', 'Broward', '10', '2', 10, '1', 'Yeah we did some work', NULL, 'Asphalt', 'Internet', 'Boss', '1', '10-4pm every day', '2022-11-01 13:14:06', '2022-11-01 13:14:06');
INSERT INTO `leads` (`id`, `contact_type_id`, `first_name`, `last_name`, `email`, `phone`, `address1`, `address2`, `city`, `postal_code`, `state`, `county`, `created_by`, `status_id`, `assigned_to`, `worked_before`, `worked_before_description`, `previous_assigned_to`, `type_of_work_needed`, `lead_source`, `how_related`, `onsite`, `best_days`, `created_at`, `updated_at`) VALUES (NULL, 18,'Sam', 'Franklin', 'sam.trachtenberg@gmail.com', '7866767777', '123 Street', NULL, 'Weston', '33326', 'FL', 'Broward', '10', '2', 675, '1', 'Yeah we did some work', NULL, 'Asphalt', 'Internet', 'Boss', '1', '10-4pm every day', '2022-11-01 13:14:06', '2022-11-01 13:14:06');
INSERT INTO `leads` (`id`, `contact_type_id`,`first_name`, `last_name`, `email`, `phone`, `address1`, `address2`, `city`, `postal_code`, `state`, `county`, `created_by`, `status_id`, `assigned_to`, `worked_before`, `worked_before_description`, `previous_assigned_to`, `type_of_work_needed`, `lead_source`, `how_related`, `onsite`, `best_days`, `created_at`, `updated_at`) VALUES (NULL, 18,'Art', 'Manna', 'art.trachtenberg@gmail.com', '7866767777', '123 Street', NULL, 'Weston', '33326', 'FL', 'Broward', '10', '1', NULL, '1', 'Yeah we did some work', NULL, 'Asphalt', 'Internet', 'Boss', '1', '10-4pm every day', '2022-11-01 13:14:06', '2022-11-01 13:14:06');
INSERT INTO `leads` (`id`,`contact_type_id`, `first_name`, `last_name`, `email`, `phone`, `address1`, `address2`, `city`, `postal_code`, `state`, `county`, `created_by`, `status_id`, `assigned_to`, `worked_before`, `worked_before_description`, `previous_assigned_to`, `type_of_work_needed`, `lead_source`, `how_related`, `onsite`, `best_days`, `created_at`, `updated_at`) VALUES (NULL, 18,'Frank', 'Salzberg', 'frank.trachtenberg@gmail.com', '7866767777', '123 Street', NULL, 'Weston', '33326', 'FL', 'Broward', '10', '1', NULL, '1', 'Yeah we did some work', NULL, 'Asphalt', 'Internet', 'Boss', '1', '10-4pm every day', '2022-11-01 13:14:06', '2022-11-01 13:14:06');

INSERT INTO `leads` (`id`,`contact_type_id`, `first_name`, `last_name`, `email`, `phone`, `address1`, `address2`, `city`, `postal_code`, `state`, `county`, `created_by`, `status_id`, `assigned_to`, `worked_before`, `worked_before_description`, `previous_assigned_to`, `type_of_work_needed`, `lead_source`, `how_related`, `onsite`, `best_days`, `created_at`, `updated_at`) VALUES (NULL,18, 'Sam', 'Hallop', 'test@gmail.com', '7866767777', '123 Street', NULL, 'Weston', '33326', 'FL', 'Broward', '10', '2', 10, '1', 'Yeah we did some work', NULL, 'Asphalt', 'Internet', 'Boss', '1', '10-4pm every day', '2022-11-01 13:14:06', '2022-11-01 13:14:06');
INSERT INTO `leads` (`id`,`contact_type_id`, `first_name`, `last_name`, `email`, `phone`, `address1`, `address2`, `city`, `postal_code`, `state`, `county`, `created_by`, `status_id`, `assigned_to`, `worked_before`, `worked_before_description`, `previous_assigned_to`, `type_of_work_needed`, `lead_source`, `how_related`, `onsite`, `best_days`, `created_at`, `updated_at`) VALUES (NULL,18, 'Asia', 'Tasmin', 'test@gmail.com', '7866767777', '123 Street', NULL, 'Weston', '33326', 'FL', 'Broward', '10', '2',675, '1', 'Yeah we did some work', NULL, 'Asphalt', 'Internet', 'Boss', '1', '10-4pm every day', '2022-11-01 13:14:06', '2022-11-01 13:14:06');


INSERT INTO `lead_notes` (`id`, `lead_id`, `created_by`, `note`, `created_at`, `updated_at`) VALUES (NULL, '1', '10', 'This is another test note for lead 2', '2022-10-20 20:35:58', NULL);
INSERT INTO `lead_notes` (`id`, `lead_id`, `created_by`, `note`, `created_at`, `updated_at`) VALUES (NULL, '2', '10', 'This is another test note for lead 2', '2022-10-20 20:35:58', NULL);


#create alert

Update proposals set on_alert = 1, alert_reason ='This is a test'  Where id = 10288;
Update proposals set on_alert = 1, alert_reason ='This was put on alert because' Where id = 10279;
Update proposals set on_alert = 1, alert_reason ='This was put on alert because' Where id = 10277;

INSERT INTO `proposal_actions` (`id`, `proposal_id`, `action_id`, `created_by`, `note`, `created_at`, `updated_at`) 
VALUES (NULL, '10219', '5', '10', 'Set alert on this proposal because testing', NULL, NULL), 
(NULL, '10218', '5', '10', 'Test alert on this proposal because test', NULL, NULL),
(NULL, '10217', '5', '10', 'Test alert on this proposal because test', NULL, NULL);


#Update `proposal_details` Set `status_id` = 3 where `proposal_id` = 10278;
#Update `proposal_details` Set `status_id` = 3 where `proposal_id` = 10284;

#fake some notes
INSERT INTO `contact_notes` (`id`, `contact_id`, `created_by`, `note`, `created_at`, `updated_at`) VALUES
(NULL, '20', '10', 'Note from lead here', '2022-08-01 10:06:13', '2022-08-01 10:06:13'),
(NULL, '20', '7366', 'Note from Damon Petters', '2022-08-03 10:07:15', '2022-08-03 10:07:15'),
(NULL, '49', '9', 'This is a note from Tom Siodlak', '2022-08-10 10:07:15', '2022-08-10 10:07:15'),
(NULL, '49', '3927', 'Patrick Daly wrote this note', '2022-08-11 10:07:15', '2022-08-11 10:07:15');

UPDATE `contacts` SET `note` = 'This is the initial note for ABM Parking Services', `created_at` = '2022-07-04 10:09:53', `updated_at` = '2022-08-10 10:09:53', `deleted_at` = NULL WHERE `contacts`.`id` = 20;
UPDATE `contacts` SET `note` = 'This is the first note created to Brigadoon Condo HOA', `created_at` = '2022-06-07 10:11:11', `updated_at` = '2022-08-15 10:11:11', `deleted_at` = NULL WHERE `contacts`.`id` = 49;

UPDATE `lead_status` SET `color` = 'd0d0d0' WHERE `lead_status`.`id` = 1;



#create some permit records
INSERT INTO `permits` (`id`, `proposal_id`, `proposal_detail_id`, `status`, `type`, `number`, `city`,`county`, `expires_on`,`created_by`, `created_at`, `updated_at`) VALUES (NULL, '8549', NULL, 'Not Submitted', 'Regular', '12345','Ft. Lauderdale', 'Broward', '2022-11-17 13:58:27','10840', '2022-11-17 13:58:27', NULL);
INSERT INTO `permits` (`id`, `proposal_id`, `proposal_detail_id`, `status`, `type`, `number`, `city`,`county`, `expires_on`,`created_by`, `created_at`, `updated_at`) VALUES (NULL, '5878', NULL, 'Not Submitted', 'Regular', '12345','Ft. Lauderdale', 'Broward','2022-11-17 13:58:27', '10840', '2022-11-17 13:58:27', NULL);
INSERT INTO `permits` (`id`, `proposal_id`, `proposal_detail_id`, `status`, `type`, `number`, `city`,`county`, `expires_on`,`created_by`, `created_at`, `updated_at`) VALUES (NULL, '5878', NULL, 'Not Submitted', 'Regular', '12345', 'Ft. Lauderdale','Broward','2022-11-17 13:58:27', '10840', '2022-11-17 13:58:27', NULL);
INSERT INTO `permits` (`id`, `proposal_id`, `proposal_detail_id`, `status`, `type`, `number`, `city`,`county`, `expires_on`,`created_by`, `created_at`, `updated_at`) VALUES (NULL, '5889', '7883', 'Not Submitted', 'Regular', '32443','Ft. Lauderdale', 'Broward', '2022-11-17 13:58:27','10', '2022-11-17 14:01:46', NULL);

INSERT INTO `permit_notes` (`id`, `permit_id`, `created_by`, `note`, `fee`, `created_at`, `updated_at`) VALUES (NULL, '3', '10', 'Test Note for this permit', '23', '2022-11-17 14:02:45', NULL);
INSERT INTO `permit_notes` (`id`, `permit_id`, `created_by`, `note`, `fee`, `created_at`, `updated_at`) VALUES (NULL, '1', '10', 'Test Note for this permit', '23', '2022-11-17 14:02:45', NULL);
INSERT INTO `permit_notes` (`id`, `permit_id`, `created_by`, `note`, `fee`, `created_at`, `updated_at`) VALUES (NULL, '1', '10', 'Test Note for this permit times two', '0', '2022-11-17 14:02:45', NULL);
INSERT INTO `permit_notes` (`id`, `permit_id`, `created_by`, `note`, `fee`, `created_at`, `updated_at`) VALUES (NULL, '2', '10', 'Test Note for this permit', '23', '2022-11-17 14:02:45', NULL);

#fake proposals permits required
Update proposals set permit_required =1 where id = 8549;
Update proposals set permit_required =1 where id = 5878;
Update proposals set permit_required =1 where id = 5889;
Update proposals set permit_required =1, mot_required=1, nto_required=1, on_alert =1  where id = 5879;

Update proposals Set name = "Test Proposal Name" WHERE id = 10680; 
UPDATE proposals Set progressive_billing = 1 where id =10680;
DELETE FROM proposal_details WHERE proposal_id = 10680;
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by)VALUES ( 10680,1, 1, 6196, 'All Excavation', 'All Excavations', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by)VALUES ( 10680, 2, 1, 6196, 'Rock Services', 'Rock Services', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by)VALUES ( 10680,  3,  1, 6196, 'Repairs', 'Repairs', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by)VALUES (10680,  4, 1, 6196, 'Asphalt Paving - (Over 3500 SY)', 'Asphalt Paving - (Over 3500 SY)', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by)VALUES( 10680,  5,  1, 6196, 'Paving (Under 3500 SY)', 'Paving (Under 3500 SY)', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by)VALUES ( 10680, 6, 1, 6196, 'Curb (Extruded)', 'Curb (Extruded)', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by)VALUES ( 10680,  7,  1, 6196, 'Curb (Type D)', 'Curb (Type D)', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by)VALUES (10680,  8,  1, 6196, 'Curb (Type Mod D)', 'Curb (Type Mod D) ', 10);

INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680,  9,   1, 6196, 'Curb (Type F)', 'Curb (Type F)', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 10, 1, 6196,   'Curb (Valley Gutter)', 'Curb (Valley Gutter)', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 11, 1, 6196, 'Curb (Header) [New or Repairs]', 'Curb (Header) [New or Repairs]', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 12, 1, 6196,   'Slab', 'Slab', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 13, 1, 6196,   'Ramp', 'Ramp', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 14, 1, 6196,   'Sidewalks', 'Sidewalks', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 15, 1, 6196,   'Sealcoating', 'Sealcoating', 10);

INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 16, 1, 6196,  'Other Service', 'Other Service', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 17, 1, 6196,   'Any Sub Contractor', 'Any Sub Contractor', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 18, 1, 6196,  'Pavement Markings', 'Pavement Markings', 10);

INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 19, 1, 6196,   'Milling', 'Milling', 10);

INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 20, 1, 6196,   'Paver Brick', 'Paver Brick', 10);
INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 21, 1, 6196,   'Drainage and Catchbasins', 'Drainage and Catchbasins', 10);

INSERT INTO `proposal_details` (proposal_id,services_id,status_id,location_id,service_name,service_desc,created_by) VALUES ( 10680, 22, 1, 6196,   'Milling and Paving- (Over 3500 SY)', 'Milling and Paving- (Over 3500 SY)', 10);

33478
33479
33480
33481
33482
33483
33484
33485
33486
33487
33488
33489
33490
33491
33492
33493
33494
33495
33496
33497
33498
33499




INSERT INTO `proposal_detail_vehicles` VALUES (33296, 2, 'NQR', 3, 3, 8, 19.00, 20011, '2023-06-07 18:06:00', '2023-06-07 18:06:00');
INSERT INTO `proposal_detail_vehicles` VALUES (33297, 3, ' International ', 1, 1, 8, 15.00, 20011, '2023-06-08 12:19:50', '2023-06-08 12:19:50');
INSERT INTO `proposal_detail_vehicles` VALUES (33298, 3, ' International ', 1, 1, 8, 15.00, 20011, '2023-06-13 14:40:29', '2023-06-13 14:40:29');
INSERT INTO `proposal_detail_vehicles` VALUES (33299, 5, 'NQR 3', 1, 1, 8, 19.00, 20011, '2023-06-13 14:42:08', '2023-06-13 14:42:08');
INSERT INTO `proposal_detail_vehicles` VALUES (33300, 3, ' International ', 2, 2, 8, 15.00, 20011, '2023-06-13 14:49:37', '2023-06-13 14:49:37');
INSERT INTO `proposal_detail_vehicles` VALUES (33301, 3, ' International ', 1, 2, 6, 15.00, 20011, '2023-06-13 15:58:34', '2023-06-13 15:58:34');
INSERT INTO `proposal_detail_vehicles` VALUES (33302, 3, ' International ', 1, 1, 8, 15.00, 20011, '2023-06-13 16:31:47', '2023-06-13 16:31:47');
INSERT INTO `proposal_detail_vehicles` VALUES (33303, 3, ' International ', 3, 3, 8, 15.00, 20011, '2023-06-14 14:45:42', '2023-06-14 14:45:42');
INSERT INTO `proposal_detail_vehicles` VALUES (33304, 2, 'NQR', 2, 2, 7, 19.00, 20011, '2023-06-14 17:33:44', '2023-06-14 17:33:44');
INSERT INTO `proposal_detail_vehicles` VALUES (33306, 2, 'NQR', 1, 1, 8, 19.00, 20011, '2023-06-15 01:32:41', '2023-06-15 01:32:41');
INSERT INTO `proposal_detail_vehicles` VALUES (33308, 5, 'NQR 3', 2, 2, 8, 19.00, 20011, '2023-06-18 14:56:27', '2023-06-18 14:56:27');
INSERT INTO `proposal_detail_vehicles` VALUES (33307, 2, 'NQR', 2, 3, 2, 19.00, 20011, '2023-06-17 12:06:41', '2023-06-17 12:06:41');


INSERT INTO `proposal_detail_subcontractors` VALUES (33296, 751, 20011, 3334, 0, 0, 0, '33296_faqs-6522e3.pdf', 'test', '2023-06-07 18:07:21', '2023-06-07 18:07:21');
INSERT INTO `proposal_detail_subcontractors` VALUES (33296, 1571, 20011, 2323, 10, 0, 1, '33296_crp-2854-4fd846.xlsx', 'test', '2023-06-07 18:08:02', '2023-06-07 18:08:02');
INSERT INTO `proposal_detail_subcontractors` VALUES (33299, 714, 20011, 4333, 10, 0, 1, '33299_skin-6483ae.jpg', 'test', '2023-06-13 14:43:09', '2023-06-13 14:43:09');
INSERT INTO `proposal_detail_subcontractors` VALUES (33303, 754, 20011, 3334, 2, 0, 1, '33303_skin-5e0635.jpg', 'test', '2023-06-14 14:47:00', '2023-06-14 14:47:00');
INSERT INTO `proposal_detail_subcontractors` VALUES (33304, 751, 20011, 222, 2, 0, 1, '33304_skin-d38891.jpg', 'test', '2023-06-14 17:34:16', '2023-06-14 17:34:16');

INSERT INTO `proposal_detail_labor` VALUES (33296, 'Base Worker', 26.00, 2, 2, 8, 20011, '2023-06-07 18:19:44', '2023-06-07 18:19:44');
INSERT INTO `proposal_detail_labor` VALUES (33298, 'Concrete Cutting, No Skid Steer, Min 1,000', 100.00, 3, 2, 8, 20011, '2023-06-13 14:40:48', '2023-06-13 14:40:48');
INSERT INTO `proposal_detail_labor` VALUES (33299, 'Specialty Concrete (Advanced Concrete Cutting)', 50.00, 1, 2, 8, 20011, '2023-06-13 14:42:28', '2023-06-13 14:42:28');
INSERT INTO `proposal_detail_labor` VALUES (32944, 'Concrete Cutting, w/ Skid Steer, Min 1,500', 150.00, 1, 1, 8, 20011, '2023-06-13 16:33:53', '2023-06-13 16:33:53');
INSERT INTO `proposal_detail_labor` VALUES (33303, 'Base Worker', 26.00, 2, 3, 8, 20011, '2023-06-14 14:46:02', '2023-06-14 14:46:02');
INSERT INTO `proposal_detail_labor` VALUES (32948, 'Advanced Concrete Cutting, w/ Bobcat, Min 1,200', 75.00, 2, 2, 8, 20011, '2023-06-14 17:22:21', '2023-06-14 17:22:21');
INSERT INTO `proposal_detail_labor` VALUES (32950, 'Base Worker', 26.00, 2, 2, 8, 20011, '2023-06-14 17:35:44', '2023-06-14 17:35:44');
INSERT INTO `proposal_detail_labor` VALUES (33306, 'Crew Member', 28.00, 2, 2, 8, 20011, '2023-06-15 01:32:50', '2023-06-15 01:32:50');
INSERT INTO `proposal_detail_labor` VALUES (33307, 'Base Worker', 26.00, 2, 2, 8, 20011, '2023-06-17 12:07:27', '2023-06-17 12:07:27');

INSERT INTO `proposal_detail_equipment` VALUES (33296, 80, 20011, 8, 2, 2, 'per hour', 0.94, '2023-06-07 18:06:09', '2023-06-07 18:06:09');
INSERT INTO `proposal_detail_equipment` VALUES (33296, 68, 20011, 8, 1, 1, 'per hour', 0.42, '2023-06-07 18:20:21', '2023-06-07 18:20:21');
INSERT INTO `proposal_detail_equipment` VALUES (33297, 79, 20011, 8, 1, 1, 'per hour', 0.26, '2023-06-08 12:20:01', '2023-06-08 12:20:01');
INSERT INTO `proposal_detail_equipment` VALUES (33298, 81, 20011, 8, 1, 1, 'per hour', 7.81, '2023-06-13 14:40:52', '2023-06-13 14:40:52');
INSERT INTO `proposal_detail_equipment` VALUES (33299, 70, 20011, 8, 1, 1, 'per hour', 7.81, '2023-06-13 14:42:17', '2023-06-13 14:42:17');
INSERT INTO `proposal_detail_equipment` VALUES (32944, 68, 20011, 8, 2, 1, 'per hour', 0.42, '2023-06-13 16:32:55', '2023-06-13 16:32:55');
INSERT INTO `proposal_detail_equipment` VALUES (33303, 79, 20011, 8, 3, 1, 'per hour', 0.26, '2023-06-14 14:45:51', '2023-06-14 14:45:51');
INSERT INTO `proposal_detail_equipment` VALUES (33304, 68, 20011, 8, 2, 2, 'per hour', 0.42, '2023-06-14 17:33:55', '2023-06-14 17:33:55');



INSERT INTO `proposal_detail_additional_costs` VALUES (33296, 20011, 23.00, 'Other', 'test', '2023-06-07 18:23:00', '2023-06-07 18:23:00');
INSERT INTO `proposal_detail_additional_costs` VALUES (33296, 20011, 3243.00, 'Dump Fee', 'test', '2023-06-15 12:18:07', '2023-06-15 12:18:07');
INSERT INTO `proposal_detail_additional_costs` VALUES (33299, 20011, 343.00, 'Other', 'test', '2023-06-13 14:42:44', '2023-06-13 14:42:44');
INSERT INTO `proposal_detail_additional_costs` VALUES (33303, 20011, 344.00, 'Dump Fee', 'test', '2023-06-14 14:46:12', '2023-06-14 14:46:12');

