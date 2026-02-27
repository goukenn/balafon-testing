<?php
// @command: balafon --run .test/phpformat/format.php
use IGK\System\Console\Logger;
use igk\tools\Reflector\FormatPHPCode;
$formatter = new FormatPHPCode;
$formatter->blockOnly = true;
$src = implode("\n", [
    'function         a($b=      "part {-}");'
    // .'$a = 8;   ' .
    //     "\n" . "Onze || define('Onze', 9);\n"
    //     . '    /* inline - multi - comment */'
    //     . ' if ($a){ echo $a +    $b; ',
    // '',
    // '',
    // '$q=<<<EOF',
    // 'Information du jour ',
    // '   Avec quoi',
    // 'Pratique',
    // 'EOF;',
    // 'if ($delta){',
    // 'location();',
    // '}',

    // '} }'
]);


$src = implode("\n", [
    //  'function      a($a =     [a(){ $a = 12; }]){ $b = '
    //  ,'12; $s=12; }',
    //'function      a($a  = 12){ $b = 12; $s  =  12; return 4 + $b; }',
    //''
    // 'function        a(      $i,$c); ',
    // '/** sample ',
    // '* @return sample',
    // '*/'.
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
exit;
