<?php
// @command: balafon --run .test/tools/view-pictures-list.php
use IGK\System\Html\XML\XmlNode;
$file = explode("\n", file_get_contents('/Volumes/Data/Dev/PHP/balafon_site_dev/pictures.list.txt'), 500);
array_pop($file);
$n = igk_create_node('div');
$ln = strlen('/Volumes/Data/Dev');
foreach($file as $f){
    $p = substr($f, $ln);    
    $n->text('<img src=".'.$p.'" alt="'.$f.'" width="100" height="100" style="object-fit: cover">');
}
$src = '<!DOCTYPE html><html><body> loading....'. $n->render() . '</body></html>';
igk_io_w2file($o = '/Volumes/Data/Dev/out.index.html', $src);
igk_wln_e('done', 'open: '.$o);