<?php
// @command: balafon --run .test/sitenotebook/get_site_favicons.php
// @desc: retrieve sites favicon
// @remark: single thread 
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
require_once __DIR__."/.global.php";
$data = json_decode(file_get_contents(NBOOK_SITE_FILE));
$header = [];
$options = [
    CURLOPT_FOLLOWLOCATION => 1
];
$count = 0;
$file = new finfo(FILEINFO_MIME);
while (count($data) > 0) {
    $row = array_shift($data);
    $site = $row->site;
    $request = igk_uri(Path::Combine($site, 'favicon.ico'));
    if ($g = igk_curl_post_uri($request, null, $options, $header)) {
        $status = igk_curl_status();
        if ($status == 200) {
            $mime = $file->buffer($g);
            $mime = explode(';', $mime, 2)[0];
            switch ($mime) {
                case 'image/png':
                    $path = Path::Combine(__DIR__ . '/output/', $row->name, 'favicon.ico');
                    IO::CreateDir(dirname($path));
                    igk_io_w2file($path, $g);
                    $count++;
                    break;
            }
        } else if ($status == 404) {
            Logger::danger($site);
        }
    } else {
        Logger::danger('error : ' . $site);
    }
}
Logger::success('done : ' . $count);