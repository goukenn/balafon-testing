<?php
// @command: balafon --run .test/controller/action-demo.php
$ctrl = WOHApiController::ctrl(true);
$action = $ctrl->actionInstance('/v2/appointments');
$r = $action->index();
igk_wln_e($action, $r);