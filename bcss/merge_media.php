<?php
// @command: balafon --run .test/bcss/merge_media.php
use igk\bcssParser\System\IO\BcssParser;
$src = implode("\n", [
    '@def,@xsm-screen{',
    'div{ color: red; }',
    '}'
]);
$f = BcssParser::ParseFromContent($src);
echo $f->render();
exit;