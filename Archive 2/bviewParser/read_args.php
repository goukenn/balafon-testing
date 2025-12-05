<?php
// @command: balafon --run .test/bviewParser/read_args.php
use IGK\System\Console\Logger;

$src = [
    // 'className=[[:@raw->a]]',
    // 'className=$a',
    // 'className=@a',
    // 'className=[[:@raw]]',
    'className={active: [[:@raw->a ==\'88\' ? 1 : 0 ]], before: [[:@raw->a == 99 ? 1: 0 ]]}'
];
$context = (object)['raw'=>(object)[
    'a'=>88
]];

foreach($src as $k){

    $l = igk_engine_get_attr_arg($k, $context);

    echo json_encode($l, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;

}

Logger::success('done');
igk_exit();