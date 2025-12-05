<?php
// desc: test node render vue 
use IGK\System\Html\HtmlNodeBuilder;
$d = igk_create_node('div');
$d->div()->load(<<<'HTML'
<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="ddlSettings">
    <li><a class="dropdown-item" data-value="Settings" href="javascript:void(0);">Settings</a></li>
    <li><a class="dropdown-item" data-value="Share" href="javascript:void(0);">Share</a></li>    
    <li><a class="dropdown-item" data-value="Info" href="javascript:void(0);">Info</a></li>    
</ul>
HTML);
echo HtmlNodeBuilder::Generate($d);
echo PHP_EOL;
$m = igk_create_node('div');
$builder = new HtmlNodeBuilder($m);
// test explode list with tagname 
igk_debug(1);
$builder(["div" => ["ul.dropdown-menu.dropdown-menu-end" => ["_" => ["aria-labelledby" => "ddlSettings"], "li" => ["a.dropdown-item" => ["Settings", "_" => ["data-value" => "Settings", "href" => "javascript:void(0);"]]], ["@_t:li" => ["a.dropdown-item" => ["Share", "_" => ["data-value" => "Share", "href" => "javascript:void(0);"]]]], ["@_t:li" => ["a.dropdown-item" => ["Info", "_" => ["data-value" => "Info", "href" => "javascript:void(0);"]]]]]]]);
igk_debug(0);
echo PHP_EOL;
echo PHP_EOL;
$m->renderAJX();
echo PHP_EOL;
echo PHP_EOL;
$d->renderAJX();
exit;
$builder([
    "div" => [
        "div" => [
            "ul.dropdown-menu.dropdown-menu-end" => [
                "_" => ["aria-labelledby" => "ddlSettings"],
                "li" => ["a.dropdown-item" => ["Settings", "_" => ["data-value" => "Settings", "href" => "javascript:void(0);"]]],
                ["@_t:li" => ["a.dropdown-item" => ["Share", "_" => ["data-value" => "Share", "href" => "javascript:void(0);"]]]],
                ["@_t:li" => ["a.dropdown-item" => ["Info", "_" => ["data-value" => "Info", "href" => "javascript:void(0);"]]]]
            ]
        ]
    ]
]);