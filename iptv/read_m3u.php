<?php
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
$url = "/Users/charlesbondjedoue/Downloads/tvlist_data.m3u";
$content = file_get_contents($url);
$offset = 0;
$container = new RegexMatcherContainer;
// $container->
// $lr = $container->begin('\\b(tvg-logo=)', ':')->last();
$lr = $container->match('EXTM3U')->last();
// $lr->patterns = [
//     [
//         "begin" => "(\"|')",
//         "end" => "\\1"
//     ]
// ];
$g = $container->detect($content, $offset);
while ($g){
    $l = $container->end($g, $source, $offset);
    if ($l){
        Logger::print($l->value);
    }   
}
echo "done";
igk_exit();