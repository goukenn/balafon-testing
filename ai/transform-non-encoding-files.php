<?php

// @command: balafon --run .test/ai/transform-non-encoding-files.php
$tr = 'â”‚                                                           â”‚';
$tr = 'â”‚,';
$ln = strlen($tr);
igk_wln("size: ". $ln);
for($i = 0; $i<$ln;$i++){
    igk_wln(ord($ch = $tr[$i]). '= '.$ch);
}
igk_wln('data: ', );


$transform = [
    'Ã©'=>'é',
    'Ã¨'=>'è',
    'Ãª'=>'ê',
    'Ã´'=>'ô',
    'Ã '=>'à',
    'Ã§'=>'ç',
    'Å“'=>'oe',
    'Ã‰'=>'É',
    'Ã€'=>'À',
    'â”œ'=>'├',
    'â”¤'=>'┤',
    'â”Œ'=>'┌',
    'â”‚'=>'│',
    'â””'=>'└',
    'â”€'=>'─',
    'â€¢'=>'•',
    'â”˜'=>'┘',
    'â”'=>'┐'
];

$file = '/Users/charlesbondjedoue/Downloads/balafon-all-chapter-files/Chapitre_1_Fondamentaux_et_Installation_Balafon.md';
// $file = __DIR__.'/local.md';
$files = [];


$src = file_get_contents($file);

$r = strtr($src, $transform);
igk_io_w2file($file, $r);
igk_exit();