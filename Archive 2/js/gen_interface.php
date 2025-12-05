<?php

// @author: C.A.D. BONDJE DOUE
// @filename: gen_interface.php
// @date: 20250718 10:40:44
// @desc: tools: javascript generate interface helper 


// @command: balafon --run .test/js/gen_interface.php

use IGK\System\Console\Logger;
use IGK\System\IO\StringBuilder;

$n = igk_getv($params, 0);
$props = explode(',', igk_getv($params, 1) ?? '');
$type = igk_getv($command->options,'--type', 'class');
sort($props);
$sb = new StringBuilder;
$sb->appendLine($type." ".$n."{");
array_map(function($a)use($sb){
    $sb->appendLine("/**");    
    $sb->appendLine("* @type {any}");
    $sb->appendLine("*/");
    $sb->appendLine($a);
}, $props);
$sb->appendLine("}");

Logger::print($sb.'');
exit;