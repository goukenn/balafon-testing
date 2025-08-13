<?php
// @command: balafon --run .test/parse_css.php
use IGK\System\Html\Css\CssStringConverter;
use IGK\System\IO\Configuration\ConfigurationReader;
$a = "background-color    :red sdfs df ;background-image: url(http://information?q;xml=1); color:yellow; data:mploed";
$converter = new CssStringConverter;
$g = $converter->read($a);
// combine array and to array
$g = [];
$lastkey = null;
array_map(function ($a) use (&$g, & $lastkey) {
    $sep = ":";
    $b = array_map(function ($c) {
        return trim($c);
    }, $tvalues = explode($sep, $a, 2));
    if (count($b) >= 2) {
        list($key, $value) = $b;
        //  = $tvalue;
        if ($e = implode($sep, array_slice($tvalues, 2))) {
            $value .= $sep . $e;
        }
        $g[$key] = $value;
        $lastkey = $key;
        return [$key => $value];
    } else {
        if ($lastkey)
            $g[$lastkey] .= ';'.$b[0]; 
    }
}, explode(";", $a));
igk_wln_e('Parsing data : ', $g);