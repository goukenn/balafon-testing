<?php
// @author: C.A.D. BONDJE DOUE
// @filename: regex_line_matcher.php
// @date: 20260421 14:25:36
// @description: splitted regex matcher. demonstration 
// @command: balafon --run .test/phpunit/splitted_regex_matcher_demonstration.php
use IGK\System\Console\Logger; 
use IGK\System\Text\RegexMatcherContainer;
/**
* auto generate doc.
* @param mixed $f
* @param RegexMatcherContainer $regex
* @return mixed
*/
function treat_($f, RegexMatcherContainer $regex)
{
    if (!is_array($f)) {
        $f = [$f];
    }
    $u = [];
    while (count($f) > 0) {
        $line = array_shift($f);
        $pos = 0;
        // + | important for multiline
        $regex->markEndOfSource = empty($f);
        $line = $regex->updateBufferLine($line, $pos);
        // + | normal algorithm logic
        while ($d = $regex->detect($line, $pos)) {
            if ($e = $regex->end($d, $line, $pos)) {
                $id = $e->tokenID;
                Logger::info('tokenid: ' . $id . ' value: ' . json_encode($e->value));
                if ($e->getisRootCaptured()) {
                    $u[] = $e->value;
                }
            } 
        }
    }
    return implode("", $u);
}
$regex  = new RegexMatcherContainer;
$info = $regex->match("Info", "info")->last(); 
$comment = $regex->appendSingleLineComment()->last();
$block = $regex->begin('\{', '\}', 'block')->last();
$block->patterns = [
    $info,
    $comment,
    $block,
];
$c = treat_(implode("", ['   {  ', "\n", '// Info', ' de jour', " avec", '} }}} ', "\n}"]), $regex); 
igk_wln_e($c);