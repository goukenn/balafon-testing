<?php
// + | check already contains definitions 
$tab = json_decode(file_get_contents('/Volumes/Data/Dev/JsonDatas/sites.json'));
$rcp = []; $error = [];
$tab = array_merge(...array_map(function($m)use(& $rcp, & $error){
    if (isset($rcp[$m->name])){
        $error[$m->name ] = 1;
        return [];
    }
    $rcp[$m->name] = 1;
    return [$m->name=>$m];
}, $tab));
igk_wln_e("already ",  $error);