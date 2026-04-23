<?php
// @command: balafon --run .test/webscrapper/check_sub_url.php
use IGK\Helper\IO;
use IGK\System\Html\HtmlNodeBuilder;
use igk\tools\webscrapper\Tests\WebScrapperTestHttpClient;
use igk\tools\webscrapper\WebScrapperDocument;

$module = igk_get_module('igk.tools.webscrapper');
require_once $module->getDeclaredDir(). '/Lib/Tests/WebScrapperTestHttpClient.php';
$n = igk_create_node('ul');
$builder = new HtmlNodeBuilder($n);
HtmlNodeBuilder::RunBuild($n, [
    'ul'=>[
        "li > a[href:/pages/about?info#x-node]"=>'About 2',
        "li > a[href:/pages/index]"=> 'Index 1',
    ]
]);
$document = new WebScrapperDocument;
$t = '<!DOCTYPE html><html><head><meta charset="UTF-8" /></head><body><a href="/pages/about">first bout</a>'.$n->render().'</body></html>';
$document->base = 'https://local.com:7300';
$document->setHttpClient(new WebScrapperTestHttpClient);
if ($document->parseContent($t)) {
    $temp = "/tmp/div/";
    $document->exportTo($temp);
    print_r($document->resources());
}