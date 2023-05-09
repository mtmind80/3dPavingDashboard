
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
Update  accepted_documents set extension = 'gif,jpg,png' where type ='Image';


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




Update contacts set postal_code = '33323' where postal_code ='33423';
Update contacts set postal_code = '33341' where postal_code ='33431';
Update contacts set postal_code = '33936' where postal_code ='33963';

 
Update contacts set postal_code = '33255' WHERE postal_code ='33258';

#update counties in contacts
UPDATE contacts p JOIN counties c on c.zip = p.postal_code Set p.county = c.county; 

#check any missing zips that we might find
SELECT postal_code, count(*) FROM `contacts` WHERE postal_code <> '' 
AND LEFT(postal_code,2) = '33' 
AND postal_code is not null 
AND LENGTH(postal_code) = 5 
AND county is null 
Group By postal_code 
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
UPDATE proposal_details set id = id* 42333;
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


#SELECT * FROM potbljobordersubcontractors JOIN potbljoborderdetail on potbljoborderdetail.jordID = potbljobordersubcontractors.posubjordID JOIN potbljoborders on potbljoborders.jobID = potbljoborderdetail.jordJobID WHERE YEAR(potbljoborders.jobCreatedDateTime) > 2019 


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

UPDATE equipment set id = id * 2121;
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
rate, 
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
striping_cost_id,
quantity,
cost,
description
)
SELECT 
jobmultijordID, 
jobmultiScatID,
jobmultiQuantity,
jobmultiCost,
jobmultiSERVICE_DESC
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
service_id
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



#run 
#load fake data
#Build Actions
#Build MaxIDs


#DROP TABLES