<?php
// @command: balafon --run .test/text/regex/detect_mixed.php
use IGK\System\IO\File\PHPScriptMixedDetector;

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
igk_wln_e('exit');