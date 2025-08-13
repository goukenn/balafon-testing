<?php
$cond = <<<'JS'
   {i, j} in items
JS;
$g = preg_match('/^\s*(?P<cond>.+)\s+(?P<op>in|of)\s+(?P<exp>.+)\s*$/', $cond, $tab);
//$tab['cond'] = trim($tab['cond']);
$cond = $tab['cond'];
$op = $tab['op'];
$mode = preg_match('/^\{.+\}$/', $tab['cond']) ? 1 : 
        (preg_match('/^\(.+\)$/', $tab['cond']) ? 2 : 0);
$exp = $tab['exp'];
$data = 'h("div","454")';
switch($mode){
    case 1:
        $firstkey = trim(explode(",", substr($cond,1,-1))[0]);
        $src = sprintf(<<<'JS'
(function(l,key){for(key in l){((%s)=>this.push(%s))(l[key]) } return this}).apply([],[%s]) 
JS, $cond, $data,$exp );
        break;
    default:
        break;
}
igk_wln_e($g, $tab, $mode, strlen($tab['cond']), $src, "ll");