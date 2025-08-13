<?php
// @command: balafon --run .test/text/regex/detect_mixed.php
use IGK\System\IO\File\PHPScriptMixedDetector;
// $sr0 = <<<'OHP'
// information - POUR PAPA - du jour-2+,
// <?php
// echo 'hello world';
// OHP;

$sr1 = <<<'OHP'
home     4<?php
echo 'hello ?> world';
info = <<<ESS
the <?php here ?>
ESS;

?> finish writing <?php 
echo local
OHP;
$sr1 = <<<'OHP'
      <?php /* job of the years */ 
      ?> do some = <?= $x ?>
OHP;
 $detector = new PHPScriptMixedDetector;
 echo json_encode($detector->detectFromSource($sr1));
// $c = new RegexMatcherContainerTmLanguageConverter;
// echo json_encode($c->Convert($regex), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
// igk_exit();
// Logger::SetColorizer(new Colorize);
// echo str_repeat('-',20), PHP_EOL;
// Logger::print(json_encode([
//     'mixed'=>$mixed,
//     'source'=>$source,
// ], JSON_PRETTY_PRINT));
igk_wln_e('exit');