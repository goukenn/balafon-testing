<?php
// @command: balafon --run .test/tools/view-pictures-list.php
use IGK\System\Html\XML\XmlNode;
use IGK\System\IO\Path;

$dev_dir = getenv('IGK_DEV_DIR');
$site_dev_dir = getenv('IGK_SITE_DEV_DIR');
if (empty($dev_dir))
    igk_die('missing IGK_DEV_DIR in environment path');
if (empty($site_dev_dir)){
    igk_die('missing site dev directory IGK_SITE_DEV_DIR');
}
$fcile = $site_dev_dir.'/pictures.list.txt';
if (!file_exists($fcile)){
    igk_die('missing : '.$fcile);
}
$file = explode("\n", file_get_contents($fcile), 500);
array_pop($file);
$n = igk_create_node('div');
$ln = strlen($dev_dir);
foreach($file as $f){
    $p = substr($f, $ln);    
    $n->text('<img src=".'.$p.'" alt="'.$f.'" width="100" height="100" style="object-fit: cover">');
}
$src = '<!DOCTYPE html><html><body> loading....'. $n->render() . '</body></html>';
igk_io_w2file($o = Path::Combine($dev_dir, 'result', basename(__FILE__), 'out.index.html'), $src);
igk_wln_e('done', 'open: '.$o);