TRUNCATE `duplocations`;

Insert Into duplocations Select MIN(id) as bad, MAX(id) as gid, LEFT(address_line1, 15) as addr, city, postal_code from locations
GROUP BY LEFT(address_line1, 15), city, postal_code
HAVING COUNT(*) >=2;

#Select MIN(id) as bad, MAX(id) as gid, LEFT(address_line1, 15) as addr, city, postal_code from locations
#GROUP BY LEFT(address_line1, 15), city, postal_code
#HAVING COUNT(*) >=2;

#Select MIN(id) as bad, MAX(id) as gid, LEFT(address_line1, 12) as addr, city, postal_code from locations
#GROUP BY LEFT(address_line1, 12), city, postal_code
#HAVING COUNT(*) >=2;

