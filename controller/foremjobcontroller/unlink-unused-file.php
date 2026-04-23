<?php
// @author: C.A.D. BONDJE DOUE
// @filename: unlink-unused-file.php
// @date: 20260330 07:18:22
// @desc: unlink unused file 
// @command: balafon --run .test/controller/foremjobcontroller/unlink-unused-file.php
use com\igkdev\projects\ForemJobDashboard\Helpers\JobUtility;
use com\igkdev\projects\ForemJobDashboard\Models\JobDocs;
use IGK\Database\DbFieldOperator;
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;

/**
 * @var ForemJobDashboardController
 */
$ctrl = ForemJobDashboardController::ctrl(true);
($user && $ctrl::login($user)) || igk_die('missing current user');
JobUtility::UnlinkUnusedFileFromLocalStorage($ctrl);
igk_exit();