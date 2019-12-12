show tables;


select * from locations;

insert into locations (name,latitude,longitude) values ('equator',0,0);

select * from locations;

select * from locations where name = 'equator';
delete from locations where location_id = 10;

select * from locations;

select * from loads;
select * from trucks;

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
        JOIN
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
    leg_locations.location_id as leg_loc_id, leg_locations.name as leg_loc_name,
    load_legs.number_in_sequence as leg_sequence_number
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
    locations leg_locations ON leg_locations.location_id = load_legs.location_id
;

-- all locations, routes and tracks
SELECT 
    *
FROM
    locations
        LEFT JOIN
    loads ON locations.location_id = loads.start_location_id
        RIGHT JOIN
    trucks USING (truck_id);