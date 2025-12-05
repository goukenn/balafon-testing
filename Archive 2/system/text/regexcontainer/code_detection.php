<?php
// @command: balafon --run .test/system/text/regexcontainer/code_detection.php
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;
$src = <<<MARKDOWN
hello `friend` of mine
MARKDOWN;
$ctn = new RegexMatcherContainer;
$code_block = $ctn->begin('`', '`', 'code-block')->last();
$result = '';
$lpos = 0;
$ctn->treat($src, function ($g, & $next_pos, & $data)use( & $result, $lpos){
    if ($g->getisRootCaptured()){
        RegexMatcherUtility::Skip($g, $next_pos, $data, $lpos, $result);
        $result .= sprintf('<code class="igk-code">%s</code>', trim($g->value, '`')); 
    } else if ($g->trailingEnd){
        $result .= $g->value; 
    }
});
echo $result . "\n";