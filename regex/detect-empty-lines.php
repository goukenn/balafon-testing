<?php
// @author: C.A.D. BONDJE DOUE
// @filename: detect-empty-lines.php
// @date: 20260328 19:54:11
// @desc: check detecting empty line on a litteral 
// @command: balafon --run .test/regex/detect-empty-lines.php
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;

$src = implode("\n", [
    'baba',
    '',
    '   ',
    'hola',
    '   ',
    '',
    'mys',
    '',
    'friend.',
    '        ',
    ' '
]);
$regex = new RegexMatcherContainer;
$regex->match('^[^\\S\\n]*(?=\\n)', 'empty-line'); 
$regex->appendEmptyLineDetection();
$pos = 0;
while ($g = $regex->detect($src, $pos)) {
    if ($e = $regex->end($g, $src, $pos)) {
        $id = $e->tokenID;
        Logger::info(implode("", ['detect-id: '.$id,
        ' from:'.$e->from,
        ' to:'.$e->to,
        ' position:'.$pos,
        ' value:'.json_encode($e->value)]));
    }
}
Logger::success('done');
igk_exit();