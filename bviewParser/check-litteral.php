<?php
// @command: balafon --run .test/bviewParser/check-litteral.php
use igk\bviewParser\BviewDataArgs;
use igk\bviewParser\System\IO\BviewParser;
use IGK\Models\Users;
use IGK\System\Console\Logger;
use IGK\System\Html\HtmlNodeBuilder; 
// igk_wln_e("config ". igk_configs()->db_server);
// $g = \igk\bviewParser\System\Configs::getInstance();
// igk_wln_e("s", $g);
// $tab = [];
// foreach([
//     '"',
//     'ifno "',
//     '\"',
//     '\\"'
// ] as $k){
//     $c = preg_match("/(?<!\\\)\"/", $k, $tab);
//     Logger::info('check: '.$k);
//     igk_wln($c, $tab);
// }
// Logger::success('done');
// igk_exit();
// $c = implode("\n", [
//     'info  du jour     base ',
//     '',
//     '',
//     'avec one'
// ]);
// $g = preg_match_all('/[^\\w\\n]{2,}/', $c, $tab);
// igk_wln_e(json_encode(compact('g','c')));
$file = igk_getv($params, 0 ) ?? '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Packages/Modules/igk/bviewParser/Lib/Tests/Data/test-inline-items.bview';
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
// igk_wln_e($builder->t->render());
Logger::success('done');
igk_exit();