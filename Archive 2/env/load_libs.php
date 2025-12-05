<?php
// @command: balafon --run .test/env/load_libs.php
error_reporting(-1);
ini_set('display_errors', 1);
$dir = '/Volumes/Data/wwwroot/core/Projects';
$exclude = [
    'Tests'=>1,
    'Configs'=>1,
    'cgi-bin'=>1,
    'Classes'=>1,
    '.vscode'=>1,
    '.git'=>1,
    'Scripts'=>1,
    'command-scripts'=>1,
    'Views'=>1,
    'Articles'=>1,
    'Data'=>1,
    'ViewLayout'=>1,
    'Lib'=>1,
    'Contents'=>1,
    'FrontApp'=>1
];
$files = igk_loadlib_dirs($dir, '.php', $exclude);
print_r($files);
echo "done";
exit;