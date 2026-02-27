<?php
// @command: balafon --run .test/core/install/gen-merge-files.php
use IGK\System\Console\Commands\Sync\SyncProjectCommand;

$token ='';
$name ='';
$l = SyncProjectCommand::GetScriptInstall(
            [  
                IGK_LIB_CLASSES_DIR . "/Traits/BacktickHelperCommandTrait.php",
                'installer-core-function.pinc',
                'installer-helper.pinc', // entry helper
                'installer.helper.pinc', // intaller helper class 
                'install.project.script.pinc'
            ],
            $token,
            $name
        );

echo $l;
igk_exit();