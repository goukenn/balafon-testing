<?php
use com\igkdev\bantubeat\CommunityManagerRolesConstants;
use com\igkdev\bantubeat\Models\CommunityManager;
use com\igkdev\bantubeat\Models\Users;
use com\igkdev\bantubeat\Profiles;
use IGK\System\Console\Logger;
$ctrl = bantubeatController::ctrl();
$ctrl->register_autoload();
$u1 = Users::factory(1)->create() ?? igk_die("failed to create an user1");
$u2 = Users::factory(1)->create() ?? igk_die("failed to create an user 2");
$ctrl->model(CommunityManager::class)->BindToUser($u1[0], $u2[0], 
Profiles::COMM_PROFILE_FEEDS_ADMINISTRATOR );
Logger::success("Done");
exit;