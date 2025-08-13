<?php
// + | --------------------------------------------------------------------
// + | detect global script variable - that will be exported 
// + |
// @command: balafon --run .test/js/detect_global_identifier.php
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
use IGK\System\Text\RegexMatcherContainer;
$file = Path::Combine(__DIR__, 'to_detect', igk_getv($params, 0));
if (!file_exists($file)){
    igk_die('missing file');
}
$src = file_get_contents($file);
$list = [];
$regex = new RegexMatcherContainer;
$regex->begin('{','}', 'outside-brank');
$regex->begin('\(','\)', 'outside-call');
$m = $regex->begin('\\b(const|let|var)\\b', '(?<=;|\})', 'declare-field')->last();
$func = $regex->begin('\\b(function)\\b', '(?<=;|\})', 'declare-func')->last();
$regex->autoStore = false;
$blockc = $regex->begin('{', '}')->last();
$blockb = $regex->begin('\(', '\)')->last();
$blockc->patterns = $blockb->patterns = [
    $blockb,
    $blockc
];
$field = $regex->match('(?i)[_a-z][a-z0-9_]*', 'field')->last();
$equal = $regex->begin('=', '(?<=,|;)')->last();
$inline_b = $regex->begin('{','}', 'inline-brank')->last();
$inline_b->patterns  = [
    $field
]; 
$regex->autoStore = true;
$m->patterns = [
    $field,
    $inline_b,
    $equal
];
$func->patterns = [
    $field,
    $blockc,
    $blockb
];
$pos = 0;
while($g = $regex->detect($src, $pos)){
    if ($e = $regex->end($g, $src, $pos)){
        if ($e->tokenID=='field'){
            $list[$e->value] = 1;
        }
    }
}
echo json_encode(['list'=>$list], JSON_PRETTY_PRINT), PHP_EOL;
Logger::success('complete');
igk_exit();