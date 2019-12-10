
/* Database for transport management project */

drop database if exists transport_management;
create database transport_management;
use transport_management;

CREATE TABLE locations (
    location_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(32),
    latitude DECIMAL(8 , 6 ),
    longitude DECIMAL(9 , 6 )
);

CREATE TABLE trucks (
    truck_id INT PRIMARY KEY AUTO_INCREMENT,
    fixed_cost_per_hour DECIMAL,
    cost_per_km DECIMAL,
    avg_speed DECIMAL
);

CREATE TABLE loads (
    load_id INT PRIMARY KEY AUTO_INCREMENT,
    truck_id INT,
    start_location_id INT,
    target_location_id INT,
    start_time_estimate TIMESTAMP null DEFAULT null,
    start_time_actual TIMESTAMP null DEFAULT null,
    -- target_time_estimate timestamp default current_timestamp, -- calculated value!
    target_time_actual TIMESTAMP null DEFAULT null,
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
    location_id INT,
    number_in_sequence INT,
    time_estimate TIMESTAMP null DEFAULT null, -- calculated value!
    time_actual TIMESTAMP null DEFAULT null,
    foreign key (load_id) references loads (load_id),
    foreign key (location_id) references locations (location_id)
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

insert into trucks (fixed_cost_per_hour,cost_per_km,avg_speed) values (100.0,50.0,75.0);

insert into loads (truck_id, start_location_id,target_location_id,start_time_estimate) values (1,3,8,current_timestamp);

insert into load_legs (load_id,location_id,number_in_sequence,time_estimate) values (1,4,1,current_timestamp);
insert into load_legs (load_id,location_id,number_in_sequence,time_estimate) values (1,6,2,current_timestamp);

select * from locations;
select * from loads;
