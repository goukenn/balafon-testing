<?php
// @command: balafon --run .test/treat/willonhair_file.php 
use IGK\Helper\IO;
use IGK\System\Console\Logger;
$regex = '/(String|int|bool|Media|Wallet|double|DateTime|PaymentMethod|User)\\s/';
($dir  = igk_getv($params, 0) ) ?? igk_die('missing directory');
$files = IO::GetFiles($dir, "/\.dart$/", true);
$h =  [
    'file_names',
    'unnecessary_this',
    'overridden_fields',
    'unnecessary_new',
    'prefer_interpolation_to_compose_strings',
    'no_leading_underscores_for_local_identifiers',
    'unused_local_variable',
    'non_constant_identifier_names',
    'constant_identifier_names',
    'avoid_init_to_null',
    'prefer_collection_literals',
    'avoid_print',
    'library_private_types_in_public_api',
    'prefer_final_fields',
    'empty_constructor_bodies',
    'use_key_in_widget_constructors',
    'prefer_const_constructors_in_immutables',
    'sort_child_properties_last',
    'avoid_unnecessary_containers',
    'sized_box_for_whitespace',
    'unused_field',
    'unnecessary_null_comparison',
    'avoid_function_literals_in_foreach_calls',
    'empty_catches', // allow empty caches
    'prefer_function_declarations_over_variables',
    'use_build_context_synchronously',
    'no_leading_underscores_for_library_prefixes'
];
sort($h);
$ignore = implode(',',$h);
foreach($files as $f){
    $src = file_get_contents($f);
    Logger::info('treat = '.$f);
    // $src = preg_replace($regex, "\\1? ", $src);
    if (!preg_match("//",$src)){
        $src = implode("\n", ["// ignore_for_file:".$ignore, $src]);
    }else {
        # code...
        if (preg_match("/^\/\//", $src = trim($src))){
            $d = explode("\n", $src, 2);
            array_shift($d);
            $src = $d[0];
        }
        $src = implode("\n", ["// ignore_for_file:".$ignore, $src]);
    }
    igk_io_w2file($f,  $src);
}
igk_exit();