<?php
// @command: php .test/installer/run.code.installer.php
// @description: run code manual installer 
include '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Lib/igk/Lib/Classes/IGKBacktickHelperCommandTrait.php';
include '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Lib/igk/Inc/core/installer.helper.pinc';
$php_cmd = InstallerHelper::GetPhpBinary();
$balafon_cmd = '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Lib/igk/bin/balafon';
// InstallerHelper::RmFiles('/tmp/balafon_bcd/.balafon');
$b_cd = getcwd();
$dir = '/tmp/balafon_bcd';
chdir($dir);
$app_dir = '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application';
$envs = [
    'IGK_WORKING_DIR'=>$dir.'/src',
    'IGK_APP_DIR'=>$app_dir,
    'IGK_PACKAGE_DIR'=>$app_dir.'/Packages',
    'IGK_MODULE_DIR'=>$app_dir.'/Packages/Modules',
    'IGK_PROJECT_DIR'=>$app_dir.'/Projects'
];
ksort($envs);
// $sb = '<environments-constants>';
// foreach(array_keys($envs) as $n){
//     $sb .= '<env name="'.$n.'">';
//     $sb .= '<description lang="fr"></description>';
//     $sb .= '<description lang="en"></description>';
//     $sb .= '</env>';
// }
// $sb.='</environments-constants>';
// echo $sb, PHP_EOL;//json_encode($envs);
// exit;
foreach($envs as $k=>$v){
    putenv(sprintf('%s=%s', $k, $v));
}
$r = InstallerHelper::HandleBacktickCommand(`{$php_cmd} {$balafon_cmd} --project:list --debug 1>&1 2>&1; echo $?`);
chdir($b_cd);
print_r($r);
exit;