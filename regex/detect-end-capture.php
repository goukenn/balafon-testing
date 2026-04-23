<?php
// @author: C.A.D. BONDJE DOUE
// @filename: detect-empty-lines.php
// @date: 20260328 19:54:11
// @desc: check detecting empty line on a litteral 
// @command: balafon --run .test/regex/detect-end-capture.php
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;

$src = implode("\n", [
    'hola hovito > for! every one',
    'sample'
]);
$regex = new RegexMatcherContainer;
$g = $regex->begin('[^!>\\n]+', '>|$', 'block')->last();
$g->patterns = [
    $regex->createPattern([
        'match' => '(?=!)',
        'tokenID' => 'end-capture'
    ])
];
$pos = 0;
$out = [];
/**
 * @var \IGK\System\Text\RegexMatcherCapture $e
 */
while ($g = $regex->detect($src, $pos)) {
    if ($e = $regex->end($g, $src, $pos)) {
        $id = $e->tokenID;
        Logger::info(implode("", [
            'detect-id: ' . $id,
            ' from:' . $e->from,
            ' to:' . $e->to,
            ' position:' . $pos,
            ' value:' . json_encode($e->value)
        ]));
        $stop_parent = ($e->from == $e->to) && $e->parentInfo;
        $end = $e->getisEnd();
        $out[] = $e->value;
    }
}
igk_wln(implode('|', $out));
Logger::success('done');
igk_exit();