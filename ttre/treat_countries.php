<?php
// treat countries.csv
// balafon --run .test/ttre/treat_countries.php > .test/ttre/countries.json
$lines = explode("\n", file_get_contents(__DIR__."/countries.csv"));
$tab = [];
foreach($lines as $m){
    $tp = preg_split('/[ \t]/', $m);
    $code = array_shift($tp);
    $name = implode(" ", $tp);
    $tab[] = (object)compact('code', 'name');
}
echo json_encode($tab, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
exit;