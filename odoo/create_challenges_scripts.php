<?php
// @command: balafon --run .test/oddo/create_challenges_scripts.php

use IGK\Helper\StringUtility;

$dir_ = '/Volumes/Data/Documents/FormationOdoo2026/CoderBytes';
$file = $dir_.'/challenges.md';
$lines = array_filter(explode("\n", file_get_contents($file)));
$gt = [
    'py'=>function($n){
        return implode("\n",[
            'def '.$n.'():',
            '    pass'
        ]);
    },
    'php'=>function($n){
        return implode("\n", ["<?php", "function ".$n."(){}", ""]);
    }
];
foreach($lines as $l){
    $n_ = strtolower(trim($l));
    foreach(['php', 'py'] as $ext){
        $n = 'exam-'.$n_.'.'.$ext;
        $f = $dir_.'/'.$ext.'/'.$n;
        if (!file_exists($f)){
            $src = $gt[$ext];
            if ($src instanceof closure){
                $src = $src(StringUtility::FuncName($n_));
            }
            igk_io_w2file($f, $src);
        }

    }
}


