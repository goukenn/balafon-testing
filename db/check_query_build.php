<?php
// @command: balafon --run .test/db/check_query_build.php
use com\igkdev\projects\WOHApiController\WOHLatepointHelper;

$ctrl = WOHApiController::ctrl(true);
$h = WOHLatepointHelper::GetLeaveIDs([1,3]);
igk_wln_e('done', $h);