<?php
// @author: C.A.D. BONDJE DOUE
// @filename: download-pics.php
// @date: 20260131 08:43:57
// @desc: download picture from adt site
// @command: balafon --run .test/adt/download-pics.php
use IGK\System\Console\Helper\ConsoleUtility;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;

$url = igk_getv($params, 0) ?? igk_die('missing url');
$dest = igk_getv($params, 1) ?? igk_getv($command->options, '--outdir') ?? __DIR__.'/output';
$count = igk_getv($params, 2) ?? igk_getv($command->options, '--count') ?? 10;
$from = intval(igk_getv($params, 3) ?? igk_getv($command->options, '--from') ?? 1);
$pad_size = intval(igk_getv($params, 3) ?? igk_getv($command->options, '--pad') ?? 2);
$help = property_exists($command->options, '--help');
if ($help){
    echo "get pictures:";
    ConsoleUtility::ShowOptionsCommand([
        '--outdir:dir'=>'where to store downloaded pictures',
        '--from:[number]'=>'start at',
        '--count:[number]'=>'count',
        '--pad:[number]'=>'padding format',
    ], 'command');
    igk_exit();
}
if (false === strpos($url, '%s')){
    $url .='$s';
}
/**
* auto generate doc.
* @param mixed $v
*/
function _hex($v){
    return str_pad(dechex($v), 2, '0', STR_PAD_LEFT);
}
$infinity = ($count < 0);
$count = ($count < 0) ?9999:$count;
$ldint = $count + $from;
$c=1;
for($i = $from ; $infinity || $i<$ldint; $i++){
    $furi = sprintf($url, str_pad($i, $pad_size, '0', STR_PAD_LEFT));
    $g = igk_curl_post_uri($furi, null);
    if (!$g)break;
    list($status, $mimetype) = igk_extract($curl_info = igk_curl_info(), 'Status|Content-Type');
    if ($status==200){
        $ext = igk_io_mimetype_ext($mimetype, '.jpg');
        igk_io_w2file($cf = Path::Combine($dest, "pic_"._hex($c).$ext), $g);
        Logger::info('write: '.$cf);
        $c++; 
    }else{
        if ($infinity){
            break;
        }
        Logger::danger('failed to get resources: '.$furi);
    }
}
Logger::success('complete');
Logger::success('output: '.$dest);
igk_exit();