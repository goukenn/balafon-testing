<?php
// @command: balafon --run .test/livecareer/extract_details.php
use IGK\System\Html\HtmlReader;

$f = igk_getv($params,0) ?? '/Users/charlesbondjedoue/Desktop/it_dev_details.html';
$c = HtmlReader::Load(file_get_contents($f));
$ls = $c->getElementsByTagName('div');
$tls = [];
while(count($ls)){
    if ($c = array_shift($ls)->getInnerHtml()){
        if (!preg_match("/<\\w+\\b/", $c)){
            $tls[] = $c;
        }
    }
} 
echo json_encode($tls, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
igk_exit();