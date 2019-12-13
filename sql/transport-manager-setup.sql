
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
    start_time_estimate TIMESTAMP NULL DEFAULT NULL,
    start_time_actual TIMESTAMP NULL DEFAULT NULL,
    target_time_estimate TIMESTAMP DEFAULT NULL,
    target_time_actual TIMESTAMP NULL DEFAULT NULL,
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
    start_time_actual TIMESTAMP NULL DEFAULT NULL,
    target_time_estimate TIMESTAMP DEFAULT NULL,
    target_time_actual TIMESTAMP NULL DEFAULT NULL,
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

insert into trucks (license_plate,fixed_cost_per_hour,cost_per_km,avg_speed) values ('LL-1234G',100.0,50.0,75.0);

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

select * from locations;

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
