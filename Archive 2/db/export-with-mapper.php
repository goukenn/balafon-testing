<?php
// @command: balafon --run .test/db/export-with-mapper.php controller model output_file.json
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\System\Console\App;
use IGK\System\Console\AppExecCommand;
use IGK\System\Console\Logger;
use IGK\System\Database\Import\DbModelImporterMap;
$ctrl = \IGK\Helper\SysUtils::GetControllerByName(igk_getv($params, 0) ?? '');
$ctrl::register_autoload();
$model = igk_getv($params, 1);
$file = igk_getv($params, 2) ?? igk_die('required file');
$model = $ctrl->model($model) ?? igk_die("missing model {$model}");
$h = null;
$h = $mapping = DbModelImporterMap::CreateFrom($model);
$d = $h->export();
$option = JSonEncodeOption::IgnoreEmpty();
if ($d){
igk_io_w2file($file, JSon::Encode($d, $option));
Logger::print("store: ".$file);
}