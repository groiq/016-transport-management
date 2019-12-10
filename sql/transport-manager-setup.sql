
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
    target_location_id int,
    start_time_estimate TIMESTAMP default current_timestamp,
    start_time_actual timestamp default current_timestamp,
    target_time_estimate timestamp default current_timestamp,
    target_time_actual timestamp default current_timestamp,
    foreign key (truck_id) references trucks (truck_id),
    foreign key (start_location_id) references locations (location_id),
    foreign key (target_location_id) references locations (location_id)
);

CREATE TABLE load_legs (
    load_leg_id INT PRIMARY KEY AUTO_INCREMENT,
    load_id INT,
    location_id INT,
    number_in_sequence INT,
    time_estimate timestamp default current_timestamp,
    time_actual timestamp default current_timestamp,
    foreign key (load_id) references loads (load_id),
    foreign key (location_id) references locations (location_id)
);

