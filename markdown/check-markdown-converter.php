<?php

// @command: balafon --run .test/markdown/check-markdown-converter.php

use IGK\System\Console\Logger;
use IGK\System\IO\Markdown\MarkdownConverter;

function _transform($src){
    $converter = new MarkdownConverter;
    $converter->allowLinkDocument = true;
    $converter->useCodeFormatter = true;
    $l = $converter->transformToHtml($src);
    return $l;
}
// TODO : PB avec : 
// [BLF] - parent not updated. matcher misconfiguration #f-html-attribs-value 
// At: /Volumes/Data/Dev/PHP/balafon2/src/Lib/igk/igk_core.php:199


$f = IGKServices::getInstance()->services();
$tf = igk_app()->getService('formatters.html');
$heighlight = igk_app()->getService(IGKServices::CORE_CODE_HIGHLIGHT);
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