<?php
// treat type comment 
use IGK\System\Text\IRegexMatcherDetectInfo;
use IGK\System\Text\RegexMatcherContainer;
$match = new RegexMatcherContainer;
$src = file_get_contents(__DIR__.'/info.d.ts');
$block =  $match->begin('\{', '\}', 'curly-block')->last();
$brank_block =  $match->begin('\(', '\)', 'brank-block')->last();
$generic_block =  $match->begin('<', '>', 'generic-block')->last();
$block->patterns = [
    $block
];
$doc_comment = $match->appendCommentDocBlock()->last();
// $i = $match->begin('\\btype\\b\\s+(?P<name>\\w+)\\s*(:|=)', ';|^(?=\s*\b(?:\w+)\b)', 'type')->last();
$i = $match->begin('\\btype\\b\\s+(?P<name>\\w+)\\s*(:|=)', ';', 'type')->last();
$i->patterns = [
    $block,
    $doc_comment,
    $i->createEscapedString(),
    $i->match("(?<=(?:(?:'|\"|\})))\s*(\|)\s*", 'glue-type-def'), 
    $i->match("^(?=\s*\b(\w+)\b)","---end---"),
    //$i->match("^\s*\b(\w+)\b","--end--")
    $i->match("(?=[\{\(\]\[\}].*)", "--not-allowed---")
];
// $i = $match->begin('\\bdeclare\\s+\\b(?P<type>const|function)\\b\\s+(?P<name>\\w+)\\s*', ';', 'declare-type')->last();
// $i->patterns = [
//     $block,
//     $brank_block,
//     $generic_block,
//     $doc_comment
// ];
$match->treat($src,  
function($g, $next){
    /**
     * @var IRegexMatcherDetectInfo $g
     */ 
    // if (!$g->parentInfo)
        igk_wln($next .':data:'.$g->tokenID . ': '.$g->value);
});
$position = $match->getLastPosition();
echo "length    : " .strlen($src) . " \n";
echo "position  : " .$position. " \n";
echo "rest-data : " .substr($src, $position). " \n";
igk_exit();