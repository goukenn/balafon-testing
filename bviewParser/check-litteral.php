<?php
// @command: balafon --run .test/bviewParser/check-litteral.php
use igk\bviewParser\BviewDataArgs;
use igk\bviewParser\System\IO\BviewParser;
use IGK\Models\Users;
use IGK\System\Console\Logger;
use IGK\System\Html\HtmlNodeBuilder; 

$file = igk_getv($params, 0 ) ?? getenv('IGK_SITE_DEV_DIR').'/src/application/Packages/Modules/igk/bviewParser/Lib/Tests/Data/test-inline-items.bview';
$data = BviewParser::ParseFromContent(file_get_contents($file));
$builder = new HtmlNodeBuilder;
$builder->contextDataArgsClass = BviewDataArgs::class;
$n = $builder($data->data, null, [
    'raw'=> [
        'a'=>88,
        'c'=>99,
        'b'=>['i'=>30, 'j'=>'basic'],
        'users'=>array_slice(Users::select_all(), 0, 2)
    ],
    'ctrl'=>ForemJobDashboardController::ctrl(true)
]);
echo $builder->t->render(), PHP_EOL;
Logger::success('done');
igk_exit();