<?php
// @command: balafon --run .test/phpformat/format.php
use IGK\System\Console\Logger;
use igk\tools\Reflector\FormatPHPCode;

$formatter = new FormatPHPCode;
$formatter->blockOnly = true;
$src = implode("\n", [
    'function         a($b=      "part {-}");'
]);
$src = implode("\n", [
    'function callSample($c=12,$i=12){ if      (true&&',
    '$a) { echo "weldone"; if (false || !   ($k==lamda))  { echo "bad"; } }  $s = invoke(<<<EOF',
    'jour de gloire!!!!',
    'EOF);',
    '/** pratical list ',
    '* @return {}',
    ' */ return function(){ return 11; };} ',
    'function indigo(){}',
]);



Logger::info('source:' . $src);
$s = $formatter->format($src);
Logger::success('Result:');
igk_wln_e($s);
igk_exit();
