<?php
// @command: balafon --run .test/odoo/glue.test.php


$files = [
    '/Volumes/Data/Dev/PHP/balafon_site_dev/.test/odoo/coderbytes/tests/CodeLangUserValidationTest.php',
    '/Volumes/Data/Documents/FormationOdoo2026/CoderBytes/php/exam-codelangusernamevalidation.php',
];
$n = sys_get_temp_dir().'/'.basename($files[0]);
// rename($n, $n = $n.'.php');
$tempfile = $n;
$src = file_get_contents(array_shift($files));

igk_io_w2file($tempfile , implode("\n", array_merge([$src. "\n".'?>', "<?php"], array_map(function($a){
    return 'require_once \''.$a."';";
}, $files))));
igk_wln('file: '.$tempfile);
igk_wln(shell_exec(implode(' ', ['/Volumes/Data/wwwroot/core/Packages/vendor/bin/phpunit', $tempfile])));