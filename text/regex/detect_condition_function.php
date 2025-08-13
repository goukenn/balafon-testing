<?php
// @command: balafon --run .test/text/regex/detect_condition_function.php
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
$src = <<<EOF

if (!function_exists('ddd')){
    function method(){

    }
}

EOF;
if (true){
}
elseif (false){
}
$regex = new RegexMatcherContainer;
$regex->autoStore = false;
$regex->autoStore = true;
$regex->match("\\bif|else|elseif\\b", 'gdc-condition-start');
$regex->match("\\bfunction((\\s*&)|\\b)", 'gdc-function');
$pos = 0;
while($g = $regex->detect($src, $pos)){
    if ($e = $regex->end($g,$src,  $pos, )){
        Logger::info('tokenID:'.$e->tokenID);
    }
}
igk_wln_e("end");