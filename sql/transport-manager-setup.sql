
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
    distance DECIMAL(8 , 2 ),
    FOREIGN KEY (start_id)
        REFERENCES locations (location_id),
    FOREIGN KEY (target_id)
        REFERENCES locations (location_id)
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
    total_distance decimal(8,2),
    start_time TIMESTAMP NULL DEFAULT NULL,
    target_time_prior_estimate TIMESTAMP NULL DEFAULT NULL,
    duration_prior_estimate time,
    target_time_actual TIMESTAMP NULL DEFAULT NULL,
    duration_actual time,
    total_cost_prior_estimate decimal(8,2),
    total_cost_actual decimal(8,2),
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
    distance DECIMAL(8 , 2 ),
    start_time TIMESTAMP NULL DEFAULT NULL,
    duration_estimate TIME,
    duration_actual TIME,
    target_time TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (load_id)
        REFERENCES loads (load_id),
    FOREIGN KEY (start_location_id)
        REFERENCES locations (location_id),
    FOREIGN KEY (target_location_id)
        REFERENCES locations (location_id),
    UNIQUE (load_id , number_in_sequence)
);

/* stored procedures */
/* ----------------- */

-- distances are saved unidirectionally, this procedure makes them bidirectional.
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

-- helper procedure to calculate total costs of a load
USE `transport_management`;
DROP procedure IF EXISTS `calculate_costs`;

DELIMITER $$
USE `transport_management`$$
CREATE PROCEDURE `calculate_costs` (load_id_param int, modifier_param varchar(20))
BEGIN
	-- declare result decimal(8,2);
    set @load_id = load_id_param;
    set @modifier = modifier_param;
    set @total_distance = (select total_distance from loads where load_id = load_id_param);
	
    if (@modifier = 'prior_estimate') then
		set @total_duration = (select duration_prior_estimate from loads where load_id = @load_id);
    else
		set @total_duration = (select duration_actual from loads where load_id = @load_id);
    end if;
    /*
     prepare get_total_time from
 		'set @total_duration = (select duration_(?,1) from loads where load_id = (?,2));';
 	execute get_total_time using @modifier, @load_id;
     deallocate prepare get_total_time;
     */
    set @duration_as_decimal = ( time_to_sec(@total_duration) / 3600);
    set @fixed_cost = (select fixed_cost_per_hour from loads join trucks using (truck_id) where load_id = load_id_param);
    set @cost_per_km = (select cost_per_km from loads join trucks using (truck_id) where load_id = load_id_param);
    set @result = ( (@total_distance * @cost_per_km) + (@fixed_cost * @duration_as_decimal));
    -- select @total_distance;
    if (@modifier = 'prior_estimate') then
		update loads set total_cost_prior_estimate = @result where load_id = @load_id;
	else
		update loads set total_cost_actual = @result where load_id = @load_id;
	end if;
    /*
    prepare set_calculated_cost from
		'update loads set total_cost_(?,1) = (?,2) where load_id = (?,3);';
	execute set_calculated_cost using @modifier, @result, @load_id;
    deallocate prepare set_calculated_cost;
    */
    
END$$

DELIMITER ;

-- add a load leg
USE `transport_management`;
DROP procedure IF EXISTS `add_leg`;

DELIMITER $$
USE `transport_management`$$
CREATE PROCEDURE `add_leg` (load_param int, start_location_param int, target_location_param int)
BEGIN

	declare number_in_sequence_next int default 0;
    declare load_target_location_id int default 0;
    declare avg_speed_local decimal(8,2);
    -- declare distance_local decimal(8,2);
    declare duration_estimate_calculated time;
    
    -- handle leg count. Load legs are supposed to always be in the order they are entered in. 
    set number_in_sequence_next = ((select max(number_in_sequence) from load_legs where load_id = load_param)+1);
    if (number_in_sequence_next is null) then
		set number_in_sequence_next = 1;
	end if;
    
    -- calculate duration estimate
	call distance(start_location_param, target_location_param, @distance);
    set avg_speed_local = (select avg_speed from trucks join loads using (truck_id) where loads.load_id = load_param);
    set duration_estimate_calculated = sec_to_time(floor((@distance / avg_speed_local) * 3600));
    
    -- the actual insert
    insert into load_legs 
			(load_id, start_location_id, target_location_id, number_in_sequence, distance, duration_estimate) 
		values 
			(load_param, start_location_param, target_location_param, number_in_sequence_next, @distance, duration_estimate_calculated);
    
    -- if leg target equals load target, then we are on the, uh, last leg. In this case calculate total distance duration + cost estimate for the whole load. 
    set load_target_location_id = (select target_location_id from loads where load_id = load_param);
    if (target_location_param = load_target_location_id) then
		update loads set total_distance = ( select sum(distance) from load_legs where load_id = load_param ) where load_id = load_param;
		update loads set duration_prior_estimate = ( select sum(duration_estimate) from load_legs where load_id = load_param ) where load_id = load_param;
        call calculate_costs(load_param, 'prior_estimate');
    end if;
    
END$$

DELIMITER ;

-- add a timestamp to a load leg
USE `transport_management`;
DROP procedure IF EXISTS `add_timestamp`;

DELIMITER $$
USE `transport_management`$$
CREATE PROCEDURE `add_timestamp` (load_id_param int, leg_number_param int, timestamp_param timestamp)
BEGIN

	declare leg_counter int default 0;
    declare leg_limit int default 0;
    declare current_leg_estimated_timestamp timestamp default null;
    
	-- if leg# is 0, then we are starting the first leg
	if (leg_number_param = 0) then
		update load_legs set start_time = timestamp_param where load_id = load_id_param and number_in_sequence = 1;
        update loads set start_time = timestamp_param where load_id = load_id_param;
	-- otherwise we finish the current leg and start the next one
	else
		update load_legs set target_time = timestamp_param, duration_actual = timediff(timestamp_param, start_time) where load_id = load_id_param and number_in_sequence = leg_number_param;
		UPDATE load_legs 
		SET 
			start_time = timestamp_param
		WHERE
			load_id = load_id_param
				AND number_in_sequence = (leg_number_param + 1);
    end if;
    
    -- if target location is load target location, we've finished the tour,
    -- so calculate total cost + duration
    if (select target_location_id from load_legs where number_in_sequence = leg_number_param and load_id = load_id_param) = (select target_location_id from loads where load_id = load_id_param) then
    
		-- write target time to loads table
        update loads set target_time_actual = timestamp_param where load_id = load_id_param;
	    -- calculate total duration
        update loads set duration_actual = timediff(timestamp_param,(select start_time from load_legs where load_id = load_id_param and number_in_sequence = 1)) where load_id = load_id_param;
        -- calculate total cost
        call calculate_costs(load_id_param, 'actual');
        
	-- otherwise recalculate time estimates for all legs from here on
    else
    
		set leg_counter = leg_number_param + 1;
		set leg_limit = (select max(number_in_sequence) from load_legs where load_id = load_id_param);
		set current_leg_estimated_timestamp = timestamp_param;
		repeat
			set current_leg_estimated_timestamp = timestamp(current_leg_estimated_timestamp,
				(select duration_estimate from load_legs where load_id = load_id_param and number_in_sequence = leg_counter));
				-- select current_leg_estimated_timestamp;
			UPDATE load_legs 
			SET 
				target_time = current_leg_estimated_timestamp
			WHERE
				load_id = load_id_param
					AND number_in_sequence = leg_counter;
					set leg_counter = leg_counter + 1;
			UPDATE load_legs 
			SET 
				start_time = current_leg_estimated_timestamp
			WHERE
				load_id = load_id_param
					AND number_in_sequence = leg_counter;
		until leg_counter > leg_limit end repeat;
        
        -- if we're on start of tour, write target time prior estimate to loads table
        if (leg_number_param = 0) then
			update loads set target_time_prior_estimate = current_leg_estimated_timestamp where load_id = load_id_param;
        end if;

    end if;
    
END$$

DELIMITER ;

-- view for load legs with location names, for use by the php app
drop view if exists load_leg_data;
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

CREATE VIEW load_reports AS
    (SELECT 
        load_id,
        license_plate,
        start_loc.name as start_name,
        target_loc.name as target_name,
        total_distance,
        start_time,
        target_time_prior_estimate,
        target_time_actual,
        duration_prior_estimate,
        duration_actual,
        total_cost_prior_estimate,
        total_cost_actual
    FROM
        loads
            JOIN
        locations start_loc ON start_loc.location_id = loads.start_location_id
            JOIN
        locations target_loc ON target_loc.location_id = loads.target_location_id
            JOIN
        trucks USING (truck_id));

/* sample core data */
/* ---------------- */

insert into locations (name,latitude,longitude) values ('Bregenz',47.505,9.749167);
insert into locations (name,latitude,longitude) values ('Innsbruck',47.267222,11.392778);
insert into locations (name,latitude,longitude) values ('Salzburg',47.8,13.033333);
insert into locations (name,latitude,longitude) values ('Linz',48.303056,14.290556);
insert into locations (name,latitude,longitude) values ('Klagenfurt',46.617778,14.305556);
insert into locations (name,latitude,longitude) values ('St. Poelten',48.204722,15.626667);
insert into locations (name,latitude,longitude) values ('Graz',47.066667,15.433333);
insert into locations (name,latitude,longitude) values ('Wien',48.208333,16.373056);
insert into locations (name,latitude,longitude) values ('Eisenstadt',47.845556,16.518889);

insert into trucks (license_plate,fixed_cost_per_hour,cost_per_km,avg_speed) values ('LL-1234G',50.0,5.0,50.0);
insert into trucks (license_plate,fixed_cost_per_hour,cost_per_km,avg_speed) values ('W-56785G',50.0,50.0,50.0);
insert into trucks (license_plate,fixed_cost_per_hour,cost_per_km,avg_speed) values ('VL-3454G',50.0,50.0,50.0);

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

select * from load_legs order by load_id, number_in_sequence;

