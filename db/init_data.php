<?php
use IGK\Helper\Database;
$ctrl = TonerafrikaController::ctrl();
$ctrl->register_autoload();
$response = Database::InitData($ctrl);
igk_exit();