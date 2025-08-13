<?php
// @command: balafon --run .test/vscode/create-mardown.php directory 
use IGK\System\Console\Helper\ConsoleUtility;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
use IGK\System\IO\StringBuilder;
($dir = igk_getv($params, 0) ); // ?? igk_die("missing output directry");
$file = 'README.md';
if ($info = igk_getv($params, 1)){
    if (file_exists($info)){
        ($info = json_decode(file_get_contents($info)) ) || igk_die("not a valid json file");
    }
}
$sb = new StringBuilder;
$sections = ['title','features', 'versions', 'license'];
foreach($sections as $t){
    $sb->appendLine("# ".$t);
    $sb->appendLine('---');
    // section conten
}
$content = $sb.'';
function igk_html_doctype(){
    return '<!DOCTYPE html>';
}
if (!$dir){
    igk_wln_e($content);
}
igk_io_w2file($of = Path::Combine($dir, $file), 
$content);
$bind = [];
// preview markdown 
$bind[$dir.'/index.html'] = function($file){
    $n = igk_create_node('html'); // IGKHtmlDoc::CreateDocument('--markdown');
    $head = $n->head();
    $head->link()->setAttributes([
        'href'=>'assets/css/main.css',
        'rel'=>'stylesheet'
    ]);
    $head->title()->text('preview markdown');
    $body = $n->body(); 
    $body->div()->add(igk_html_host(
        'div.container > section',
        igk_html_host('h1', '( Markdown )')
    ));
    $body->div()->setAttributes([
        'data-content' => 'title to convert `code`',
        'class'=>'md-previewer'
    ]);
    $body->script('assets/js/lib/markdown/markdown.js');
    $body->script()->content = <<<JS
let q = document.querySelector(".md-previewer"); 
let g = markdown.toHTML('Type **Markdown** here.');
if (q){
    fetch('/README.md').then(a=>{   
        return a.text();
    }).then(d=>{
        q.innerHTML = markdown.toHTML(d); // q.getAttribute('data-content'));
    });
}
JS;
    igk_io_w2file($file , implode("\n",
        [igk_html_doctype(),$n->render()]));
};
ConsoleUtility::MakeFiles($bind, null, true);
Logger::print("OF Data : ".$of);
exit;