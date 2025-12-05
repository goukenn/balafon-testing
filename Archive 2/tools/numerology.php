<?php
for ($i = 0; $i < 26; $i++) {
    $td[chr(ord('a') + $i)] = ($i % 9) + 1;
}
function isVowel($ch)
{
    return preg_match("/[aeiouy]/", $ch);
}
$n = 'BONDJE DOUE CHARLES AIME DIEUDONNE';
$n = 'ANGELINE LEMAIRE CYNDIE';
$soul_number = 0;
$not_pnumber = 0;
$number = 0;
foreach (str_split(strtolower($n)) as $s) {
    $c = isset($td[$s]) ? $td[$s] : 0;
    $number += $c;
    if (isVowel($s)) {
        $soul_number += $c;
    } else {
        $not_pnumber += $c;
    }
}
/**
 * 
 * @param int $number 
 * @return mixed 
 */
function isTreatNumber(int $number)
{
    while ($number >= 10) {
        $lnumber = '' . $number;
        $ln = strlen($lnumber);
        $c = 0;
        $i = 0;
        for ($i = 0; $i < $ln; $i++) {
            $c += $lnumber[$i];
        }
        $number = $c;
        if ($number == 11) {
            break;
        }
        if ($number == 22) {
            break;
        }
        if ($number == 33) {
            break;
        }
        if ($number == 44) {
            break;
        }
    }
    return $number;
}
igk_wln_e($td, json_encode((object)[
    'expression'=>$number,
    'number'=>isTreatNumber($number) ,
    'soul_number'=>isTreatNumber($soul_number),
    'poul_number'=>isTreatNumber($not_pnumber),
], JSON_PRETTY_PRINT ));