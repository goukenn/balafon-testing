<?php
// @command: balafon --run .test/controller/getcontroller_from_dir.php
$ctrl = ForemJobDashboardController::ctrl(true);
$r = igk_controller_from_dir($ctrl->getDeclaredDir());
igk_wln_e("the controller ", $r);