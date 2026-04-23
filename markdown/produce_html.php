<?php
// @command: balafon --run .test/markdown/produce_html.php
use IGK\System\Console\Logger;
use IGK\System\Html\Dom\HtmlDocTheme;
use IGK\System\IO\StringBuilder;

$s = <<<MD
# Hello 
- sample avec dignite - location xxx
---
```php
\$x = 32;
doAction();
invoke();
(\$x + 5) && igk_die("ok");
```
MD;
$t = igk_create_node('div');
$t->markdown($s);
$css = new HtmlDocTheme;
$cl['core-fcl'] = '#040816';
$css['*'] = 'box-sizing: border-box;';
$css['body'] = "font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;";
$css['body, html'] = "padding:0px; margin:0px;";
$css['h1'] = 'color:'.$cl['core-fcl'].';';
$css['.igk-code'] = 'display:block; padding:4px; width:100%; border-color:'.$cl['core-fcl'].'; border-width:1px; border-radius:4px; border-style:solid;';
$sb = new StringBuilder;
$sb->appendLine(implode("\n", [
    '<html><title>Pasting-Document</title>',
    '<style>'.$css->get_css_def().'</style>',
    '<body>',
    $t->render(),
    '</body></html>'
]));
$output = igk_getv($params, 0) ??  __DIR__.'/output.html';
igk_io_w2file($output, $sb.'');
Logger::info($output);
igk_exit();