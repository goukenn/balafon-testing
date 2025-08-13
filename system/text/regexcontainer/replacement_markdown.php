<?php
// @command: balafon --run .test/system/text/regexcontainer/replacement_markdown.php
use IGK\Helper\StringUtility;
use IGK\System\Console\App; 
use IGK\System\Console\Logger; 
use IGK\System\IO\Markdown\MarkdownConverter;
use IGK\System\Text\RegexMatcherContainer;
/**
 * transform to html 
 * @package 
 */
$file = igk_getv($params, 0); 
$converter = new MarkdownConverter; 
$src = ''; 
if ($file && file_exists($file)){
    $src = file_get_contents($file);
}
$src = implode("\n", [
    // '',
    // '',
    // '',
    '#',
    '',
    '## Docker - ',
    '',
    '```',
     'docker compose down',
    '```',
]);
/// treat task 
$s = $converter->transformToHtml($src);
Logger::warn('result:');
echo $s . "\n";
igk_io_w2file(__DIR__ . "/index.html", implode("\n", [
    '<!DOCTYPE html>',
    '<html>',
    '<head><title>mardown document</title><style>',
    'body{font-family: system-ui, arial, sans-serif;} .igk-code{display:block;}',
    'span.s{ color: #ce1818; }',
    '</style></head>',
    '<body>',
    $s,
    '</body>',
    '</html>'
]));
igk_exit();