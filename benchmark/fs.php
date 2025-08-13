<?php
// @command: balafon --run .test/benchmark/fs.php
$fs = ['sample.txt'=>'sample.txt'];
while(count($fs)<1000){
    $fs[] = rand(0,1000);
}
igk_start_time('file');
$T = $c = 1000000;
while($c>0){
if (file_exists('sample.txt')){
}
$c--;
}
echo 'file access: '.igk_execute_time('file'), PHP_EOL;
igk_start_time('in_array');
$c = $T;
while($c>0){
if (in_array('sample.txt', $fs)){
}
$c--;
}
echo 'in_array access: '.igk_execute_time('in_array'), PHP_EOL;
igk_start_time('isset');
$c = $T;
while($c>0){
if (isset($fs['sample.txt'])){
}
$c--;
}
echo 'isset access: '.igk_execute_time('isset'), PHP_EOL;
igk_start_time('key_exists');
$c = $T;
while($c>0){
if (key_exists('sample.txt', $fs)){
}
$c--;
}
echo 'key_exists access: '.igk_execute_time('key_exists'), PHP_EOL;
igk_exit();