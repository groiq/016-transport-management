#!/usr/bin/env python3

count = 9

for i in range(count):
    for j in range(count):
        if j > i:
            print("insert into distances (start_id,target_id,distance) values ({},{},100);".format(i+1,j+1))
        
