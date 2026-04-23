<?php
// @command: balafon --run .test/core/markdown/test_array.php
use IGK\System\IO\Markdown\MarkdownConverter;

$ts = implode("\n", [
    "`a\|b` | m",
    "`c \|d` | quote ",            
]);
$conv = new MarkdownConverter;
$l = $conv->transformToHtml($ts);
$l = str_replace('\\|', '|', $l);
igk_wln_e($l);