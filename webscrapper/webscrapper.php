<?php
// @run: balafon --run .test/webscrapper/webscrapper.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\Html\Dom\HtmlDocumentNode;
use IGK\System\Http\IHttpClient;
use IGK\System\Http\IHttpClientOptions;
use igk\tools\webscrapper\WebScrapperDocument;
$p = new WebScrapperDocument;
$p->base = 'https://local.com:7300';
$outdir = __DIR__.'/web-site-scrap';
IO::RmDir($outdir);
// if ($p->parseContent('<script src="assets/Scripts/igk.js?version=3"></script><script src="https://unpkg.com/vue-router@4.2.4/dist/vue-router.global.prod.js?format"></script>')){
//     Logger::success('export ok');
//     $p->exportTo($outdir);
//     Logger::success('exports to ');
// }
// if ($p->parseContent('<script src="demo/js">')){
//     Logger::success('export ok');
//     $p->exportTo($outdir);
//     Logger::success('exports to ');
// }
// if ($p->parseContent('<link rel="stylesheet" href="https://local.com:7300/assets/Styles/balafon.css?v=13.02.0.0831">')){
//     Logger::success('export ok');
//     $p->exportTo($outdir);
//     Logger::success('exports to ');
// }
// $src = file_get_contents("/Volumes/Data/Dev/Vite/vite-project/dist/assets/index-d7bd537c.js");
// detect js inline uri spécificattion
// preg_match_all("/('|\")[^;, ]+\.(svg|png|jp(e)?g)\\1/", $src, $tab);
// preg_match("/import\s*\(\s*(?P<url>'[^']*'|\"[^\"]*\")\s*\)/", "import('presentation');", $tab);
// preg_match("/import\s*\(\s*(?P<url>'[^']*'|\"[^\"]*\")\s*\)/", "import(\"presentation\");", $tab);
// print_r($tab);
// igk_wln_e("done");
// $p->base = 'http://localhost:4173/';
// if ($p->parseContent(file_get_contents("/Volumes/Data/Dev/Vite/vite-project/dist/index.html"))){
//     Logger::success('export ok');
//     $p->exportTo($outdir);
//     Logger::success('exports to ');
//     igk_wln($p->resources());
// }
// treat style content 
$p->base = 'https://local.com:7300';
if ($p->parseContent('<!DOCTYPE html><html><head>'.    
    '<script type="module" src="/assets/_mod_/igk/js/Vue3/Scripts/default.js"></script>',
    '</head><body>Presentation</body></html>')){
    Logger::success('export ok');
    $p->exportTo($outdir);
    Logger::success('exports to ');
    igk_wln_e($p->resources());
}
class LocalClient implements IHttpClient{
    var $followLocation;
    public function getRequestHeaderResponse(): ?array { return null; }
    public function download(string $url, IHttpClientOptions $options) { }
    public function get(string $url) { }
    public function post(string $url, array $data = []) { }
    public function request(string $url) { 
        $list = [
            "https://local.com:7300/home/p/info-about"=>function(){return $this->getPage("<p>About page</p><a href='/cv'>let cv</a><a href='../'>go back</a>", "About");},
            "https://local.com:7300/cv"=>function(){ return $this->getPage("<p>MyCV</p><a href='../'>go back</a>", "CV");},
        ];
        $o = igk_getv($list, $url);
        if ($o){
            if (is_callable($o)){
                return $o();
            }
        }
        return $this->getErrorDocument('missing - '.$url);
    }
    public function getPage($content,string $title, ?string $id=null){
        $doc = IGKHtmlDoc::CreateDocument('page'.igk_str_assert_prepend($id,'-'));
        $this->_disableSetting($doc);
        $doc->title = $title;
        $doc->getHead()->link()->setAttributes([
            "rel"=>"icon",
            "type"=>"image/svg+xml",
            "href"=>"data:image/svg+xml;base64,PHN2ZyBzdHJva2U9ImN1cnJlbnRDb2xvciIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDUxMiA1MTIiPjx0aXRsZT5pb25pY29ucy12NS1hPC90aXRsZT48cGF0aCBkPSJNNDQ4LDI1NmMwLTEwNi04Ni0xOTItMTkyLTE5MlM2NCwxNTAsNjQsMjU2czg2LDE5MiwxOTIsMTkyUzQ0OCwzNjIsNDQ4LDI1NloiIHN0eWxlPSJmaWxsOm5vbmU7c3Ryb2tlLW1pdGVybGltaXQ6MTA7c3Ryb2tlLXdpZHRoOjMycHgiLz48bGluZSB4MT0iMjU2IiB5MT0iMTc2IiB4Mj0iMjU2IiB5Mj0iMzM2IiBzdHlsZT0iZmlsbDpub25lO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2Utd2lkdGg6MzJweCIvPjxsaW5lIHgxPSIzMzYiIHkxPSIyNTYiIHgyPSIxNzYiIHkyPSIyNTYiIHN0eWxlPSJmaWxsOm5vbmU7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS13aWR0aDozMnB4Ii8+PC9zdmc+"
        ]);
        $doc->getBody()->Content = $content;
        return $doc->render();
    }
    private function _disableSetting($doc){
        $doc->noCoreCss = true;
        $doc->noCoreScript = true;
        $doc->noPowered = true;
    }
    public function getErrorDocument($msg){
        $doc = IGKHtmlDoc::CreateDocument('error');
        $this->_disableSetting($doc);
        $doc->title = "Error-";
        $doc->getBody()->div()->Content = $msg;
        return $doc->render();
    }
    public function getStatus(): int { 
        return 200;
    }
} 
igk_wln_e("done: ".$outdir);