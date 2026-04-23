<?php
// @command: balafon --run .test/sync/test_installer_helper.php
use IGK\Helper\IO;

$ctrl = ForemJobDashboardController::ctrl(true);
require_once(IGK_LIB_DIR.'/Inc/core/installer.helper.pinc');
IO::CreateDir($ctrl->getDeclaredDir().'/tmp/data');
InstallerHelper::RmFiles($ctrl->getDeclaredDir().'/tmp');