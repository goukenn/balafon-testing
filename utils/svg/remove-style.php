<?php
// @command: balafon --run .test/utils/svg/remove-style.php [file] 

use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;

$file = igk_getv($params, 0);
$src = file_get_contents($file);

$regex = new RegexMatcherContainer;
$pos = 0;
$e = $regex->begin('\\b(fill|stroke)\\b\\s*=', '(?<="|\'|true|false)', 'cap')->last();
$e->patterns = [
    $regex->createPattern(['begin' => '("|\')', 'end' => '\\1', 'tokenID' => 'string'])
];
// define

$o = '';
$toffset = 0;
while ($g = $regex->detect($src, $pos)) {
    if ($e = $regex->end($g, $src, $pos)) {
        if (is_null($e->parentInfo)) {
            Logger::info('remove: '.json_encode($e->value));
            $o = rtrim($o).substr($src, $toffset, $e->from - $toffset);
            $toffset = $e->to;
        }
    }
}
$o = rtrim($o). substr($src, $toffset);


Logger::print($o);
igk_exit();
