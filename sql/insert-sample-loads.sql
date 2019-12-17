
/* Insert two sample loads into db, one with and one without legs */

insert into loads (truck_id, start_location_id, target_location_id) values (1,1,2); 
call add_leg(1,1,2); 
-- insert into load_legs 
	-- (load_id, start_location_id, target_location_id, number_in_sequence, start_time_estimate) 
--     values (1,1,2,1,'2019-12-12 08:00:00');

insert into loads (truck_id, start_location_id, target_location_id) values (1,3,8);  
call add_leg(2,3,4);
call add_leg(2,4,6);
call add_leg(2,6,8);
-- insert into load_legs 
-- 	(load_id, start_location_id, target_location_id, number_in_sequence) 
--     values (2,3,4,1);
-- insert into load_legs 
-- 	(load_id, start_location_id, target_location_id, number_in_sequence) 
--     values (2,4,6,2);
-- insert into load_legs 
-- 	(load_id, start_location_id, target_location_id, number_in_sequence) 
--     values (2,6,8,3);

-- select * from load_legs order by load_id, number_in_sequence;
-- CREATE PROCEDURE `add_timestamp` (load_id_param int, leg_number_param int, timestamp_param timestamp)
-- CREATE PROCEDURE `add_leg` (load_param int, start_location_param int, target_location_param int)

