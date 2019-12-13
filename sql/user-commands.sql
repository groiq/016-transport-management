show tables;


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

-- loads with load legs
SELECT 
    load_id,
    truck_id,
    loads.start_location_id,
    start_locations.name AS start_loc_name,
    loads.target_location_id,
    target_locations.name AS target_loc_name,
    load_legs.number_in_sequence AS leg_sequence_number,
    leg_start_locations.name,
    leg_target_locations.name
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

describe trucks;
insert into trucks (license_plate) values ('w-xyz23');
select * from trucks;
select last_insert_id();
select * from loads;
insert into loads (truck_id) values (last_insert_id());

select * from loads;

-- all locations, routes and tracks
SELECT 
    *
FROM
    locations
        LEFT JOIN
    loads ON locations.location_id = loads.start_location_id
        RIGHT JOIN
    trucks USING (truck_id);
    
-- locations, loads and load_legs
SELECT 
    load_id,
    start_location_id,
    start_loc.name,
    load_leg_id,
    leg_location.location_id,
    leg_location.name,
    number_in_sequence
FROM
    loads
        JOIN
    load_legs USING (load_id)
        JOIN
    locations start_loc ON loads.start_location_id = start_loc.location_id
        JOIN
    locations leg_location ON load_legs.location_id = leg_location.location_id
ORDER BY number_in_sequence;

