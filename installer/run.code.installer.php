<?php
// @command: php .test/installer/run.code.installer.php
// @description: run code manual installer 

include IGK_LIB_CLASSES_DIR . '/IGKBacktickHelperCommandTrait.php';
include IGK_LIB_DIR. '/Inc/core/installer.helper.pinc';
$php_cmd = InstallerHelper::GetPhpBinary();
$balafon_cmd = getenv('IGK_SITE_DEV_DIR').'/src/application/Lib/igk/bin/balafon';
$b_cd = getcwd();
$dir = '/tmp/balafon_bcd';
chdir($dir);
$app_dir = getenv('IGK_SITE_DEV_DIR').'/src/application';
$envs = [
    'IGK_WORKING_DIR'=>$dir.'/src',
    'IGK_APP_DIR'=>$app_dir,
    'IGK_PACKAGE_DIR'=>$app_dir.'/Packages',
    'IGK_MODULE_DIR'=>$app_dir.'/Packages/Modules',
    'IGK_PROJECT_DIR'=>$app_dir.'/Projects'
];
ksort($envs);
foreach($envs as $k=>$v){
    putenv(sprintf('%s=%s', $k, $v));
}
$r = InstallerHelper::HandleBacktickCommand(shell_exec("{$php_cmd} {$balafon_cmd} --project:list --debug 1>&1 2>&1; echo $?"));
chdir($b_cd);
print_r($r);
exit;