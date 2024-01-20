

#DELETE XTRA Herb Trevathan from Contacts
DELETE From crmtblcontacts Where cntFirstName ='Herb' AND cntIsEmployee = 1 AND cntStatusId =0;
UPDATE `crmtblcontacts` Set cntCreatedBy = 10 WHERE cntCreatedBy = 100;
UPDATE `crmtblcontacts` Set cntCreatedBy = 10 WHERE cntCreatedBy = 2396;
UPDATE `crmtblcontacts` Set cntCreatedBy = 54 WHERE cntCreatedBy = 8341;
UPDATE `crmtblcontacts` Set cntCreatedBy = 55 WHERE cntCreatedBy = 8617;
UPDATE `crmtblcontacts` Set cntCreatedBy = 18 WHERE cntCreatedBy = 852;
UPDATE `crmtblcontacts` Set cntCreatedBy = 42 WHERE cntCreatedBy = 11629;
UPDATE `crmtblcontacts` Set cntCreatedBy = 21 WHERE cntCreatedBy = 2672;
UPDATE `crmtblcontacts` Set cntCreatedBy = 57 WHERE cntCreatedBy = 10840;
UPDATE `crmtblcontacts` Set cntCreatedBy = 10 WHERE cntCreatedBy = 1;
UPDATE `crmtblcontacts` Set cntCreatedBy = 15 WHERE cntCreatedBy = 684;
UPDATE `crmtblcontacts` Set cntCreatedBy = 21 WHERE cntCreatedBy = 903;
UPDATE `crmtblcontacts` Set `cntPrimaryAddress1` = "123" WHERE cntID = 679;
UPDATE `crmtblcontacts` Set cntIsEmployee = 1 WHERE `cntId` = 676;
UPDATE `crmtblcontacts` Set cntcid = 18 WHERE `cntcid` = 6;
UPDATE `crmtblcontacts` Set cntcid = 12 WHERE `cntId` = 840;
UPDATE crmtblcontacts 
LEFT JOIN users ON users.id = crmtblcontacts.cntCreatedBy 
SET crmtblcontacts.cntCreatedBy = 10 
WHERE crmtblcontacts.cntcid NOT IN(2, 11, 19, 9, 20) 
AND users.id IS NULL;

UPDATE potbljoborders Set jobAddress1 = 'UNKNOWN' WHERE jobAddress1 is null AND jobId = 5058; 

DELETE From crmtblcontacts Where cntFirstName LIKE '%DEMO%' AND cntIsEmployee = 1; 

SELECT COUNT(crmtblcontacts.cntId), crmtblcontacts.cntCreatedBy 
FROM crmtblcontacts LEFT JOIN users ON users.id = crmtblcontacts.cntCreatedBy 
WHERE crmtblcontacts.cntcid NOT IN(2, 11, 19, 9, 20) AND users.id IS NULL 
GROUP BY crmtblcontacts.cntCreatedby, crmtblcontacts.cntCreatedBy, users.id 
ORDER BY COUNT(crmtblcontacts.cntId) DESC;

    
#USE HOME CONTROLLER TO CREATE USERS

#realign employees to have their old id
UPDATE users Set id = id*20011 where old_id = 0;
UPDATE users Set id = id*2011 where old_id > 0;
UPDATE users set id = old_id where old_id > 0;

UPDATE users set role_id = 4 where role_id = 3;
UPDATE users set role_id = 3 where role_id = 2;
UPDATE users set role_id = 2 where role_id = 6;
UPDATE users set role_id = 6 where role_id = 5;

DELETE FROM users where id =2;

#2. Alter old tables to hold some new ids
ALTER TABLE `crmtblcontacts` 
MODIFY cntCreatedDate DateTime NULL,
ADD `contact_id` INT NULL DEFAULT '0' AFTER `cntlogout`, 
ADD `user_id` INT NULL DEFAULT '0' AFTER `cntlogout`, 
ADD `location_id` INT NULL AFTER `cntlogout`, 
ADD `contractor_id` INT NULL AFTER `cntlogout`;


ALTER TABLE `wolkpjoborderchecklist` ADD `service_id` INT NULL AFTER `clCheckList`;


#SELECT COUNT(p.jobID), c.cntcid FROM `potbljoborders` p JOIN crmtblcontacts c ON c.cntId = p.jobManagerID WHERE p.jobManagerID > 0 AND c.cntIsEmployee = 0 GROUP BY c.cntcid Order By COUNT(p.jobID)

#alter proposals add fields
ALTER TABLE `potbljoborders`  
ADD `location_id` INT NULL AFTER `jobProjectDate`;


#add fields to job details
ALTER TABLE `potbljoborderdetail` 
ADD `location_id` INT NULL AFTER `jordCustomNote`,
ADD `status_id` INT NULL AFTER `jordCustomNote`, 
ADD `vendor_id` INT NULL AFTER `jordCustomNote`; 

ALTER TABLE datatblmultivendorpricing ADD `service_id` INT NOT NULL AFTER `SERVICE`;


#save web config
INSERT INTO web_configs (`KEY`,`VALUE`) SELECT `cfgKey`,`cfgValue` FROM `syswebconfig`;
#setup backup for config
INSERT INTO web_config_bak (`KEY`,`VALUE`) SELECT `cfgKey`,`cfgValue` FROM `syswebconfig`;
#INSERT INTO web_configs (`KEY`,`VALUE`) VALUES('webSitetitle','3-DPaving.com');

# Update new employee id to use 
UPDATE crmtblcontacts c inner join users u on c.cntId = u.old_id set c.user_id = u.id;

#setup terms
INSERT INTO terms (
text,
section,
title
)
Select 
TCText,
TCSection,
TCTitle
from datalkptc;

#Doctypes
INSERT INTO media_types
(type,
section,
old_id
)
SELECT 
PODocTypeName,
PODocTypeSection,
PODocTypeID
FROM polkpdocumenttypes;

UPDATE media_types Set id = id * 223;
Update media_types set id = old_id;

#system doc types
INSERT INTO accepted_documents
(
old_id,
type,
Description,
extension
)
SELECT 
docid,
docType,
docDesc,
docAllowedExtension
FROM sysdocumenttypes;

UPDATE accepted_documents Set id = id * 223;
UPDATE accepted_documents set id = old_id;
Update  accepted_documents set extension = 'gif,jpg,png,jpeg' where type ='Image';


#XXXXX


#CHECK create 16 contractors from crm contacts
#SELECT o.posubVendorID, c.cntFirstName, c.cntLastName, c.cntcid FROM potbljobordersubcontractors o JOIN crmtblcontacts c on c.cntId = o.posubVendorID WHERE c.cntcid = 11 AND cntFirstName NOT LIKE '%(DO NOT USE)%';

INSERT INTO contractors 
        (name,contact,email,phone,address_line1, address_line2,city,state,postal_code,old_id, contractor_type_id)
SELECT
    CONCAT(
        c.cntFirstName,
        " ",
        c.cntLastName
    ),
    c.cntAltContactName,
    cntPrimaryEmail,
    cntPrimaryPhone,
    cntPrimaryAddress1,
    cntPrimaryAddress2,
    cntPrimaryCity,
    cntPrimaryState,
    cntPrimaryZip,
    c.cntId,
    1
FROM
    crmtblcontacts c
WHERE
    c.cntcid IN(11,2) AND c.cntFirstName NOT LIKE "%(DO NOT USE)%";


#special contractors
INSERT INTO contractors 
        (old_id, name,contact,email,phone,address_line1, address_line2,city,state,postal_code,contractor_type_id)
SELECT DISTINCT cntId, 
    CONCAT(
        c.cntFirstName,
        " ",
        c.cntLastName
    ),
    c.cntAltContactName,
    cntPrimaryEmail,
    cntPrimaryPhone,
    cntPrimaryAddress1,
    cntPrimaryAddress2,
    cntPrimaryCity,
    cntPrimaryState,
    cntPrimaryZip,
    1
FROM
    crmtblcontacts c
WHERE
 c.cntcid <> 19 AND
 c.cntId IN(690,790);


UPDATE contractors Set id = id*2322;
UPDATE contractors Set id = old_id;


#xxxxxxx

#start vendors



INSERT INTO vendors 
        (name,contact,email,phone,address_line1, address_line2,city,state,postal_code,old_id, vendor_type_id)
SELECT
    CONCAT(
        c.cntFirstName,
        " ",
        c.cntLastName
    ),
    c.cntAltContactName,
    cntPrimaryEmail,
    cntPrimaryPhone,
    cntPrimaryAddress1,
    cntPrimaryAddress2,
    cntPrimaryCity,
    cntPrimaryState,
    cntPrimaryZip,
    c.cntId,
    2
FROM
    crmtblcontacts c
WHERE
    c.cntcid = 19 AND c.cntFirstName NOT LIKE "%(DO NOT USE)%";


#special vendors
INSERT INTO vendors 
        (old_id, name,contact,email,phone,address_line1, address_line2,city,state,postal_code,vendor_type_id)
SELECT DISTINCT cntId, 
    CONCAT(
        c.cntFirstName,
        " ",
        c.cntLastName
    ),
    c.cntAltContactName,
    cntPrimaryEmail,
    cntPrimaryPhone,
    cntPrimaryAddress1,
    cntPrimaryAddress2,
    cntPrimaryCity,
    cntPrimaryState,
    cntPrimaryZip,
    2
FROM
    crmtblcontacts c
WHERE
    c.cntcid = 19 AND c.cntId IN(690,790);



UPDATE vendors Set id = id*2322;
UPDATE vendors Set id = old_id;



#end vendors
#check
SELECT
    potbljoborders.jobcntID,
    crmtblcontacts.cntFirstName,
    crmtblcontacts.cntcid,
    potbljoborders.jobCreatedDateTime,
    contacts.id
FROM
    `potbljoborders`    
JOIN contacts ON contacts.id = potbljoborders.jobcntID
JOIN crmtblcontacts ON crmtblcontacts.cntId = potbljoborders.jobcntID
WHERE
    crmtblcontacts.cntcid = 1 
    AND YEAR(potbljoborders.jobCreatedDateTime) > 2019
ORDER BY
    `crmtblcontacts`.`cntcid` ASC;

    
#push back id to crmtblcontacts
UPDATE crmtblcontacts c 
JOIN vendors d on d.old_id = c.cntId
SET c.contractor_id = d.id;

UPDATE crmtblcontacts set cntCreatedDate = null where
YEAR(cntCreatedDate) < 2019;


# move customers to new contacts table
    
#contacts
INSERT INTO contacts(
    contact_type_id,
    first_name,
    related_to,
    email,
    phone,
    alt_email,
    alt_phone,
    address1,
    address2,
    city,
    postal_code,
    state,
    billing_address1,
    billing_address2,
    billing_city,
    billing_state,
    billing_postal_code,
    contact,
    note,
    created_by,
    old_id,
   created_at
)
SELECT
    c.cntcid,
    c.cntFirstName,
    c.cntCompanyId,
    c.cntPrimaryEmail,
    c.cntPrimaryPhone,
    c.cntAltEmail,
    c.cntAltPhone1,
    c.cntPrimaryAddress1,
    c.cntPrimaryAddress2,
    c.cntPrimaryCity,
    c.cntPrimaryZip,
    c.cntPrimaryState,
    c.cntBillAddress1,
    c.cntBillAddress2,
    c.cntBillCity,
    c.cntBillState,
    c.cntBillZip,
    c.cntAltContactName,
    c.cntComment,
    c.cntCreatedBy,
    c.cntId,
from_unixtime(unix_timestamp(c.cntCreatedDate))
FROM
    crmtblcontacts c
WHERE
     c.cntcid NOT IN(2,11,19,9,20)  
     AND c.cntIsEmployee = 0 
     AND c.cntFirstName NOT LIKE "%Do Not Use%" 
     AND c.cntFirstName NOT LIKE "%DO NOT USE%"  
     AND c.cntFirstName <> "" 
     AND c.cntFirstName <> "0" 
     AND c.cntFirstName is not null 
     AND c.cntPrimaryAddress1 is not null;

#insert 

    SET FOREIGN_KEY_CHECKS=0;
     
     INSERT INTO contacts(
         contact_type_id,
         first_name,
         related_to,
         email,
         phone,
         alt_email,
         alt_phone,
         address1,
         address2,
         city,
         postal_code,
         state,
         billing_address1,
         billing_address2,
         billing_city,
         billing_state,
         billing_postal_code,
         contact,
         note,
         created_by,
         old_id,
        created_at
     )
SELECT DISTINCT
    18,
    c.cntFirstName,
    c.cntCompanyId,
    c.cntPrimaryEmail,
    c.cntPrimaryPhone,
    c.cntAltEmail,
    c.cntAltPhone1,
    c.cntPrimaryAddress1,
    c.cntPrimaryAddress2,
    c.cntPrimaryCity,
    c.cntPrimaryZip,
    c.cntPrimaryState,
    c.cntBillAddress1,
    c.cntBillAddress2,
    c.cntBillCity,
    c.cntBillState,
    c.cntBillZip,
    c.cntAltContactName,
    c.cntComment,
    c.cntCreatedBy,
    c.cntId,
    FROM_UNIXTIME(
        UNIX_TIMESTAMP(c.cntCreatedDate)
    )
FROM
    crmtblcontacts c
JOIN potbljoborders p ON
    p.jobcntID = c.cntId
LEFT JOIN contacts cf ON
    cf.old_id = p.jobcntID
WHERE
    cf.old_id IS NULL 
    AND c.cntIsEmployee = 0 
    AND c.cntFirstName NOT LIKE "%Do Not Use%" 
    AND c.cntFirstName NOT LIKE "%DO NOT USE%" 
    AND c.cntFirstName <> "" 
    AND c.cntFirstName <> "0" 
    AND c.cntFirstName IS NOT NULL 
    AND c.cntPrimaryAddress1 IS NOT NULL;
    
SET FOREIGN_KEY_CHECKS=1;


UPDATE `contacts` 
LEFT join users on users.id = contacts.created_by 
Set contacts.created_by  = 10 
Where users.id is null;

#xxxxxx
#CHECK
#SELECT COUNT(crmtblcontacts.cntcid), crmtblcontacts.cntcid FROM crmtblcontacts JOIN potbljoborders ON potbljoborders.jobcntID = crmtblcontacts.cntId GROUP BY crmtblcontacts.cntcid ORDER BY crmtblcontacts.cntcid;

#CHECK
#SELECT `cntcid`, `cntFirstName`, `cntCreatedDate`, `cntPrimaryEmail`, `cntPrimaryPhone`, `cntAltEmail`, `cntAltPhone1`, `cntPrimaryAddress1`, `cntPrimaryAddress2`, `cntPrimaryCity`, `cntPrimaryZip`, `cntPrimaryState`, `cntBillAddress1`, `cntBillAddress2`, `cntBillCity`, `cntBillState`, `cntBillZip`, `cntAltContactName`, `cntComment`, `cntCreatedBy`, `cntId` FROM `crmtblcontacts` WHERE YEAR ( `cntCreatedDate` ) > 2019 AND `cntcid` IN ( 1, 7, 10, 14, 16, 17, 18 ) Order By cntcid; 

    
#realign contact id to their old id crm id
UPDATE contacts set id = id * 15233;
UPDATE contacts set id = old_id;
UPDATE contacts set id = contact_type_id = 18 where contact_type_id = 6;

#START HERE
 
    
# NOTE: In the old database addresses locations are saved many times, we are trying to collapse all those entries into a 'locations' table based on address1.
#This will NOT eliminate all duplicates but it is a start.

# populate locations , only work orders from the last 4 years
# these are primary locations from proposals that were converted to work orders
INSERT INTO locations2(
    address_line1,
    address_line2,
    city,
    state,
    postal_code,
    parcel_number,
    original_table,
    original_id
)
SELECT
    jobAddress1,
    jobAddress2,
    jobCity,
    jobState,
    jobZip,
    jobParcel,
    "potbljoborders",
    jobID
FROM
    potbljoborders
WHERE
    YEAR(potbljoborders.jobCreatedDateTime) > 2019 
    AND jobZip is not null 
    AND potbljoborders.jobMasterID > 0 
    AND potbljoborders.jobAddress1 IS NOT NULL 
    AND TRIM(potbljoborders.jobAddress1) <> ''
    AND potbljoborders.jobAddress1 <> 'na';
    
    

#now get primary locations for all open proposals from current year

INSERT INTO locations2(
    address_line1,
    address_line2,
    city,
    state,
    postal_code,
    parcel_number,
    original_table,
    original_id
)
SELECT
    jobAddress1,
    jobAddress2,
    jobCity,
    jobState,
    jobZip,
    jobParcel,
    "potbljoborders",
    jobID
FROM
    potbljoborders
WHERE YEAR(potbljoborders.jobCreatedDateTime) >= 2019 
AND potbljoborders.jobMasterID = 0 
AND TRIM(potbljoborders.jobAddress1) <> '' 
AND jobZip is not null 
AND potbljoborders.jobAddress1 IS NOT NULL 
AND potbljoborders.jobAddress1 <> 'na'; 


INSERT INTO locations2(
    address_line1,
    address_line2,
    city,
    state,
    postal_code,
    original_table,
    original_id
)
SELECT
    jobBillingAddress1,
    jobBillingAddress2,
    jobBillingCity,
    jobBillingState,
    jobBillingZip,
    "potbljoborders",
    jobID
FROM
    potbljoborders
WHERE YEAR(potbljoborders.jobCreatedDateTime) >= 2019 
AND TRIM(potbljoborders.jobBillingAddress1) <> '' 
AND jobBillingZip is not null 
AND potbljoborders.jobBillingAddress1 IS NOT NULL 
AND potbljoborders.jobBillingAddress1 <> 'na'; 
    

#now get locations from job details

INSERT INTO locations2 (
    address_line1,
    address_line2,
    city,
    state,
    postal_code,
    parcel_number,
    original_table,
    original_id
)
SELECT
    jordAddress1,
    jordAddress2,
    jordCity,
    jordState,
    jordZip,
    jordParcel,
    "potbljoborderdetail",
    jordID
FROM
    potbljoborderdetail
WHERE
potbljoborderdetail.jordZip is not null
AND TRIM(potbljoborderdetail.jordAddress1) <> '' 
AND potbljoborderdetail.jordAddress1 IS NOT NULL;

#insert locations from contacts 

INSERT INTO locations2(
    address_line1,
    address_line2,
    city,
    state,
    postal_code,
    location_type_id,
    original_table,
    original_id
)
SELECT
    cntPrimaryAddress1,
    cntPrimaryAddress2,
    cntPrimaryCity,
    cntPrimaryState,
    cntPrimaryZip,
    cntcid,
    "crmtblcontacts",
    cntId
FROM
    crmtblcontacts
WHERE
    cntPrimaryZip IS NOT NULL 
    AND TRIM(cntPrimaryAddress1) <> '' 
    AND cntPrimaryAddress1 <> 'na' 
    AND cntPrimaryAddress1 IS NOT NULL;
    
    

# Compact many addresses into one (there are many duplicates)
INSERT INTO locations(
    address_line1,
    address_line2,
    state,
    city,
    postal_code,
    location_type_id,
    parcel_number
)
SELECT
    address_line1,
    address_line2,
    state,
    UPPER(city),
    postal_code,
    location_type_id,
    parcel_number
FROM
    locations2
WHERE 
    locations2.postal_code is not null AND locations2.address_line1 <> ''
GROUP BY
    address_line1
ORDER BY
    City;
    
    
    
    
    Update locations2 
    JOIN locations on locations.address_line1 = locations2.address_line1 
    set locations2.new_id = locations.id;
    
    Select * from locations2 where new_id is null;


    Update potbljoborders 
    JOIN locations2 on locations2.original_id = potbljoborders.jobID
    Set potbljoborders.location_id = locations2.new_id
    WHERE locations2.original_table ='potbljoborders';
    
    SELECT * From locations2 where original_table = 'potbljoborders';
    
    
    Update crmtblcontacts 
    JOIN locations2 on locations2.original_id = crmtblcontacts.cntId
    Set crmtblcontacts.location_id = locations2.new_id
    WHERE locations2.original_table ='crmtblcontacts';
    
    
    Update potbljoborderdetail 
    JOIN locations2 on locations2.original_id = potbljoborderdetail.jordID
    Set potbljoborderdetail.location_id = locations2.new_id
WHERE locations2.original_table ='potbljoborderdetail';




#CHECKfind orders without a location id

	#SELECT jobID From potbljoborders where jobID = 6289;
	#SELECT jobID From potbljoborders where jobID = 6290;
	#SELECT jobID From potbljoborders where jobID = 6291;
	#SELECT jobID From potbljoborders where jobID = 6293;
	#SELECT jobID From potbljoborders where jobID = 6296;
	#SELECT jobID From potbljoborders where jobID = 7109;
	


	Delete From potbljoborders where jobID = 6289;
	Delete From potbljoborders where jobID = 6290;
	Delete From potbljoborders where jobID = 6291;
	Delete From potbljoborders where jobID = 6293;
	Delete From potbljoborders where jobID = 6296;
	Delete From potbljoborders where jobID = 7109;
	
	Select * from potbljoborders where jobAddress1 is not null AND jobAddress1 <> 'na' AND TRIM(jobAddress1) <> '' AND location_id is null AND YEAR(potbljoborders.jobCreatedDateTime) > 2019;
    
#D)D)D)

#CHECKfind contacts without a location id
#Select * from crmtblcontacts where cntIsEmployee = 0 AND YEAR(cntCreatedDate) > 2019 and location_id is null;
    
    ##START HERE
#CHECK find order details without a location id
#Select * from potbljoborderdetail where location_id is null;

#SELECT COUNT(`jobcntID`), YEAR(`jobCreatedDateTime`) FROM `potbljoborders` WHERE YEAR(`jobCreatedDateTime`) > 2019  GROUP BY YEAR(`jobCreatedDateTime`);


#check 24
Select * from potbljoborders 
JOIN contacts on contacts.id = potbljoborders.jobcntID
JOIN crmtblcontacts on crmtblcontacts.cntId = potbljoborders.jobcntID
where YEAR(potbljoborders.jobCreatedDateTime) > 2019;

#CHECK find work orders without a new contact

SELECT
    *
FROM
    potbljoborders j
LEFT JOIN contacts c ON
    c.id = j.jobcntID
WHERE
    YEAR(j.jobCreatedDateTime) > 2019 AND c.id IS NULL AND j.jobMasterID > 0
ORDER BY
    j.JobID
DESC;


#Normalize counties
#add missing zips / counties
INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33082', '', '', 'FL', 'Pembroke Pines', 'Broward');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33077', '', '', 'FL', 'Pompano Beach', 'Broward');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33256', '25.64856100', '-80.33181400', 'FL', 'Miami', 'Dade');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33571', '', '', 'FL', 'Sun City Center', 'Hillsborough');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33075', '26.27007600', '-80.25176800', 'FL', 'Pompano Beach', 'Broward');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33336', '26.16540900', '-80.16901400', 'Fl', 'Fort Lauderdale', 'Broward');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33422', '26.73437500', '-80.11828100', 'FL', 'West Palm beach', 'Palm Beach');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33482', '26.46513600', '-80.12255200', 'FL', 'Delray Beach ', 'Palm Beach');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '32258', '30.14475', '-81.540956', 'FL', 'Jacksonville', 'Duval');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33318', '26.1216', '-80.1439', 'FL', 'Ft. Lauderdale', 'Broward');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33329', '26.0645', '-80.2322', 'FL', 'Ft. Lauderdale', 'Broward');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33340', '26.1216', '-80.1439', 'FL', 'Ft. Lauderdale', 'Broward');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33421', '26.7132', '-80.0723', 'FL', 'Royal Palm Beach', 'Palm Beach');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33416', '26.1216', '-80.1439', 'FL', 'West Palm Beach', 'Palm Beach');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33424', '26.5253', '-80.0668', 'FL', 'Boynton Beach', 'Palm Beach');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33481', '26.3583', '-80.0835', 'FL', 'Boca Raton', 'Palm Beach');


INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33488', '26.3774', '-80.0986', 'FL', 'Boca Raton', 'Palm Beach');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33497', '26.3583', '-80.0835', 'FL', 'Boca Raton', 'Palm Beach');


INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33587', '26.9635', '-80.2063', 'FL', 'Sydney', 'Hillsborough');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33488', '26.3774', '-80.0986', 'FL', 'Boca Raton', 'Palm Beach');

INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33394', '26.1216', '-80.1439', 'FL', 'Ft. Lauderdale', 'Broward');


INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33341', '26.3583', '-80.0835', 'FL', 'Boca Raton', 'Palm Beach');
INSERT INTO `counties` (`id`, `zip`, `lat`, `lng`, `state`, `city`, `county`) VALUES (NULL, '33255', '26.3583', '-80.0835', 'FL', 'Miami', 'Dade');

 	



Update contacts set postal_code = '33125' where postal_code ='33255';
Update contacts set postal_code = '33125' where postal_code ='33343';
Update contacts set postal_code = '33323' where postal_code ='33423';
Update contacts set postal_code = '33431' where postal_code ='33341';
Update contacts set postal_code = '33936' where postal_code ='33963';
Update contacts set postal_code = '33255' WHERE postal_code ='33258';

 
Update contacts set postal_code = '33431' where city ='Boca Raton' and postal_code ='33343';
Update contacts set postal_code = '33431' where city ='Boca Raton' and postal_code ='33341';
Update contacts set postal_code = '33441' where city ='Deerfield Beach' and postal_code ='33341';

#update counties in contacts
UPDATE contacts p JOIN counties c on c.zip = p.postal_code Set p.county = c.county; 

#check any missing zips that we might find
SELECT city, postal_code, count(*) FROM `contacts` WHERE postal_code <> '' 
AND LEFT(postal_code,2) = '33' 
AND postal_code is not null 
AND LENGTH(postal_code) = 5 
AND county is null 
Group By city, postal_code 
Order by postal_code;

#Update County on locations
UPDATE locations l
JOIN counties c on c.zip = l.postal_code
SET l.county = c.county;




#insert into services
Insert into services (old_service_cat,name,description,service_text_en,service_text_es,service_template, old_id)  
SELECT `cmpServiceCat`,`cmpServiceName`,`cmpServiceDesc`,`cmpProposalTextAlt`,`cmpProposalTextAltES`,`cmpProposalText`,`cmpServiceID` FROM `datatblcompanyservices` ;
    

UPDATE services set id = id * 232;
UPDATE services set id = old_id;


INSERT into office_locations
(
name,
address,
city,
state,
zip,
phone,
manager
)
SELECT 
locName,
locAddress,
locCity,
locState,
locZip,
locPhone,
locManager
FROM datatbllocations;

##EEEEEE

#update service id
UPDATE services s
JOIN service_categories sc
On s.old_service_cat = sc.name
Set s.service_category_id = sc.id;


#create Order status
INSERT INTO proposal_statuses (status) SELECT ordStatus from datalkporderstatus;
INSERT INTO proposal_statuses (status) VALUES('Paid');
#create proposals

SELECT * FROM potbljoborders 
Where jobSalesManagerAssigned = 0;

SELECT * FROM  potbljoborders 
Where jobSalesManagerAssigned = 1021;

# STOP !!!!

UPDATE potbljoborders 
Set jobSalesManagerAssigned = null Where jobSalesManagerAssigned = 0;
UPDATE potbljoborders 
Set jobSalesManagerAssigned = null Where jobSalesManagerAssigned = 1021;


SELECT * From potbljoborders 
Where jobSalesAssigned = 0
AND YEAR(jobCreatedDateTime) > 2019 ;
SELECT * From potbljoborders 
Where jobSalesAssigned = 1022
AND YEAR(jobCreatedDateTime) > 2019 ;

#STOP !!!

UPDATE potbljoborders 
Set jobSalesAssigned = null Where jobSalesAssigned = 0
AND YEAR(jobCreatedDateTime) > 2019;
UPDATE potbljoborders 
Set jobSalesAssigned = null Where jobSalesAssigned = 1022
AND YEAR(jobCreatedDateTime) > 2019;

#make jobcntId nullable 
ALTER TABLE potbljoborders CHANGE jobcntId jobcntId INTEGER(11) DEFAULT NULL;

SELECT * FROM potbljoborders 
Where (jobcntId = 0
OR jobcntId is null)
AND YEAR(jobApprovedDate) > 2019;

#XXXXX STOP !!!!

#make jobcntId nullable 
UPDATE potbljoborders 
Set jobcntId = null Where jobcntId = 0
AND YEAR(jobApprovedDate) > 2019;

#make all orpahned number 1

SELECT * FROM potbljoborders
LEFT JOIN contacts on contacts.id = potbljoborders.jobcntID
Where contacts.id is null
AND YEAR(jobApprovedDate) > 2019;
#XXXXX STOP !!!!


UPDATE potbljoborders
LEFT JOIN contacts on contacts.id = potbljoborders.jobcntID
Set potbljoborders.jobcntID = 1
Where contacts.id is null;


Select * FROM `contacts` WHERE id = 1;

#insert a number 1
INSERT INTO `contacts` (`id`, `contact_type_id`, `first_name`, `last_name`, `email`, `phone`, `alt_email`, `alt_phone`, `address1`, `address2`, `city`, `postal_code`, `state`, `county`, `billing_address1`, `billing_address2`, `billing_city`, `billing_postal_code`, `billing_state`, `contact`, `note`, `related_to`, `created_by`, `old_id`, `created_at`, `updated_at`, `deleted_at`) VALUES ('1', '18', 'demo do not use', NULL, NULL, NULL, NULL, NULL, '123 street', NULL, NULL, NULL, 'FL', NULL, NULL, NULL, NULL, NULL, 'FL', NULL, NULL, '0', '10', '1', NULL, NULL, NULL);


INSERT INTO proposals (old_id, name,job_master_id,location_id,created_by,contact_id,
customer_staff_id,salesmanager_id,salesperson_id, proposal_statuses_id, 
sale_date,proposal_date, nto_required, permit_required, mot_required, created_at)
SELECT
    `jobID`,
    `jobName`,
    CONCAT(
        wotbljobmaster.jobMasterYear,
        ":",
        wotbljobmaster.jobMasterMonth,
        ":",
        wotbljobmaster.jobMasterNumber
    ) AS workOrderNumber,
    `location_id`,
    `jobCreatedBy`,
    `jobcntID`,
    `jobManagerID`,
    `jobSalesManagerAssigned`,
    `jobSalesAssigned`,
    `jobStatus`,
    jobApprovedDate,
    jobCreatedDateTime,
    jobNTO,
    jobPermit,
    jobMOT,
    from_unixtime(unix_timestamp(jobCreatedDateTime))    
FROM
    `potbljoborders`
LEFT JOIN wotbljobmaster ON wotbljobmaster.jobMasterID = potbljoborders.jobMasterID
WHERE
    (YEAR(
        potbljoborders.jobCreatedDateTime
    ) > 2019 OR YEAR(jobApprovedDate) > 2019)
    AND potbljoborders.jobcntid <> 0;
    
    


#UPDATE `proposals` SET created_at = from_unixtime(unix_timestamp(proposal_date)) 


#realign proposal ID
UPDATE
    proposals
SET
    id = id * 42133;
    
UPDATE
    proposals
SET
    id = old_id;
        
    
#XXXXXX
# TODO set milestones what is current status

#SELECT `jobID`, CONCAT( wotbljobmaster.jobMasterMonth, ":", wotbljobmaster.jobMasterYear, ":", wotbljobmaster.jobMasterNumber ) AS workOrderNumber, `new_location_id`, `new_manager_id`, `contact_id`, `new_sales_id`, `new_sales_manager_id`, `created_by_id` FROM `potbljoborders` LEFT JOIN wotbljobmaster ON wotbljobmaster.jobMasterID = potbljoborders.jobMasterID WHERE YEAR( potbljoborders.jobCreatedDateTime ) > 2019 ;

#Now Update proposal detail status a set of queries based on the old fields
UPDATE potbljoborderdetail
JOIN proposal_detail_statuses ON proposal_detail_statuses.status = potbljoborderdetail.jordStatus
Set potbljoborderdetail.status_id = proposal_detail_statuses.id;

#TODO Update proposal detail vendor id
#x3x3x3

#UPDATE with status id potbljoborderdetail

#make jordvendor id nullable
ALTER TABLE potbljoborderdetail CHANGE jordVendorID jordVendorID INTEGER(11) DEFAULT NULL;


UPDATE potbljoborderdetail set jordVendorID = null where jordVendorID = 0; 
UPDATE potbljoborderdetail set jordVendorID = null where jordVendorID = 433; 
UPDATE potbljoborderdetail set jordVendorID = null where jordVendorID = 692; 

#SELECT count(potbljoborderdetail.jordID), potbljoborderdetail.jordVendorID FROM potbljoborderdetail left join contractors on contractors.id = potbljoborderdetail.jordVendorID WHERE contractors.id is null Group by potbljoborderdetail.jordVendorID ;

# create new proposal details
INSERT INTO proposal_details (
proposal_id,
services_id,
service_name,
service_desc,
linear_feet,
cost_per_linear_feet,
square_feet,
square_yards,
cubic_yards,
tons,
loads,
locations,
depth,
profit,
days,
cost_per_day,
break_even,
primer,
yield,
fast_set,
additive,
sealer,
sand,
phases,
overhead,
cost,
dsort,
proposal_text,
proposal_note,
proposal_field_note,
fieldmanager_id,
second_fieldmanager_id,
start_date,
end_date,
status_id,
location_id,
scheduled_by,
created_by,
completed_by,
completed_date,
contractor_id,
old_status,
old_vendor_id,
old_detail_id,
old_proposal_id
)
SELECT 
jordJobid,
jordServiceID,
jordName,
jordVendorServiceDescription,
jordLinearFeet,
jordCostPerLF,
jordSquareFeet,
jordSQYards,
jordCubicYards,
jordTons,
jordLoads,
jordLocations,
jordDepthInInches,
jordProfit,
jordDays,
jordCostPerDay,
jordBreakeven,
jordPrimer,
jordYield,
jordFastSet,
jordAdditive,
jordSealer,
jordSand,
jordPhases,
jordOverhead,
jordCost,
jordSort,
jordProposalText,
jordNote,
jordCustomNote,
jordJobManagerID,
jordJobManagerID2, 
jordStartDate,
jordEndDate,
status_id,
potbljoborderdetail.location_id,
jordScheduledBy,
jordUpdatedBy,
jordCompletedBy,
jordCompletedDateTime,
jordVendorID,
jordStatus,
jordVendorID,
jordID,
jordJobID
FROM potbljoborderdetail
JOIN proposals ON proposals.id = potbljoborderdetail.jordJobID;


#REALIGN potbljoborderdetail with the new id
UPDATE proposal_details set id = id * 42333;
UPDATE proposal_details set id = old_detail_id;




#MOVE ADDITIONAL COSTS

INSERT INTO proposal_detail_additional_costs(
    proposal_detail_id,
    description,
    type,
    amount
)
SELECT
    jobcostjordID,
    jobcostDescription,
    jobcostTitle,
    jobcostAmount
FROM
    potbljoborderadditionalcosts
    JOIN proposal_details on proposal_details.id = potbljoborderadditionalcosts.jobcostjordID;





DELETE FROM potbljobordersubcontractors where potbljobordersubcontractors.posubVendorID is null;

#MOVE CONTRACTORS
ALTER TABLE potbljobordersubcontractors CHANGE posubVendorID posubVendorID INTEGER(11) DEFAULT NULL;
UPDATE potbljobordersubcontractors set posubVendorID = 690 where posubVendorID = 713; 
UPDATE potbljobordersubcontractors set posubVendorID = 690 where posubVendorID = 691; 
UPDATE potbljobordersubcontractors set posubVendorID = 3 where posubVendorID = 692; 
UPDATE potbljobordersubcontractors set posubVendorID = 3 where posubVendorID = 433;
UPDATE potbljobordersubcontractors set posubVendorID = NULL where posubVendorID = 0;

INSERT INTO proposal_detail_subcontractors(
proposal_detail_id,
contractor_id,
cost,
overhead,
havebid,
description
)
SELECT 
posubjordID,
posubVendorID,
posubCost,
posubOverHead,
posubHaveBid,
posubDescription
From potbljobordersubcontractors
JOIN potbljoborderdetail on potbljoborderdetail.jordID = potbljobordersubcontractors.posubjordID 
JOIN potbljoborders on potbljoborders.jobID = potbljoborderdetail.jordJobID 
WHERE YEAR(potbljoborders.jobCreatedDateTime) > 2019
AND posubVendorID is not null
AND posubVendorID <> 3;


#SELECT * FROM potbljobordersubcontractors 
#JOIN potbljoborderdetail on potbljoborderdetail.jordID = potbljobordersubcontractors.posubjordID 
#JOIN potbljoborders on potbljoborders.jobID = potbljoborderdetail.jordJobID 
#WHERE YEAR(potbljoborders.jobCreatedDateTime) > 2019 


#EQUIPMENT
INSERT INTO equipment (
name,
rate_type,
rate,
min_cost,
do_not_use,
old_id
)
SELECT
equipName,
equipRate,
equipCost,
equipMinCost,
1,
equipID
FROM 
datatblequipment;

UPDATE equipment set id = id * 4212;
UPDATE equipment set id = old_id;



#MOVE proposal detail EQUIPMENT 
INSERT INTO proposal_detail_equipment(
    proposal_detail_id,
    equipment_id,
    hours,
    days,
    number_of_units,
    rate_type,
    rate,
    created_by
)
select POequipjordID,
    POequipEquipmentID,
    POequipHoursDay,
    POequipDaysNeeded,
    POequipNumber,
    POequipRate,
    POequipCost,
    10
from potbljoborderequipment
join potbljoborderdetail on potbljoborderdetail.jordID = potbljoborderequipment.POequipjordID
join potbljoborders on potbljoborders.jobID =  potbljoborderdetail.jordJobID
WHERE YEAR(potbljoborders.jobCreatedDateTime) > 2019;





 
 
#CREATE LOOKUPS vehicles, Material Costs




#VEHICLES
INSERT INTO vehicles (
vehicle_types_id,
name,
description,
active, 
office_location_id,
old_id
)
SELECT
vehicleTypeID,
vehicleName,
vehicleDescription,
vehicleActive,
vehicleLocation,
vehicleID
FROM 
datatblcompanyvehicles;

UPDATE vehicles set id = id * 2121;
UPDATE vehicles set id = old_id;

#XXXXXX
# create striping table
INSERT INTO striping_services 
(name,dsort)
SELECT 
SERVICE,
DSORT 
FROM 
datatblstripingservices;

#update service-id in multivendor
UPDATE datatblmultivendorpricing
JOIN striping_services on striping_services.name = datatblmultivendorpricing.SERVICE
Set datatblmultivendorpricing.service_id = striping_services.id;




#striping rates
INSERT INTO striping_costs
(striping_service_id,
description, 
cost, 
old_id
)
SELECT 
service_id,
SERVICE_DESC,
STANDARD,
ScatID
FROM datatblmultivendorpricing;

UPDATE striping_costs set id = id * 231;
UPDATE striping_costs set id = old_id;



#proposal striping 

INSERT INTO proposal_detail_striping_services
(
proposal_detail_id,
striping_service_id,
quantity,
cost,
description,
name
)
SELECT 
potbljobordermultipricing.jobmultijordID, 
potbljobordermultipricing.jobmultiScatID,
potbljobordermultipricing.jobmultiQuantity,
potbljobordermultipricing.jobmultiCost,
potbljobordermultipricing.jobmultiSERVICE_DESC,
potbljobordermultipricing.jobmultiSERVICE
FROM
potbljobordermultipricing
join potbljoborderdetail on potbljoborderdetail.jordID = potbljobordermultipricing.jobmultijordID
join potbljoborders on potbljoborders.jobID =  potbljoborderdetail.jordJobID
WHERE YEAR(potbljoborders.jobCreatedDateTime) > 2019;



#vahicle types
INSERT INTO vehicle_types
(old_id,
name,
description,
rate
)
SELECT 
vtypeID,
vtypeName,
vtypeDescription,
vtypeRate
FROM datalkpvehicletypes;

UPDATE vehicle_types Set id = id * 212;
UPDATE vehicle_types Set id = old_id;

#update service _id in wolkpjoborderchecklist
UPDATE wolkpjoborderchecklist
JOIN service_categories on service_categories.name = wolkpjoborderchecklist.clServiceCat
SET wolkpjoborderchecklist.service_id = service_categories.id;

UPDATE wolkpjoborderchecklist
SET wolkpjoborderchecklist.service_id = 0 WHERE clServiceCat ='ALL'; 


INSERT INTO proposal_materials
(
proposal_id,
material_id,
name,
cost,
service_category_id
)
SELECT 
omatjobID,
omatMatID,
omatName,
omatCost,
0
FROM potblmaterialscost;



# create work order media
INSERT INTO proposal_media
(
proposal_id,
proposal_detail_id,
media_type_id,
description,
file_name,
file_type,
file_path,
original_name,
file_ext,
file_size,
IsImage,
image_width,
image_height,
admin_only
)
SELECT
jobmdJobID,
jobmdJordID,
jobmdDocumentTypeID,
jobmdDescription,
jobmdFileName,
jobmdfileType,
jobmdfilePath,
jobmdorigName,
jobmdfileExt,
jobmdfileSize,
jobmdisImage,
jobmdImageWidth,
jobmdImageHeight,
jobmdAdminOnly
FROM potbljobordermedia;


INSERT INTO proposal_detail_labor
(
proposal_detail_id,
labor_name,
rate_per_hour,
number,
days,
hours
)
SELECT
POlaborjordID,
POlaborName,
POlaborRate,
POlaborNumber,
POlaborDaysNeeded,
POlaborHoursDay
From potbljoborderlabor
join potbljoborderdetail on potbljoborderdetail.jordID = potbljoborderlabor.POlaborjordID
join potbljoborders on potbljoborders.jobID =  potbljoborderdetail.jordJobID
WHERE YEAR(potbljoborders.jobCreatedDateTime) > 2019;



INSERT INTO proposal_detail_vehicles
(
proposal_detail_id,
vehicle_id,
vehicle_name,
number_of_vehicles,
days,
hours,
rate_per_hour
)
SELECT
POVjordID,
POVvehicleTypeID,
POVvehicleName,
POVNumber,
POVDaysNeeded,
POVHoursDay,
POVRate
From potbljobordervehicles
join potbljoborderdetail on potbljoborderdetail.jordID = potbljobordervehicles.POVjordID
join potbljoborders on potbljoborders.jobID =  potbljoborderdetail.jordJobID
WHERE YEAR(potbljoborders.jobCreatedDateTime) > 2019;



#WIERD BEHAVIOR this always seems to import twice, not sure why, 
#labor rates
INSERT INTO labor_rates (name,rate) SELECT `POlaborName`,`POlaborRate` from potbljoborderlabor GROUP BY `POlaborName`,`POlaborRate`; 


#UPDATE labor_rates set id = id * 231;

#UPDATE labor_rates set id = old_id;


      
      UPDATE contacts c1
      LEFT JOIN contacts c2 ON c2.id = c1.related_to
      Set c1.related_to = null
WHERE c2.id is null;

        
DELETE FROM contacts where last_name like '%demo%';

DELETE FROM proposal_media where media_type_id = 12;

#ALTER TABLE `proposals` ADD `changeorder` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `progressive_billing`; 
#RUN PROPOSAL_ACTIONS INSERT
INSERT INTO `web_configs` (`key`, `value`) VALUES ('webSalesGoals', '20000000'); 


#delete duplication locations attempt 1


TRUNCATE `duplocations`;

Insert Into duplocations Select MIN(id) as bad, MAX(id) as gid, LEFT(address_line1, 12) as addr, city, postal_code from locations
GROUP BY LEFT(address_line1, 12), city, postal_code
HAVING COUNT(*) >=2;


# realign Patrick Daly

UPDATE proposals 
Set salesperson_id = 3927 
WHERE salesperson_id = 676;

UPDATE proposals 
Set salesmanager_id = 3927 
WHERE salesmanager_id = 676;


UPDATE proposal_details 
JOIN duplocations on duplocations.bad = proposal_details.location_id
Set proposal_details.location_id = duplocations.gid;

UPDATE proposals 
JOIN duplocations on duplocations.bad = proposals.location_id
Set proposals.location_id = duplocations.gid;

UPDATE locations 
JOIN duplocations on duplocations.bad = locations.id
SET locations.note = 'DELETE';

UPDATE locations 
SET locations.note = ''
WHERE id =1108;

UPDATE locations 
SET locations.note = ''
WHERE id =4043;

DELETE FROM locations 
WHERE locations.note = 'DELETE';


# end delete duplocations
#search for proposals without location

#search for proposal details without location


#INSETRT LABOR
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("DIEGO","MEJIA REYES", "labor1@3dpaving.com" , 1,"Password123abc",'en',6, 21);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("NIGEL","BURTON", "labor2@3dpaving.com" , 1,"Password123abc",'en',6, 24);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("EMILIO","MENDOZA CARRANZA", "labor3@3dpaving.com" , 1,"Password123abc",'en',6, 17);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("ENRIQUE","LARA DELGADO", "labor4@3dpaving.com" , 1,"Password123abc",'en',6, 18);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("FIDELMAR","R CORNEJO", "labor5@3dpaving.com" , 1,"Password123abc",'en',6, 20);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("FRANCISCO","ARELLANO", "labor6@3dpaving.com" , 1,"Password123abc",'en',6, 23);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("GERVIN","BUSTILLO FLORES", "labor7@3dpaving.com" , 1,"Password123abc",'en',6, 15);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("JOCELYN","JACQUES", "labor8@3dpaving.com" , 1,"Password123abc",'en',6, 22);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("JOSE","A MALDONADO", "labor9@3dpaving.com" , 1,"Password123abc",'en',6, 18);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("JOSE","F MALDONADO MORENO", "labor11@3dpaving.com" , 1,"Password123abc",'en',6, 19);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("JOSE","S MONTENEGRO", "labor12@3dpaving.com" , 1,"Password123abc",'en',6, 23);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("JUAN","G LOPEZ", "labor13@3dpaving.com" , 1,"Password123abc",'en',6, 22.5);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("JUAN","J MONTENEGRO", "labor14@3dpaving.com" , 1,"Password123abc",'en',6, 26);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("JUAN","JUAN M QUILO", "labor15@3dpaving.com" , 1,"Password123abc",'en',6, 21);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("JUAN","P MALDONADO", "labor16@3dpaving.com" , 1,"Password123abc",'en',6, 21);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("JUAN","R LOPEZ", "labor17@3dpaving.com" , 1,"Password123abc",'en',6, 16);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("LUIS","PATINO", "labor18@3dpaving.com" , 1,"Password123abc",'en',6, 17);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("MARTIN","MONTENEGRO", "labor19@3dpaving.com" , 1,"Password123abc",'en',6, 16);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("MIGUEL","A NIETO", "labor20@3dpaving.com" , 1,"Password123abc",'en',6, 17);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("OSCAR","MONTENEGRO", "labor21@3dpaving.com" , 1,"Password123abc",'en',6, 16);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("PRISCO","SANCHEZ ROMERO", "labor22@3dpaving.com" , 1,"Password123abc",'en',6, 16);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("ROHAN","E HENRY", "labor23@3dpaving.com" , 1,"Password123abc",'en',6, 20);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("RUBEN","MONTENEGRO SR.", "labor24@3dpaving.com" , 1,"Password123abc",'en',6, 20);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("SAMUEL","J MALDONADO", "labor2@53dpaving.com" , 1,"Password123abc",'en',6, 26);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("SILVIAU","JEAN GLY", "labor26@3dpaving.com" , 1,"Password123abc",'en',6, 14);
Insert Into users (fname, lname, email, status, password, language, role_id, rate_per_hour) VALUES
("VICTOR","M MALDONADO", "labor27@3dpaving.com" , 1,"Password123abc",'en',6, 24);



# lock out old equipment
Update equipment set do_not_use = 1;
#insert new equipment
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Street saws","per hour","0.31",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Asphalt Paver Weiler","per hour","26.04",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Crew Trucks (Izusu) with Beds","per hour","7.81",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Curb Machine","per hour","1.04",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Cut off saws","per hour","0.42",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Excavator","per hour","8.33",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("F250","per hour","7.81",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("F550 with Bed","per hour","7.81",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Loader","per hour","26.04",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Mack Trucks","per hour","20.83",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Misc Concrete Tools","per hour","2.60",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Misc Trailers","per hour","1.56",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Misc. Patching / Paving Tools","per hour","2.60",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Misc. Sealcoating Tools","per hour","1.56",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Motograder","per hour","10.42",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Plate Compactor","per hour","0.26",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Reversible Compactor","per hour","0.94",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Roller Rubber","per hour","7.81",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Roller Steel","per hour","6.77",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Sealcoat Trailer","per hour","3.13",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Sealcoat Truck","per hour","12.50",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Service Truck","per hour","7.29",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Single Axle Truck","per hour","10.42",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("SkidSteer w/ Attachments","per hour","9.38",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Sterling Truck","per hour","8.33",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Stump Grinder","per hour","3.13",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Tack Wagon","per hour","3.13",0);
INSERT into equipment (name,rate_type,rate,do_not_use) VALUES("Trencher","per hour","2.60",0);


#re orient catchbasins
Update proposal_details set catchbasins = additive where services_id = 21;
Update proposal_details set additive = 0 where services_id = 21;


update proposal_materials 
JOIN materials ON materials.id = proposal_materials.material_id
set proposal_materials.service_category_id = materials.service_category_id ;

#START to remove uneeded fields

ALTER TABLE equipment
  DROP COLUMN old_id;
  

#  ALTER TABLE proposals  DROP COLUMN old_id;
  
    ALTER TABLE vehicles
  DROP COLUMN old_id;
  
  
      ALTER TABLE vehicle_types
    DROP COLUMN old_id;
  
  ALTER TABLE striping_costs
      DROP COLUMN old_id;
      
      
      
#  ALTER TABLE contractors  DROP COLUMN old_id;

  #ALTER TABLE contacts  DROP COLUMN old_id;
      
      
  ALTER TABLE services
      DROP COLUMN old_id;
      
#  ALTER TABLE users DROP COLUMN old_id;
      
      ALTER TABLE services
      DROP COLUMN old_service_cat;
      


ALTER TABLE proposal_details 
DROP COLUMN old_status,
DROP COLUMN old_vendor_id,
DROP COLUMN old_detail_id,
DROP COLUMN old_proposal_id;


Update proposals set location_id = 6196 where id = 217;

UPDATE proposal_details
JOIN proposals on proposals.id = proposal_details.proposal_id
Set proposal_details.location_id = proposals.location_id
where proposal_details.location_id is null;

#You can drop table locations2 now
DROP TABLE locations2;

Update proposal_statuses set status = 'Presented To Client' where id =4;

#run 
#load fake data
#Build Actions
#Build MaxIDs
#Build New Contact types
#Swap out services because you fixed the descriptions

/*
php artisan view:cache
php artisan route:cache
php artisan config:clear
php artisan config:cache
php artisan cache:clear

*/

#Update sale date by job master ID, show chcnage orders in same date as original proposal
update proposals set proposal_date = "2020-2-21 09:47:05" where job_master_id = "2018:9:314";
update proposals set proposal_date = "2020-3-27 01:22:44" where job_master_id = "2019:11:306";
update proposals set proposal_date = "2020-1-11 08:43:07" where job_master_id = "2019:3:80";
update proposals set proposal_date = "2019-11-11 12:40:33" where job_master_id = "2019:8:202";
update proposals set proposal_date = "2020-1-3 08:30:12" where job_master_id = "2019:9:264";
update proposals set proposal_date = "2019-9-17 11:35:55" where job_master_id = "2020:1:1";
update proposals set proposal_date = "2019-3-26 10:01:56" where job_master_id = "2020:1:16";
update proposals set proposal_date = "2019-2-8 08:46:44" where job_master_id = "2020:1:19";
update proposals set proposal_date = "2019-11-8 02:05:20" where job_master_id = "2020:1:6";
update proposals set proposal_date = "2019-10-30 08:33:06" where job_master_id = "2020:1:7";
update proposals set proposal_date = "2020-1-6 12:11:11" where job_master_id = "2020:1:9";
update proposals set proposal_date = "2020-8-17 01:25:22" where job_master_id = "2020:10:328";
update proposals set proposal_date = "2019-4-23 11:31:50" where job_master_id = "2020:10:337";
update proposals set proposal_date = "2020-9-2 09:20:59" where job_master_id = "2020:10:339";
update proposals set proposal_date = "2020-1-30 02:58:25" where job_master_id = "2020:2:32";
update proposals set proposal_date = "2020-2-4 08:56:59" where job_master_id = "2020:2:42";
update proposals set proposal_date = "2018-1-4 11:00:07" where job_master_id = "2020:2:49";
update proposals set proposal_date = "2020-2-25 01:15:19" where job_master_id = "2020:3:58";
update proposals set proposal_date = "2019-12-4 10:47:06" where job_master_id = "2020:3:61";
update proposals set proposal_date = "2020-3-10 08:11:00" where job_master_id = "2020:3:74";
update proposals set proposal_date = "2020-2-5 07:07:46" where job_master_id = "2020:4:92";
update proposals set proposal_date = "2020-4-21 03:14:10" where job_master_id = "2020:5:119";
update proposals set proposal_date = "2020-1-11 10:56:57" where job_master_id = "2020:5:144";
update proposals set proposal_date = "2020-5-11 09:46:04" where job_master_id = "2020:5:156";
update proposals set proposal_date = "2020-6-2 08:54:02" where job_master_id = "2020:6:175";
update proposals set proposal_date = "2020-6-16 11:28:17" where job_master_id = "2020:6:188";
update proposals set proposal_date = "2019-10-9 07:47:01" where job_master_id = "2020:8:257";
update proposals set proposal_date = "2019-3-25 11:27:01" where job_master_id = "2020:8:264";
update proposals set proposal_date = "2020-5-26 11:18:02" where job_master_id = "2020:8:279";
update proposals set proposal_date = "2019-9-30 09:05:32" where job_master_id = "2020:9:289";
update proposals set proposal_date = "2019-10-21 10:32:10" where job_master_id = "2020:9:292";
update proposals set proposal_date = "2020-9-23 01:31:02" where job_master_id = "2020:9:309";
update proposals set proposal_date = "2020-5-22 09:12:10" where job_master_id = "2021:1:5";
update proposals set proposal_date = "2021-9-30 11:32:45" where job_master_id = "2021:10:404";
update proposals set proposal_date = "2020-2-12 11:51:13" where job_master_id = "2021:10:405";
update proposals set proposal_date = "2020-11-24 06:02:38" where job_master_id = "2021:3:113";
update proposals set proposal_date = "2021-3-11 12:42:06" where job_master_id = "2021:3:85";
update proposals set proposal_date = "2020-2-11 07:54:35" where job_master_id = "2021:3:93";
update proposals set proposal_date = "2021-2-19 01:51:00" where job_master_id = "2021:3:95";
update proposals set proposal_date = "2021-1-21 12:01:42" where job_master_id = "2021:5:166";
update proposals set proposal_date = "2021-3-31 02:14:17" where job_master_id = "2021:5:170";
update proposals set proposal_date = "2021-5-5 06:52:40" where job_master_id = "2021:5:179";
update proposals set proposal_date = "2021-5-12 06:55:00" where job_master_id = "2021:6:198";
update proposals set proposal_date = "2021-6-13 12:58:46" where job_master_id = "2021:6:227";
update proposals set proposal_date = "2021-4-17 04:30:21" where job_master_id = "2021:6:236";
update proposals set proposal_date = "2020-4-27 02:50:53" where job_master_id = "2021:6:237";
update proposals set proposal_date = "2018-8-15 12:11:40" where job_master_id = "2021:7:256";
update proposals set proposal_date = "2021-7-27 05:01:06" where job_master_id = "2021:7:272";
update proposals set proposal_date = "2021-2-19 02:08:44" where job_master_id = "2021:8:294";
update proposals set proposal_date = "2021-4-28 07:24:50" where job_master_id = "2021:8:325";
update proposals set proposal_date = "2021-6-21 04:08:09" where job_master_id = "2021:8:329";
update proposals set proposal_date = "2021-5-24 03:18:12" where job_master_id = "2021:8:330";
update proposals set proposal_date = "2019-9-12 09:26:19" where job_master_id = "2021:8:335";
update proposals set proposal_date = "2021-5-11 03:49:14" where job_master_id = "2021:8:336";
update proposals set proposal_date = "2021-4-27 01:09:37" where job_master_id = "2021:8:339";
update proposals set proposal_date = "2021-10-4 11:36:35" where job_master_id = "2022:1:11";
update proposals set proposal_date = "2021-9-20 01:25:32" where job_master_id = "2022:1:27";
update proposals set proposal_date = "2022-1-3 06:14:10" where job_master_id = "2022:1:36";
update proposals set proposal_date = "2021-7-27 03:18:56" where job_master_id = "2022:1:6";
update proposals set proposal_date = "2021-7-14 07:52:12" where job_master_id = "2022:11:416";
update proposals set proposal_date = "2022-1-27 12:32:29" where job_master_id = "2022:2:62";
update proposals set proposal_date = "2022-2-17 01:23:48" where job_master_id = "2022:2:81";
update proposals set proposal_date = "2021-5-7 05:28:56" where job_master_id = "2022:3:122";
update proposals set proposal_date = "2022-3-2 06:16:45" where job_master_id = "2022:3:85";
update proposals set proposal_date = "2022-1-28 02:43:59" where job_master_id = "2022:4:143";
update proposals set proposal_date = "2022-2-24 04:28:09" where job_master_id = "2022:4:158";
update proposals set proposal_date = "2022-3-11 04:30:54" where job_master_id = "2022:4:162";
update proposals set proposal_date = "2022-4-26 01:11:09" where job_master_id = "2022:5:181";
update proposals set proposal_date = "2022-3-3 06:50:28" where job_master_id = "2022:5:184";
update proposals set proposal_date = "2022-3-15 04:16:44" where job_master_id = "2022:7:256";
update proposals set proposal_date = "2022-7-21 02:17:01" where job_master_id = "2022:8:305";
update proposals set proposal_date = "2022-7-14 11:51:42" where job_master_id = "2022:8:307";
update proposals set proposal_date = "2020-11-17 11:28:10" where job_master_id = "2022:8:309";
update proposals set proposal_date = "2021-10-8 12:31:29" where job_master_id = "2022:9:341";
update proposals set proposal_date = "2022-8-19 01:06:35" where job_master_id = "2022:9:343";
update proposals set proposal_date = "2022-7-8 08:29:02" where job_master_id = "2022:9:364";
update proposals set proposal_date = "2023-1-16 07:30:18" where job_master_id = "2023:1:26";
update proposals set proposal_date = "2023-1-6 01:26:59" where job_master_id = "2023:1:33";
update proposals set proposal_date = "2021-9-14 01:46:44" where job_master_id = "2023:1:42";
update proposals set proposal_date = "2023-1-30 06:50:13" where job_master_id = "2023:2:46";
update proposals set proposal_date = "2023-1-22 02:57:21" where job_master_id = "2023:4:116";
update proposals set proposal_date = "2022-1-17 02:23:11" where job_master_id = "2023:4:119";
update proposals set proposal_date = "2023-3-6 01:51:38" where job_master_id = "2023:4:124";
update proposals set proposal_date = "2023-3-20 05:58:12" where job_master_id = "2023:4:125";
update proposals set proposal_date = "2021-6-3 01:16:26" where job_master_id = "2023:4:129";
update proposals set proposal_date = "2023-5-2 11:38:57" where job_master_id = "2023:6:171";
update proposals set proposal_date = "2022-3-15 11:52:45" where job_master_id = "2023:6:175";
update proposals set proposal_date = "2023-5-2 12:40:19" where job_master_id = "2023:7:209";
update proposals set proposal_date = "2023-5-11 04:15:11" where job_master_id = "2023:7:210";
update proposals set proposal_date = "2023-6-30 05:40:06" where job_master_id = "2023:8:240";

#DROP TABLES

INSERT INTO `terms_of_service` (`id`, `text`, `section`, `title`) VALUES (1, '<ul><li>Asphalt services carry a 1-year warranty\n                </li>\n                <li>Additional mobilizations billed at $1,250.00 for repairs, $4,500.00 for paving, and $6,000.00 for milling</li>\n                <li>Twist marks from tires are natural and will wear out over time. Please refrain from turning wheels in park or neutral for first 2 weeks after asphalt work is completed\n                </li>\n                <li>3-D Paving cannot guarantee complete removal of all millings from surrounding landscaping. This asphalt poses no long-term threat to plant-life</li>\n                <li>3-D Paving cannot guarantee no tack over-spray or tracking through unpaved areas.</li>\n                <li>3-D Paving is not responsible for reflective cracking of new asphalt after milling & re-paving or asphalt overlay due to the cracked condition of the existing asphalt pavement</li>\n                <li>3-D Paving & Sealcoating will not be responsible for asphalt repairs thicker than 2&quot;. Any asphalt repairs thicker than 2&quot; will be charged to the Owner.</li>\n            </ul>', 1, 'ASPHALT');
INSERT INTO `terms_of_service` (`id`, `text`, `section`, `title`) VALUES (2, '<ul><li>Sealcoating carries a 1-year warranty on workmanship and material</li>\n                <li>Additional mobilizations billed at $1,500.00 for sealcoating</li>\n                <li>Some sealer overspray near landscaping is natural and will disappear after the next round of lawn-care services</li>\n                <li>Twist marks from tires are natural and will disappear over time. Please refrain from turning wheels in park or neutral for first 2 weeks after sealcoating work completed</li>\n                <li>Cracks will still be visible after sealcoating</li>\n                <li>Sprinklers should be turned off 24 hours prior to service and 48 hours after work is completed. Please also avoid scheduling landscaping services or fertilization during sealcoating schedule</li>\n            </ul>', 2, 'SEALCOATING');
INSERT INTO `terms_of_service` (`id`, `text`, `section`, `title`) VALUES (3, '<ul><li>Pavement markings carry a 1-year warranty</li>\n                <li>Additional mobilizations billed at $550.00 for pavement markings</li>\n                <li>3-D Paving & Sealcoating recommends replacing R.P.M.s in sealcoated area. However, if owner chooses to keep them, we cannot guarantee that sealer will not get on them even though we tape them prior to sealing</li>\n                <li>3-D Paving & Sealcoating cannot guarantee complete removal of broken R.P.M.s without damaging surface underneath</li>\n            </ul>\n\n', 3, 'PAVEMENT MARKINGS');
INSERT INTO `terms_of_service` (`id`, `text`, `section`, `title`) VALUES (4, '<ul><li>No services can be performed in the rain, or when it has recently rained. If it&#39;s raining the day your work is scheduled, assume we aren&#39;t coming and we will contact you ASAP to reschedule. If it has rained the night before, or if it is scheduled to rain later that day, we will contact you first thing in the morning to alert you of any scheduling changes</li>\n                <li>Once barricaded areas are established, 3-D Paving & Sealcoating is not responsible for damage to work areas caused by trespassing beyond barriers</li>\n                <li>If any vehicles are still in the area where work is to be performed when our crews arrive, they will be towed at owner&#39;s expense</li>\n\n            </ul>', 5, 'ALL SERVICES');
INSERT INTO `terms_of_service` (`id`, `text`, `section`, `title`) VALUES (5, '<ul><li>Concrete services carries a 1-year warranty</li>\n                <li>Additional mobilizations billed at $1,975.00</li>\n                <li>3-D Paving & Sealcoating is not responsible for concrete discoloration caused by water during the curing process</li>\n            </ul>', 4, 'CONCRETE');


##LOAD SERVICES

-- ----------------------------
-- Table structure for newcontacttypes
-- ----------------------------
DROP TABLE IF EXISTS `newcontacttypes`;
CREATE TABLE `newcontacttypes`  (
  `Contact_Type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `contact_id` bigint(20) NOT NULL,
  `contact_type_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`contact_id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Records of newcontacttypes
-- ----------------------------
BEGIN;
INSERT INTO `newcontacttypes` (`Contact_Type`, `email`, `contact_id`, `contact_type_id`) VALUES ('Management Company', 'BrendaH@mmpm.us', 8410, 7), ('Property Owner', 'brucew@cpaweinberg.com', 8927, 16), ('Property Owner', 'Jeremy@resf.com', 11340, 16), ('Property Owner', 'DeanKCSE@gmail.com', 10496, 16), ('Property Owner', 'peter@jamsrx.com', 12066, 16), ('POA/COA/HOA', 'na', 10412, 14), ('POA/COA/HOA', 'na', 10415, 14), ('POA/COA/HOA', 'na', 10385, 14), ('POA/COA/HOA', 'na', 10418, 14), ('Property Owner', 'annefotopoulos@gmail.com', 12937, 16), ('Property Owner', 'liz4hodgins@gmail.com', 11694, 16), ('Property Owner', 'na', 12037, 16), ('Property Owner', 'na', 11001, 16), ('Property Owner', '2marsllc@gmail.com', 10499, 16), ('POA/COA/HOA', 'Board@Board.com', 10536, 14), ('POA/COA/HOA', 'esabates@waypointci.com', 9860, 14), ('POA/COA/HOA', 'frank@franklind.org', 9891, 14), ('Property Owner', 'harrybajaj@aol.com', 12759, 16), ('POA/COA/HOA', 'Gchevannes@castlegroup.com', 10555, 14), ('Property Owner', 'parrotfisch@gmail.com', 10064, 16), ('Property Owner', 'Steveachee66@gmail.com', 9921, 16), ('Property Owner', 'office@gordonco.com', 10340, 16), ('POA/COA/HOA', 'adam@sofloapartments.com', 9918, 14), ('POA/COA/HOA', 'Steve_haynie@hotmail.com', 11454, 14), ('Property Owner', 'brit3030@aol.com', 11156, 16), ('Property Owner', 'brit3030@aol.com', 11159, 16), ('POA/COA/HOA', 'lee@Fitzgeraldgroup.com', 9585, 14), ('POA/COA/HOA', 'na', 12335, 14), ('POA/COA/HOA', 'Pierangelilima@aol.com', 11005, 14), ('POA/COA/HOA', 'na', 11017, 14), ('Management Company', 'andrea.arboleda@colliers.com', 992, 7), ('General Contact', 'na', 8687, 18), ('POA/COA/HOA', 'JVdegrace@aol.com', 9080, 14), ('Property Owner', 'lori@mannysseafood.com', 12857, 16), ('POA/COA/HOA', 'sandpiper59@bellsouth.net', 10617, 14), ('Government Entity', 'cwoolweaver@fortlauderdale.gov', 11217, 10), ('Management Company', 'st@sflregroup.com', 1431, 7), ('Property Owner', 'na', 10925, 16), ('Property Owner', 'Nghetzel@yahoo.com', 12827, 16), ('Management Company', '88stmanagement@gmail.com', 8253, 7), ('POA/COA/HOA', 'Donnadarnell2@gmail.com', 9824, 14), ('POA/COA/HOA', 'kmartinez@unitedcommunity.net', 11152, 14), ('POA/COA/HOA', 'sstcondo@yahoo.com', 10127, 14), ('General Contact', 'na', 12800, 18), ('General Contact', 'na', 12795, 18), ('Property Owner', 'access.storagefla@gmail.com', 10901, 16), ('Management Company', 'JTownes@a.d.morgan.com', 8873, 7), ('Contractor', 'jon@ahacontracting.com', 13024, 22), ('Management Company', 'ariel@aarongrouprealty.com', 12975, 7), ('Property Owner', 'na', 11123, 16), ('Property Owner', 'shakainc@mail.com', 13059, 16), ('Property Owner', 'riky@msn.com', 12277, 16), ('Property Owner', 'Kayce.Mccandleff@abcsupply.com', 9292, 16), ('Property Owner', 'info@abcoflearning.com', 13048, 16), ('Property Owner', 'access.storagefla@gmail.com', 12259, 16), ('Management Company', 'milette@acclaimcares.com', 8349, 7), ('National Company', NULL, 21, 1), ('National Company', 'jfernandez@acplm.net', 5071, 1), ('Contractor', 'paulactionelectric@gmail.com', 9237, 22), ('Contractor', 'marc.syracuse@actn.com', 11897, 22), ('POA/COA/HOA', 'addisonplaceasst@jrk.com', 12684, 14), ('POA/COA/HOA', 'Scooksey@adler-partners.com', 8884, 14), ('Contractor', 'marcel@advancecontractorgroup.com', 4954, 22), ('Contractor', 'advcutter@aol.com', 7316, 22), ('Management Company', 'kevin.blanchard@myafs.com', 9420, 7), ('Management Company', 'DW@managedbyaffinity.com', 10946, 7), ('Property Owner', 'bodyshopteam@aol.com', 12537, 16), ('POA/COA/HOA', 'airandportcondo@gmail.com', 11261, 14), ('Management Company', 'blueadmin@akam.com', 12810, 7), ('POA/COA/HOA', 'Krisk1913@gmail.com', 11025, 14), ('Contractor', 'Jorge.ALESGroupGC@gmail.com', 9212, 22), ('Management Company', 'J.turner@allamericanfacilities.com', 7663, 7), ('Contractor', 'loydb@bellsouth.net', 10025, 22), ('National Company', 'aoboyle@allcountypaving.com', 8549, 1), ('Management Company', 'jdervishi@allpropsys.net', 10469, 7), ('Management Company', 'Jhoffman@allpropsys.net', 2239, 7), ('Management Company', 'jbeaulieu@ameracorporation.com', 4898, 7), ('Contractor', 'ashley@theacecorp.com', 10832, 22), ('Contractor', 'jkopelakis@aedcorp.com', 5162, 22), ('Contractor', 'dfevrier@aedcorp.com', 5889, 22), ('Contractor', 'oscar.black@amerigas.com', 9368, 22), ('Contractor', 'larry.netti@ameritechfs.com', 3419, 22), ('Contractor', 'codyc@amiciec.com', 12618, 22), ('Management Company', 'na', 1398, 7), ('Property Owner', 'AWiseLady@gmail.com', 9125, 16), ('Contractor', 'anacodev@yahoo.com', 12763, 22), ('Management Company', 'annettealvarez@anderson-realestate.com', 2342, 7), ('General Contact', 'Mkolbjornsen59@hotmail.com', 8977, 18), ('POA/COA/HOA', 'AndrosPM@campbellproperty.com', 12842, 14), ('Property Owner', 'Satie91794@Bellsouth.net', 9584, 16), ('Property Owner', 'ST_photo@yahoo.com', 10352, 16), ('Management Company', 'Ronaldy@angelpm.com', 9194, 7), ('Property Owner', 'manidjar@anl-law.com', 8711, 16), ('Contractor', 'Jzak@AnzColnc.com', 6228, 22), ('Contractor', 'gasper@apexservicesfl.com', 13004, 22), ('Contractor', 'gasper@apexservicesfl.com', 7809, 22), ('Contractor', 'apexpaversconcepts@gmail.com', 9905, 22), ('POA/COA/HOA', 'skryppy1@bellsouth.net', 12624, 14), ('Management Company', 'jharnett@archoice.com', 12315, 7), ('Contractor', 'richard.kappes@arcadis.com', 11787, 22), ('Contractor', 'crivas@arcomurray.com', 12546, 22), ('Contractor', 'sgallub@aol.com', 12727, 22), ('POA/COA/HOA', 'mwolters@arkdcs.com', 8813, 14), ('Contractor', 'jp@artandtec.net', 11715, 22), ('Management Company', 'DFasano@asgflorida.com', 5763, 7), ('Management Company', 'asrser@hotmail.com', 9960, 7), ('Management Company', 'mgordon@associagulfcoast.com', 4074, 7), ('Management Company', 'mrubin@apm247.net', 3934, 7), ('Management Company', 'oblanco@associaflorida.com', 9187, 7), ('Management Company', 'cmellton@associaflorida.com', 12148, 7), ('Management Company', 'james@atisecuritysystems.com', 12141, 7), ('Management Company', 'oscar@atisecuritysystems.com', 8829, 7), ('Management Company', 'na', 3328, 7), ('POA/COA/HOA', 'harriscurtis1745@gmail.com', 12909, 14), ('General Contact', 'austinj.mcmahon@gmail.com', 12452, 18), ('POA/COA/HOA', 'admin@avalon-estates.com', 10696, 14), ('Management Company', 'yvirtue@avalonpropertyinc.com', 8228, 7), ('POA/COA/HOA', 'Manager@aventuraisleshoa.com', 12436, 14), ('POA/COA/HOA', 'office@aventuralakesHOA.com', 11704, 14), ('Contractor', 'amanda@awesomeconstruction.com', 1563, 22), ('General Contact', 'info@azulfinancial.net', 10758, 18), ('Property Owner', 'cvbad2dbone@gmail.com', 9412, 16), ('General Contact', 'PGannon@ParkPartners.com', 6976, 18), ('General Contact', 'YMorabito@bankunited.com', 11187, 18), ('Management Company', NULL, 35, 7), ('Contractor', 'Jstroud@banyancs.com', 11405, 22), ('Contractor', 'nbarnett@bardezco.com', 10942, 22), ('Contractor', 'chris@barrarchitecture.com', 5909, 22), ('POA/COA/HOA', 'Jtuot@comcast.net', 10466, 14), ('Property Owner', 'EmbreyT@FloridaPanthers.com', 4768, 16), ('Property Owner', 'ana@bbcw.com', 10203, 16), ('Contractor', 'nick@bdimarineandsite.com', 9514, 22), ('POA/COA/HOA', 'ylastra@alaps.com', 7357, 14), ('Property Owner', 'beatrizmolina.ns@gmail.com', 9510, 16), ('POA/COA/HOA', 'sandysonkin323@gmail.com', 10719, 14), ('Management Company', 'neal@benchmarkpm.com', 3657, 7), ('Management Company', 'Ldozier@bergercommercial.com', 10748, 7), ('Contractor', 'admin@bergerwindows.com', 11892, 22), ('POA/COA/HOA', 'sdawes@berkeleypartners.com', 9659, 14), ('POA/COA/HOA', 'RMturetzky@aol.com', 12912, 14), ('POA/COA/HOA', 'bernardapts@aol.com', 12954, 14), ('Contractor', 'info@bestrestaurantssolutions.com', 9449, 22), ('General Contact', 'DoorDiva@gmail.com', 8981, 18), ('General Contact', 'Msco14@aol.com', 8843, 18), ('Management Company', 'na', 3953, 7), ('Management Company', 'Michael.Cicero@bhmanagement.com', 10305, 7), ('Property Owner', 'KennyG3255@gmail.com', 8136, 16), ('Property Owner', 'dbassion@gmail.com', 8960, 16), ('POA/COA/HOA', 'ftacia.pencz@five.com', 11799, 14), ('Property Owner', 'amanda.matthews@blackcreekgroup.com', 9528, 16), ('POA/COA/HOA', 'denisemede32@gmail.com', 8077, 14), ('Management Company', 'ivalentino@bldmpr.com', 9033, 7), ('Management Company', 'bill@blueshieldpm.com', 10732, 7), ('Contractor', 'bluegrassbuildersfl@gmail.com', 4807, 22), ('Contractor', 'cumbaugh@bluwatered.com', 10463, 22), ('General Contact', 'robertxweinstein@gmail.com', 9425, 18), ('POA/COA/HOA', 'awaller@investmentslimited.com', 5674, 14), ('POA/COA/HOA', 'rlrichwagen@yahoo.com', 10962, 14), ('POA/COA/HOA', 'prettyboy11111@bellsouth.net', 8721, 14), ('POA/COA/HOA', 'kend@bocaflasher.com', 12863, 14), ('POA/COA/HOA', 'na', 776, 14), ('POA/COA/HOA', 'bocainlt@comcast.net', 3702, 14), ('Management Company', 'Steve@SCCPAPA.com', 9441, 7), ('POA/COA/HOA', 'bocalakesmanager@gmail.com', 10867, 14), ('Property Owner', 'fworath@mac.com', 10199, 16), ('POA/COA/HOA', 'na', 3645, 14), ('Property Owner', 'arlene@scasurgery.com', 12377, 16), ('Property Owner', 'fritacco@thebocaraton.com', 12210, 16), ('POA/COA/HOA', 'bocateeca7@gmail.com', 10067, 14), ('POA/COA/HOA', 'Laurie33067@gmail.com', 12269, 14), ('POA/COA/HOA', 'na', 9804, 14), ('Management Company', 'aura@boultonCRE.com', 10638, 7), ('Contractor', 'info@bradfordelectric.net', 12331, 22), ('POA/COA/HOA', 'larrygarfinkel@comcast.net', 9011, 14), ('POA/COA/HOA', NULL, 679, 14), ('POA/COA/HOA', 'Briarwoodclub@bellsouth.net', 9581, 14), ('Contractor', 'bkrush@brinkred.com', 12885, 22), ('Management Company', 'Lsciarretti@brinwo.com', 1094, 7), ('POA/COA/HOA', 'brinymaintenance@gmail.com', 8964, 14), ('Management Company', 'na', 11366, 7), ('POA/COA/HOA', 'Larahmi9@hotmail.com', 8439, 14), ('POA/COA/HOA', 'Fambry1059@gmail.com', 13036, 14), ('Government Entity', 'tpalmer@bchafl.org', 9662, 10), ('Contractor', 'marthac@bryanbuild.com', 11666, 22), ('Property Owner', 'busybeesacademy@bellsouth.net', 9935, 16), ('Contractor', 'phuntington@butters.com', 8025, 22), ('Management Company', 'wcollins@butters.com', 6562, 7), ('Contractor', 'RAckner@candrcontractors.com', 11337, 22), ('Management Company', 'tg@calidus.com', 1893, 7), ('Management Company', 'Angel.Fagundo@CambriaUSA.com', 12779, 7), ('Management Company', 'na', 8684, 7), ('Management Company', 'CamiAcademy96@gmail.com', 9302, 7), ('Management Company', 'DonnaL@CampbellProperty.com', 3901, 7), ('Management Company', 'androspm@campbellproperty.com', 11253, 7), ('Management Company', 'bbernhardt@campbellproperty.com', 4912, 7), ('Management Company', 'LFrancis@campbellproperty.com', 10818, 7), ('Management Company', 'LPaparella@campbellproperty.com', 9882, 7), ('Management Company', 'manager@pbphoa.com', 7578, 7), ('Management Company', 'NEhrlich@campbellproperty.com', 1935, 7), ('Management Company', 'pserfass@campbellproperty.com', 8697, 7), ('Management Company', 'sleal@campbellproperty.com', 10085, 7), ('Management Company', 'srusso@campbellproperty.com', 6856, 7), ('Property Owner', 'johan.borneo@ctca-hope.com', 10620, 16), ('POA/COA/HOA', 'na', 10473, 14), ('Management Company', 'TCooper@CRA.email', 1833, 7), ('Management Company', 'carlos@caracofl.com', 12463, 7), ('Contractor', 'timu@carlpursell.com', 7450, 22), ('Property Owner', 'chadmcurtiss@gmail.com', 12834, 16), ('Property Owner', 'Hotdogs360@aol.com', 9170, 16), ('POA/COA/HOA', 'jckayan@bellsouth.net', 12756, 14), ('Management Company', 'jsolano@castlegroup.com', 11910, 7), ('Management Company', 'Lstengel@castlegroup.com', 7051, 7), ('Management Company', 'lvarela@castlegroup.com', 11223, 7), ('Management Company', 'sthomsen@castlegroup.com', 11739, 7), ('Management Company', 'gherrera@castlegroup.com', 12678, 7), ('Management Company', 'ddelvecchio@castlegroup.com', 3237, 7), ('Management Company', 'na', 962, 7), ('Management Company', 'mwschlosberg@aol.com', 9092, 7), ('Contractor', 'rachel@catfishdevelopmentsolutions.com', 8019, 22), ('General Contact', 'Plato3lita@comcast.net', 9100, 18), ('Contractor', 'jforbes@cwiassoc.com', 3920, 22), ('Management Company', 'junior.belfort@cbre.com', 11275, 7), ('Management Company', 'centergroup@msn.com', 5684, 7), ('Contractor', 'randy@centerlineinc.com', 9216, 22), ('Contractor', 'kbaer@centralcivil.com', 10043, 22), ('POA/COA/HOA', 'Benny.robinson@fsresidential.com', 8915, 14), ('POA/COA/HOA', 'steve.karen@aol.com', 13075, 14), ('Property Owner', 'office@mychabadcenter.org', 12717, 16), ('General Contact', 'na', 10277, 18), ('Property Owner', 'Frozansky@ima-re.com', 8821, 16), ('POA/COA/HOA', 'na', 9447, 14), ('POA/COA/HOA', 'info@yourmanagementservices.com', 12752, 14), ('Contractor', 'UpKeep@ChicagoLandBuilding.com', 12549, 22), ('Property Owner', 'na', 9745, 16), ('Property Owner', 'dcarlson1@hotmail.com', 11088, 16), ('Property Owner', 'randalln@cityfurniture.com', 12567, 16), ('Government Entity', 'lbermudez@coralsprings.org', 4682, 10), ('Government Entity', 'dgreene@daniabeachfl.gov', 11171, 10), ('Government Entity', 'cscott@fortlauderdale.gov', 6325, 10), ('Government Entity', 'esaey@fortlauderdale.gov', 9467, 10), ('Government Entity', 'ksashi@hollywoodfl.org', 10096, 10), ('Government Entity', 'wsilvestro@cityofhomestead.com', 13099, 10), ('Government Entity', 'dmalone@lauderhill-fl.gov', 8355, 10), ('Government Entity', 'amontano@sunrisefl.gov', 7059, 10), ('Government Entity', 'michael.morrison@tamarac.org', 8481, 10), ('Government Entity', 'sfabien@westonfl.org', 11658, 10), ('Contractor', 'elwellh@bellsouth.net', 10408, 22), ('Management Company', 'frontdesk@condomanagementalternative.com', 7695, 7), ('Management Company', 'jackie@cmcmanagement.biz', 66, 7), ('Property Owner', 'punita304@gmail.com', 10010, 16), ('Management Company', 'karen@coastalstheone.com', 1661, 7), ('Property Owner', 'lharpster@coastalmetal.biz', 11271, 16), ('POA/COA/HOA', 'Larry@procommunitymgmt.com', 2281, 14), ('Property Owner', 'suemattix@aol.com', 3035, 16), ('Management Company', 'Jen@cohesivecamfl.com', 10967, 7), ('POA/COA/HOA', 'Rinadelray@aol.com', 9311, 14), ('POA/COA/HOA', 'Golladay_John@comcast.net', 9669, 14), ('Management Company', 'andrea.arboleda@colliers.com', 12069, 7), ('Management Company', 'Becky.miller@Colliers.com', 9915, 7), ('Management Company', 'blake.weiser@colliers.com', 11410, 7), ('Management Company', 'blake.weiser@colliers.com', 7102, 7), ('Contractor', 'matt@collinsdev.com', 10230, 22), ('POA/COA/HOA', 'pennelopehealy@gmail.com', 10313, 14), ('Management Company', 'nicolef@commercialpreservation.com', 7784, 7), ('Management Company', 'Commercialdouglas@outlook.com', 10983, 7), ('Contractor', 'josh@completehi.com', 6730, 22), ('POA/COA/HOA', 'info@concordvillage.net', 1896, 14), ('POA/COA/HOA', 'na', 1616, 14), ('Management Company', 'lwhite@ccmfla.com', 9517, 7), ('Management Company', 'na', 783, 7), ('Contractor', 'daniel@constructionspecialtiesfl.com', 7256, 22), ('POA/COA/HOA', 'Jpadilla@gmssf.com', 10565, 14), ('Contractor', 'carwashpalace4600@gmail.com', 12502, 22), ('POA/COA/HOA', 'gm@coralridgeyachtclub.com', 11520, 14), ('General Contact', 'rromero@coralspringsanimalhosp.com', 12312, 18), ('Property Owner', 'albatross53@aol.com', 11690, 16), ('Property Owner', 'albatross53@aol.com', 6022, 16), ('Property Owner', 'w180mgr4@costco.com', 9057, 16), ('Contractor', 'guy@cougarconstructionfl.com', 12838, 22), ('POA/COA/HOA', 'countryclublakesmgr@middiv.com', 11836, 14), ('POA/COA/HOA', 'chenry@ccofcs.com', 11864, 14), ('Property Owner', 'Chackmans@att.net', 9614, 16), ('Management Company', 'BSchaefer@CPgcre.com', 1119, 7), ('Management Company', 'TMiller@cpgcre.com', 11357, 7), ('Property Owner', 'bswan@creativeconvenience.com', 12160, 16), ('Management Company', 'rbudd@crestmanagementgroup.com', 9558, 7), ('Property Owner', 'madeline.langbaum@buyproperties.com', 9841, 16), ('Management Company', 'EFitzgerald@CAFUAmanagement.com', 11847, 7), ('Property Owner', 'Rjayb13@aol.com', 8276, 16), ('Property Owner', 'matthew@cummingsleasing.com', 8612, 16), ('Management Company', 'traci.garner@cushwake.com', 11284, 7), ('Contractor', 'na', 9756, 22), ('POA/COA/HOA', 'na', 9562, 14), ('Contractor', 'don@dsconstructionservice.com', 2443, 22), ('Property Owner', 'fttlaudrod@gmail.com', 12931, 16), ('General Contact', 'na', 8465, 18), ('Property Owner', 'dgreen@attawayservices.com', 10795, 16), ('Management Company', 'rgavsie@danron.com', 8942, 7), ('General Contact', 'Srhopkins1946@gmail.com', 8835, 18), ('Management Company', 'Susan@davenportpro.net', 1468, 7), ('Property Owner', 'frestrepo444@gmail.com', 9908, 16), ('Contractor', 'jake@dbf-construction.com', 9470, 22), ('Contractor', 'Chrisr@dcconstructionassociates.com', 1500, 22), ('Contractor', 'dave@dcconstructionassociates.com', 2803, 22), ('Management Company', 'David@elitestagers.com', 8681, 7), ('Management Company', 'ssprong@dei-corp.com', 9944, 7), ('Management Company', NULL, 1, 7), ('National Company', NULL, 86, 1), ('National Company', 'spoynter@dentco.com', 1546, 1), ('Management Company', 'Ralph.Reynolds@dms.myflorida.com', 12226, 7), ('Property Owner', 'GSelby@Derecktor.com', 1174, 16), ('Contractor', 'sdawes@dezer.com', 7229, 22), ('Management Company', 'chad@diversifiedcos.com', 6384, 7), ('Property Owner', 'apicella@bellsouth.net', 10143, 16), ('Management Company', 'dcrouch@divisioninc.com', 11511, 7), ('Management Company', 'djonathas25@yahoo.com', 12040, 7), ('Property Owner', 'paul@dolphincarpet.com', 3383, 16), ('Contractor', 'terra@dovehillcos.com', 12564, 22), ('Management Company', 'Sfahrer@dreamprojectmgmt.com', 8102, 7), ('Management Company', 'fherrera@dhmhotels.com', 8519, 7), ('Management Company', 'njustin@dstpm.net', 12533, 7), ('POA/COA/HOA', 'chastors@gmail.com', 10830, 14), ('Property Owner', 'Apatel@delraydonuts.com', 11013, 16), ('Property Owner', 'na', 9751, 16), ('POA/COA/HOA', 'neillaallen9@gmail.com', 11349, 14), ('POA/COA/HOA', 'na', 8554, 14), ('Management Company', 'Erica.Toda@eastgroup.net', 11733, 7), ('Management Company', 'PPadron@campbellproperty.com', 8487, 7), ('Contractor', 'cland@edgeconstruction-inc.com', 11179, 22), ('Contractor', 'tmanos@edgeconstruction-inc.com', 8405, 22), ('POA/COA/HOA', 'vend4@aol.com', 11362, 14), ('Contractor', 'cricci@elev8construction.com', 12525, 22), ('Management Company', 'elizakharia@gmail.com', 10309, 7), ('Contractor', 'etl5173@gmail.com', 12721, 22), ('POA/COA/HOA', 'Nobogey4@aol.com', 9160, 14), ('POA/COA/HOA', 'rabobfl@hotmail.com', 11978, 14), ('Management Company', 'cm@winslowpm.com', 12285, 7), ('Management Company', 'dbenedetto@excluisivepm.net', 1371, 7), ('Management Company', 'na', 100, 7), ('Management Company', 'Djocpa@gmail.com', 12291, 7), ('Management Company', 'mrs.allysoncopeland@gmail.com', 13056, 7), ('POA/COA/HOA', 'office@fairfieldatboca.com', 5253, 14), ('POA/COA/HOA', 'Fairwaysmgr@hrpliving.com', 11018, 14), ('POA/COA/HOA', 'diplomat400@outlook.com', 9897, 14), ('POA/COA/HOA', 'tamfairways@gmail.com', 8984, 14), ('Management Company', 'chris@farrisproperty.com', 1656, 7), ('Property Owner', 'itzel.fastautoservice@gmail.com', 8918, 16), ('Contractor', 'sven@fed-eng.com', 9726, 22), ('Management Company', 'AAlvarez@federalrealty.com', 12698, 7), ('Property Owner', 'tomfite@ferrellgas.com', 11796, 16), ('Contractor', 'gabriel@pavement.net', 10997, 22), ('Contractor', 'jerry@pavement.net', 8660, 22), ('Management Company', 'sjdawes@gmail.com', 12615, 7), ('Contractor', 'asteinborg@finelinecontractors.com', 9060, 22), ('National Company', 'Arthur@firmfoundations.solutions', 11782, 1), ('Property Owner', 'raally@att.net', 11463, 16), ('Management Company', 'na', 1610, 7), ('Property Owner', 'wvalentin@universalpetro.com', 1265, 16), ('Management Company', 'felix.pena@fsresidential.com', 11212, 7), ('Management Company', 'john.siervo@fsresidential.com', 3055, 7), ('Management Company', 'muriel.tablada@fsresidential.com', 9736, 7), ('Management Company', 'na', 106, 7), ('Management Company', 'na', 3405, 7), ('Management Company', 'pam.adams@fsresidential.com', 2798, 7), ('Management Company', 'rachel.rudley@fsresidential.com', 1983, 7), ('Management Company', 'samarra.miller@fsresidential.com', 10785, 7), ('Management Company', 'tara.walker@fsresidential.com', 8650, 7), ('Management Company', 'kathleen.odonovan@fsresidential.com', 13087, 7), ('Management Company', 'juan.dominguez@fsresidential.com', 12996, 7), ('Management Company', 'danielle.wade@fsresidential.com', 10445, 7), ('Management Company', 'Jeimy.revueltas@fsresidential.com', 12207, 7), ('Management Company', 'na', 4208, 7), ('POA/COA/HOA', 'Bob@pembertonbuilding.com', 10439, 14), ('Management Company', 'na', 109, 7), ('Management Company', 'na', 10334, 7), ('Property Owner', 'na', 10659, 16), ('Management Company', 'tbrown@fcmsfl.com', 10701, 7), ('Government Entity', 'Jeffrey.Mason@flhealth.gov', 12255, 10), ('General Contact', 'na', 9579, 18), ('Property Owner', 'tomadams@floridamedspace.com', 11779, 16), ('Contractor', 'dylan.p@floridapalm.com', 13001, 22), ('Contractor', 'Ken@Floridapave.com', 3956, 22), ('Contractor', 'jeff@miamitransfer.com', 11587, 22), ('Management Company', 'info@floridaskylinemanagement.com', 13065, 7), ('Contractor', 'Hurricanprone@aol.com', 12516, 22), ('Management Company', 'Thomas.fltech@gmail.com', 8263, 7), ('Management Company', 'admin@floridatrustrealtyinc.com', 4909, 7), ('Property Owner', 'mmartinez@fpd.com', 12940, 16), ('POA/COA/HOA', 'bayonee@gmail.com', 11830, 14), ('Property Owner', 'mypetsdoctor@hotmail.com', 9346, 16), ('General Contact', 'kjt@fortresscn.com', 9975, 18), ('Management Company', 'kevin.gonzalez@foundrycommercial.com', 12703, 7), ('POA/COA/HOA', 'Debbie.Herman@stiles.com', 10364, 14), ('General Contact', 'DeSenaFrank@yahoo.com', 8643, 18), ('Property Owner', 'Frankagentile@aol.com', 8305, 16), ('POA/COA/HOA', 'French.Quartercondo@yahoo.com', 10887, 14), ('Contractor', 'brian.k@fulcrumgroup.com', 12868, 22), ('Management Company', 'nora.moisa@fyve.com', 13113, 7), ('Management Company', 'Stacia.pencz@fyve.com', 11813, 7), ('Contractor', 'jjackson@gableseng.com', 11633, 22), ('Management Company', 'cfuzy@barrondev.com', 11334, 7), ('POA/COA/HOA', 'Bkettwig@campbellproperty.com', 10181, 14), ('POA/COA/HOA', 'pastorjoe@calvarycs.org', 11265, 14), ('POA/COA/HOA', 'jgolf33326@aol.com', 3691, 14), ('POA/COA/HOA', 'Bkettwig@campbellproperty.com', 9572, 14), ('General Contact', 'Parrotheadsouth@hotmail.com', 8882, 18), ('Contractor', 'gaslight1947@gmail.com', 12129, 22), ('Contractor', 'csmith@gcgbuilds.com', 12012, 22), ('Contractor', 'rob@generalasphalt.com', 11682, 22), ('Management Company', 'info@generalrental.com', 7488, 7), ('Management Company', 'severino1970@gmail.com', 12766, 7), ('Contractor', 'cgerhold@gerholdelect.com', 11091, 22), ('Contractor', 'felipe@gogforce.com', 11969, 22), ('Management Company', 'david.dicaprio@glhomes.com', 6541, 7), ('Contractor', 'blake.pruitt@glhomes.com', 9225, 22), ('Property Owner', 'Schrageg@bellsouth.net', 9027, 16), ('National Company', 'keemit.schilling@gmail.com', 8952, 1), ('National Company', 'les@glmcgroup.com', 10875, 1), ('Management Company', 'jennifer@myglobal.us', 12588, 7), ('Management Company', 'sfrazier@gfinvestments.com', 11049, 7), ('Property Owner', 'jrebuck@goldcoastschools.com', 10931, 16), ('POA/COA/HOA', 'lramsubir@hotmail.com', 12743, 14), ('Management Company', 'pburgess@gmssf.com', 12358, 7), ('Property Owner', 'robbieh@graceboca.org', 12048, 16), ('Property Owner', 'vtblaw@bellsouth.net', 9878, 16), ('POA/COA/HOA', 'tylerdurand@gmail.com', 9694, 14), ('POA/COA/HOA', 'slmillshome@gmail.com', 12190, 14), ('POA/COA/HOA', 'Linda@3-dPaving.com', 8369, 14), ('POA/COA/HOA', 'dkg6163@gmail.com', 8922, 14), ('POA/COA/HOA', 'rlrichwagen@yahoo.com', 1889, 14), ('Property Owner', 'alex@greenthumbmowers.com', 6964, 16), ('Management Company', 'LTurner@grsmgt.com', 9821, 7), ('Management Company', 'na', 118, 7), ('Contractor', 'tony@grscinc.net', 12440, 22), ('Contractor', 'chrisc@gulfbuilding.com', 8565, 22), ('Property Owner', 'info@gunsandrange.com', 8791, 16), ('Management Company', 'onplanemarine@hotmail.com', 10130, 7), ('Contractor', 'vperez@hjcontracting.com', 12659, 22), ('Management Company', 'lori@handlpropertymanagement.com', 5516, 7), ('Management Company', 'Dwhite@haagcompanies.com', 2619, 7), ('Contractor', 'eric@hallmarkconstr.com', 3670, 22), ('General Contact', 'Hehos12@gmail.com', 8987, 18), ('Contractor', 'ssummas@hardrivespaving.com', 8484, 22), ('Contractor', 'hardscapesfl@gmail.com', 6471, 22), ('Property Owner', 'BrendaH@mmpm.us', 9307, 16), ('General Contact', 'mikebigigie1967@gmail.com', 11032, 18), ('Property Owner', 'Taylor@merchant360.org', 12374, 16), ('Property Owner', 'Taylor@merchant360.org', 12380, 16), ('Management Company', 'npare@hawkeyefla.com', 4859, 7), ('Management Company', 'emcveigh@hrpliving.com', 8435, 7), ('Contractor', 'kcloaninger@hedrickbrothers.com', 1749, 22), ('Management Company', 'henryfl1@gmail.com', 10251, 7), ('POA/COA/HOA', 'jrowell@kwpmc.com', 7510, 14), ('Contractor', 'alainhccorp@gmail.com', 3651, 22), ('Management Company', 'juda@hershequity.com', 10186, 7), ('Contractor', 'phil@hi-techplumbingandair.com', 8232, 22), ('Management Company', 'deercreekcd@highmarkres.com', 10427, 7), ('POA/COA/HOA', 'cperez@castlegroup.com', 11094, 14), ('POA/COA/HOA', 'dhfah3470@gmail.com', 11853, 14), ('Contractor', 'estimating@himmelconstruction.com', 6786, 22), ('Property Owner', 'Jariwala.ck@gmail.com', 7716, 16), ('Property Owner', 'comp@bellsouth.net', 9491, 16), ('POA/COA/HOA', 'sbbroward@aol.com', 10954, 14), ('Property Owner', 'Nadia.cuadros@hilton.com', 8360, 16), ('Property Owner', 'ABarnes4881@gmail.com', 9772, 16), ('Property Owner', 'Blumj225@gmail.com', 9278, 16), ('Property Owner', 'Cucusa2003@msn.com', 9403, 16), ('Property Owner', 'Drew@overalloutdoorservices.com', 9535, 16), ('Property Owner', 'Eddie305@yahoo.com', 9782, 16), ('Property Owner', 'Fatigate@bellsouth.net', 9716, 16), ('Property Owner', 'Fatigate@bellsouth.net', 9714, 16), ('Property Owner', 'FFiftiesrock@aol.com', 9399, 16), ('Property Owner', 'HerculesB@bellsouth.net', 9605, 16), ('Property Owner', 'Janetmon70@aol.com', 9495, 16), ('Property Owner', 'Jennariejoseph@gmail.com', 9407, 16), ('Property Owner', 'Jerzap99@aol.com', 9270, 16), ('Property Owner', 'Johnhshaffer@gmail.com', 9707, 16), ('Property Owner', 'KaJen13@aol.com', 9364, 16), ('Property Owner', 'KimMadrid@hotmail.com', 9537, 16), ('Property Owner', 'Krisorion3@aol.com', 9465, 16), ('Property Owner', 'MarkAlexis545@gmail.com', 9463, 16), ('Property Owner', 'na', 9625, 16), ('Property Owner', 'OvettaMena@gmail.com', 9373, 16), ('Property Owner', 'Pepperlong53@gmail.com', 9401, 16), ('Property Owner', 'Pkostick13@att.net', 9336, 16), ('Property Owner', 'Priscilla_PR@live.com', 9267, 16), ('Property Owner', 'Rowe1419@yahoo.com', 9288, 16), ('Property Owner', 'Roxroy_Ashley@yahoo.com', 9201, 16), ('Property Owner', 'Salvador1973Medina@gmail.com', 9434, 16), ('Property Owner', 'ScottyMiller95@gmail.com', 9620, 16), ('Property Owner', 'Shana@profitablestrategies.com', 9190, 16), ('Property Owner', 'Suragecla@gmail.com', 9324, 16), ('Property Owner', 'Tampa7401@gmail.com', 9526, 16), ('Property Owner', 'Thienthui197599@yahoo.com', 9760, 16), ('Property Owner', 'WhyteMark73.mw@gmail.com', 9497, 16), ('Property Owner', 'Winfield89@yahoo.com', 9777, 16), ('Contractor', 'rochoa@homesteadconcrete.com', 12006, 22), ('Government Entity', 'rricardo@hefmanagement.com', 9989, 10), ('Government Entity', 'SFurtado@HACFL.com', 124, 10), ('Management Company', 'fdesanti@hudson-advisors.com', 11054, 7), ('Contractor', 'hurricanepls@yahoo.com', 1239, 22), ('Property Owner', 'rich@icecreamclub.com', 9113, 16), ('Property Owner', 'Idealindustrialproperties@gmail.com', 6112, 16), ('Contractor', 'noelle@imburgiarealty.com', 8892, 22), ('POA/COA/HOA', 'imperialtowers@att.net', 5774, 14), ('Management Company', 'dan@imperiumcap.com', 12304, 7), ('Property Owner', 'raju_9019@yahoo.com', 12019, 16), ('Property Owner', 'admin@experienceinnovative.com', 12201, 16), ('Management Company', 'mike@ipmflorida.com', 8631, 7), ('Property Owner', 'Michael.Blanton@intermiamicf.com', 12773, 16), ('Management Company', 'zac@interfaceproperties.com', 8168, 7), ('Management Company', 'danderson@investmentslimited.com', 6010, 7), ('Management Company', 'kpisco@investmentslimited.com', 1799, 7), ('Contractor', 'lillya@itascaconstruction.com', 12595, 22), ('Management Company', 'na', 2892, 7), ('Contractor', 'jandmmilling@gmail.com', 12638, 22), ('Contractor', 'jroemer@jray.com', 9887, 22), ('Management Company', 'Jennifer@jlpropertymgmt.com', 1091, 7), ('Contractor', 'scanovali@jackovic-construction.com', 9030, 22), ('Contractor', 'ed.foss@jacksonld.com', 5084, 22), ('Management Company', 'Hello@JacksonLastra.com', 12497, 7), ('General Contact', 'griff350@gmail.com', 8871, 18), ('Management Company', 'alvarado@allpropsys.net', 11500, 7), ('General Contact', 'policanoj@volencenter.com', 1086, 18), ('General Contact', 'operations@jasminhospitality.com', 13052, 18), ('General Contact', 'Jasongiven@bellsouth.net', 8975, 18), ('General Contact', 'Jhenig23@aol.com', 8968, 18), ('Contractor', 'Patty@jbibuilders.com', 13102, 22), ('Contractor', 'dov@jbldvlp.com', 12925, 22), ('Property Owner', 'jwpub@hotmail.com', 12116, 16), ('Property Owner', 'pteixeira@jeldwen.com', 11644, 16), ('Contractor', 'jetpave@bellsouth.net', 8669, 22), ('Contractor', 'estimating@jfbconstruction.net', 12687, 22), ('Property Owner', 'jmbaker0492@gmail.com', 11546, 16), ('Property Owner', 'na', 10206, 16), ('Contractor', 'thomasw@jjwconstruction.com', 12905, 22), ('Management Company', 'Nicole@JKpropertymanagement.com', 9672, 7), ('Contractor', 'alex@jlsservices.net', 7308, 22), ('Property Owner', 'dennis.arserio@jmfamily.com', 5304, 16), ('Management Company', 'barrton@jmdproperties.com', 12121, 7), ('Contractor', 'JNlandservices@outlook.com', 11231, 22), ('General Contact', 'Msco14@aol.com', 8850, 18), ('POA/COA/HOA', 'na', 9145, 14), ('Contractor', 'jfiore611@gmail.com', 8640, 22), ('Property Owner', 'jengland2413@gmail.com', 9221, 16), ('Property Owner', 'jhhaug@hotmail.com', 10772, 16), ('POA/COA/HOA', 'jwitt@jkvfl.com', 12561, 14), ('Contractor', 'Jon@jonathanthomasdevelopers.com', 10060, 22), ('Property Owner', 'Joytowingfl@gmail.com', 12915, 16), ('Management Company', 'jamie@jsbprop.com', 5865, 7), ('Property Owner', 'mikecuts@gmail.com', 10158, 16), ('Contractor', 'joevolkman32@gmail.com', 8999, 22), ('Property Owner', '6061@comcast.net', 12395, 16), ('Management Company', 'mariana7000@gmail.com', 12641, 7), ('Contractor', 'mphillips@kastbuild.com', 5404, 22), ('Management Company', 'Mvoyer@KPRCenters.com', 11249, 7), ('Contractor', 'htaylor@kaufmanlynn.com', 7734, 22), ('Property Owner', 'j.morgan@kbelectronics.net', 12785, 16), ('Property Owner', 'Krisk913@gmail.com', 10978, 16), ('POA/COA/HOA', 'pm@kendallbreezehoa.org', 12692, 14), ('POA/COA/HOA', 'normapa@bellsouth.net', 8690, 14), ('General Contact', 'Chemist7565@hotmail.com', 9051, 18), ('Management Company', 'brandon@keyirc.com', 12386, 7), ('Contractor', 'leonardo.velandia@kiewit.com', 12460, 22), ('Contractor', 'kilbournepaving@aol.com', 9197, 22), ('Property Owner', 'na', 10105, 16), ('Property Owner', 'wayne.jarvis@sci-us.com', 2818, 16), ('Management Company', 'manoloalv00@gmail.com', 11858, 7), ('Management Company', 'iballard@kwpmc.com', 6278, 7), ('Management Company', 'na', 2022, 7), ('POA/COA/HOA', 'abdel.perez@fsresidential.com', 10856, 14), ('POA/COA/HOA', 'sretze@aol.com', 10826, 14), ('POA/COA/HOA', 'Lindoflorida@gmail.com', 10975, 14), ('POA/COA/HOA', 'lagowestcondos@gmail.com', 13018, 14), ('POA/COA/HOA', 'Leewillocks@lakesatbocario.net', 9377, 14), ('POA/COA/HOA', 'President@lakesatlapaz.com', 10082, 14), ('POA/COA/HOA', 'dgaus69@gmail.com', 12934, 14), ('POA/COA/HOA', 'lakewoodatpalmbeach@yahoo.com', 12058, 14), ('Property Owner', 'emmassey@bellsouth.net', 11669, 16), ('POA/COA/HOA', 'Richlevy14339@comcast.net', 10271, 14), ('POA/COA/HOA', 'arbortree@aol.com', 8008, 14), ('Management Company', 'matt@landmarkmgmt.com', 9356, 7), ('Contractor', 'Tom@LandscapeServicePros.com', 9139, 22), ('Contractor', 'Jeremytorisk@gmail.com', 9655, 22), ('POA/COA/HOA', 'ralpha@lviusa.com', 11480, 14), ('Management Company', 'Angelaa@langmanagement.com', 9832, 7), ('Management Company', 'Lyndaa@langmanagement.com', 8746, 7), ('Management Company', 'edp@langmanagement.com', 9488, 7), ('Management Company', 'stevenp@langmanagement.com', 10878, 7), ('Property Owner', 'mweymouth@thelasolascompany.com', 5657, 16), ('POA/COA/HOA', 'stephanie.sicard@fsresidential.com', 12171, 14), ('POA/COA/HOA', 'raymondkubler@gmail.com', 8145, 14), ('POA/COA/HOA', 'raymondkubler@gmail.com', 8147, 14), ('Contractor', 'blake@lasereng.net', 9807, 22), ('POA/COA/HOA', 'JMClean@lauderdalewest.org', 11957, 14), ('General Contact', 'Llrb2910@gmail.com', 8837, 18), ('Property Owner', 'CHGarcia@lazparking.com', 11791, 16), ('Contractor', 'krobinson@lebolo.com', 9409, 22), ('Management Company', 'asmall@ledergroup.com', 2545, 7), ('POA/COA/HOA', 'hurst@legacyhealing.com', 12062, 14), ('Contractor', 'francisco@legnaconstruction.com', 8694, 22), ('POA/COA/HOA', 'larrym24@gmail.com', 11743, 14), ('Property Owner', 'leonmondesir@gmail.com', 11766, 16), ('POA/COA/HOA', 'manager@200lesliecondo.com', 11134, 14), ('National Company', 'mrosen@letspave.com', 4197, 1), ('POA/COA/HOA', 'Beth@superiormgmt.net', 8958, 14), ('POA/COA/HOA', 'susancarroll09@gmail.com', 12000, 14), ('Property Owner', 'rdakak@bellsouth.net', 11244, 16), ('POA/COA/HOA', 'mlisby@lighthousecove.com', 11021, 14), ('Property Owner', 'plugin@lightingcharge.com', 10449, 16), ('Property Owner', 'Suso@listahouse.net', 12152, 16), ('Property Owner', 'Livelikejake@gmail.com', 9379, 16), ('Property Owner', 'tony@ljsheehan.com', 8652, 16), ('POA/COA/HOA', 'UnionOffice@3080Fire.com', 9554, 14), ('Property Owner', 'davidvittier@bachrodt.com', 9993, 16), ('Property Owner', 'mknauss@loubachrodt.com', 10088, 16), ('Management Company', 'apm01@loyaltymgmtgroup.com', 12877, 7), ('Government Entity', 'MChaloux@lynn.edu', 6835, 10), ('Management Company', 'lorraine@mmpm.us', 2339, 7), ('Management Company', 'Jerry@mmpm.com', 171, 7), ('POA/COA/HOA', 'robinbaker13@gmail.com', 8607, 14), ('POA/COA/HOA', 'park432@aol.com', 6289, 14), ('POA/COA/HOA', 'na', 13015, 14), ('POA/COA/HOA', 'jpeskoff@castlegroup.com', 7191, 14), ('Contractor', 'mallardcontracting@gmail.com', 13012, 22), ('Contractor', 'lphillips@mancini-inc.com', 12902, 22), ('Contractor', 'mannexcavation@gmail.com', 11775, 22), ('Management Company', 'manorlanes@aol.com', 12288, 7), ('Property Owner', 'callej@mapei.com', 12047, 16), ('POA/COA/HOA', 'n121cg@aol.com', 7998, 14), ('POA/COA/HOA', 'n121cg@aol.com', 795, 14), ('POA/COA/HOA', 'na', 9392, 14), ('POA/COA/HOA', 'charger9700@yahoo.com', 10030, 14), ('Management Company', 'mitch@marinefundinggroup.com', 8357, 7), ('POA/COA/HOA', 'angelae@langmanagement.com', 4470, 14), ('POA/COA/HOA', 'cdmarquis@liverangewater.com', 8580, 14), ('Property Owner', 'john.bridges@hotelconsmgmt.com', 8529, 16), ('POA/COA/HOA', 'Oceanpinescondomgr@gmail.com', 9102, 14), ('Contractor', 'tony.palumbo@mattamycorp.com', 9246, 22), ('Property Owner', 'rebecca.segovia@yahoo.com', 10680, 16), ('Contractor', 'Baris@mbcconstructiongroup.com', 9360, 22), ('Contractor', 'rpesta@mbrconstruction.com', 5300, 22), ('POA/COA/HOA', 'turner65@bellsouth.net', 1773, 14), ('Management Company', 'methodmanagement@att.net', 8724, 7), ('Management Company', 'Malissa@mhmc.me', 8735, 7), ('Management Company', 'TorjmanProperties@gmail.com', 2449, 7), ('Management Company', 'umajor@miamimanagement.com', 9260, 7), ('Management Company', 'mhansen@miamimanagement.com', 11593, 7), ('Management Company', 'shane@mdcengineers.com', 9047, 7), ('Contractor', 'admin@miamimoldspecialists.com', 12978, 22), ('POA/COA/HOA', 'Judy.Diaz@foundrycommercial.com', 8200, 14), ('Property Owner', 'michaelcasas1979@gmail.com', 8973, 16), ('Property Owner', 'michele_hickford@hotmail.com', 13007, 16), ('Property Owner', 'prvca1@aol.com', 12030, 16), ('POA/COA/HOA', 'juiterwyk@hrpliving.com', 8438, 14), ('General Contact', 'Mflorio14@gmail.com', 9065, 18), ('General Contact', 'mikelisa95@gmail.com', 8912, 18), ('POA/COA/HOA', 'MCCA0166@gmail.com', 10849, 14), ('Contractor', 'mike@mjclandev.com', 6800, 22), ('Management Company', 'joerodrigues1515@gmail.com', 10176, 7), ('Contractor', 'Mksmail1@yahoo.com', 9166, 22), ('Management Company', 'Lou@momentum-properties.net', 6419, 7), ('Management Company', 'Rickderf2@aol.com', 10113, 7), ('Contractor', 'mslswan@aol.com', 8427, 22), ('Contractor', 'shane@mdcengineers.com', 10852, 22), ('Contractor', 'shane@mdcengineers.com', 939, 22), ('Contractor', 'Robbie@MunyanPainting.com', 12510, 22), ('Contractor', 'ger@murphypaintersinc.com', 12186, 22), ('Contractor', 'nandbplumbinginc@gmail.com', 12895, 22), ('Property Owner', 'cferguson@nationsafedrivers.com', 11772, 16), ('Management Company', 'Jaci@Nationalcoingroup.org', 12131, 7), ('Management Company', 'molly.fagan@gonrsg.com', 12010, 7), ('Property Owner', 'naturainc7@comcast.net', 9597, 16), ('Management Company', 'Michael@nealrealty.net', 10347, 7), ('Management Company', 'michael@nealrealty.net', 6737, 7), ('Management Company', 'greg.hyson@nelsonasc.com', 3959, 7), ('POA/COA/HOA', 'edelahoz@opsuites.com', 8936, 14), ('Property Owner', 'na', 9118, 16), ('Property Owner', 'office@NewLifeinDavie.com', 12891, 16), ('POA/COA/HOA', 'yaadgreenberg@gmail.com', 8783, 14), ('Management Company', 'manager@nextgenfla.com', 12051, 7), ('Management Company', 'valp@nextgenfla.com', 9136, 7), ('Management Company', 'tangerine@nextgenfla.com', 2288, 7), ('Contractor', 'raymondkubler@gmail.com', 9871, 22), ('Contractor', 'lmoreno@ninomoreno.com', 12494, 22), ('Management Company', 'mslswan@aol.com', 8432, 7), ('Contractor', 'info@northborobuilders.com', 9149, 22), ('Contractor', 'brian@northstargc.com', 7281, 22), ('Management Company', 'northwestmanager@aol.com', 7404, 7), ('Management Company', 'hgillings@gmail.com', 10196, 7), ('Management Company', 'manuel.lagoa@nuvosuites.com', 10727, 7), ('Contractor', 'Mark@nvmpaving.net', 11888, 22), ('POA/COA/HOA', 'na', 12662, 14), ('POA/COA/HOA', 'kmooney5525@gmail.com', 11984, 14), ('Management Company', 'na', 1754, 7), ('POA/COA/HOA', 'edelahoz@opsuites.com', 5804, 14), ('POA/COA/HOA', 'Manager@oceanviewparkcondo.com', 12972, 14), ('POA/COA/HOA', 'manager@oceanviewparkcondo.com', 8414, 14), ('POA/COA/HOA', 'Ofcrfred@bellsouth.net', 12627, 14), ('Contractor', 'krigerbruce@gmail.com', 11673, 22), ('Contractor', 'dean@orangemencorp.com', 8571, 22), ('Contractor', 'orlandomobileservices@yahoo.com', 7019, 22), ('POA/COA/HOA', 'jason.bailey@brightview.com', 11935, 14), ('POA/COA/HOA', 'na', 12665, 14), ('POA/COA/HOA', 'sunflconst@gmail.com', 13031, 14), ('POA/COA/HOA', 'dannydespain@gmail.com', 8865, 14), ('Property Owner', 'inda@bioverseas.com', 9681, 16), ('Management Company', 'Tharrison.asst2@gmail.com', 6321, 7), ('Contractor', 'spage@pagecontracting.biz', 3331, 22), ('Management Company', 'erick@pbbiltmore.com', 1671, 7), ('Management Company', 'lukem.pbw@gmail.com', 12610, 7), ('POA/COA/HOA', 'na', 4141, 14), ('POA/COA/HOA', 'pgannon@parkpartners.com', 8802, 14), ('POA/COA/HOA', 'devon.newton@stiles.com', 12668, 14), ('POA/COA/HOA', 'Pancallo@lagomar.com', 9314, 14), ('National Company', 'David.Bohner@PandaRG.com', 6271, 1), ('Management Company', 'manager@fairwaysroyaleassn.com', 12571, 7), ('POA/COA/HOA', '88stmanagement@gmail.com', 3999, 14), ('POA/COA/HOA', 'Ptvillas@gmail.com', 9182, 14), ('POA/COA/HOA', 'na', 10242, 14), ('Property Owner', 'pcheung@fau.edu', 9003, 16), ('National Company', 'mstone@paveco.com', 10492, 1), ('National Company', 'Michele@pavementexchange.com', 8515, 1), ('Contractor', 'lfrankel@pebbent.com', 9644, 22), ('Contractor', 'ray@pembertonbuilding.com', 202, 22), ('POA/COA/HOA', 'lenny@pembrokeisles.org', 11603, 14), ('POA/COA/HOA', 'samoz@gmail.com', 12541, 14), ('Contractor', 'supervision@perfectpavers.com', 1839, 22), ('Property Owner', 'Karina@pjlmarine.com', 9096, 16), ('Property Owner', 'peterpandiner@aol.com', 11436, 16), ('General Contact', 'frank@pettineo.om', 9711, 18), ('Management Company', 'ruben.a.benavides@gmail.com', 7524, 7), ('Contractor', 'JRolfe@JeffRolfe.com', 3637, 22), ('Management Company', 'na', 205, 7), ('Management Company', 'luis@phoenixfla.com', 1674, 7), ('POA/COA/HOA', 'na', 3805, 14), ('POA/COA/HOA', 'na', 10215, 14), ('POA/COA/HOA', 'r44line@hotmail.com', 5913, 14), ('Management Company', 'catherine@pinespropertymanagement.com', 206, 7), ('Management Company', 'ksparks@placeservicesinc.com', 8715, 7), ('POA/COA/HOA', 'placideoffice@gmail.com', 9042, 14), ('Property Owner', 'ag@agarchitect.com', 12874, 16), ('Management Company', 'seb6331@aol.com', 9622, 7), ('Management Company', 'stevejhoward@comast.net', 10579, 7), ('Management Company', 'Johnpmg@bellsouth.net', 1928, 7), ('Management Company', 'manager4@pointe.group', 11933, 7), ('Property Owner', 'greg.henady@washingtonprime.com', 2056, 16), ('POA/COA/HOA', 'jericho561@comcast.net', 10806, 14), ('Property Owner', 'jason.schroeder@pw.utc.com', 8471, 16), ('Contractor', 'charlie@precisestripes.com', 9531, 22), ('Management Company', 'mbanmiller@premierassociationservices.com', 1033, 7), ('Management Company', 'manager5@pam-fl.com', 7039, 7), ('Management Company', 'rcurry@premierassociationservices.com', 10219, 7), ('Management Company', 'aventine@presidiocondo.com', 8457, 7), ('Property Owner', 'christina@piinsurancegroup.com', 9934, 16), ('POA/COA/HOA', 'na', 9178, 14), ('Contractor', 'chadrawlinson@princelandinc.com', 6359, 22), ('POA/COA/HOA', 'info@tdsunshine.com', 10704, 14), ('Management Company', 'cdumonceaux@reichelrealty.com', 9617, 7), ('Management Company', 'april@property-keepers.com', 6251, 7), ('Property Owner', 'AhizaHjohnsonPA@gmail.com', 9590, 16), ('Property Owner', 'rcargar@aol.com', 9647, 16), ('Management Company', 'sgarcia@psbusinessparks.com', 10646, 7), ('Management Company', 'paola.constantino@qm-us.com', 12946, 7), ('POA/COA/HOA', 'DBpalamad@gmail.com', 12342, 14), ('POA/COA/HOA', 'dperez2@castlegroup.com', 9569, 14), ('Contractor', 'broncobob68@aol.com', 10281, 22), ('Management Company', 'fernanjr@rgdevelopment.net', 10609, 7), ('Contractor', 'randtdev@bellsouth.net', 12099, 22), ('Management Company', 'Rachel.Rudley@fsresidential.com', 9477, 7), ('Management Company', 'rachel.rudley@fsresidential.com', 1161, 7), ('POA/COA/HOA', 'na', 11953, 14), ('Property Owner', 'JMW@RamseyGlobal.com', 9184, 16), ('Contractor', 'dgilfillan@rangerconstruction.com', 216, 22), ('Contractor', 'Jason.Klocks@rangerconstruction.com', 5623, 22), ('Management Company', 'Jon@rauschcamfl.com', 12023, 7), ('Contractor', 'Tjohnston@rccassociaes.com', 2039, 22), ('Management Company', 'bwhite@realtimepm.com', 3785, 7), ('Property Owner', 'maglusman@aol.com', 8330, 16), ('Contractor', 'scottgreiner@rdcdesignbuild.com', 13062, 22), ('Property Owner', 'jesse@redcon1.com', 12365, 16), ('Contractor', 'marco.garcia-menocal@redlandcompany.com', 9458, 22), ('Contractor', 'ashley@rcsgroup.us', 10270, 22), ('Management Company', 'cdumonceaux@reichelrealty.com', 7850, 7), ('Management Company', 'reliablesunllc@gmail.com', 11197, 7), ('POA/COA/HOA', 'ana@intermng.com', 8540, 14), ('Management Company', 'Bocalakesmanager@gmail.com', 8637, 7), ('Management Company', 'mpalombi@rmcflorida.com', 6449, 7), ('Management Company', 'ryates@rpg123.com', 5292, 7), ('Contractor', 'mike@rhiroofing.com', 13068, 22), ('Contractor', 'michaelsummerville@rickshipman.com', 12281, 22), ('POA/COA/HOA', 'charlie@cwiassoc.com', 11964, 14), ('POA/COA/HOA', 'jamespdacey@aol.com', 8386, 14), ('POA/COA/HOA', 'Epcollins@bellsouth.net', 12472, 14), ('POA/COA/HOA', 'nandez59@gmail.com', 13027, 14), ('Management Company', 'Aprilbuikema.RLL@gmail.com', 9192, 7), ('Management Company', 'jack@rmkms.com', 8877, 7), ('Management Company', 'matthewstelecom@gmail.com', 9078, 7), ('Management Company', 'rick@robertsequities.com', 3905, 7), ('National Company', 'jgarrett@rosepaving.com', 1198, 1), ('National Company', 'drew.demumbrum@rosepaving.com', 11056, 1), ('National Company', 'nile.kurschinski@rosepaving.com', 8626, 1), ('National Company', 'jdaniecki@rosepaving.com', 11425, 1), ('National Company', 'jsmalley@rosepaving.com', 8868, 1), ('National Company', 'logan.barnette@rosepaving.com', 11749, 1), ('National Company', 'jbunworth@rosepaving.com', 12093, 1), ('National Company', 'asimmons@rosepaving.com', 9243, 1), ('National Company', 'ddemumbrum@rosepaving.com', 9455, 1), ('National Company', 'fernando.palacios@rosepaving.com', 11064, 1), ('National Company', 'fredy.ibarra@rosepaving.com', 10421, 1), ('National Company', 'gschmitt@rosepaving.com', 9385, 1), ('National Company', 'mfaber@rosepaving.com', 9729, 1), ('National Company', 'michael.kornacker@rosepaving.com', 11140, 1), ('National Company', 'mwilliams@rosepaving.com', 10481, 1), ('National Company', 'alex.amos@rosepaving.com', 12214, 1), ('National Company', 'bkirley@rosepaving.com', 11126, 1), ('National Company', 'chill@rosepaving.com', 8705, 1), ('National Company', 'colin.rady@rosepaving.com', 11916, 1), ('National Company', 'dfenton@rosepaving.com', 1536, 1), ('National Company', 'elysia.huff@rosepaving.com', 11163, 1), ('National Company', 'llinn@rosepaving.com', 10937, 1), ('National Company', 'mike.kampschnieder@rosepaving.com', 12475, 1), ('National Company', 'nile.kurschinski@rosepaving.com', 8824, 1), ('National Company', 'paul.petrulis@rosepaving.com', 11416, 1), ('National Company', 'tstanislaus@rosepaving.com', 11950, 1), ('Management Company', 'mlanier@rosemurgyproperties.com', 4126, 7), ('POA/COA/HOA', NULL, 230, 14), ('POA/COA/HOA', 'Whaleman@maui.net', 11083, 14), ('POA/COA/HOA', 'royalpalmtowers@att.net', 12584, 14), ('Management Company', 'giselle.rahman@rpmliving.com', 12500, 7), ('Management Company', 'rsnmgmt@gmail.com', 4964, 7), ('Management Company', 'rgowder@rvga.net', 11516, 7), ('Contractor', 'charding@ryanfl.com', 7163, 22), ('Contractor', 'cboone@ryanfl.com', 8776, 22), ('Contractor', 'khenry@ryanfl.com', 8889, 22), ('POA/COA/HOA', 'mmillerproperties@gmail.com', 9088, 14), ('Property Owner', 'ssglickman@aol.com', 8468, 16), ('Property Owner', 'SAFIEProperties@gmail.com', 11183, 16), ('Contractor', 'vassil@saglo.com', 9067, 22), ('POA/COA/HOA', 'na', 8729, 14), ('Property Owner', 'tfalls@tfcm.cc', 8751, 16), ('Property Owner', 'Service@OneSource.inc', 12606, 16), ('POA/COA/HOA', 'KuntzThree@yahoo.com', 11120, 14), ('Property Owner', 'sandrabowyer@hotmail.com', 8839, 16), ('Property Owner', 'Zane.Zynda@saulcenters.com', 10987, 16), ('Property Owner', 'mlmangini@castlegroup.com', 9678, 16), ('Management Company', 'na', 12576, 7), ('Contractor', 'mail@scandinavianbuildersinc.com', 5916, 22), ('Management Company', 'tim.mclean@schultedc.com', 11810, 7), ('General Contact', 'Scott76@yahoo.com', 9053, 18), ('Property Owner', 'scottclancy1@yahoo.com', 10434, 16), ('Management Company', 'na', 3967, 7), ('Management Company', 'ahuggins@sepropertyadvicors.com', 13080, 7), ('Management Company', 'krystle@seabreezecms.com', 239, 7), ('Management Company', 'na', 240, 7), ('Contractor', 'djohnson@seakayconstruction.com', 9331, 22), ('General Contact', '1shakasean@gmail.com', 8677, 18), ('POA/COA/HOA', 'breese@crockerpartners.com', 8205, 14), ('Property Owner', 'aspinnato@verizon.net', 9127, 16), ('Management Company', 'seminolemarine@comcast.net', 11295, 7), ('POA/COA/HOA', 'myswerveon@gmail.com', 9327, 14), ('Property Owner', 'brianclark@semtribe.com', 10911, 16), ('Contractor', '7starsGC@Att.Net', 6128, 22), ('Contractor', 'dankkkk@aol.com', 11840, 22), ('Contractor', 'jerry@pavement.net', 9294, 22), ('Property Owner', 'sinatra.sharon@gmail.com', 12579, 16), ('Contractor', 'rantola@sharpgc.com', 2303, 22), ('Property Owner', 'tomsheehan@sheehanbuickgmc.com', 9595, 16), ('Property Owner', 'manapolsky@sheehanbuickgmc.com', 9172, 16), ('Property Owner', 'tomsheehan@sheehanbuickgmc.com', 9709, 16), ('POA/COA/HOA', 'Johnmonestime@bellsouth.net', 9740, 14), ('POA/COA/HOA', 'sbtb@comcast.net', 1047, 14), ('Contractor', 'jds@shiff.com', 8909, 22), ('POA/COA/HOA', 'vanessasusar@gmail.com', 9298, 14), ('Management Company', 'cytcyt0909@hotmail.com', 8452, 7), ('Contractor', 'jcarlino@sirenconstruct.com', 12399, 22), ('Management Company', 'skabrysr@aol.com', 9304, 7), ('Contractor', 'david@slbgeneral.com', 8402, 22), ('Contractor', 'jimmie@smithsonnet.com', 8244, 22), ('Contractor', 'solomoscontracting@gmail.com', 9105, 22), ('Management Company', 'Jenny@solutions-re.com', 10692, 7), ('Management Company', 'afalco@somprop.com', 12466, 7), ('Management Company', 'Sopherrealtymiami@aol.com', 10602, 7), ('Government Entity', 'andrew@sbdd.org', 10716, 10), ('Property Owner', 'info@pmisouthflorida.com', 7871, 16), ('Management Company', 'Golladay_John@comcast.net', 12531, 7), ('POA/COA/HOA', 'mgmtcondo@aol.com', 8624, 14), ('POA/COA/HOA', 'Barbara.Basile.Miller@gmail.com', 6759, 14), ('Management Company', 'nikko@sp24services.com', 8547, 7), ('Contractor', 'BSnyder@SPPlus.com', 1869, 22), ('POA/COA/HOA', 'rich.osgood@gmail.com', 9175, 14), ('POA/COA/HOA', 'bshiles@spanishwellscountryclub.com', 9874, 14), ('Government Entity', 'rquiroga@sdsinc.org', 11116, 10), ('Management Company', 'mike@spotsourcesolutions.com', 1048, 7), ('POA/COA/HOA', 'info@sketchmasters.com', 11663, 14), ('Property Owner', 'sfdentalcare@hotmail.com', 10685, 16), ('Property Owner', 'richard@stpaulboca.com', 1088, 16), ('Management Company', 'Shannonb@starpropertiesre.com', 9250, 7), ('Contractor', 'na', 6547, 22), ('Contractor', 'bb@starsealfl.com', 12134, 22), ('Contractor', 'statewidegrading@yahoo.com', 5191, 22), ('Contractor', 'na', 11061, 22), ('Management Company', 'gabyselcer@bellsouth.net', 9898, 7), ('General Contact', 'SueL81@aol.com', 8862, 18), ('General Contact', 'skokie208@gmail.com', 8880, 18), ('Management Company', 'anthony.valdez@stiles.com', 7737, 7), ('Contractor', 'dennis@scg.builders', 9388, 22), ('Management Company', 'Sebastian@StoreageSense.com', 11257, 7), ('National Company', 'gregg@stripe-america.com', 12354, 1), ('Contractor', 'j.bryan@stryker-electric.com', 10395, 22), ('POA/COA/HOA', 'WaldenRW1@gmail.com', 11938, 14), ('Contractor', 'steve@summasasphaltconcrete.com', 4974, 22), ('Contractor', 'summas@live.com', 8758, 22), ('Management Company', 'schowd4416@aol.com', 12423, 7), ('POA/COA/HOA', 'Yvonne.Reyes@fsresidential.com', 9208, 14), ('Management Company', 'imusthaveitall@me.com', 12431, 7), ('POA/COA/HOA', 'nhermida@castlegroup.com', 4584, 14), ('Management Company', 'bob@sunsetmaintenance.org', 12650, 7), ('Contractor', 'backflowjosh@aol.com', 10398, 22), ('Management Company', 'Jhernandez@sunstatecam.com', 7211, 7), ('Property Owner', 'supersudscarwash@comcast.net', 9794, 16), ('Management Company', 'beth@superiormgmt.net', 1126, 7), ('Contractor', 'jevans@specengineering.net', 3673, 22), ('Contractor', 'vcenteno@sweepingcorp.com', 12196, 22), ('Management Company', 'kenny@swflcam.com', 7472, 7), ('Management Company', 'CarlosRam6@aol.com', 2079, 7), ('Management Company', 'james@tgms.com', 9037, 7), ('Contractor', 'tandsroofing@comcast.net', 12853, 22), ('Property Owner', 'na', 8900, 16), ('Management Company', 'Sandro@tmc-management.com', 8267, 7), ('Management Company', 'Tina@tallfield.com', 8645, 7), ('National Company', 'ttply18@aol.com', 10374, 1), ('Management Company', 'rudy.hernandez@tawinc.com', 9702, 7), ('Contractor', 'tldinc@bellsouth.net', 1822, 22), ('Management Company', 'info@tdsunshine.com', 3390, 7), ('Contractor', 'lindsey.crawford@tdsconstruction.com', 8500, 22), ('Property Owner', 'jarret@technicsdental.com', 12770, 16), ('Contractor', 'televacsouth@bellsouth.net', 8040, 22), ('Property Owner', 'Marck.Reiner52@gmail.com', 11397, 16), ('Property Owner', 'na', 11433, 16), ('Management Company', 'Abc12345@gmail.com', 8618, 7), ('General Contact', 'Thad70@hotmail.com', 9086, 18), ('POA/COA/HOA', 'AMIR.AMIRANI@THEADDISON.COM', 8382, 14), ('POA/COA/HOA', 'joe@bnaiaviv.org', 10401, 14), ('POA/COA/HOA', 'fritacco@thebocaraton.com', 12239, 14), ('POA/COA/HOA', 'rickderf2@aol.com', 11929, 14), ('Property Owner', 'chg@thecarpetboutique.com', 11401, 16), ('POA/COA/HOA', 'adimauro@theclubatbocapointe.com', 6492, 14), ('Property Owner', 'dena@thecookandcork.com', 11754, 16), ('POA/COA/HOA', 'natygrose@yahoo.com', 9452, 14), ('POA/COA/HOA', 'vblack5@comcast.met', 8594, 14), ('POA/COA/HOA', 'bonaventureleasing@roizman.com', 1787, 14), ('Property Owner', 'ralphbutton@onehope.net', 11763, 16), ('Management Company', 'swalbrecht@thekroenkegroup.com', 4383, 7), ('Management Company', 'na', 8476, 7), ('Property Owner', 'gbethea@tlechildcare.com', 12803, 16), ('POA/COA/HOA', 'Info@THEMARQUESACONDOMINIUMS.COM', 12125, 14), ('POA/COA/HOA', 'mwftlaud@bellsouth.net', 2669, 14), ('POA/COA/HOA', 'Manager@grandpalmsresorts.com', 10651, 14), ('National Company', 'eli@thepavementgroup.com', 8504, 1), ('POA/COA/HOA', 'nick@poloclub.net', 10745, 14), ('POA/COA/HOA', 'yaadgreenberg@gmail.com', 9282, 14), ('POA/COA/HOA', 'manager@rockharborclub.com', 7538, 14), ('Contractor', 'PKnight@ryanfl.com', 3796, 22), ('POA/COA/HOA', 'Virgilalonzo@aol.com', 7419, 14), ('POA/COA/HOA', 'amartija@thestoutgroup.com', 12082, 14), ('POA/COA/HOA', 'Warcon7002@gmail.com', 7046, 14), ('POA/COA/HOA', 'aaperez@bellsouth.net', 9953, 14), ('Management Company', 'Bonita@thewrightcommunity.com', 12263, 7), ('Property Owner', 'grace@theyachtclubaventura.com', 9006, 16), ('General Contact', 'andrew@ancoprecision.com', 12489, 18), ('Contractor', 'tomwilliamstwci@msn.com', 11564, 22), ('POA/COA/HOA', 'TideWaterEstates-Mgr@outlook.com', 8092, 14), ('Property Owner', 'ee@eesmith.net', 4362, 16), ('Management Company', 'RPTJN1@comcast.net', 12818, 7), ('Management Company', 'iballard@tmrealtyservices.com', 12695, 7), ('Management Company', 'TorjmanProperties@gmail.com', 11884, 7), ('POA/COA/HOA', 'mgr.tovs@gmail.com', 12339, 14), ('Government Entity', 'ScottI@jupiter.fl.us', 2282, 10), ('POA/COA/HOA', 'manuelfcogcm@gmail.com', 9857, 14), ('POA/COA/HOA', 'kat7351@gmail.com', 9969, 14), ('Property Owner', 'taylorpostin@edmorse.com', 12898, 16), ('Management Company', 'therese@tridentmiami.com', 12469, 7), ('Contractor', 'zgarces@trintecinc.com', 12675, 22), ('General Contact', 'drtsiostsias@aol.com', 11451, 18), ('Property Owner', 'bded1@aol.com', 13118, 16), ('POA/COA/HOA', 'rnbkr@yahoo.com', 1080, 14), ('POA/COA/HOA', 'jonathan.Greiger@kci.com', 12368, 14), ('POA/COA/HOA', 'dshelton@campbellproperty.com', 13043, 14), ('Contractor', 'sales@uspave.com', 10863, 22), ('Property Owner', 'miriam@uhs-hardware.com', 11723, 16), ('Property Owner', 'miriam@uhs-warehouse.com', 11719, 16), ('General Contact', 'george@uniqueproducers.com', 8832, 18), ('Management Company', 'jrausch@unitedcommunity.net', 11370, 7), ('Management Company', 'michele@unitedcommunity.net', 2352, 7), ('Property Owner', 'noemail@none.com', 8793, 16), ('Contractor', 'bri.palaniuk@usbrickandblock.com', 9638, 22), ('Contractor', 'nickm@uscpfl.com', 11036, 22), ('POA/COA/HOA', 'manager@vacationinnrvpark.com', 12602, 14), ('POA/COA/HOA', 'rlinares@dmresorts.com', 12242, 14), ('POA/COA/HOA', 'bbashkoff@gmail.com', 6381, 14), ('Contractor', 'varneytree@yahoo.com', 11945, 22), ('Management Company', 'brothersutilities@gmail.com', 10708, 7), ('Property Owner', 'jconnet@vccusa.com', 12016, 16), ('Management Company', 'randy.johnson@velex.com', 12634, 7), ('POA/COA/HOA', 'donald.dean10@comcast.net', 8375, 14), ('Contractor', 'sb@vercetti.net', 8586, 22), ('Contractor', 'chris.taraba@verdex.com', 10754, 22), ('Property Owner', 'Mark@verochem.com', 12027, 16), ('Management Company', 'Dbrown@vestapropertyservices.com', 8543, 7), ('POA/COA/HOA', 'tahughes7777@att.net', 10056, 14), ('Property Owner', 'Vcarter8060@gmail.com', 8764, 16), ('POA/COA/HOA', 'johnvictoriaisles@gmail.com', 12106, 14), ('POA/COA/HOA', 'na', 11825, 14), ('POA/COA/HOA', 'villaescondidahoa@gmail.com', 11047, 14), ('POA/COA/HOA', 'na', 11207, 14), ('POA/COA/HOA', 'villageparkoakland@outlook.com', 10483, 14), ('POA/COA/HOA', 'DBPalamad@gmail.com', 8773, 14), ('POA/COA/HOA', 'villaslakesPM@comcast.net', 4327, 14), ('Property Owner', 'na', 9764, 16), ('Property Owner', 'dvitier@vtpgo.com', 11543, 16), ('General Contact', 'diana@vividinteriorsfl.com', 12414, 18), ('General Contact', 'jlandin@wellermgt.com', 11597, 18), ('Management Company', 'cdaniel@whbass.com', 12109, 7), ('Contractor', 'justice@wabentz.com', 6744, 22), ('Contractor', 'MWalker@walkercontractinggroup.com', 5897, 22), ('POA/COA/HOA', 'walnutcreekfla@comcast.net', 8577, 14), ('Property Owner', 'w180adm@costco.com', 10120, 16), ('Management Company', 'jonathan.martin@washingtonprime.com', 8056, 7), ('Property Owner', 'cschulle@wasteprousa.com', 10367, 16), ('Property Owner', 'hanyharoun57@yahoo.com', 11954, 16), ('Management Company', 'na', 13090, 7), ('Management Company', 'tom@wbmanage.com', 5007, 7), ('Contractor', 'sdeckard@westconstructioninc.net', 8366, 22), ('POA/COA/HOA', 'pvelandia@miamimanagement.com', 12406, 14), ('Management Company', 'barbara.graw@colliers.com', 10319, 7), ('Contractor', 'andreb@westwindcontracting.com', 10814, 22), ('POA/COA/HOA', 'beaslone@bellsouth.com', 5769, 14), ('Contractor', 'jessica.luce@westwoodcontractors.com', 9998, 22), ('POA/COA/HOA', 'patrickpenkwitt@mac.com', 9485, 14), ('Contractor', 'gabriel.marquez@whiting-turner.com', 11147, 22), ('Contractor', 'raymondkubler@gmail.com', 9684, 22), ('POA/COA/HOA', 'david@legacygroupsvcs.com', 11576, 14), ('POA/COA/HOA', 'sandysonkin323@gmail.com', 10634, 14), ('Management Company', 'miramarmaint@windsorcommunities.com', 10150, 7), ('POA/COA/HOA', 'manager@windwoodboca.com', 5443, 14), ('General Contact', 'plum1234@aol.com', 9130, 18), ('POA/COA/HOA', 'manager@windwoodboca.com', 5441, 14), ('General Contact', 'fernando.2fconsulting@gmail.com', 12921, 18), ('General Contact', 'fernando.2fconsulting@gmail.com', 12918, 18), ('POA/COA/HOA', 'bddy5398@aol.com', 10110, 14), ('POA/COA/HOA', 'atisci@pbgrading.net', 5049, 14), ('Management Company', 'na', 1306, 7), ('Contractor', 'prohkamm@worlddiamondsource.com', 9073, 22), ('POA/COA/HOA', 'na', 12274, 14), ('POA/COA/HOA', 'jcecile64@gmail.com', 12506, 14), ('Property Owner', 'malikm@xtremeactionpark.com', 12984, 16), ('Property Owner', 'yachthavendocks@gmail.com', 8754, 16), ('Management Company', 'info@yourmanagementservices.com', 12748, 7), ('Management Company', 'hpages@zabikandassociates.com', 8524, 7), ('Management Company', 'jyoung@zaymanagement.com', 8602, 7), ('Contractor', 'nick@zicarosplumbing.com', 10155, 22), ('Contractor', 'zieglerbuilders@yahoo.com', 8372, 22), ('Management Company', 'mwc@zrsmanagement.com', 11529, 7);
COMMIT;


Update contacts 
join newcontacttypes on newcontacttypes.contact_id = contacts.id
Set contacts.contact_type_id = newcontacttypes.contact_type_id;
