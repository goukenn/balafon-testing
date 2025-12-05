<?php

// @command: balafon --run .test/modules/igk.js.vue3/convertsetup_to_commonjs.php

use igk\js\Vue3\JsBuild\VueSetupConverter;
use IGK\System\Console\Logger;

$conv = VueSetupConverter::CreateConverter();

$src = file_get_contents(__DIR__.'/script.js');

$g = $conv->transform($src);
Logger::print($g);
Logger::success('done - convert');
igk_exit();