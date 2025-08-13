<?php
// + | --------------------------------------------------------------------
// + | initialize the database with value from year 2023
// + |
// @command: balafon --run .test/db/jobresearch/db-init.php
// @desc: get date search definition random
$dates = [];
$max_count = 200;
$mounth = 1;
$day = 1;
$year = 2023;
// starting date
// start date
$year = 2023;
$mounth = 1;
$day = 1;
if (isset($from)){
    list($year, $mounth, $day) = array_map('intval', igk_extract(explode('-', $from), '0|1|2')); 
}
// + | -----------------------------------------------------------------
// + | mark of list per date
// + |  
$marks = [3, 7, 3, 6, 3];
$week = 1;
$rand_list = [2, 0, 12, 0, 7, 0, 6, 3, 0];
$excludes = isset($excludes)? $excludes : [
    ['2024-06-01', '2024-06-23'],
    ['2024-12-15', '2025-01-06']
];
// + | calculate end date time
define('DATE_FORMAT', 'Y-m-d');
list($c_year, $c_mounth, $c_day) = explode('-', date(DATE_FORMAT));
// + | setup end current date 
if (isset($to)){
    list($c_year, $c_mounth, $c_day) = array_map('intval', igk_extract(explode('-', $to), '0|1|2')); 
} else{
$c_year = 2025;
$c_mounth = 2;
$c_day = 20;
}
/**
 * get the first monday
 * @param mixed $year 
 * @param mixed $mounth 
 * @param mixed $day 
 * @return string 
 */
function get_year_first_monday($year, $mounth, $day)
{
    $date = null;
    while (true) {
        $timespan = strtotime(sprintf(
            "%s-%s-%s",
            $year,
            str_pad($mounth, 2, '0', STR_PAD_LEFT),
            str_pad($day, 2, '0', STR_PAD_LEFT)
        ));
        $tdate = explode(" ", date(DATE_FORMAT . " N", $timespan), 2);
        if ($tdate[1] == 7) {
            $day += 1;
            continue;
        }
        $date = $tdate[0];
        break;
    }
    return $date;
}
$date = get_year_first_monday($year, $mounth, $day); 
$now = date(DATE_FORMAT);  
$enddate = sprintf("%s-%s-%s", 
    $c_year,
    str_pad($c_mounth,2, '0', STR_PAD_LEFT),
    str_pad($c_day,2, '0', STR_PAD_LEFT)
);
$counter = 0;
$tbb = [];
do {
    // + | ----------------------------------------------------------------------------------------------
    // + | skip week
    // + |
    $is_exlude = false;
    foreach($excludes as $kd)
    {
        if ($date>$kd[0] && ($date<$kd[1])){
            $is_exlude = true;
            break;
        }
    }
    if ($is_exlude) 
        continue;
    // if (($date > '2024-06-01') && ($date < '2024-06-23')) {
    //     continue;
    // }
    $dates[] = $date;
    $week++;
    $index = rand(0, count($rand_list));
    $TLIST = $cp = igk_getv($rand_list, $index);
    $counter += $cp;
    // days in week 
    $days = range(0, 4);
    $b = [];
    while ($cp > 0) {
        $tc = count($days);
        $search = $cp;
        $day = 0;
        if ((count($b)==0) && in_array($TLIST, $marks) && rand(0,1))
        {
            $index = array_search($TLIST, $marks);
            $day = rand(0, $tc - 1);
            unset($marks[$index]);
            $search = $TLIST;
        }
        else if (($tc-1) > 0) {
            $day = rand(0, $tc - 1);
            $search = round(1, $cp);           
        }  
        $tb = [ "day"=>$days[$day] , "search"=>$search];
        $b[] = $tb;
       // igk_wln("searching ", json_encode($tb, JSON_PRETTY_PRINT));
        unset($days[$day]);
        $days = array_values($days);
        $cp -= $search;
    }
    if ($b) {
        usort($b, function ($a, $b) {
            return strcmp($a['day'], $b['day']);
        });
        $tbb[$date][] = ["total"=>$TLIST , "tsearch"=>$b];
    }
}
// while((($date = date('Y-m-d', strtotime($date . ' next week'))) < $now));
while((($date = date('Y-m-d', strtotime($date . ' next week'))) < $enddate));
return $tbb;