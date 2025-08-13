<?php
use igk\svg\SvgUtil;
use IGK\System\Console\Logger;
use function igk_clamp as clamp;
$distance = SvgUtil::Distance(0, 0, 100, 100);
Logger::print("distance : ". $distance);
$points = [
    100, 100, 
    200, 200, 
    400, 200, 
    500, 100
];
$out = [];
$T = 50;
$step = 1/$T;
$t = 0;
$reduce = 100;
$last = null;
while($t <= 1){
    list($x, $y) = SvgUtil::CalculateBezierPoint($t, ...$points);
    $skip = false;
    $x = round($x, 3);
    $y = round($y, 3);
    if (($t!=1) && $last && $reduce){
        $distance = SvgUtil::Distance($last[0], $last[1], $x, $y);
        if ($distance < $reduce){
            $skip = true;
        }
    }
    if (!$skip){
        array_push($out, $x, $y);
        $last = [$x, $y];
    }
    if ($t==1){
        break;
    }
    $t = clamp($t+$step, 1);
}
Logger::print(json_encode($out));
Logger::success('done');
igk_exit();