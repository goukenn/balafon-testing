<?php
// @command: balafon --run .test/document-parser/index.php
use igk\devtools\DocumentParser\DocumentParser;
use igk\devtools\DocumentParser\UriDetector;
use igk\devtools\DocumentParserMockHttpClient;
use IGK\Helper\IO;
use IGK\System\Html\XML\XmlNode;
use IGK\System\Console\Logger;
$mod = igk_require_module(\igk\devtools::class);
require_once $mod->getTestClassesDir() . "/DocumentParserMockHttpClient.php";
// preg_match("/(\s*|,|;)import\s*".UriDetector::URL_BRACKET_RX."/", "import 'info\"'", $tab);
// igk_wln_e("tab", $tab);
function _getRenderDocument1()
{
    $content = "<!DOCTYPE html>";
    $html = new XmlNode('html');
    $head = $html->head();
    $head->meta()->setAttributes(["charset" => "UTF-8"]);
    $head->meta()->setAttributes(["name"=>"viewport", "content"=>"width=device-width, initial-scale=1.0"]);
    $head->title()->Content = 'home';
    $head->link()->setAttributes(['rel' => 'stylesheet', 'href' => 'assets/css/core.css?query']);
    $head->link()->setAttributes(['rel' => 'icon', 'href' => 'favicon.ico']);
    $head->style()->Content = "@import 'assets/css/main.css?query'; @import 'assets/css/main.css'";
    $html->body()->Content = "<div>test application level </div>";
    $content .= $html->render();
    return $content;
}
$parser = new DocumentParser;
$parser->uri = "local://" . __FUNCTION__;
$client = new DocumentParserMockHttpClient();
$client->base = "local://" . __FUNCTION__;
$content = _getRenderDocument1();
$parser->setHttpClient($client);
if ($r = $parser->parse($content)) {
    $title = $parser->getTitle();
    // $this->assertEquals("home", $title);
}
$temp = "/tmp/doc-parser"; // igk_io_tempdir('doc-parser');
IO::CreateDir($temp);
$parser->exportTo($temp);
$m = $parser->render();
Logger::info("output");
Logger::print($m);
// save to index file
igk_io_w2file($temp . "/index.html", $m);
// $this->assertTrue($r); 
//$sb = `http-server {$temp}`;
echo $temp;