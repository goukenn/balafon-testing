<?php
// update site definition with favicons png picture
// @command : balafon --run .test/sitenotebook/treat_sites.php
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
include_once(__DIR__.'/.global.php');
$finfo  = new finfo(FILEINFO_MIME);
$file= NBOOK_SITE_FILE;
$data = json_decode(file_get_contents($file ));
$data = $data->sites;
usort($data, function($a, $b){
    return strcmp($a->name, $b->name);
});
$dir = __DIR__.'/output/';
$svg = 0;
foreach($data as $row){
    $path = Path::Combine($dir, $row->name, 'favicon.ico');
    if (!file_exists($path)) continue;
    $buffer = file_get_contents($path);
    $mime = explode(';', $finfo->buffer($buffer),2)[0];
    switch($mime){
        case 'application/octet-stream':
            break;
        case 'image/bmp':
        case 'image/jpeg':
        case 'image/jpg':
        case 'image/gif':
            break;
        case 'image/svg+xml':
            $svg++;
            break;
        case 'image/vnd.microsoft.icon':
            break;
        case 'image/png':
            break;
        case 'text/html':
        case 'text/plain':
            Logger::warn('remove : '.$mime. ' => '.$path);
            @unlink($path);
            break;
        default:
            Logger::warn('remove : '.$mime. ' => '.$path);
            @unlink($path);
        break;
    }
}
$src = JSon::Encode(['sites'=>$data], JSonEncodeOption::IgnoreEmpty(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); 
igk_io_w2file($ofile = $file.'.o.json', $src); 
Logger::success("finish: ".$ofile);
igk_wln_e("count : ".$svg);