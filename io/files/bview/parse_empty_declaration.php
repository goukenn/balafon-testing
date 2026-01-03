<?php
 // @command: balafon --run .test/io/files/bview/parse_empty_declaration.php

use igk\bviewParser\System\IO\BviewParser; 
use IGK\System\Html\HtmlNodeBuilder;
// $file = igk_getv($params, 0) ?? igk_die('missing file');
// $content = file_get_contents($file); 
 
// passing $context to view 
$content = <<<'BView'
# @title presentation du jour
# @author C.A.D. BONDJE DOUE
ul{ 
    li > loop(2){
        span[title*:igk_str_pipe_value($raw->title, 'uppercase')]{ - data {{$raw}} }       
    }
}
BView; 
 

$c = BviewParser::ParseFromContent($content, null);

$n = igk_create_notagnode();
$builder = new HtmlNodeBuilder($n);                 
$builder($c->data, null, (object)[
    'raw'=>[
        "title"=>'this is for sample'
    ]
]);

igk_wln_e($n);