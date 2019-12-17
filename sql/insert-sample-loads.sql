
/* Insert two sample loads into db, one with and one without legs */

insert into loads (truck_id, start_location_id, target_location_id, start_time_estimate) values (1,1,2,'2019-12-12 08:00:00');  
insert into load_legs 
	(load_id, start_location_id, target_location_id, number_in_sequence, start_time_estimate) 
    values (1,1,2,1,'2019-12-12 08:00:00');

insert into loads (truck_id, start_location_id, target_location_id, start_time_estimate) values (1,3,8,'2019-12-12 08:00:00');  
insert into load_legs 
	(load_id, start_location_id, target_location_id, number_in_sequence, start_time_estimate) 
    values (2,3,4,1,'2019-12-12 08:00:00');
insert into load_legs 
	(load_id, start_location_id, target_location_id, number_in_sequence) 
    values (2,4,6,2);
insert into load_legs 
	(load_id, start_location_id, target_location_id, number_in_sequence) 
    values (2,6,8,3);

update load_legs set target_time_estimate = '2019-12-13 09:30:00' where load_id = 1;
