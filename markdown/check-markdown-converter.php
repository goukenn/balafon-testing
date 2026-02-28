<?php
// @command: balafon --run .test/markdown/check-markdown-converter.php
use IGK\System\Console\Logger;
use IGK\System\IO\Markdown\MarkdownConverter;
// TODO : PB avec : 
// [BLF] - parent not updated. matcher misconfiguration #f-html-attribs-value 
// At: /Volumes/Data/Dev/PHP/balafon2/src/Lib/igk/igk_core.php:199
$f = IGKServices::getInstance()->services();
$tf = igk_app()->getService('formatters.html');
$ptf = igk_app()->getService('formatters.php');
$pttf = igk_app()->getService('php-formatter');
$heighlight = igk_app()->getService(IGKServices::CORE_CODE_HIGHLIGHT); 
$o = $heighlight->format(implode("\n", ['$g=12;', '// represent ', '$a = $g + 15;']), 'phpdd');
igk_wln_e('o = '.$o);
$src = '<div class="mark"><!-- sample '."\n".'<span>code</span>--></div>';
// + | PB 
// $src = '<div class="mark" ><!-- sample '."\n".'<span>code</span>--></div>';
$g = $tf->format($src);
Logger::print('height --------------------');
Logger::print($g);
Logger::print(':::');
$o = $heighlight->format($g, 'html');
igk_wln($o);
igk_exit();
// igk_wln_e("the formatter ", json_encode(compact('g', 'o'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); //  $tf->format('info'));
// + | --------------------------------------------------------------------
// + | check tranform code to html 
// + | --------------------------------------------------------------------

/**
* auto generate doc.
* @param mixed $src
* @return string
*/
function _transform($src){
    $converter = new MarkdownConverter;
    $converter->allowLinkDocument = true;
    $converter->useCodeFormatter = true;
    $l = $converter->transformToHtml($src);
    return $l;
}
$src = implode("\n", [
    '# document',
    '- [link](#sample)',
    '## déjà  à l\'hôtel',
    'writing sample',
    '```php',
    '$x = 48',
    '```'
]);
$d = _transform($src);
igk_wln_e($d);