<?php
use com\igkdev\bantubeat\Database\Macros\UsersMacros;
use com\igkdev\bantubeat\Helper\Utility;
use com\igkdev\bantubeat\Models\Users;
use IGK\Helper\JSon;
use IGK\Helper\SysUtils;
$ctrl = bantubeatController::ctrl();
$ctrl->register_autoload();
$pattern = 'goukenn-7897';
if (preg_match("/(?P<name>.+)(-(?P<number>[0-9]+))$/i", $pattern, $tab)) {
    $rf = $tab['name'];
    $max = $tab['number'];
    if ($u = Users::select_all([
        '@@' . Users::FD_USR_PROFILENAME => $rf . '%',
    ], ["Columns" => [
        Users::FD_USR_PROFILENAME => "profile"
    ], "OrderBy" => ["profile|ASC"]])) {
        $max = 0;
        foreach ($u as $t) {
            $tab = explode("-", $t->profile);
            if (is_numeric($c = array_pop($tab))) {
                $max = max($max, $c);
            }
        }
        $newProfile = $rf . "-" . ($max + 1);
        igk_wln_e(JSon::Encode($u), "max = " . $max,);
    }
    else {
        $newProfile = $pattern;
    }
    return compact('newProfile', 'max');
}