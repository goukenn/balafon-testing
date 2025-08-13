# 'bashing for jobs controller'
# ForemJobDashboardController
balafon --clearcache
balafon --db:mysql --action:drop-tables --filter:%foremjob%
balafon --db:initdb --controller:ForemJobDashboardController --force
balafon --run .test/db/jobresearch/db-user-for-me.php --debug
echo 'seed ...'
balafon --db:seed ForemJobDashboardController


echo 'register jobs...';
balafon --run .test/db/jobresearch/db-register-job.php 00340023 45
balafon --run .test/db/jobresearch/db-register-job.php 00340029 42