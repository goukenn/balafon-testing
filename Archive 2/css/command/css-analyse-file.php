<?php
// @author: C.A.D. BONDJE DOUE
// @filename: css-analyse-file.php
// @date: 20250627 05:06:31
// @desc: use to analyse css file 
// @command: balafon --run .test/css/command/css-analyse-file.php [file] [options*]
use IGK\Css\Analyzer\CssAnalyzer;
use IGK\Css\Analyzer\CssSpeudoSplitter;
use IGK\System\Console\Logger;
$file = igk_getv($params, 0) ?? igk_die('required file');
$trimmer = new CssSpeudoSplitter;
// $c = $trimmer->split('hell"o, "friend');
// $c = $trimmer->split('hello, "friend');
// igk_wln_e($c);
$analyser = new CssAnalyzer;
$analyser->setSplitListerner($trimmer);
$analyser->analyse($file);
echo json_encode($analyser, JSON_PRETTY_PRINT);
echo PHP_EOL;
Logger::success('done css - analyser');
igk_exit();