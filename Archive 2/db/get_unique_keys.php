<?php
// balafon --run .test/db/get_unique_keys.php --controller:LlvGStockController
use IGK\System\Caches\DBCaches;
DBCaches::Clear();
$info = $ctrl->getDataTableDefinition();
if (!$info){
    igk_die("no definition found");
}
$tab = (array)$info->tables;
// + | --------------------------------------------------------------------
// + | get column schema - index 
// + |
$columns = [];
foreach($tab as $t => $info){
    foreach($info->columnInfo as $cl){
        if (is_null($cl)){
            continue;
        }       
        if ($cl->clIsUniqueColumnMember){
            if (!isset($columns[$t])){
                $columns[$t] = [];
            }
            if (!($index = $cl->clColumnMemberIndex)){
                $index = 0;
            }
            $columns[$t][$index][] = $cl->clName;
        }
    }
}
foreach($columns as $t=>$v){
    $q = array_map(function($m)use($t){
        return sprintf("ALTER TABLE ".$t." DROP KEY %s;", implode(", ", $m));
    }, $v);
    // print_r($q);
}
print_r($columns); 
exit;
igk_wln_e($columns);//array_keys((array)$info->tables));