# job search utilility creator 

## create a job reference 

```sh
balafon --run .test/db/jobresearch/db-init-for-me.php --querydebug
```
- my command
```
balafon --run .test/db/jobresearch/db-init-for-me.php --querydebug cbondje@igkdev.com --to:2025-08-01 --from:2025-01-01 --exclude:2025-01-10,2025-01-20 --exclude:2025-02-01 --exclude:2025-05-12,2025-05-31 
```


## make a zip for rabamansoa

```sh
balafon --run .test/db/jobresearch/db-download.php
```

## generate a print document 

print a pdf list 
```sh
https://local.com:7300/forem/jobs/print
```


```sh 
# search job for enterprise
balafon --request:view ForemJobDashboard enterprise/default --no-cache --method:POST --content-type:application/json -srv_request:enterprise=AG 
```


