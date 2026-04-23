<?php

$r = shell_exec("balafon --db:mysql --action:query "alter table tbigk_foremjobs_jobs DROP jbs_category_id"");
print_r($r);
shell_exec("balafon --db:migrate ForemJobDashboard --querydebug --debug");