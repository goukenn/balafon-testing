<?php
// @author: C.A.D. BONDJE DOUE
// @filename: create-mardown.php
// @date: 20251024 10:23:34
// @desc: create a README.md helper to vscode extension 
// @command: balafon --run .test/vscode/create-mardown.php directory 
// @balafon-command: vscode-markdown
use IGK\System\Console\Helper\ConsoleUtility;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
use IGK\System\IO\StringBuilder;

($dir = igk_getv($params, 0) ); 
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
}
$content = $sb.'';
/**
* auto generate doc.
*/
function igk_html_doctype(){
    return '<!DOCTYPE html>';
}
if (!$dir){
    igk_wln_e($content);
}
igk_io_w2file($of = Path::Combine($dir, $file), 
$content);
$bind = [];
$bind[$dir.'/index.html'] = function($file){
    $n = igk_create_node('html'); 
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
igk_exit();