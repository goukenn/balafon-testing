<?php
// @command: balafon --run .test/io/igk_convert_heic2jpg.php 
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;

Logger::print("😒 don't be so shy. command line not working. ffmpeg -i from to");
if ('darwin' == strtolower(PHP_OS)) {
  Logger::warn("import .heic to photos.app then export (Edit > Export To JPEG tool)");
};
igk_exit();
$dir = igk_getv($params, 0) ?? igk_die('required dirname');
$files = IO::GetFiles($dir, "/\.heic$/", true);
foreach ($files as $c) {
  $o = Path::Combine(dirname($c), igk_io_basenamewithoutext($c) . '.jpg');
}
Logger::success('done');
igk_exit();