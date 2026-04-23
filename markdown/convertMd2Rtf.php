<?php
// @author: C.A.D. BONDJE DOUE
// @filename: convertMd2Rtf.php
// @date: 20260126 14:17:02
// @desc: convert rtf to markdown test 
// @command: balafon --run .test/markdown/convertMd2Rtf.php
use igk\Markdown\MarkdownToRtfConverter;
use IGK\System\Drawing\Colorf;

$cl = Colorf::FromString('#32c4aB');
$converter = new MarkdownToRtfConverter;
$src = implode("\n", [
    '# the title',
    '- ibm',
    '- src',
    '> du jour au matin',
    '> avec la foi',
    '```php',
    'litteral = 45;',
    '```'
]);
$c = $converter->convert($src);
igk_wln_e($c);