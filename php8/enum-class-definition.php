<?php
// @command: balafon --run .test/php8/enum-class-definition.php
declare(strict_types=1);
use com\igkdev\projects\Veteran\EnumChampion;
include __DIR__.'/enum-gen.php';
enum EnumDays{
    case Monday;
    case Friday;
}
$x = (object)['one'=>'data'];
igk_wln("the day: ",  EnumDays::Friday);
igk_wln("array the day: ", (array) EnumDays::Friday, $x);
igk_wln('champ', EnumChampion::one);