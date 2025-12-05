<?php
function escape_pdf_string($str){
    $str = preg_replace_callback("/(?<!\\\\)[\(\)]/",function($a){
        return "\\".$a[0];
    }, $str);
    return $str; 
}
function pdf_date_string($timespan){
    return sprintf('D:'.date('YmdHis', $timespan).'Z00\'00\'');
}
$file = igk_getv($params, 0);
$title = igk_getv($params, 1) ?? IGK_AUTHOR;
$c = file_get_contents($file);
// preg_match($regex = "/\/Title\s+\((?:(?<!\\\\).)*?\)/", $c, $tab); // not correct 
preg_match($regex = "/\/(Title|Author|Creator|Producer|Subject|Keyword|ModDate|CreationDate)\s+\((?:.*?(?<!\\\\))\)/", $c, $tab); // Ok
$info = igk_createobj();
$info->Title = $title;
$info->Author = IGK_AUTHOR;
$info->Producer = "Balafon";
$info->Creator = 'ForemJobDashboardController';
$info->ModDate = pdf_date_string(strtotime("2024-09-05 12:30:00"));
$info->CreationDate = pdf_date_string(strtotime("2024-09-05 12:30:00"));
$c = preg_replace_callback($regex, function($a)use($info){
    $b = igk_getv($info, $a[1]);
    if ($b)
        return "/".$a[1]." (".escape_pdf_string($b).")";
    return $a[0];
}, $c);
igk_io_w2file($file, $c);
igk_wln_e("done");