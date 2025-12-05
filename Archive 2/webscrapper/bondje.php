<?php
// @command: balafon --run .test/webscrapper/bondje.php
use IGK\Helper\IO;
use IGK\System\Http\CurlHttpClient;
use IGK\System\IO\Path;
use igk\tools\webscrapper\WebScrapperDocument;
$url = "https://local.com:7300/bondje";
$client = new CurlHttpClient;
$client->accept = 'text/html';
$client->followLocation = true;
$client->controller = null;
igk_set_timeout(0);
$outdir = __DIR__ . '/bondje-sites';
IO::RmDir($outdir);
if ($content =<<<'HTML'
<ul>
    <li><a href="/bondje/cv">CV</a></li>     
</ul>
HTML    //$client->request($url)
) {
    $parser = new WebScrapperDocument;
    $parser->base = "https://local.com:7300";
    //$parser->entry = '/bondje';
    if ($parser->parseContent($content)) {
        $parser->exportTo($outdir);
        igk_wln_e('done', $parser->render());
    }
}
Logger::danger("error");
igk_exit();