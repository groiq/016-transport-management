
/* Doodles and sample queries designed to be executed line by line as needed. */
/* -------------------------------------------------------------------------- */

use transport_management;
show tables;


/* Handle loads + load legs */
/* ------------------------ */

-- insert load #1 and #2 if needed
insert into loads (truck_id, start_location_id, target_location_id) values (1,3,8); 
insert into loads (truck_id, start_location_id, target_location_id) values (1,3,8); 

-- delete legs from load #1 if needed
delete from load_legs where load_id = 2;

select * from loads;

-- add legs
call add_leg(1,3,4);
call add_leg(1,4,6);
call add_leg(1,6,8);

select * from loads;

-- add timestamps and keep tracking
select * from load_legs order by load_id, number_in_sequence;
call add_timestamp (1,0,'19-12-17 08:00:00');
select * from load_legs order by load_id, number_in_sequence;
call add_timestamp (1,1,'19-12-17 09:00:00');
select * from load_legs order by load_id, number_in_sequence;
call add_timestamp (1,2,'19-12-17 10:00:00');
select * from load_legs order by load_id, number_in_sequence;
call add_timestamp (1,3,'19-12-17 11:00:00');
select * from load_legs order by load_id, number_in_sequence;

select * from loads;

select * from load_reports;

select * from load_legs where load_id = 3 order by number_in_sequence;

/*---------------------------------*/

insert into loads (truck_id, start_location_id, target_location_id) values (1,8,9); 
set @curload = 4;
select @curload;

call add_leg(@curload,8,9);

call add_timestamp (@curload,0,'2019-12-18 16:17:30');
call add_timestamp (@curload,1,'2019-12-18 16:17:32');

select * from loads;
select * from loads where load_id = @curload;
select * from load_legs order by load_id, number_in_sequence;
select * from load_legs where load_id = @curload order by number_in_sequence;
select * from load_reports;

/*-------------*/
select current_timestamp;

/* from here on it's pretty much legacy */
/* ------------------------------------ */

-- select max(number_in_sequence) from load_legs where load_id = 2;
select * from distances;


select * from loads order by load_id;
    

-- SET GLOBAL log_bin_trust_function_creators = 1;

select * from locations;

insert into locations (name,latitude,longitude) values ('equator',0,0);

select * from locations;

select * from locations where name = 'equator';
delete from locations where location_id = 10;

select * from locations;

select * from loads;
select * from trucks;
select * from load_legs;

-- locations and distances
SELECT 
    *
FROM
    locations starts
        JOIN
    distances ON starts.location_id = distances.start_id
        JOIN
    locations targets ON targets.location_id = distances.target_id;

-- loads with locations 
SELECT 
    load_id,
    truck_id,
    loads.start_location_id,
    start_locations.name AS start_loc_name,
    loads.target_location_id,
    target_locations.name AS target_loc_name
FROM
    trucks
        JOIN
    loads USING (truck_id)
        JOIN
    locations start_locations ON start_locations.location_id = loads.start_location_id
        LEFT JOIN
    locations target_locations ON target_locations.location_id = loads.target_location_id
ORDER BY load_id
;

describe trucks;
insert into trucks (license_plate) values ('w-xyz23');
select * from trucks;
select last_insert_id();
select * from loads;
insert into loads (truck_id) values (last_insert_id());

select * from loads;

-- all locations, loads and tracks
SELECT 
    *
FROM
    locations
        LEFT JOIN
    loads ON locations.location_id = loads.start_location_id
        RIGHT JOIN
    trucks USING (truck_id);
    
-- loads with load legs
SELECT 
    load_id,
    truck_id,
    loads.start_location_id,
    start_locations.name AS start_location,
    loads.target_location_id,
    target_locations.name AS target_location,
    load_legs.number_in_sequence AS leg_sequence_number,
    leg_start_locations.name as leg_start_location,
    leg_target_locations.name as leg_target_location
FROM
    trucks
        RIGHT JOIN
    loads USING (truck_id)
        JOIN
    locations start_locations ON start_locations.location_id = loads.start_location_id
        JOIN
    locations target_locations ON target_locations.location_id = loads.target_location_id
        LEFT JOIN
    load_legs USING (load_id)
        LEFT JOIN
    locations leg_start_locations ON leg_start_locations.location_id = load_legs.start_location_id
        LEFT JOIN
    locations leg_target_locations ON leg_target_locations.location_id = load_legs.target_location_id
ORDER BY load_id , leg_sequence_number
;

-- legs with locations
SELECT 
    load_leg_id, load_id, number_in_sequence, start_location_id, start_loc.name, target_location_id, target_loc.name
FROM
    locations start_loc
        JOIN
    load_legs ON start_loc.location_id = load_legs.start_location_id
        JOIN
    locations target_loc ON load_legs.target_location_id = target_loc.location_id
ORDER BY load_id , number_in_sequence;

SELECT 
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
    locations target_location ON load_legs.target_location_id = target_location.location_id;

select * from trucks;
select * from distances;
select * from loads;
select * from load_legs;
select target_location_id from load_legs where load_id = 2 and number_in_sequence = 1;

  --  set distance_local = (select distance from distances where start_id = start_location_param and target_id = target_location_param);
--     set avg_speed_local = (select avg_speed from trucks join loads using (truck_id) where loads.load_id = load_param);
--     -- set duration_estimate_calculated = ( distance / avg_speed ) * 3600;
--     set duration_estimate_calculated = sec_to_time((distance / avg_speed) * 3600);
--   
select distance from distances where start_id = 1 and target_id = 2;
select avg_speed from trucks join loads using (truck_id) where loads.load_id = 1;
select sec_to_time((100.0 / 75.0 ) * 3600);

update trucks set avg_speed = 50.0 where truck_id = 1;
call add_load(1,2,1);
call add_leg(1,1,3);
call add_leg(1,3,2);

-- select max(number_in_sequence) from load_legs where load_id = 2;
select * from distances;

select * from load_legs order by load_id, number_in_sequence;
call add_timestamp (2,0,'19-12-17 11:12:16');
call add_timestamp (2,1,'19-12-17 11:12:17');
call add_timestamp (2,2,'19-12-17 11:12:18');
call add_timestamp (2,3,'19-12-17 11:12:19');

select * from loads order by load_id;
    
select * from load_leg_data;
select * from load_leg_data where load_id = 2;

-- insert into batches (spec_id,tundish_id,ladle_id,start_time,cast_finish_time) 
-- values (1,1,1,'2019-05-05 09:00:00','2019-05-05 11:00:00');

-- SELECT TIMESTAMPDIFF(SECOND, '2012-06-06 13:13:55', '2012-06-06 15:20:18')
select * from load_legs;
SELECT 
    load_leg_id,
    load_id,
    number_in_sequence,
    start_time_estimate,
    target_time_estimate,
    TIMESTAMPDIFF(MINUTE,
        start_time_estimate,
        target_time_estimate) AS duration
FROM
    load_legs;
