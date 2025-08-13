<?php
define('DATA', <<<EOF


PGh0bWwgPgogICA8aGVhZD4KICAgCQk8bWV0YSBodHRwLWVxdWl2PSJ
Db250ZW50LVR5cGUieT4KPC9odG1sPg==


info
EOF);
$s = implode("\r\n", 
explode("\n", DATA)
);
preg_match('/(?:(?:.+)\r\n)+\r\n\r\n/', $s, $tab);
print_r($tab);
igk_exit();
$tab = [
    'ixm',
    'im',
    'i',
    'xim',
    'xmi',
    'x',
    'mi',
    'mi',
    'xxx',
    'xx',
    "x:a"
];
foreach($tab as $v){
  echo $v . " = ".  preg_match('/^\(\?\b(?P<add>i(m|x|(mx|xm)?)|m(i|x|(ix|xi))?|x(i|m|(im|mi))?)\b(:\b(?P<remove>i(m|x|(mx|xm)?)|m(i|x|(ix|xi))?|x(i|m|(im|mi))?)\b)?\)/',
   "(?".$v.")", $tab);
    echo "\n";
}
igk_wln_e("done");