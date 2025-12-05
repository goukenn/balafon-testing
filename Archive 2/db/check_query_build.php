<?php
// @command: balafon --run .test/db/check_query_build.php
use com\igkdev\projects\WOHApiController\WOHLatepointHelper;
$ctrl = WOHApiController::ctrl(true);
$h = WOHLatepointHelper::GetLeaveIDs([1,3]);
// $c = WOHLatepointHelper::AgentWorkDay(3);
// $t = woh_minutes_to_time(930);
// $h = woh_time_to_minutes('10:10');
igk_wln_e('done', $h);