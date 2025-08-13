<?php
// balafon --run /Volumes/Data/Dev/PHP/balafon_site_dev/.test/testuri.php
// $data = "background-image: url(../webfonts/fa-brands-400.eot?#iefix);";
// $regex = '/url\s*\((?<path>(((\.)?\.\/|(?P<protocol>[a-z0-9]+):\/\/))[^\)\#\?\,\+\ ]+)(?P<extra>([^\)]+))?\)/i';
// preg_match_all($regex, $data , $all);
// print_r($all);
// igk_wln_e("print ", $all);
// flatten path 
use IGK\System\IO\Path;
// $gc = "./";
// $g = Path::Combine($gc, sha1('cdnjs.cloudflare.com'));
// igk_wln_e("the g : ", $g);
$dirname = "igk/dev-tools";
$g = preg_replace("/[^0-9\_a-z\/]/i", "",$dirname);
igk_wln_e("sanitize ", $g);
// $s = trim('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/../../webfonts/../local/fa-brands-400.svg#fontawesome');
// if (strpos($s, '../')>0){
//     $g = explode('../', $s);
//     $p = "";
//     while(count($g)>0){
//         $q = array_shift($g);
//         if (empty($p)){
//             $p = $q;
//             continue;
//         }
//         $p = dirname($p);
//         if (empty($q)){
//             continue;
//         }
//         else{
//             $p.="/".$q;
//         }
//     }
//     $s = str_replace("/./", "/", $p);
//     echo "source : ".$s."\n";
//     echo "file : ".$p;
// }