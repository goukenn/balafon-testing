#!/bin/bash

# testing applicaiton depending on profile 

export LC_NUMERIC=C

for i in {1..10}
do
	curl -so /dev/null --insecure -H "Pragma: no-cache" -w "%{time_total}\n" https://local.com:7800/testapi/gen_db_llv 
done  | awk '{ sum += $1 ; n++; print $1 } END { if (n > 0 ) print "AVG : " sum / n; }'

