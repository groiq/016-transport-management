
/* Database for transport management project */

drop database if exists transport_management;
create database transport_management;
use transport_management;

CREATE TABLE locations (
    location_id INT PRIMARY KEY AUTO_INCREMENT,
    name NVARCHAR(32),
    latitude DECIMAL(8 , 6 ),
    longitude DECIMAL(9 , 6 )
);

CREATE TABLE distances (
    distance_id INT PRIMARY KEY AUTO_INCREMENT,
    start_id INT,
    target_id INT,
    distance DECIMAL(8 , 2 )
);

CREATE TABLE trucks (
    truck_id INT PRIMARY KEY AUTO_INCREMENT,
    license_plate NVARCHAR(8),
    fixed_cost_per_hour DECIMAL(8 , 2 ),
    cost_per_km DECIMAL(8 , 2 ),
    avg_speed DECIMAL(8 , 2 )
);

CREATE TABLE loads (
    load_id INT PRIMARY KEY AUTO_INCREMENT,
    truck_id INT,
    start_location_id INT,
    target_location_id INT,
    start_time TIMESTAMP NULL DEFAULT NULL,
    target_time_estimate TIMESTAMP NULL DEFAULT NULL,
    duration_estimate time,
    target_time_actual TIMESTAMP NULL DEFAULT NULL,
    duration_actual time,
    FOREIGN KEY (truck_id)
        REFERENCES trucks (truck_id),
    FOREIGN KEY (start_location_id)
        REFERENCES locations (location_id),
    FOREIGN KEY (target_location_id)
        REFERENCES locations (location_id)
);

CREATE TABLE load_legs (
    load_leg_id INT PRIMARY KEY AUTO_INCREMENT,
    load_id INT,
    start_location_id INT,
    target_location_id INT,
    number_in_sequence INT,
    start_time_estimate TIMESTAMP NULL DEFAULT NULL,
    target_time_estimate TIMESTAMP NULL DEFAULT NULL,
    duration_estimate time,
    start_time_actual TIMESTAMP NULL DEFAULT NULL,
    target_time_actual TIMESTAMP NULL DEFAULT NULL,
    duration_actual time,
    FOREIGN KEY (load_id)
        REFERENCES loads (load_id),
    FOREIGN KEY (start_location_id)
        REFERENCES locations (location_id),
    FOREIGN KEY (target_location_id)
        REFERENCES locations (location_id)
);



insert into locations (name,latitude,longitude) values ('Bregenz',47.505,9.749167);
insert into locations (name,latitude,longitude) values ('Innsbruck',47.267222,11.392778);
insert into locations (name,latitude,longitude) values ('Salzburg',47.8,13.033333);
insert into locations (name,latitude,longitude) values ('Linz',48.303056,14.290556);
insert into locations (name,latitude,longitude) values ('Klagenfurt',46.617778,14.305556);
insert into locations (name,latitude,longitude) values ('St. Poelten',48.204722,15.626667);
insert into locations (name,latitude,longitude) values ('Graz',47.066667,15.433333);
insert into locations (name,latitude,longitude) values ('Wien',48.208333,16.373056);
insert into locations (name,latitude,longitude) values ('Eisenstadt',47.845556,16.518889);

insert into trucks (license_plate,fixed_cost_per_hour,cost_per_km,avg_speed) values ('LL-1234G',100.0,50.0,50.0);

insert into distances (start_id,target_id,distance) values (1,2,100);
insert into distances (start_id,target_id,distance) values (1,3,100);
insert into distances (start_id,target_id,distance) values (1,4,100);
insert into distances (start_id,target_id,distance) values (1,5,100);
insert into distances (start_id,target_id,distance) values (1,6,100);
insert into distances (start_id,target_id,distance) values (1,7,100);
insert into distances (start_id,target_id,distance) values (1,8,100);
insert into distances (start_id,target_id,distance) values (1,9,100);
insert into distances (start_id,target_id,distance) values (2,3,100);
insert into distances (start_id,target_id,distance) values (2,4,100);
insert into distances (start_id,target_id,distance) values (2,5,100);
insert into distances (start_id,target_id,distance) values (2,6,100);
insert into distances (start_id,target_id,distance) values (2,7,100);
insert into distances (start_id,target_id,distance) values (2,8,100);
insert into distances (start_id,target_id,distance) values (2,9,100);
insert into distances (start_id,target_id,distance) values (3,4,100);
insert into distances (start_id,target_id,distance) values (3,5,100);
insert into distances (start_id,target_id,distance) values (3,6,100);
insert into distances (start_id,target_id,distance) values (3,7,100);
insert into distances (start_id,target_id,distance) values (3,8,100);
insert into distances (start_id,target_id,distance) values (3,9,100);
insert into distances (start_id,target_id,distance) values (4,5,100);
insert into distances (start_id,target_id,distance) values (4,6,100);
insert into distances (start_id,target_id,distance) values (4,7,100);
insert into distances (start_id,target_id,distance) values (4,8,100);
insert into distances (start_id,target_id,distance) values (4,9,100);
insert into distances (start_id,target_id,distance) values (5,6,100);
insert into distances (start_id,target_id,distance) values (5,7,100);
insert into distances (start_id,target_id,distance) values (5,8,100);
insert into distances (start_id,target_id,distance) values (5,9,100);
insert into distances (start_id,target_id,distance) values (6,7,100);
insert into distances (start_id,target_id,distance) values (6,8,100);
insert into distances (start_id,target_id,distance) values (6,9,100);
insert into distances (start_id,target_id,distance) values (7,8,100);
insert into distances (start_id,target_id,distance) values (7,9,100);
insert into distances (start_id,target_id,distance) values (8,9,100);

-- select * from locations;

/*
insert into loads (truck_id, start_location_id,start_time_estimate) values (1,3,current_timestamp);

insert into load_legs (load_id,location_id,number_in_sequence,time_estimate) values (1,4,1,current_timestamp);
insert into load_legs (load_id,location_id,number_in_sequence,time_estimate) values (1,6,2,current_timestamp);
insert into load_legs (load_id,location_id,number_in_sequence,time_estimate) values (1,8,3,current_timestamp);

-- select * from locations;
-- select * from loads;

SELECT 
    load_id,
    truck_id,
    loads.start_location_id,
    start_locations.name AS start_loc_name,
    -- loads.target_location_id,
    -- target_locations.name AS target_loc_name,
    leg_locations.location_id as leg_loc_id, leg_locations.name as leg_loc_name,
    load_legs.number_in_sequence as leg_sequence_number
FROM
    trucks
        JOIN
    loads USING (truck_id)
        JOIN
    locations start_locations ON start_locations.location_id = loads.start_location_id
        -- JOIN
    -- locations target_locations ON target_locations.location_id = loads.target_location_id
        LEFT JOIN
    load_legs USING (load_id)
        JOIN
    locations leg_locations ON leg_locations.location_id = load_legs.location_id
;
*/

-- stored procedures

USE `transport_management`;
DROP procedure IF EXISTS `add_load`;

DELIMITER $$
USE `transport_management`$$
CREATE PROCEDURE `add_load` (start_location_param int, target_location_param int, truck_param int)
BEGIN
	-- $statement = $pdo->prepare("INSERT INTO loads (start_location_id,target_location_id,truck_id) VALUES (?,?,?);");
	insert into loads (start_location_id,target_location_id,truck_id) values (start_location_param,target_location_param,truck_param);
END$$

DELIMITER ;


USE `transport_management`;
DROP procedure IF EXISTS `distance`;

DELIMITER $$
USE `transport_management`$$
CREATE PROCEDURE `distance` (in start_id_param int, target_id_param int, out distance_param decimal(8,2))
BEGIN
	if (start_id_param < target_id_param) then
		select distance into distance_param from distances where start_id = start_id_param and target_id = target_id_param;
	elseif (start_id_param > target_id_param) then
		select distance into distance_param from distances where start_id = target_id_param and target_id = start_id_param;
	else 
		select 0.0 into distance_param;
    end if;
END$$

DELIMITER ;



USE `transport_management`;
DROP procedure IF EXISTS `add_leg`;

DELIMITER $$
USE `transport_management`$$
CREATE PROCEDURE `add_leg` (load_param int, start_location_param int, target_location_param int)
BEGIN

	declare number_in_sequence_next int default 0;
    declare load_target_location_id int default 0;
    declare avg_speed_local decimal(8,2);
    declare distance_local decimal(8,2);
    declare duration_estimate_calculated time;
    
    -- handle leg count
    set number_in_sequence_next = ((select max(number_in_sequence) from load_legs where load_id = load_param)+1);
    if (number_in_sequence_next is null) then
		set number_in_sequence_next = 1;
	end if;
	
    -- calculate duration estimate
--     CALL GetOrderCountByStatus('Shipped',@total);
-- SELECT @total;
	call distance(start_location_param, target_location_param, @distance);
    -- set distance_local = select  @distance;
   --  set distance_local = distance(start_location_param, target_location_param);
    -- set distance_local = select  @distance;
    set avg_speed_local = (select avg_speed from trucks join loads using (truck_id) where loads.load_id = load_param);
    set duration_estimate_calculated = sec_to_time(floor((@distance / avg_speed_local) * 3600));
    
    -- the actual insert
    insert into load_legs 
			(load_id, start_location_id, target_location_id, number_in_sequence, duration_estimate) 
		values 
			(load_param, start_location_param, target_location_param, number_in_sequence_next, duration_estimate_calculated);
    
    -- if leg target equals load target, then we are on the, uh, last leg. In this case calculate duration estimate for the whole journey. 
    set load_target_location_id = (select target_location_id from loads where load_id = load_param);
    if (target_location_param = load_target_location_id) then
		update loads set duration_estimate = ( select sum(duration_estimate) from load_legs where load_id = load_param ) where load_id = load_param;
    end if;
    
END$$

DELIMITER ;




-- USE `transport_management`;
-- DROP procedure IF EXISTS `checkpoint`;

-- DELIMITER $$
-- USE `transport_management`$$
-- CREATE PROCEDURE `checkpoint` (load_param int, location_param int, time_param timestamp)
-- BEGIN
-- 	select (load_param, location_param, time_param);
-- END$$

-- DELIMITER ;


drop view if exists full_load_data;
CREATE VIEW load_leg_data AS
    (SELECT 
        load_id,
        number_in_sequence,
        start_location_id,
        start_location.name AS start_location_name,
        target_location_id,
        target_location.name AS target_location_name
    FROM
        locations start_location
            JOIN
        load_legs ON start_location.location_id = load_legs.start_location_id
            JOIN
        locations target_location ON load_legs.target_location_id = target_location.location_id) ORDER BY number_in_sequence;

/*
$statement = $pdo->prepare("call insert_timestamp(?,?,?);");
// $statement->execute(array($_GET['loadId'],$_GET['legId'],$_GET['timestamp']));
*/

USE `transport_management`;
DROP procedure IF EXISTS `add_timestamp`;

DELIMITER $$
USE `transport_management`$$
CREATE PROCEDURE `add_timestamp` (load_id_param int, leg_number_param int, timestamp_param timestamp)
BEGIN
	-- if leg# is 0, then we are starting the first leg
	if (leg_number_param = 0) then
		update load_legs set start_time_estimate = timestamp_param, start_time_actual = timestamp_param where load_id = load_id_param and number_in_sequence = 1;
	-- otherwise we finish the current leg and start the next one
	else
		update load_legs set target_time_actual = timestamp_param, duration_actual = timediff(timestamp_param, start_time_actual) where load_id = load_id_param and number_in_sequence = leg_number_param;
        update load_legs set start_time_actual = timestamp_param where load_id = load_id_param and number_in_sequence = (leg_number_param + 1);
    end if;
	-- recalculate ALL time estimates for legs from here on
END$$

DELIMITER ;


