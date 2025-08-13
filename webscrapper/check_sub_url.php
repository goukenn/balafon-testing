<?php
use IGK\Helper\IO;
use IGK\System\Html\HtmlNodeBuilder;
use igk\tools\webscrapper\Tests\WebScrapperTestHttpClient;
use igk\tools\webscrapper\WebScrapperDocument;
require_once '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Packages/Modules/igk/tools/webscrapper/Lib/Tests/WebScrapperTestHttpClient.php';
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
    // $this->assertEquals(
    //     '<!DOCTYPE html><html><head><meta charset="UTF-8"/></head><body><a href="pages/about.html">about</a></body></html>',
    //     $document->render()
    // );
    $temp = "/tmp/div/";// igk_io_tempdir('wbs-');
    $document->exportTo($temp);
    // `code {$temp}`;
    print_r($document->resources());
    // IO::RmDir($temp);
}