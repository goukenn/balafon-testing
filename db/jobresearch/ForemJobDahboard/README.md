# Process to add joblist

- backup database 
```sh
open -a docker
docker compose start
# open phpmyadmin
# export database
```


jobtitles.txt content list of job titles


update job to now 

```sh
balafon --run .test/db/jobresearch/db-init-for-me.php --querydebug willy.meli@yahoo.fr --to:2025-06-13 --from:2025-01-01
```

initialize the fake job with `@FAKE: